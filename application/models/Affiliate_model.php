<?php
defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * Affiliate-facing aggregates and health scoring (predictive index from existing metrics).
 */
class Affiliate_model extends CI_Model
{
	/**
	 * Compute 0–100 health score from conversion, wallet share, account age, recent activity, minus fraud penalty.
	 *
	 * @param array $user Row with product_total_click, affiliate_total_click, product_total_sale, created_at, total_fraud_blocks
	 * @param float $wallet_sum Sum of approved wallet earnings for this user
	 * @param float $max_wallet Max wallet_sum among affiliates (avoid div by zero)
	 * @param int   $activity_count Click/action rows in last 30 days
	 * @param int   $max_activity Max activity among affiliates
	 * @param float $max_age_days Oldest account age in days (for normalization)
	 */
	public function calculate_health_score(
		array $user,
		$wallet_sum,
		$max_wallet,
		$activity_count,
		$max_activity,
		$max_age_days
	) {
		$clicks = (int)($user['product_total_click'] ?? 0) + (int)($user['affiliate_total_click'] ?? 0);
		$sales  = (int)($user['product_total_sale'] ?? 0);

		if ($clicks <= 0 && $sales <= 0) {
			$conversion_pct = 0.0;
		} elseif ($clicks <= 0) {
			$conversion_pct = 100.0;
		} else {
			$conversion_pct = min(100.0, ($sales / $clicks) * 100.0);
		}

		$max_wallet = (float)$max_wallet;
		$wallet_sum = (float)$wallet_sum;
		if ($max_wallet > 0) {
			$revenue_pct = min(100.0, ($wallet_sum / $max_wallet) * 100.0);
		} else {
			$revenue_pct = 0.0;
		}

		$created = !empty($user['created_at']) ? strtotime($user['created_at']) : false;
		if ($created === false) {
			$age_pct = 0.0;
		} else {
			$days = max(0, (time() - $created) / 86400);
			$max_age_days = max(1.0, (float)$max_age_days);
			$age_pct = min(100.0, ($days / $max_age_days) * 100.0);
		}

		$max_activity = max(0, (int)$max_activity);
		if ($max_activity > 0) {
			$activity_pct = min(100.0, ((int)$activity_count / $max_activity) * 100.0);
		} else {
			$activity_pct = 0.0;
		}

		$score = (0.40 * $conversion_pct)
			+ (0.30 * $revenue_pct)
			+ (0.20 * $age_pct)
			+ (0.10 * $activity_pct);

		$fraud_blocks = (int)($user['total_fraud_blocks'] ?? 0);
		$score -= (10 * $fraud_blocks);

		return max(0, min(100, round($score, 2)));
	}

	/**
	 * Batch recompute health_score for all rows with type = user.
	 *
	 * @return array{success:bool,updated?:int,message?:string}
	 */
	public function update_all_health_scores()
	{
		if (!$this->db->field_exists('health_score', 'users')) {
			return [
				'success' => false,
				'message_key' => 'admin.health_score_column_missing',
			];
		}

		$cutoff = date('Y-m-d H:i:s', strtotime('-30 days'));

		$affiliates = $this->db->query(
			"SELECT id, product_total_click, affiliate_total_click, product_total_sale, created_at, total_fraud_blocks
			FROM users WHERE type = 'user'"
		)->result_array();

		if (!$affiliates) {
			return ['success' => true, 'updated' => 0, 'message_key' => 'admin.health_score_no_affiliates'];
		}

		$wallet_rows = $this->db->query(
			"SELECT u.id, COALESCE(SUM(w.amount), 0) AS wallet_sum
			FROM users u
			LEFT JOIN wallet w ON w.user_id = u.id AND w.status > 0 AND w.type NOT IN ('vendor_sale_commission')
			WHERE u.type = 'user'
			GROUP BY u.id"
		)->result_array();

		$wallet_by_id = [];
		$max_wallet = 0.0;
		foreach ($wallet_rows as $row) {
			$wid = (int)$row['id'];
			$sum = (float)$row['wallet_sum'];
			$wallet_by_id[$wid] = $sum;
			if ($sum > $max_wallet) {
				$max_wallet = $sum;
			}
		}

		$activity_sql = "
			SELECT user_id, SUM(c) AS tot FROM (
				SELECT user_id, COUNT(*) AS c FROM integration_clicks_action
				WHERE created_at >= ? AND user_id > 0 GROUP BY user_id
				UNION ALL
				SELECT user_id, COUNT(*) AS c FROM product_action
				WHERE created_at >= ? AND user_id > 0 GROUP BY user_id
				UNION ALL
				SELECT user_id, COUNT(*) AS c FROM form_action
				WHERE created_at >= ? AND user_id > 0 GROUP BY user_id
				UNION ALL
				SELECT user_id, COUNT(*) AS c FROM affiliate_action
				WHERE created_at >= ? AND user_id > 0 GROUP BY user_id
			) x GROUP BY user_id
		";
		$activity_rows = $this->db->query($activity_sql, [$cutoff, $cutoff, $cutoff, $cutoff])->result_array();
		$activity_by_id = [];
		$max_activity = 0;
		foreach ($activity_rows as $row) {
			$uid = (int)$row['user_id'];
			$tot = (int)$row['tot'];
			$activity_by_id[$uid] = $tot;
			if ($tot > $max_activity) {
				$max_activity = $tot;
			}
		}

		$max_age_days = 1.0;
		foreach ($affiliates as $u) {
			if (empty($u['created_at'])) {
				continue;
			}
			$d = max(0, (time() - strtotime($u['created_at'])) / 86400);
			if ($d > $max_age_days) {
				$max_age_days = $d;
			}
		}

		$updated = 0;
		$this->db->trans_start();

		foreach ($affiliates as $u) {
			$uid = (int)$u['id'];
			$wallet_sum = $wallet_by_id[$uid] ?? 0.0;
			$act = $activity_by_id[$uid] ?? 0;
			$score = $this->calculate_health_score($u, $wallet_sum, $max_wallet, $act, $max_activity, $max_age_days);
			$this->db->where('id', $uid)->update('users', ['health_score' => $score]);
			$updated++;
		}

		$this->db->trans_complete();

		if ($this->db->trans_status() === false) {
			return ['success' => false, 'message_key' => 'admin.health_score_update_failed'];
		}

		return [
			'success' => true,
			'updated' => $updated,
			'message_key' => 'admin.health_score_updated_count',
			'message_param' => $updated,
		];
	}
}
