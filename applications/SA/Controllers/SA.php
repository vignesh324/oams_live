<?php

namespace Applications\SA\Controllers;

use CodeIgniter\Controller;

class SA extends Controller
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
		return view('Applications\SA\Views\index');
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
			'role_id' => 1
		]);

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
				$role_id = $response_data['data']['role_id'];
				session()->set('role_id', $role_id);
				$user_name = $response_data['data']['user_name'];
				session()->set('user_name', $user_name);
				session()->set('access_token_role', 'admin');

				return $response_data = json_decode($response, true);
				// print_r($response_data);exit;
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
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200) {
				// echo '<pre>';print_r($response_data);exit;
				session()->set('access_token', '');
				session()->set('role_id', '');
				session()->set('access_token_role', '');
				session()->set('user_name', '');
				redirect()->route('admin_login')->send();
				exit();
				return redirect()->route('SA');
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
