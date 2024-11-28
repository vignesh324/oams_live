<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Area extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');

		$session = session();
		$token = $session->get('access_token');
		$token_role = $session->get('access_token_role');
		$role_id = $session->get('role_id');

		$permissions = $session->get('permissions');
		if ($permissions) {
			$filtered_permissions = array_filter($permissions, function ($value, $key) {
				return $value['module_id'] == 3;
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

		$url = @apiURL . "user/area";
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

		// print_r($response_data);exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Area\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function create()
	{
		$state_list = array();
		$city_list = array();

		$state_url = @apiURL . "user/stateDropdown";
		$city_url = @apiURL . "user/city";
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

		$city_response = make_curl_request($city_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $city_response['error'];
		} else {
			$city_list = json_decode($city_response, true);
		}

		return view('Applications\USER\Views\Area\create', [
			'state_list' => $state_list,
			'city_list' => $city_list
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();
		$stateCityArea = array();

		$url = @apiURL . "user/area/" . $data['id'];

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


		$_url = @apiURL . "user/stateCityArea";

		$_response = make_curl_request($_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $_response['error'];
		} else {
			$stateCityArea = json_decode($_response, true);
		}

		return view('Applications\USER\Views\Area\edit', [
			'response_data' => $response_data,
			'list_data' => $stateCityArea,
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/area";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'code' => $this->request->getpost('code'),
			'state_id' => $this->request->getpost('state_id'),
			'city_id' => $this->request->getpost('city_id'),
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
		$id = $this->request->getpost('id');
		// print_r($id);exit;
		$session_user_id = session()->get('session_user_id');

		$url = @apiURL . "user/area/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'code' => $this->request->getpost('code'),
			'state_id' => $this->request->getpost('state_id'),
			'city_id' => $this->request->getpost('city_id'),
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

		$url = @apiURL . "user/area/" . $data['id'];
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
	public function cityArea()
	{
		$data = [
			'id' => $this->request->getpost('city_id'),
		];
		$response_data = array();

		$url = @apiURL . "user/cityarea/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', $data);
		//print_r($response);exit;

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
