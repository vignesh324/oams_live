<?php

namespace Applications\SA\Controllers;

use CodeIgniter\Controller;

class Users extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		$role_id = session()->get('role_id');
		if ((empty($token) || empty($token_role) || empty($role_id)) && $token_role != 'admin' && $role_id !=1) {
			redirect()->route('admin_login')->send();
			exit();
		}
	}
	public function index()
	{
		$response_data = array();

		$url = @apiURL ."sa/user";
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
		// print_r($response_data['users']);exit;
		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//$active_main_arr  = explode("\\", $router->methodName());
		//echo "<pre>"; print_r($list_response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\SA\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\SA\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\SA\Views\footer', $data);

		return view('Applications\SA\Views\users',  [
			'data' => $data,
			'response_data' => $response_data
		]);
	}



	public function create()
	{
		$list_response_da = array();
		$list_url = @apiURL ."sa/roles";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$list_response = make_curl_request($list_url, $headers, 'GET');
		if (isset($list_response['error'])) {
			echo "cURL Error: " . $list_response['error'];
		} else {
			$list_response_data = json_decode($list_response, true);
		}
		// print_r($list_response_data);exit;
		return view('Applications\SA\Views\user_add', [
			'list_response_data' => $list_response_data['modules'],
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		// print_r($data['id']);exit;
		$response_data = array();
		$list_response_data = array();

		$url = @apiURL ."sa/user/" . $data['id'];
		$list_url = @apiURL ."sa/roles";
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

		$list_response = make_curl_request($list_url, $headers, 'GET');
		if (isset($list_response['error'])) {
			echo "cURL Error: " . $list_response['error'];
		} else {
			$list_response_data = json_decode($list_response, true);
		}
		// print_r($list_response_data);exit;

		return view('Applications\SA\Views\user_edit', [
			'response_data' => $response_data,
			'list_response_data' => $list_response_data['modules'],
		]);
	}

	public function store()
	{
		$url = @apiURL ."sa/user";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'username' => $this->request->getpost('username'),
			'password' => $this->request->getpost('password'),
			'role_id' => $this->request->getpost('role_id'),
			'email' => $this->request->getpost('email'),
			'phone' => $this->request->getpost('phone'),
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
		$url = @apiURL ."sa/user/" . $id;
		$token = session()->get('access_token');

		// print_r($id);
		// exit;

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'username' => $this->request->getpost('username'),
			'password' => $this->request->getpost('password'),
			'role_id' => $this->request->getpost('role_id'),
			'email' => $this->request->getpost('email'),
			'phone' => $this->request->getpost('phone'),
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
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL ."sa/user/" . $data['id'];
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
}
