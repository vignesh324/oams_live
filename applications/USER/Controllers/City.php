<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class City extends Controller
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
				return $value['module_id'] == 2;
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

		$url = @apiURL . "user/city";
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


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\City\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}


	public function create()
	{
		$state_list = array();

		$state_url = @apiURL . "user/stateDropdown";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$state_response = make_curl_request($state_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $state_response['error'];
		} else {
			$state_list = json_decode($state_response, true);
		}

		return view('Applications\USER\Views\City\create', [
			'state_list' => $state_list
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();
		$state_list = array();

		$url = @apiURL . "user/city/" . $data['id'];
		$state_url = @apiURL . "user/stateDropdown";

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

		$state_response = make_curl_request($state_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $state_response['error'];
		} else {
			$state_list = json_decode($state_response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\City\edit', [
			'response_data' => $response_data,
			'state_list' => $state_list
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/city";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'code' => $this->request->getpost('code'),
			'state_id' => $this->request->getpost('state_id'),
			'created_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
		]);

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
		$url = @apiURL . "user/city/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'code' => $this->request->getpost('code'),
			'state_id' => $this->request->getpost('state_id'),
			'updated_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
		]);

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
		$id = $this->request->getpost('id');
		$response_data = array();

		$url = @apiURL . "user/city/" . $id;
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

	public function stateCity()
	{
		$data = [
			'id' => $this->request->getpost('state_id'),
		];
		$response_data = array();

		$url = @apiURL . "user/statecity/" . $data['id'];
		$token = session()->get('access_token');

		// print_r($token);exit;
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

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
				// print_r($response);exit;
				$this->response->setStatusCode(422);
				if (isset($response_data['messages'])) {

					return json_encode(array('errors' => $response_data['messages']));
				} else {
					return json_encode(array('error' => "Unprocessable Entity: Validation Error"));
				}
			} elseif (isset($response_data['status']) && $response_data['status'] == 404) {
				return json_encode($response_data);
			} else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}
}
