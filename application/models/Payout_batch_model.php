<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Mass payout export batches (PayPal / Wise CSV) and return-file reconciliation.
 */
class Payout_batch_model extends CI_Model {

	/** wallet_requests.status values allowed for new export batches */
	const ELIGIBLE_EXPORT_STATUSES = [7, 12];

	/** Success / failure reconciliation targets (Withdrawal_payment_model status ids) */
	const RECONCILE_SUCCESS_STATUS = 1;
	const RECONCILE_FAILURE_STATUS = 5;

	/** Max data rows processed from provider return CSV (excludes header); prevents memory/CPU exhaustion */
	const MAX_CSV_DATA_ROWS = 10000;

	public function get_eligible_mass_payout_statuses() {
		return self::ELIGIBLE_EXPORT_STATUSES;
	}

	public function get_default_currency_code() {
		$row = $this->db->query("SELECT code FROM currency WHERE is_default = 1 AND status = 1 LIMIT 1")->row_array();
		return $row && !empty($row['code']) ? strtoupper((string) $row['code']) : 'USD';
	}

	/**
	 * @param array $filter user_id?, date?, status (must be 7 or 12)
	 */
	public function get_exportable_requests(array $filter, $per_page, $offset) {
		$this->db->select('wr.*, u.username, u.firstname, u.lastname, u.email AS user_account_email');
		$this->db->from('wallet_requests wr');
		$this->db->join('users u', 'u.id = wr.user_id', 'left');
		$this->db->where('wr.batch_export_id IS NULL', null, false);
		if (!empty($filter['user_id'])) {
			$this->db->where('wr.user_id', (int) $filter['user_id']);
		}
		if (!empty($filter['date']) && strpos($filter['date'], ' - ') !== false) {
			list($start_date, $end_date) = explode(' - ', $filter['date'], 2);
			$start_date = date('Y-m-d', strtotime(trim($start_date)));
			$end_date = date('Y-m-d', strtotime(trim($end_date)));
			$this->db->where('DATE(wr.created_at) >=', $start_date);
			$this->db->where('DATE(wr.created_at) <=', $end_date);
		}
		if (!empty($filter['status']) && $filter['status'] === 'all') {
			$this->db->where_in('wr.status', self::ELIGIBLE_EXPORT_STATUSES);
		} else {
			$status = isset($filter['status']) ? (int) $filter['status'] : null;
			if ($status === null || !in_array($status, self::ELIGIBLE_EXPORT_STATUSES, true)) {
				$this->db->where_in('wr.status', self::ELIGIBLE_EXPORT_STATUSES);
			} else {
				$this->db->where('wr.status', $status);
			}
		}
		$this->db->order_by('wr.created_at', 'DESC');
		$this->db->limit((int) $per_page, (int) $offset);
		$rows = $this->db->get()->result_array();
		foreach ($rows as &$row) {
			$row['payout_receiver_paypal'] = $this->resolve_receiver_for_processor($row, 'paypal');
			$row['payout_receiver_wise'] = $this->resolve_receiver_for_processor($row, 'wise');
		}
		return $rows;
	}

	public function count_exportable_requests(array $filter) {
		$this->db->from('wallet_requests wr');
		$this->db->where('wr.batch_export_id IS NULL', null, false);
		if (!empty($filter['user_id'])) {
			$this->db->where('wr.user_id', (int) $filter['user_id']);
		}
		if (!empty($filter['date']) && strpos($filter['date'], ' - ') !== false) {
			list($start_date, $end_date) = explode(' - ', $filter['date'], 2);
			$start_date = date('Y-m-d', strtotime(trim($start_date)));
			$end_date = date('Y-m-d', strtotime(trim($end_date)));
			$this->db->where('DATE(wr.created_at) >=', $start_date);
			$this->db->where('DATE(wr.created_at) <=', $end_date);
		}
		if (!empty($filter['status']) && $filter['status'] === 'all') {
			$this->db->where_in('wr.status', self::ELIGIBLE_EXPORT_STATUSES);
		} else {
			$status = isset($filter['status']) ? (int) $filter['status'] : null;
			if ($status === null || !in_array($status, self::ELIGIBLE_EXPORT_STATUSES, true)) {
				$this->db->where_in('wr.status', self::ELIGIBLE_EXPORT_STATUSES);
			} else {
				$this->db->where('wr.status', $status);
			}
		}
		return (int) $this->db->count_all_results();
	}

