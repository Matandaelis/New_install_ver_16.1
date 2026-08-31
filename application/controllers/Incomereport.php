<?php if (!defined('BASEPATH')) exit('No direct script access allowed');

class Incomereport extends MY_Controller {

	function __construct() {
		parent::__construct();
		$this->load->model('user_model', 'user');
		$this->load->model('Product_model');
		$this->load->model('Income_model');
		$this->load->model('Wallet_model');
		$this->load->model('Report_model');
		___construct(1);
		$this->checkSessionTimeout();
		if (function_exists('check_admin_permission')) {
			check_admin_permission();
		}
	}

	public function userdetails(){ return $this->session->userdata('administrator'); }

	public function userlogins(){ return $this->session->userdata('user'); }

	public function index(){

		if(!$this->userdetails()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data = array();

		$this->Report_model->view('incomereports/admin/admin_transaction', $data);

	}



	public function statistics(){

		if(!$this->userlogins()){ redirect('admincontrol/dashboard', 'refresh'); }

		$data = array();

		$this->Report_model->view('incomereports/user/transaction', $data , 'usercontrol');
	}

    public function get_data() {
        $filter = $this->input->post(null, true);
        $userdetails = $this->userlogins();
        $data = $this->Income_model->get_report($filter);

        // Check if it's an export request
        if (isset($_GET['export'])) {
            if ($_GET['export'] == 'excel') {
                // Existing Excel export code
                header('Content-Type: application/vnd.ms-excel');
                header('Content-Disposition: attachment;filename="Income_Report_'.date('Y-m-d').'.xls"');
                header('Cache-Control: max-age=0');
                header('Content-Type: text/html; charset=utf-8');
                
                echo '
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                </head>
                <body>
                    <table border="1">
                        <tr>
                            <th align="center">No</th>
                            <th align="center">Affiliate Name</th>
                            <th align="center">Username</th>
                            <th align="center">Clicks</th>
                            <th align="center">Commission</th>
                            <th align="center">Sales</th>
                            <th align="center">Total</th>
                            <th align="center">Commission</th>
                            <th align="center">CPA</th>
                            <th align="center">Income</th>
                            <th align="center">Total Commission</th>
                        </tr>';

                $i = 1;
                foreach($data['data'] as $record) {
                    echo '<tr>';
                    echo '<td align="center">' . $i . '</td>';
                    echo '<td align="center">' . strip_tags($record['name']) . '</td>';
                    echo '<td align="center">' . $record['username'] . '</td>';
                    echo '<td align="center">' . $record['total_click'] . '</td>';
                    echo '<td align="center">' . $record['total_click_amount'] . '</td>';
                    echo '<td align="center">' . $record['total_sale_count'] . '</td>';
                    echo '<td align="center">' . $record['total_sale_amount'] . '</td>';
                    echo '<td align="center">' . $record['total_sale_comm'] . '</td>';
                    echo '<td align="center">' . $record['external_action_click'] . '/' . $record['action_click_commission'] . '</td>';
                    echo '<td align="center">' . $record['wallet_accept_amount'] . '</td>';
                    echo '<td align="center">' . $record['total_commission'] . '</td>';
                    echo '</tr>';
                    $i++;
                }

                echo '</table></body></html>';
                exit;
            }
            else if ($_GET['export'] == 'pdf') {
                // Similar approach for PDF but with PDF-specific styling
                header('Content-Type: application/pdf');
                header('Content-Disposition: attachment;filename="Income_Report_'.date('Y-m-d').'.pdf"');
                
                echo '
                <html>
                <head>
                    <meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
                    <style>
                        body { font-family: DejaVu Sans, sans-serif; }
                        table { border-collapse: collapse; width: 100%; }
                        th, td { border: 1px solid #000; padding: 8px; text-align: center; }
                        th { background-color: #f2f2f2; }
                        h1 { text-align: center; }
                    </style>
                </head>
                <body>
                    <h1>Income Report</h1>
                    <table>
                        <tr>
                            <th>No</th>
                            <th>Affiliate Name</th>
                            <th>Username</th>
                            <th>Clicks</th>
                            <th>Commission</th>
                            <th>Sales</th>
                            <th>Total</th>
                            <th>Commission</th>
                            <th>CPA</th>
                            <th>Income</th>
                            <th>Total Commission</th>
                        </tr>';

                $i = 1;
                foreach($data['data'] as $record) {
                    echo '<tr>';
                    echo '<td>' . $i . '</td>';
                    echo '<td>' . strip_tags($record['name']) . '</td>';
                    echo '<td>' . $record['username'] . '</td>';
                    echo '<td>' . $record['total_click'] . '</td>';
                    echo '<td>' . $record['total_click_amount'] . '</td>';
                    echo '<td>' . $record['total_sale_count'] . '</td>';
                    echo '<td>' . $record['total_sale_amount'] . '</td>';
                    echo '<td>' . $record['total_sale_comm'] . '</td>';
                    echo '<td>' . $record['external_action_click'] . '/' . $record['action_click_commission'] . '</td>';
                    echo '<td>' . $record['wallet_accept_amount'] . '</td>';
                    echo '<td>' . $record['total_commission'] . '</td>';
                    echo '</tr>';
                    $i++;
                }

                echo '</table></body></html>';
                exit;
            }
        }

        // Regular JSON response code remains the same...
        $jsonResponse = ['data' => []];
        if (isset($filter['destination']) && $filter['destination'] === 'admin-user-stat') {
            $jsonResponse['recordsFiltered'] = $jsonResponse['recordsTotal'] = $this->Income_model->get_report_count($filter);
        }
        
        $loop = $filter['page_no'];
        foreach ($data['data'] as $key => $record) {
            $countryFlagSrc = base_url('assets/template/images/flags/' . strtolower($record['country_code']) . '.png');
            
            $jsonResponse['data'][] = [
                $loop + 1,
                "{$record['name']} <img src='{$countryFlagSrc}' width='20' height='15' class='ms-2'>",
                $record['username'],
                $record['total_click'],
                $record['total_click_amount'],
                $record['total_sale_count'],
                $record['total_sale_amount'],
                $record['total_sale_comm'],
                "{$record['external_action_click']}/{$record['action_click_commission']}",
                $record['wallet_accept_amount'],
                $record['total_commission'],
            ];
            
            $loop++;
        }
        echo json_encode($jsonResponse);
    }

	public function user_search(){

		$data = $this->db->query("SELECT id,CONCAT(firstname,' ',lastname, ' - (', username ,')') as name  FROM users WHERE type='user' AND firstname like '%". $this->input->get('p') ."%' ")->result_array();

		echo json_encode($data);

	}

}

