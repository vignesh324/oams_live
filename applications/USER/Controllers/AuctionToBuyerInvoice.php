<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class AuctionToBuyerInvoice extends Controller
{
	public function __construct()
    {
        helper(['url','curl']);
		helper('common');
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		$role_id = session()->get('role_id');
		if ((empty($token) || empty($token_role) || empty($role_id)) && $token_role != 'user' && $role_id !=2) {
			redirect()->route('user_login')->send();
			exit();
		}
    }
    public function index()
	{
		//Index
		//Get Active Menu

		$url = @apiURL . "user/auctionbuyerinvoice";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		// echo '<pre>';print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo '<pre>';print_r($response_data);exit;

		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\AuctionToBuyerInvoice\index', [
			'data' => $data,
			'response_data' => $response_data['invoices']
		]);
	}
    public function view($id)
	{
		$id = base64_decode($id);
		$url = @apiURL . "user/auctionbuyerinvoice/".$id;
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		
		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo '<pre>';print_r($response_data);exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		//return view('Applications\USER\Views\Invoice\view',$data);
		return view('Applications\USER\Views\AuctionToBuyerInvoice\view', [
			'data' => $data,
			'response_data' => $response_data['invoices']
		]);
	}

}