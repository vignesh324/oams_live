<?php

namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		if ((empty($token) || empty($token_role)) && $token_role != 'buyer') {
			redirect()->route('buyer_login')->send();
			exit();
		}
	}
	public function index()
	{

		$response_data = array();


		$url = @apiURL . "buyer/dashboard";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		//echo '<pre>';print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//$active_main_arr  = explode("\\", $router->methodName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\BUYER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\BUYER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\BUYER\Views\footer', $data);

		return view('Applications\BUYER\Views\dashboard', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function profile()
	{
		// echo '<pre>';print_r('hii');exit;

		$response_data = array();
		$id = session()->get('session_user_id');

		$url = @apiURL . "buyer/profile/" . $id;
		$token = session()->get('access_token');
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

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//$active_main_arr  = explode("\\", $router->methodName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\BUYER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\BUYER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\BUYER\Views\footer', $data);

		return view('Applications\BUYER\Views\profile', [
			'data' => $data,
			'url' => 	@basePath . "USER/Buyer/Update",
			'title' => 	"Update Buyer",
			'response_data' => $response_data
		]);
	}

	public function profileUpdate()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "buyer/profileupdate";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'id' => $this->request->getpost('id'),
			'name' => $this->request->getpost('name'),
			'gst_no' => $this->request->getpost('gst_no'),
			'address' => $this->request->getpost('address')
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// echo '<pre>';print_r($response);exit;

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
}
