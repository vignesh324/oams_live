<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class USER extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
		
	}
	public function index()
	{
		//Index
		return view('Applications\USER\Views\index');
	}

	public function Login()
	{

		$url = @apiURL . "login";
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer YourAccessTokenHere',
		];
		$data = json_encode([
			'email' => $this->request->getpost('email'),
			'password' => $this->request->getpost('password'),
			'role_id' => 2
		]);

		$token = session()->get('access_token');
		// if (!empty($token)) {
		// 	redirect()->route('user_dashboard')->send();
		// 	exit();
		// }

		$response = make_curl_request($url, $headers, 'POST', $data);

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);

			if (isset($response_data['status']) && $response_data['status'] == 200) {
				// Store the token and role ID in the session

				$token = $response_data['data']['token'];
				session()->set('access_token', $token);
				$session_user_id = $response_data['data']['session_user_id'];
				session()->set('session_user_id', $session_user_id);
				$role_id = $response_data['data']['role_id'];
				session()->set('role_id', $role_id);
				$access_modules = $response_data['data']['permissions'];
				session()->set('permissions', $access_modules);
				$user_name = $response_data['data']['user_name'];
				session()->set('user_name', $user_name);
				session()->set('access_token_role', 'user');
				// print_r($session_user_id);exit;

				return $response_data = json_decode($response, true);
			} elseif (isset($response_data['status']) && $response_data['status'] == 422) {
				$this->response->setStatusCode(422);
				if (isset($response_data['message'])) {
					return json_encode(['errors' => $response_data['message']]);
				} else {
					return json_encode(['error' => "Unprocessable Entity: Validation Error"]);
				}
			} else {
				$this->response->setStatusCode(500);
				return json_encode(['error' => "Unexpected status code: " . $response_data['status']]);
			}
		}
	}

	public function Logout()
	{

		$url = @apiURL . "logout";
		$token = session()->get('access_token');


		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		// echo '<pre>';print_r($response);exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);

			if (isset($response_data['status']) && $response_data['status'] == 200) {
				// echo '<pre>';print_r($response_data);print_r($token);exit;
				session()->set('access_token', '');
				session()->set('role_id', '');
				session()->set('user_name', '');
				session()->set('access_token_role', '');
				redirect()->route('user_login')->send();
				exit();
			} elseif (isset($response_data['status']) && $response_data['status'] == 422) { //change status code here

				$this->response->setStatusCode(422);

				if (isset($response_data['message'])) {

					return json_encode(array('errors' => $response_data['message']));
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
