<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class SampleReceipt extends Controller
{

	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		$role_id = session()->get('role_id');

		$permissions = $session->get('permissions');
		if ($permissions) {
			$filtered_permissions = array_filter($permissions, function ($value, $key) {
				return $value['module_id'] == 20;
			}, ARRAY_FILTER_USE_BOTH);

			$area_permission = array_values($filtered_permissions);
		}
		
		$redirectUrl = null;

		// Check if the token, token_role, or role_id are empty and the token_role is not 'user' or role_id is not 2
		if ((empty($token) || empty($token_role) || empty($role_id)) || ($token_role != 'user' || $role_id != 2)) {
			$redirectUrl = 'user_login';
		} elseif (empty($area_permission)) {
			$redirectUrl = 'access_denied';
		}
		// echo '<pre>';print_r($redirectUrl);exit;

		// Perform redirection if needed
		if ($redirectUrl !== null) {
			redirect()->route($redirectUrl)->send();
			exit();
		}
	}
	public function index()
	{
		$response_data = array();

		$url = @apiURL . "user/samplereceipt";
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
		// print_r($response_data['sampleReceipt']);exit;

		//Index
		//Get Active Menu
		//Get cActive Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		// print_r($data['footer']);exit;

		$settings_url = @apiURL . "user/settings";

		$settings_response = make_curl_request($settings_url, $headers, 'GET');

		if (isset($settings_response['error'])) {
			echo "cURL Error: " . $settings_response['error'];
		} else {
			$settings_response_data = json_decode($settings_response, true);
		}

		return view('Applications\USER\Views\SampleReceipt\index', [
			'data' => $data,
			'response_data' => $response_data,
			"settings_response_data" => $settings_response_data['settings'],
		]);
	}

	public function create()
	{
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$buyer_data = array();

		$buyer_url = @apiURL . "user/buyerDropdown";

		$buyer_response = make_curl_request($buyer_url, $headers, 'GET');
		if (isset($buyer_response['error'])) {
			echo "cURL Error: " . $buyer_response['error'];
		} else {
			$buyer_data = json_decode($buyer_response, true);
		}


		$lot_url = @apiURL . "user/auctionSaleNo";

		$lot_response = make_curl_request($lot_url, $headers, 'GET');

		if (isset($lot_response['error'])) {
			echo "cURL Error: " . $lot_response['error'];
		} else {
			$lot_response_data = json_decode($lot_response, true);
		}


		$sample_url = @apiURL . "user/samplequantityDropdown";

		$sample_response = make_curl_request($sample_url, $headers, 'GET');

		if (isset($sample_response['error'])) {
			echo "cURL Error: " . $sample_response['error'];
		} else {
			$sample_response_data = json_decode($sample_response, true);
		}
		
		$settings_url = @apiURL . "user/settings";

		$settings_response = make_curl_request($settings_url, $headers, 'GET');

		if (isset($settings_response['error'])) {
			echo "cURL Error: " . $settings_response['error'];
		} else {
			$settings_response_data = json_decode($settings_response, true);
		}

		// echo "<pre>";
		// print_r($lot_response_data);
		// exit;
		return view('Applications\USER\Views\SampleReceipt\create', [
			"buyer_data" => $buyer_data['buyer'],
			"lot_response_data" => $lot_response_data['auction'],
			"sample_response_data" => $sample_response_data['sampleQuantity'],
			"settings_response_data" => $settings_response_data['settings'],
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/samplereceipt/" . $data['id'];
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


		$buyer_data = array();

		$buyer_url = @apiURL . "user/buyerDropdown";

		$buyer_response = make_curl_request($buyer_url, $headers, 'GET');
		if (isset($buyer_response['error'])) {
			echo "cURL Error: " . $buyer_response['error'];
		} else {
			$buyer_data = json_decode($buyer_response, true);
		}

		$sale_url = @apiURL . "user/auctionSaleNo";

		$sale_response = make_curl_request($sale_url, $headers, 'GET');

		if (isset($lot_response['error'])) {
			echo "cURL Error: " . $sale_response['error'];
		} else {
			$sale_response_data = json_decode($sale_response, true);
		}


		$lot_url = @apiURL . "user/salenoWiseLotnoSelect";

		$lot_response = make_curl_request($lot_url, $headers, 'GET');

		if (isset($lot_response['error'])) {
			echo "cURL Error: " . $lot_response['error'];
		} else {
			$lot_response_data = json_decode($lot_response, true);
		}

		$sample_url = @apiURL . "user/samplequantityDropdown";

		$sample_response = make_curl_request($sample_url, $headers, 'GET');

		if (isset($sample_response['error'])) {
			echo "cURL Error: " . $sample_response['error'];
		} else {
			$sample_response = json_decode($sample_response, true);
		}

		// echo '<pre>';print_r($response_data);exit;

		return view('Applications\USER\Views\SampleReceipt\edit', [
			"response_data" => $response_data,
			"buyer_data" => $buyer_data['buyer'],
			"lot_response_data" => $lot_response_data,
			"sale_response_data" => $sale_response_data['auction'],
			"sample_response_data" => $sample_response['sampleQuantity']
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/samplereceipt";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		// print_r($_POST);exit;
		$data = json_encode([
			'quantity' => $this->request->getpost('quantity'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'lot_no' => $this->request->getpost('lot_no'),
			'auctionitem_id' => $this->request->getpost('auctionitem_id'),
			'auction_id' => $this->request->getpost('sale_no'),
			'status' => $this->request->getpost('status'),
			'created_by' => $session_user_id,
		]);

		// echo '<pre>';print_r($data);exit;
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

	public function update()
	{
		$session_user_id = session()->get('session_user_id');
		$id = $this->request->getpost('id');
		$url = @apiURL . "user/samplereceipt/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'quantity' => $this->request->getpost('quantity'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'lot_no' => $this->request->getpost('lot_no'),
			'auctionitem_id' => $this->request->getpost('auctionitem_id'),
			'auction_id' => $this->request->getpost('sale_no'),
			'status' => $this->request->getpost('status'),
			'created_by' => $session_user_id,
		]);
		// echo '<pre>';print_r($data);exit;

		$response = make_curl_request($url, $headers, 'PUT', $data);

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

	public function delete()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/samplereceipt/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'DELETE');

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

	public function salenoWiseLot()
	{
		// echo 'hii';exit;
		$data = [
			'auction_id' => $this->request->getpost('auction_id'),
		];
		$response_data = array();

		$url = @apiURL . "user/salenoWiseLot";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];


		$response = make_curl_request($url, $headers, 'POST', json_encode($data));
		// print_r($response);

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
