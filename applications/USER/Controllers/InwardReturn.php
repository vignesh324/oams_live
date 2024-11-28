<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class InwardReturn extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
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
		$url = @apiURL . "user/inwardreturn";
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
        // print_r($response);exit;

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\InwardReturn\index', [
			"data" => $data,
			"response_data" => $response_data['inwardreturn']
		]);
	}

	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/inwardreturn/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
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
		// print_r($response_data);exit;

		return view('Applications\USER\Views\InwardReturn\view', ['response_data' => $response_data]);
	}

	public function add()
	{
		$response_data = array();

		$url = @apiURL . "user/inward/inwardItems1";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$inwarditems_response = make_curl_request($url, $headers, 'GET');
		
		if (isset($inwarditems_response['error'])) {
			echo "cURL Error: " . $inwarditems_response['error'];
		} else {

			$inward_item_data = json_decode($inwarditems_response, true);
		}

		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());


		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\InwardReturn\add', [
			'data' => $data,
			'response_data' => $inward_item_data,
			'url' => 	@basePath . "USER/InwardReturn/Store",
			'title' => 	"Add"
		]);
	}

	public function Store()
	{
		$session_user_id = session()->get('session_user_id');
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/inwardreturn";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = [
			'invoice_id' => $this->request->getpost('invoice_id'),
			'reason' => $this->request->getpost('reason'),
			'return_quantity' => $this->request->getpost('return_quantity'),
			'created_by' => $this->request->getpost('session_user_id'), 
			'date' => $this->request->getpost('date')
		];
		$data = json_encode($data, TRUE);
		 //=echo $data;exit;

		$response = make_curl_request($url, $headers, 'POST', $data);


		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) { //change status code here

				$this->response->setStatusCode(422);
				if (isset($response_data['messages'])) {

					return json_encode(array('errors' => $response_data['messages']));
				} else {
					return json_encode(array('error' => "Unprocessable Entity: Validation Error"));
				}
			} else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}

	public function getInvoiceDetail()
	{
		$response_data = array();
		$id = $this->request->getpost('invoice_no');

		// print_r($id);exit;
		$url = @apiURL . "user/inward/item/" . $id;
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$inwarditems_response = make_curl_request($url, $headers, 'GET');
		if (isset($inwarditems_response['error'])) {
			echo "cURL Error: " . $inwarditems_response['error'];
		} else {

			$invoice_item_detail = json_decode($inwarditems_response, true);
		}
		echo json_encode($invoice_item_detail, true);
	}

	public function delete()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/inwardreturn/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'DELETE');
		//echo '<pre>';print_r($response);exit;


		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) { //change status code here
				// print_r($response);exit;
				$this->response->setStatusCode(422);
				if (isset($response_data['messages'])) {

					return json_encode(array('errors' => $response_data['messages']));
				} else {
					return json_encode(array('error' => "Unprocessable Entity: Validation Error"));
				}
			} else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}
}
