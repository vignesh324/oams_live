<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;


class Inward extends Controller
{
	public function __construct()
	{
		helper('url');
		helper('curl');
		$session = session();
		$token = session()->get('access_token');
		$token_role = session()->get('access_token_role');
		$role_id = session()->get('role_id');
		if ((empty($token) || empty($token_role) || empty($role_id)) && $token_role != 'user' && $role_id != 2) {
			redirect()->route('user_login')->send();
			exit();
		}
	}
	public function index()
	{
		//Index
		//Get Active Menu

		$response_data = array();


		$url = @apiURL . "user/inward";
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
		// echo '<pre>';print_r($response_data);exit;
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\index', [
			'data' => $data,
			'response_data' => $response_data['inward']
		]);
	}

	public function add()
	{

		$response_data = array();

		$url = @apiURL . "user/inward/addDatas";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$filter_response = make_curl_request($url, $headers, 'GET');
		if (isset($filter_response['error'])) {
			echo "cURL Error: " . $filter_response['error'];
		} else {

			$load_data = json_decode($filter_response, true);
		}

		$load_data['gardens'] = [];
		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		// echo "<pre>"; print_r($load_data);exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\add', [
			'data' => $data,
			'response_data' => $load_data,
			'url' => 	@basePath . "USER/Inward/Store",
			'title' => 	"Add"
		]);
	}
	public function addAjax()
	{

		$response_data = array();

		$url = @apiURL . "user/inward/addDatas";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		print_r($response);
		exit;
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function Store()
	{
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/inward";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);


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

	public function Edit($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$url = @apiURL . "user/inward/addDatas";
		$detail_url = @apiURL . "user/inward/" . $id;
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$filter_response = make_curl_request($url, $headers, 'GET');
		if (isset($filter_response['error'])) {
			echo "cURL Error: " . $filter_response['error'];
		} else {

			$load_data = json_decode($filter_response, true);
		}

		$detail_response = make_curl_request($detail_url, $headers, 'GET');
		if (isset($detail_response['error'])) {
			echo "cURL Error: " . $detail_response['error'];
		} else {

			$detail_data = json_decode($detail_response, true);
		}

		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\add', [
			'data' => $data,
			'response_data' => $load_data,
			'detail_data' => $detail_data['inward'],
			'url' => 	@basePath . "USER/Inward/Update",
			'title' => 	"Edit"
		]);
	}
	public function Update()
	{
		$id = base64_decode($this->request->getpost('id'));
		//$url = @apiURL . "user/center/" . $id;
		$url = @apiURL . "user/inward/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		$response = make_curl_request($url, $headers, 'PUT', $data);
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

	public function delete()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/inward/" . $data['id'];
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

	public function GetGardenGrades()
	{

		$data = [
			'garden_id' => $this->request->getpost('garden_id'),
		];
		$data = json_encode($data, TRUE);
		$response_data = array();

		$url = @apiURL . "user/gardenGrade";
		$token = session()->get('access_token');

		// print_r($token);exit;
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

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
			} elseif (isset($response_data['status']) && $response_data['status'] == 404) {
				return json_encode($response_data);
			} else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code: " . $response_data['status']));
			}
		}
	}
	public function View($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$detail_url = @apiURL . "user/inward/detail/" . $id;
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];



		$detail_response = make_curl_request($detail_url, $headers, 'GET');
		if (isset($detail_response['error'])) {
			echo "cURL Error: " . $detail_response['error'];
		} else {

			$detail_data = json_decode($detail_response, true);
		}

		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\view', [
			'data' => $data,
			'detail_data' => $detail_data['inward'],
			'url' => 	@basePath . "USER/Inward/Update",
			'title' => 	"View"
		]);
	}
}
