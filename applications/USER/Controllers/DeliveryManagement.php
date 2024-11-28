<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class DeliveryManagement extends Controller
{
	public function __construct()
	{
		helper(['url', 'curl','common']);
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		$role_id = session()->get('role_id');
		if ((empty($token) || empty($token_role) || empty($role_id)) && $token_role != 'user' && $role_id != 2) {
			redirect()->route('user_login')->send();
			exit();
		}
	}
	public function index()
	{
		$response_data = array();
		$token = session()->get('access_token');

		$url = @apiURL . "user/deliverymanagement";

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

		// echo "<pre>"; print_r($response_data); exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\DeliveryManagement\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}
	public function getDeliveryItems($id)
	{
		$id = base64_decode($id);
		$url = @apiURL . "user/deliverymanagement/getdeliveryitems";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data1 = json_encode([
			'receipt_id' => $id
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// echo "<pre>";
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}

		// echo "<pre>"; print_r($response_data); exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\DeliveryManagement\view', [
			'data' => $data,
			'response_data' => $response_data['delivery']
		]);
	}
	public function create()
	{
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$_url = @apiURL . "user/auction";

		$_response = make_curl_request($_url, $headers, 'GET');
		if (isset($_response['error'])) {
			echo "cURL Error: " . $_response['error'];
		} else {
			$auction_data = json_decode($_response, true);
		}
		// echo "<pre>"; print_r($auction_data); exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\DeliveryManagement\add', [
			'data' => $data,
			'auction_data' => $auction_data,
		]);
	}
	public function getInvoiceByAuctionId()
	{
		$url = @apiURL . "user/deliverymanagement/getInvoices";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id')
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// echo "<pre>"; print_r($response); exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function getInvoiceItems()
	{
		$url = @apiURL . "user/deliverymanagement/getinvoiceitems";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'invoice_id' => $this->request->getpost('invoice_id')
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// echo "<pre>"; print_r($response); exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}

	public function storeItems()
	{
		$url = @apiURL . "user/deliverymanagement/create";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// echo $data;exit;

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}

	public function delete()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/deliverymanagement/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'DELETE');
		// print_r($response);exit;

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
