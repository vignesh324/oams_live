<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Log extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');

		$session = session();
		$token = $session->get('access_token');
		$token_role = $session->get('access_token_role');
		$role_id = $session->get('role_id');

		$redirectUrl = null;

		// Check if the token, token_role, or role_id are empty and the token_role is not 'user' or role_id is not 2
		if ((empty($token) || empty($token_role) || empty($role_id)) || ($token_role != 'user' || $role_id != 2)) {
			$redirectUrl = 'user_login';
		}
		// echo '<pre>';print_r($redirectUrl);exit;

		// Perform redirection if needed
		if ($redirectUrl !== null) {
			redirect()->route($redirectUrl)->send();
			exit();
		}
	}

	public function productLog()
	{
		$response_data = array();

		$url = @apiURL . "user/productlog";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		// echo $url;exit;
		$response = make_curl_request($url, $headers, 'GET');
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\ProductLog\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function productLogByDate()
	{
		$response_data = array();

		$url = @apiURL . "user/productlogbydate";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function activityLog()
	{
		$response_data = array();

		$url = @apiURL . "user/activitylog";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		// echo $url;exit;
		$response = make_curl_request($url, $headers, 'GET');
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = 'ActivityLog';

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\ActivityLog\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}


	public function activityLogByDate()
	{
		$response_data = array();

		$url = @apiURL . "user/activitylogbydate";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
}
