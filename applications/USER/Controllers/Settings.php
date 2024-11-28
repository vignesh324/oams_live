<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Settings extends Controller
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
				return $value['module_id'] == 16;
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
		$token = session()->get('access_token');

		$url = @apiURL . "user/settings/viewData";
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
		// print_r($response);exit;
		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);
		return view('Applications\USER\Views\Settings\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function create()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/settings";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'delivery_time' => $this->request->getpost('delivery_time'),
			'increment_amount' => $this->request->getpost('increment_amount'),
			'buyer_show' => $this->request->getpost('buyer_show'),
			'buyer_charges' => $this->request->getpost('buyer_charges'),
			'seller_charges' => $this->request->getpost('seller_charges'),
			'leaf_sq' => $this->request->getpost('leaf_sq'),
			'leaf_hsn' => $this->request->getpost('leaf_hsn'),
			'dust_sq' => $this->request->getpost('dust_sq'),
			'dust_hsn' => $this->request->getpost('dust_hsn'),
			'as_prefix' => $this->request->getpost('as_prefix'),
			'ab_prefix' => $this->request->getpost('ab_prefix'),
			'created_by' => $session_user_id,
		]);

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}
}
