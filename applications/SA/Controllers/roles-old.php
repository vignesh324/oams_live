<?php

namespace Applications\SA\Controllers;

use CodeIgniter\Controller;

class roles extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
	}
	public function index()
	{

		$response_data = array();

		$url = @apiURL . "sa/modules";
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

		return view('Applications\SA\Views\roll', [
			'data' => $data,
			'response_data' => $response_data['modules'],
			'list_response_data' => $list_response_data['modules'],
		]);
	}

	public function store()
	{

		//echo json_encode($_POST);exit;
		$url = @apiURL ."sa/storeRole";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($_POST);
		$response = make_curl_request($url, $headers, 'POST', $data);
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

	public function edit()
	{

		//echo json_encode($_POST);exit;
		$id = $this->request->getpost('id');
		$url = @apiURL.'sa/roles/' . $id;
		$url_modules = @apiURL.'sa/modules';



		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($_POST);
		$response = make_curl_request($url, $headers, 'GET');

		$response = json_decode($response, true);

		$response_modules = make_curl_request($url_modules, $headers, 'GET');
		if (isset($response_modules['error'])) {
			echo "cURL Error: " . $response_modules['error'];
		} else {
			$response_data = json_decode($response_modules, true);
		}

		// echo '<pre>';print_r($response);exit;

		return view('Applications\SA\Views\role_edit', [
			'data' => $data,
			'old_data' => $response,
			'response_data' => $response_data['modules']
		]);
	}


	public function update()
	{
		// echo json_encode($_POST);
		// exit;
		$id = $this->request->getpost('role_id');
		$url = @apiURL ."sa/updateRole/" . $id;
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($_POST);
		// print_r($data);exit;

		$response = make_curl_request($url, $headers, 'PUT', $data);
		// print_r($response);
		// exit;
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

		$url = @apiURL ."sa/deleteRole/" . $data['id'];
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
			print_r($response);
			exit;
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
