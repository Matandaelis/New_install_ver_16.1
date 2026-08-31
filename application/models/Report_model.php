<?php
class Report_model extends MY_Model{
	public function view($file_name, $data = array(), $control = 'admincontrol'){
			$this->load->view($control . '/includes/header', $data);
			if ($control == 'admincontrol') {
			$this->load->view($control . '/includes/topnav', $data);
			}
			$this->load->view($file_name, $data);
			$this->load->view($control . '/includes/footer', $data);
	}


	public function getAllTransaction($filter = array()){
		$where = array();

		$this->db->from('wallet');
		$this->db->join('users', 'users.id = wallet.user_id','LEFT');
		if (isset($filter['user_id'])) {
			$this->db->where('wallet.user_id', (int)$filter['user_id']);
		}
		if (isset($filter['status_gt'])) {
			$this->db->where('wallet.status >= '. (int)$filter['status_gt']);
		}
		if (isset($filter['status'])) {
			$this->db->where('wallet.status', (int)$filter['status']);
		}
		if (isset($filter['sortBy']) && $filter['sortBy'] && isset($filter['orderBy']) && $filter['orderBy']) {
			$this->db->order_by($filter['sortBy'], $filter['orderBy']);
		} else{
			$this->db->order_by('wallet.id','DESC');
		}

		$total_query = '';
		if (isset($filter['limit']) && isset($filter['page'])) {
			$limit = (int)$filter['limit'];
			$offset = (int)$filter['limit'] * ((int)$filter['page'] - 1);
			$this->db->limit($limit,$offset);
		}

		$this->db->select([
			'wallet.*',
			'users.username',
			'users.firstname',
			'users.lastname',
			"(SELECT payment_method FROM `order` WHERE wallet.reference_id = `order`.id AND wallet.type = 'sale_commission' ) as payment_method",
			"(SELECT total FROM `integration_orders` WHERE comm_from='ex' AND wallet.reference_id_2 = `integration_orders`.id AND wallet.type = 'sale_commission') as integration_orders_total",
			"(SELECT script_name FROM `integration_orders` WHERE wallet.reference_id_2 = `integration_orders`.id AND wallet.comm_from = 'ex' LIMIT 1) as order_script_name"
		]);
		
		$results = $this->db->get()->result_array();
		$_status = $this->Wallet_model->status();
		$_status_icon = $this->Wallet_model->status_icon;

		$json = array();

		if (isset($filter['limit']) && isset($filter['page'])) {
			$total = explode("\n", $this->db->last_query());
			$total[0] = 'SELECT count(wallet.id) as total';
			array_pop($total);

			$total = $this->db->query(implode("\n", $total))->row();
			$json['total'] = $total->total;
		}

		$data = array();
		foreach ($results as $key => $value) {
			$data[] = array(
				'id'                       => $value['id'],
				'username'                 => $value['username'],
				'name'                     => $value['firstname'] ." " .$value['lastname'],
				'user_id'                  => $value['user_id'],
				'amount'                   => c_format($value['amount']),
				'comment'                  => parseMessage($value['comment'],$value, isset($filter['control']) ? $filter['control'] : 'usercontrol'),
				'created_at'               => $value['created_at'],
				'type'                     => $value['type'],
				'comm_from'                => $value['comm_from'] == 'ex' ? __('admin.external') : __('admin.store'),
				'dis_type'                 => wallet_type($value),
				'status'                   => $_status[$value['status']],
				'status_id'                => $value['status'],
				'integration_orders_total' => $value['integration_orders_total'],
				'status_icon'              => $_status_icon[$value['status']],
				'payment_method'           => payment_method($value['payment_method']),
			);
		}
		
		$json['data'] = $data;

		return $json;
	}