	public function list_recent_batches($limit = 15) {
		$limit = max(1, min(100, (int) $limit));
		if (!$this->db->table_exists('payout_batch_items')) {
			$rows = $this->db->query(
				'SELECT *, row_count AS progress_total, 0 AS progress_paid, 0 AS progress_failed, row_count AS progress_pending ' .
				'FROM payout_batches ORDER BY id DESC LIMIT ' . $limit
			)->result_array();
			return $rows;
		}
		$sql = '
			SELECT pb.*,
				COALESCE(agg.cnt, pb.row_count, 0) AS progress_total,
				COALESCE(agg.paid, 0) AS progress_paid,
				COALESCE(agg.failed, 0) AS progress_failed,
				CASE
					WHEN agg.batch_id IS NULL THEN COALESCE(pb.row_count, 0)
					ELSE COALESCE(agg.pending, 0)
				END AS progress_pending
			FROM payout_batches pb
			LEFT JOIN (
				SELECT batch_id,
					COUNT(*) AS cnt,
					SUM(CASE WHEN reconciliation_status = \'paid\' THEN 1 ELSE 0 END) AS paid,
					SUM(CASE WHEN reconciliation_status = \'failed\' THEN 1 ELSE 0 END) AS failed,
					SUM(CASE WHEN COALESCE(reconciliation_status, \'\') NOT IN (\'paid\', \'failed\') THEN 1 ELSE 0 END) AS pending
				FROM payout_batch_items
				GROUP BY batch_id
			) agg ON agg.batch_id = pb.id
			ORDER BY pb.id DESC
			LIMIT ' . $limit;
		return $this->db->query($sql)->result_array();
	}

	/**
	 * Reconciliation counts for specific batch IDs (one aggregated query).
	 *
	 * @param int[] $batch_ids
	 * @return array<int, array{total:int,paid:int,failed:int,pending:int}>
	 */
	public function get_batch_progress(array $batch_ids) {
		$out = array();
		$batch_ids = array_values(array_unique(array_map('intval', $batch_ids)));
		$batch_ids = array_filter($batch_ids, function ($x) {
			return $x > 0;
		});
		if (count($batch_ids) < 1 || !$this->db->table_exists('payout_batch_items')) {
			return $out;
		}
		$in = implode(',', $batch_ids);
		$q = $this->db->query(
			'SELECT batch_id AS id,
				COUNT(*) AS total,
				SUM(CASE WHEN reconciliation_status = \'paid\' THEN 1 ELSE 0 END) AS paid,
				SUM(CASE WHEN reconciliation_status = \'failed\' THEN 1 ELSE 0 END) AS failed,
				SUM(CASE WHEN COALESCE(reconciliation_status, \'\') NOT IN (\'paid\', \'failed\') THEN 1 ELSE 0 END) AS pending
			FROM payout_batch_items
			WHERE batch_id IN (' . $in . ')
			GROUP BY batch_id'
		)->result_array();
		foreach ($q as $row) {
			$id = (int) $row['id'];
			$out[$id] = array(
				'total' => (int) $row['total'],
				'paid' => (int) $row['paid'],
				'failed' => (int) $row['failed'],
				'pending' => (int) $row['pending'],
			);
		}
		return $out;
	}

	/**
	 * UI labels for Recent batches table (badges + upload button emphasis).
	 *
	 * @param array<string,mixed> $batch Row from list_recent_batches()
	 * @return array{badge_class:string,badge_html:string,upload_btn_class:string,upload_label:string,upload_title:string,is_completed:bool}
	 */
	public function describe_batch_progress_for_ui(array $batch) {
		$total = (int) (isset($batch['progress_total']) ? $batch['progress_total'] : 0);
		$paid = (int) (isset($batch['progress_paid']) ? $batch['progress_paid'] : 0);
		$failed = (int) (isset($batch['progress_failed']) ? $batch['progress_failed'] : 0);
		$pending = (int) (isset($batch['progress_pending']) ? $batch['progress_pending'] : 0);
		if ($total < 1) {
			$total = (int) (isset($batch['row_count']) ? $batch['row_count'] : 0);
		}
		if ($total < 1) {
			return array(
				'badge_class' => 'bg-secondary',
				'badge_html' => '<span class="badge bg-secondary">' . htmlspecialchars(__('admin.mass_payout_batch_status_no_lines')) . '</span>',
				'upload_btn_class' => 'btn-outline-secondary',
				'upload_label' => __('admin.mass_payout_upload_return'),
				'upload_title' => __('admin.mass_payout_upload_return'),
				'is_completed' => false,
			);
		}
		if ($paid === 0 && $failed === 0 && $pending === 0) {
			$pending = $total;
		}

		$is_completed = ($paid === $total && $failed === 0 && $total > 0);
		$upload_label = $is_completed ? __('admin.mass_payout_reupload_return') : __('admin.mass_payout_upload_return');
		$upload_title = $is_completed ? __('admin.mass_payout_reupload_return_title') : __('admin.mass_payout_upload_return');
		$upload_btn_class = $is_completed ? 'btn-outline-secondary' : 'btn-outline-primary';

		if ($is_completed) {
			$badge_html = '<span class="badge bg-success">' . htmlspecialchars(__('admin.mass_payout_batch_status_completed')) . '</span>';
			return array(
				'badge_class' => 'bg-success',
				'badge_html' => $badge_html,
				'upload_btn_class' => $upload_btn_class,
				'upload_label' => $upload_label,
				'upload_title' => $upload_title,
				'is_completed' => true,
			);
		}

		if ($paid === 0 && $failed === 0) {
			$badge_html = '<span class="badge bg-secondary">' . htmlspecialchars(__('admin.mass_payout_batch_status_pending_upload')) . '</span>';
			return array(
				'badge_class' => 'bg-secondary',
				'badge_html' => $badge_html,
				'upload_btn_class' => $upload_btn_class,
				'upload_label' => $upload_label,
				'upload_title' => $upload_title,
				'is_completed' => false,
			);
		}

		if ($paid === 0 && $failed === $total) {
			$badge_html = '<span class="badge bg-danger">' . htmlspecialchars(__('admin.mass_payout_batch_status_all_failed')) . '</span>';
			return array(
				'badge_class' => 'bg-danger',
				'badge_html' => $badge_html,
				'upload_btn_class' => $upload_btn_class,
				'upload_label' => $upload_label,
				'upload_title' => $upload_title,
				'is_completed' => false,
			);
		}

		$badge_class = 'bg-warning text-dark';
		if ($failed > 0) {
			$text = sprintf(__('admin.mass_payout_batch_status_mixed_failed'), $paid, $total, $failed, $pending);
		} else {
			$text = sprintf(__('admin.mass_payout_batch_status_mixed_progress'), $paid, $total, $pending);
		}
		$badge_html = '<span class="badge ' . $badge_class . '">' . htmlspecialchars($text) . '</span>';
		return array(
			'badge_class' => $badge_class,
			'badge_html' => $badge_html,
			'upload_btn_class' => $upload_btn_class,
			'upload_label' => $upload_label,
			'upload_title' => $upload_title,
			'is_completed' => false,
		);
	}

	public function get_batch($id) {
		$id = (int) $id;
		if ($id < 1) {
			return null;
		}
		$row = $this->db->get_where('payout_batches', ['id' => $id])->row_array();
		return $row ?: null;
	}

	public function get_batch_items($batch_id) {
		return $this->db->get_where('payout_batch_items', ['batch_id' => (int) $batch_id])->result_array();
	}

	/**
	 * Latest mass-payout import that marked this withdrawal paid (for admin details UI).
	 *
	 * @return array<string,mixed>|null
	 */
	public function get_last_paid_mass_payout_reconciliation($wallet_request_id) {
		$wallet_request_id = (int) $wallet_request_id;
		if ($wallet_request_id < 1 || !$this->db->table_exists('payout_batch_items')) {
			return null;
		}
		$row = $this->db->query(
			'SELECT pbi.reconciliation_at, pbi.provider_txn_id, pbi.reconciliation_detail, pbi.amount,
				pb.id AS batch_id, pb.processor, pb.currency_code
			 FROM payout_batch_items pbi
			 INNER JOIN payout_batches pb ON pb.id = pbi.batch_id
			 WHERE pbi.wallet_request_id = ? AND pbi.reconciliation_status = ?
			 ORDER BY pbi.reconciliation_at DESC, pbi.id DESC
			 LIMIT 1',
			array($wallet_request_id, 'paid')
		)->row_array();
		return $row ?: null;
	}

	/**
	 * @param int[] $wallet_request_ids
	 * @return array{success:bool,message?:string,batch_id?:int}
	 */
	public function create_batch($admin_id, $processor, array $wallet_request_ids) {
		$processor = strtolower(trim((string) $processor));
		if (!in_array($processor, ['paypal', 'wise'], true)) {
			return ['success' => false, 'message' => __('admin.mass_payout_invalid_processor')];
		}
		$ids = array_values(array_unique(array_filter(array_map('intval', $wallet_request_ids))));
		if (count($ids) < 1) {
			return ['success' => false, 'message' => __('admin.mass_payout_select_requests')];
		}

		$this->load->model('Payment_details_model');

		$currency = $this->get_default_currency_code();
		$placeholders = implode(',', array_fill(0, count($ids), '?'));
		$eligible = implode(',', array_map('intval', self::ELIGIBLE_EXPORT_STATUSES));
		$sql = "SELECT wr.*, u.email AS user_account_email FROM wallet_requests wr
			LEFT JOIN users u ON u.id = wr.user_id
			WHERE wr.id IN (" . $placeholders . ") AND wr.batch_export_id IS NULL AND wr.status IN (" . $eligible . ")";
		$requests = $this->db->query($sql, $ids)->result_array();
		if (count($requests) !== count($ids)) {
			return ['success' => false, 'message' => __('admin.mass_payout_requests_unavailable_or_ineligible')];
		}

		$items = [];
		$total = 0.0;
		foreach ($requests as $req) {
			$receiver = $this->resolve_receiver_for_processor($req, $processor);
			if ($receiver === '' || !filter_var($receiver, FILTER_VALIDATE_EMAIL)) {
				return [
					'success' => false,
					'message' => __('admin.mass_payout_missing_email_for_request') . ' #' . (int) $req['id'],
				];
			}
			$amt = (float) $req['total'];
			if ($amt <= 0) {
				return [
					'success' => false,
					'message' => __('admin.mass_payout_invalid_amount_for_request') . ' #' . (int) $req['id'],
				];
			}
			$total += $amt;
			$items[] = [
				'wallet_request_id' => (int) $req['id'],
				'amount' => $amt,
				'receiver_snapshot' => $receiver,
			];
		}

		$now = date('Y-m-d H:i:s');
		$this->db->trans_begin();

		$this->db->insert('payout_batches', [
			'created_by_admin_id' => $admin_id ? (int) $admin_id : null,
			'processor' => $processor,
			'currency_code' => $currency,
			'row_count' => count($items),
			'total_amount' => round($total, 4),
			'status' => 'exported',
			'created_at' => $now,
		]);
		$batch_id = (int) $this->db->insert_id();
		if ($batch_id < 1) {
			$this->db->trans_rollback();
			return ['success' => false, 'message' => __('admin.mass_payout_batch_failed')];
		}

		foreach ($items as $it) {
			$this->db->insert('payout_batch_items', [
				'batch_id' => $batch_id,
				'wallet_request_id' => $it['wallet_request_id'],
				'amount' => $it['amount'],
				'receiver_snapshot' => $it['receiver_snapshot'],
				'reconciliation_status' => 'pending',
			]);
		}

		$this->db->where_in('id', $ids);
		$this->db->update('wallet_requests', ['batch_export_id' => $batch_id]);

		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return ['success' => false, 'message' => __('admin.mass_payout_batch_failed')];
		}
		$this->db->trans_commit();

		return ['success' => true, 'batch_id' => $batch_id];
	}

	/**
	 * @return string|null CSV contents with UTF-8 BOM
	 */
	public function build_csv($batch_id, $processor) {
		$batch = $this->get_batch($batch_id);
		if (!$batch) {
			return null;
		}
		$processor = strtolower((string) $processor);
		if ($processor !== strtolower($batch['processor'])) {
			$processor = strtolower($batch['processor']);
		}
		$items = $this->get_batch_items($batch_id);
		if (count($items) < 1) {
			return null;
		}

		$fh = fopen('php://temp', 'r+');
		if ($fh === false) {
			return null;
		}
		fprintf($fh, chr(0xEF) . chr(0xBB) . chr(0xBF));

		$currency = strtoupper((string) $batch['currency_code']);

		if ($processor === 'paypal') {
			fputcsv($fh, ['recipient_type', 'receiver', 'amount', 'currency', 'note']);
			foreach ($items as $it) {
				$note = 'Withdrawal request #' . (int) $it['wallet_request_id'];
				fputcsv($fh, [
					'EMAIL',
					$it['receiver_snapshot'],
					number_format((float) $it['amount'], 2, '.', ''),
					$currency,
					$note,
				]);
			}
		} else {
			fputcsv($fh, ['recipientEmail', 'amount', 'currency', 'reference']);
			foreach ($items as $it) {
				$ref = 'WR-' . (int) $it['wallet_request_id'];
				fputcsv($fh, [
					$it['receiver_snapshot'],
					number_format((float) $it['amount'], 2, '.', ''),
					$currency,
					$ref,
				]);
			}
		}

		rewind($fh);
		$csv = stream_get_contents($fh);
		fclose($fh);
		return $csv;
	}

	/**
	 * Parse PayPal/Wise return CSV and reconcile wallet_requests + batch items.
	 *
	 * @return array{success:bool,message?:string,paid?:int,failed?:int,skipped?:int,errors?:string[]}
	 */
	public function process_return_csv($batch_id, $tmp_path) {
		$batch_id = (int) $batch_id;
		if ($batch_id < 1 || !is_readable($tmp_path)) {
			return ['success' => false, 'message' => __('admin.mass_payout_import_invalid')];
		}

		$batch = $this->get_batch($batch_id);
		if (!$batch) {
			return ['success' => false, 'message' => __('admin.mass_payout_import_batch_not_found')];
		}

		$items = $this->get_batch_items($batch_id);
		if (count($items) < 1) {
			return ['success' => false, 'message' => __('admin.mass_payout_import_no_items')];
		}

		$by_wr = [];
		foreach ($items as $it) {
			$by_wr[(int) $it['wallet_request_id']] = $it;
		}

		$fh = fopen($tmp_path, 'rb');
		if ($fh === false) {
			return ['success' => false, 'message' => __('admin.mass_payout_import_read_error')];
		}

		$bom = fread($fh, 3);
		if ($bom !== "\xEF\xBB\xBF") {
			rewind($fh);
		}

		$header_row = fgetcsv($fh);
		if ($header_row === false || count($header_row) < 2) {
			fclose($fh);
			return ['success' => false, 'message' => __('admin.mass_payout_import_bad_csv')];
		}

		$col_map = $this->csv_detect_columns($header_row);
		$this->load->model('Withdrawal_payment_model');

		$paid = 0;
		$failed = 0;
		$skipped = 0;
		$errors = [];
		$now = date('Y-m-d H:i:s');
		$data_row_count = 0;

		while (($row = fgetcsv($fh)) !== false) {
			$data_row_count++;
			if ($data_row_count > self::MAX_CSV_DATA_ROWS) {
				fclose($fh);
				return ['success' => false, 'message' => __('admin.mass_payout_import_too_many_rows')];
			}
			if (count($row) < 1 || $this->csv_row_is_empty($row)) {
				continue;
			}

			$wr_id = $this->extract_wallet_request_id_from_csv_row($row, $col_map);
			if ($wr_id === null) {
				$skipped++;
				continue;
			}

			if (!isset($by_wr[$wr_id])) {
				$errors[] = __('admin.mass_payout_import_wr_not_in_batch') . ' #' . $wr_id;
				$skipped++;
				continue;
			}

			$item = $by_wr[$wr_id];
			$req = $this->db->get_where('wallet_requests', ['id' => $wr_id])->row_array();
			if (!$req || (int) $req['batch_export_id'] !== $batch_id) {
				$errors[] = __('admin.mass_payout_import_wr_mismatch') . ' #' . $wr_id;
				$skipped++;
				continue;
			}

			$status_raw = $this->csv_cell($row, $col_map['status']);
			$reason = trim($this->csv_cell($row, $col_map['reason']));
			$txn_id = trim($this->csv_cell($row, $col_map['txn_id']));

			$outcome = $this->classify_provider_status($status_raw);
			if ($outcome === 'unknown') {
				$outcome = $this->classify_provider_status(implode(' ', $row));
			}
			if ($outcome === 'unknown') {
				$skipped++;
				continue;
			}

			if ((int) $req['status'] === self::RECONCILE_SUCCESS_STATUS
				&& !empty($item['reconciliation_status']) && $item['reconciliation_status'] === 'paid') {
				$skipped++;
				continue;
			}

			$batch_label = 'Batch #' . $batch_id . ' import';

			if ($outcome === 'success') {
				$comment = $batch_label . ': provider success';
				if ($txn_id !== '') {
					$comment .= '; txn: ' . $txn_id;
				}
				$this->Withdrawal_payment_model->apiAddWithdrwalRequestHistory($wr_id, [
					'status_id' => self::RECONCILE_SUCCESS_STATUS,
					'comment' => $comment,
					'transaction_id' => $txn_id !== '' ? $txn_id : '',
				]);
				$this->db->where('id', (int) $item['id']);
				$this->db->update('payout_batch_items', [
					'reconciliation_status' => 'paid',
					'reconciliation_detail' => $reason !== '' ? $reason : $status_raw,
					'reconciliation_at' => $now,
					'provider_txn_id' => $txn_id !== '' ? substr($txn_id, 0, 300) : null,
				]);
				$item['reconciliation_status'] = 'paid';
				$by_wr[$wr_id] = $item;
				$paid++;
			} else {
				$fail_note = $reason !== '' ? $reason : ($status_raw !== '' ? $status_raw : 'provider failure');
				$comment = $batch_label . ': ' . $fail_note;
				$this->Withdrawal_payment_model->apiAddWithdrwalRequestHistory($wr_id, [
					'status_id' => self::RECONCILE_FAILURE_STATUS,
					'comment' => $comment,
					'transaction_id' => $txn_id !== '' ? $txn_id : '',
				]);
				$this->db->where('id', (int) $item['id']);
				$this->db->update('payout_batch_items', [
					'reconciliation_status' => 'failed',
					'reconciliation_detail' => $fail_note,
					'reconciliation_at' => $now,
					'provider_txn_id' => $txn_id !== '' ? substr($txn_id, 0, 300) : null,
				]);
				$item['reconciliation_status'] = 'failed';
				$by_wr[$wr_id] = $item;
				$failed++;
			}
		}

		fclose($fh);

		return [
			'success' => true,
			'message' => __('admin.mass_payout_import_done'),
			'paid' => $paid,
			'failed' => $failed,
			'skipped' => $skipped,
			'summary_line' => sprintf(__('admin.mass_payout_import_summary_body'), $paid, $failed, $skipped),
			'errors' => $errors,
		];
	}

	private function csv_row_is_empty(array $row) {
		foreach ($row as $c) {
			if (trim((string) $c) !== '') {
				return false;
			}
		}
		return true;
	}

	private function csv_cell(array $row, $idx) {
		if ($idx === null || !isset($row[$idx])) {
			return '';
		}
		return (string) $row[$idx];
	}

	/**
	 * @return array{status:?int,reason:?int,txn_id:?int,note:?int,reference:?int}
	 */
	private function csv_detect_columns(array $headers) {
		$norm = [];
		foreach ($headers as $i => $h) {
			$norm[$i] = strtolower(trim((string) $h));
		}

		$map = ['status' => null, 'reason' => null, 'txn_id' => null, 'note' => null, 'reference' => null];

		foreach ($norm as $i => $h) {
			if ($map['status'] === null) {
				if (strpos($h, 'transaction status') !== false || strpos($h, 'payout status') !== false
					|| strpos($h, 'item status') !== false || strpos($h, 'payment status') !== false) {
					$map['status'] = $i;
				}
			}
		}
		if ($map['status'] === null) {
			foreach ($norm as $i => $h) {
				if ($h === 'status' || (strpos($h, 'status') !== false && strpos($h, 'subscriber') === false)) {
					$map['status'] = $i;
					break;
				}
			}
		}

		$reason_hints = ['failure', 'error message', 'error', 'reason', 'denied', 'detail', 'description', 'notes', 'note to recipient', 'response'];
		foreach ($reason_hints as $hint) {
			foreach ($norm as $i => $h) {
				if ($map['reason'] === null && strpos($h, $hint) !== false) {
					$map['reason'] = $i;
					break 2;
				}
			}
		}

		$txn_hints = ['transaction id', 'txn id', 'txn', 'payout id', 'paypal reference', 'reference id', 'processor id', 'payment id'];
		foreach ($txn_hints as $hint) {
			foreach ($norm as $i => $h) {
				if ($map['txn_id'] === null && strpos($h, $hint) !== false) {
					$map['txn_id'] = $i;
					break 2;
				}
			}
		}

		foreach ($norm as $i => $h) {
			if ($map['note'] === null && (strpos($h, 'note') !== false || strpos($h, 'memo') !== false)) {
				$map['note'] = $i;
			}
			if ($map['reference'] === null && strpos($h, 'reference') !== false && strpos($h, 'transaction') === false) {
				$map['reference'] = $i;
			}
		}

		return $map;
	}

	/**
	 * @return int|null
	 */
	private function extract_wallet_request_id_from_csv_row(array $cells, array $col_map) {
		$check = [];
		if ($col_map['note'] !== null && isset($cells[$col_map['note']])) {
			$check[] = (string) $cells[$col_map['note']];
		}
		if ($col_map['reference'] !== null && isset($cells[$col_map['reference']])) {
			$check[] = (string) $cells[$col_map['reference']];
		}
		foreach ($check as $text) {
			if (preg_match('/Withdrawal\s+request\s*#(\d+)/i', $text, $m)) {
				return (int) $m[1];
			}
			if (preg_match('/\bWR-(\d+)\b/i', $text, $m)) {
				return (int) $m[1];
			}
		}
		foreach ($cells as $text) {
			$text = (string) $text;
			if (preg_match('/Withdrawal\s+request\s*#(\d+)/i', $text, $m)) {
				return (int) $m[1];
			}
			if (preg_match('/\bWR-(\d+)\b/i', $text, $m)) {
				return (int) $m[1];
			}
		}
		return null;
	}

	/**
	 * @return string success|failure|unknown
	 */
	private function classify_provider_status($raw) {
		$s = strtolower(trim((string) $raw));
		if ($s === '') {
			return 'unknown';
		}
		$success = ['success', 'completed', 'complete', 'processed', 'paid', 'succeeded', 'delivered'];
		$fail = ['denied', 'failed', 'returned', 'canceled', 'cancelled', 'reversed', 'declined', 'blocked', 'refused', 'rejected', 'error', 'failure'];
		foreach ($success as $w) {
			if ($s === $w || strpos($s, $w) !== false) {
				return 'success';
			}
		}
		foreach ($fail as $w) {
			if ($s === $w || strpos($s, $w) !== false) {
				return 'failure';
			}
		}
		return 'unknown';
	}

	/**
	 * @param array $row wallet_requests row + optional user_account_email
	 */
	public function resolve_receiver_for_processor(array $row, $processor) {
		$processor = strtolower((string) $processor);
		$settings = [];
		if (!empty($row['settings'])) {
			$decoded = json_decode($row['settings'], true);
			if (is_array($decoded)) {
				$settings = $decoded;
			}
		}
		$paypal_email = isset($settings['paypal_email']) ? trim((string) $settings['paypal_email']) : '';

		if ($processor === 'paypal') {
			if ($paypal_email !== '' && filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
				return $paypal_email;
			}
			$this->load->model('Payment_details_model');
			$pd = $this->Payment_details_model->getUserPaymentData((int) $row['user_id'], 'paypal');
			if (!empty($pd['paypal_email']) && filter_var($pd['paypal_email'], FILTER_VALIDATE_EMAIL)) {
				return trim((string) $pd['paypal_email']);
			}
			// Bank transfer / Wise-only forms often store the contact email under wise_email or generic keys;
			// PayPal Payouts (EMAIL) still needs a valid address — reuse the same fallbacks as Wise.
			foreach (['wise_email', 'email', 'bank_email', 'recipient_email'] as $k) {
				if (!empty($settings[$k]) && filter_var(trim((string) $settings[$k]), FILTER_VALIDATE_EMAIL)) {
					return trim((string) $settings[$k]);
				}
			}
			if (!empty($row['user_account_email']) && filter_var($row['user_account_email'], FILTER_VALIDATE_EMAIL)) {
				return trim((string) $row['user_account_email']);
			}
			return '';
		}

		if ($paypal_email !== '' && filter_var($paypal_email, FILTER_VALIDATE_EMAIL)) {
			return $paypal_email;
		}
		foreach (['wise_email', 'email', 'bank_email', 'recipient_email'] as $k) {
			if (!empty($settings[$k]) && filter_var(trim((string) $settings[$k]), FILTER_VALIDATE_EMAIL)) {
				return trim((string) $settings[$k]);
			}
		}
		if (!empty($row['user_account_email']) && filter_var($row['user_account_email'], FILTER_VALIDATE_EMAIL)) {
			return trim((string) $row['user_account_email']);
		}
		$this->load->model('Payment_details_model');
		$pd = $this->Payment_details_model->getUserPaymentData((int) $row['user_id'], 'paypal');
		if (!empty($pd['paypal_email']) && filter_var($pd['paypal_email'], FILTER_VALIDATE_EMAIL)) {
			return trim((string) $pd['paypal_email']);
		}
		return '';
	}

	/**
	 * When a withdrawal request is reverted/deleted, remove its mass-payout line item and
	 * recalculate or delete the parent batch so "Recent batches" stays accurate.
	 *
	 * @param int $wallet_request_id wallet_requests.id
	 */
	public function detach_wallet_request_from_batch($wallet_request_id) {
		if (!$this->db->table_exists('payout_batch_items')) {
			return;
		}
		$wallet_request_id = (int) $wallet_request_id;
		if ($wallet_request_id < 1) {
			return;
		}
		$wr = $this->db->get_where('wallet_requests', array('id' => $wallet_request_id))->row_array();
		if (!$wr) {
			return;
		}
		$batch_id = isset($wr['batch_export_id']) && $wr['batch_export_id'] !== null && $wr['batch_export_id'] !== ''
			? (int) $wr['batch_export_id'] : 0;
		if ($batch_id < 1) {
			return;
		}
		$this->db->where('wallet_request_id', $wallet_request_id);
		$this->db->delete('payout_batch_items');

		$agg = $this->db->query(
			'SELECT COUNT(*) AS c, COALESCE(SUM(amount),0) AS t FROM payout_batch_items WHERE batch_id = ?',
			array($batch_id)
		)->row_array();
		$c = (int) ($agg['c'] ?? 0);
		if ($c < 1) {
			$this->db->where('id', $batch_id);
			$this->db->delete('payout_batches');
			return;
		}
		$this->db->where('id', $batch_id);
		$this->db->update('payout_batches', array(
			'row_count' => $c,
			'total_amount' => round((float) ($agg['t'] ?? 0), 4),
		));
	}

	/**
	 * Detach every withdrawal request that references this wallet row id in tran_ids.
	 *
	 * @param int $wallet_transaction_id wallet.id
	 */
	public function detach_wallet_requests_for_wallet_transaction_id($wallet_transaction_id) {
		if (!$this->db->table_exists('wallet_requests')) {
			return;
		}
		$tid = (int) $wallet_transaction_id;
		if ($tid < 1) {
			return;
		}
		$q = $this->db->query('SELECT id FROM wallet_requests WHERE FIND_IN_SET(?, tran_ids)', array($tid));
		foreach ($q->result_array() as $row) {
			$this->detach_wallet_request_from_batch((int) $row['id']);
		}
	}

	/**
	 * Undo an export batch: clear batch_export_id on linked withdrawals, delete items and batch.
	 * Blocked if any line was already reconciled as paid (import succeeded).
	 *
	 * @return array{success:bool,message?:string}
	 */
	public function void_batch($batch_id) {
		$batch_id = (int) $batch_id;
		if ($batch_id < 1 || !$this->db->table_exists('payout_batches') || !$this->db->table_exists('payout_batch_items')) {
			return ['success' => false, 'message' => __('admin.mass_payout_void_invalid')];
		}
		$batch = $this->get_batch($batch_id);
		if (!$batch) {
			return ['success' => false, 'message' => __('admin.mass_payout_void_invalid')];
		}
		$items = $this->get_batch_items($batch_id);
		if (count($items) < 1) {
			$this->db->where('id', $batch_id);
			$this->db->delete('payout_batches');
			return ['success' => true, 'message' => __('admin.mass_payout_void_done')];
		}
		foreach ($items as $it) {
			if (!empty($it['reconciliation_status']) && $it['reconciliation_status'] === 'paid') {
				return ['success' => false, 'message' => __('admin.mass_payout_void_has_paid')];
			}
		}
		$has_wr_col = $this->db->field_exists('batch_export_id', 'wallet_requests');
		$this->db->trans_begin();
		foreach ($items as $it) {
			$wr_id = (int) $it['wallet_request_id'];
			if ($wr_id > 0 && $has_wr_col) {
				$this->db->query(
					'UPDATE wallet_requests SET batch_export_id = NULL WHERE id = ? AND batch_export_id = ?',
					array($wr_id, $batch_id)
				);
			}
		}
		$this->db->where('batch_id', $batch_id);
		$this->db->delete('payout_batch_items');
		$this->db->where('id', $batch_id);
		$this->db->delete('payout_batches');
		if ($this->db->trans_status() === false) {
			$this->db->trans_rollback();
			return ['success' => false, 'message' => __('admin.mass_payout_void_failed')];
		}
		$this->db->trans_commit();
		return ['success' => true, 'message' => __('admin.mass_payout_void_done')];
	}
}
