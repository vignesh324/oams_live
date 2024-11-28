<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Buyer extends Controller
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
				return $value['module_id'] == 6;
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

		$url = @apiURL . "user/buyer";
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

		return view('Applications\USER\Views\Buyer\index', [
			'data' => $data,
			'response_data' => $response_data['buyer']
		]);
	}

	public function create()
	{
		$stateCityArea = array();

		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$_url = @apiURL . "user/stateCityArea";

		$_response = make_curl_request($_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $_response['error'];
		} else {
			$stateCityArea = json_decode($_response, true);
		}


		return view('Applications\USER\Views\Buyer\create_edit', [
			'list_data' => $stateCityArea,
			'url' => 	@basePath . "USER/Buyer/Create",
			'title' => 	"Add Buyer",
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();
		$stateCityArea = array();

		$url = @apiURL . "user/buyer/" . $data['id'];
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

		return view('Applications\USER\Views\Buyer\create_edit', [
			'response_data' => $response_data,
			'list_data' => $stateCityArea,
			'url' => 	@basePath . "USER/Buyer/Update",
			'title' => 	"Update Buyer",
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/buyer";
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
			'area_id' => $this->request->getpost('area_id'),
			'gst_no' => $this->request->getpost('gst_no'),
			'fssai_no' => $this->request->getpost('fssai_no'),
			'tea_board_no' => $this->request->getpost('tea_board_no'),
			'charges' => $this->request->getpost('charges'),
			'address' => $this->request->getpost('address'),
			'contact_person_name' => $this->request->getpost('contact_person_name'),
			'contact_person_number' => $this->request->getpost('contact_person_number'),
			'email' => $this->request->getpost('email'),
			'password' => $this->request->getpost('password'),
			'created_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
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

	public function update()
	{
		$session_user_id = session()->get('session_user_id');

		$id = $this->request->getpost('id');
		$url = @apiURL . "user/buyer/" . $id;
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
			'area_id' => $this->request->getpost('area_id'),
			'gst_no' => $this->request->getpost('gst_no'),
			'fssai_no' => $this->request->getpost('fssai_no'),
			'tea_board_no' => $this->request->getpost('tea_board_no'),
			'charges' => $this->request->getpost('charges'),
			'address' => $this->request->getpost('address'),
			'contact_person_name' => $this->request->getpost('contact_person_name'),
			'contact_person_number' => $this->request->getpost('contact_person_number'),
			'email' => $this->request->getpost('email'),
			'password' => $this->request->getpost('password'),
			'updated_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
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

		$url = @apiURL . "user/buyer/" . $data['id'];
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