	public function getStatistics($filter = array()){
		$where_b = ' 1 ';
		$where_c = ' 1 ';

		if(isset($filter['user_id'])){
			$where_b = " user_id = ". (int)$filter['user_id'];
			$where_c = " refid = ". (int)$filter['user_id'];
		}

		$data['clicks'] = $data['sale'] = [];

		$queryAffi = $this->db->query("SELECT m.country_code, MAX(c.name) as name FROM `affiliate_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE $where_b GROUP BY m.country_code")->result_array();
		$queryFrom = $this->db->query("SELECT m.country_code, MAX(c.name) as name FROM `form_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE $where_b GROUP BY m.country_code")->result_array();
		$queryProduct = $this->db->query("SELECT m.country_code, MAX(c.name) as name FROM `product_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE $where_b GROUP BY m.country_code")->result_array();
		$queryExternalClick = $this->db->query("SELECT m.country_code, MAX(c.name) as name FROM `integration_clicks_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE m.is_action = 0 AND m.page_name = '' AND $where_b GROUP BY m.country_code")->result_array();

		foreach ([$queryAffi, $queryFrom, $queryProduct, $queryExternalClick] as $query) {
			foreach ($query as $value) {
				$label = $value['name'] ?? 'Unknown';
				@$data['clicks'][$label]++;
				@$data['clicks_count']++;
			}
		}

		$queryActionExternalClick = $this->db->query("SELECT m.country_code, MAX(c.name) as name FROM `integration_clicks_action` m LEFT JOIN `countries` c ON c.sortname = m.country_code WHERE m.is_action = 1 AND $where_b GROUP BY m.country_code")->result_array();
		foreach ($queryActionExternalClick as $value) {
			$label = $value['name'] ?? 'Unknown';
			@$data['action_clicks'][$label]++;
			@$data['action_clicks_count']++;
		}

		if(isset($filter['user_id'])) {
			$queryOrder = $this->db->query("
				SELECT MAX(o.country_code) as country_code, MAX(c.name) as name
				FROM order_products op
				LEFT JOIN `order` o ON o.id = op.order_id
				LEFT JOIN countries c ON c.sortname = o.country_code
				WHERE o.status > 0 AND op.refer_id = ". (int)$filter['user_id'] ."
				GROUP BY o.country_code
			")->result_array();

			foreach ($queryOrder as $value) {
				$label = $value['name'] ?? 'Unknown';
				@$data['sale'][$label]++;
				@$data['sale_count']++;
			}

			$queryOrder = $this->db->query("
				SELECT o.country_code, MAX(c.name) as name
				FROM `integration_orders` o
				LEFT JOIN countries c ON c.sortname = o.country_code
				WHERE o.status > 0 AND user_id = ". (int)$filter['user_id'] ."
				GROUP BY o.country_code
			")->result_array();

			foreach ($queryOrder as $value) {
				$label = $value['name'] ?? 'Unknown';
				@$data['sale'][$label]++;
				@$data['sale_count']++;
			}
		} else {
			$queryOrder = $this->db->query("SELECT o.country_code, MAX(c.name) as name FROM `order` o LEFT JOIN countries c ON c.sortname = o.country_code WHERE o.status > 0 GROUP BY o.country_code")->result_array();
			foreach ($queryOrder as $value) {
				$label = $value['name'] ?? 'Unknown';
				@$data['sale'][$label]++;
				@$data['sale_count']++;
			}

			$queryOrder = $this->db->query("SELECT o.country_code, MAX(c.name) as name FROM `integration_orders` o LEFT JOIN countries c ON c.sortname = o.country_code WHERE o.status > 0 GROUP BY o.country_code")->result_array();
			foreach ($queryOrder as $value) {
				$label = $value['name'] ?? 'Unknown';
				@$data['sale'][$label]++;
				@$data['sale_count']++;
			}
		}

		$queryAffiUser = $this->db->query("SELECT MAX(c.name) as name FROM `users` u LEFT JOIN countries c ON c.id = u.country WHERE u.type='user' AND $where_c GROUP BY u.country")->result_array();
		foreach ($queryAffiUser as $value) {
			$label = $value['name'] ?? 'Unknown';
			@$data['affiliate_user'][$label]++;
			@$data['affiliate_user_count']++;
		}

		$queryClient = $this->db->query("SELECT MAX(c.name) as name FROM `users` u LEFT JOIN countries c ON c.id = u.country WHERE u.type='client' AND $where_c GROUP BY u.country")->result_array();
		foreach ($queryClient as $value) {
			$label = $value['name'] ?? 'Unknown';
			@$data['client_user'][$label]++;
			@$data['client_user_count']++;
		}

		return $data;
	}


	public function date_compare($element1, $element2) {
	    $datetime1 = strtotime($element1['date']); 
	    $datetime2 = strtotime($element2['date']); 
	    return ($datetime1 == $datetime2) ? 0 : (($datetime1 < $datetime2) ? 1 : -1);
	}
}
