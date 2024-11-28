<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Garden extends Controller
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
				return $value['module_id'] == 5;
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

		$url = @apiURL . "user/garden";
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
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Garden\index', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function create()
	{
		$state_list = array();

		$state_url = @apiURL . "user/stateDropdown";
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

		$seller_url = @apiURL . "user/sellerDropdown";

		$seller_response = make_curl_request($seller_url, $headers, 'GET');
		if (isset($seller_response['error'])) {
			echo "cURL Error: " . $seller_response['error'];
		} else {
			$seller_list = json_decode($seller_response, true);
		}

		$category_data = array();
		$category_url = @apiURL . "user/categoryDropdown";
		$category_response = make_curl_request($category_url, $headers, 'GET');
		if (isset($category_response['error'])) {
			echo "cURL Error: " . $category_response['error'];
		} else {
			$category_data = json_decode($category_response, true);
		}

		// print_r($seller_response);exit;
		return view('Applications\USER\Views\Garden\create', [
			'state_list' => $state_list,
			'seller_list' => $seller_list,
			'category_data' => $category_data,
		]);
	}
	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();
		$stateCityArea = array();
		$seller_list = array();

		$url = @apiURL . "user/garden/" . $data['id'];

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


		$_url = @apiURL . "user/stateCityArea";

		$_response = make_curl_request($_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $_response['error'];
		} else {
			$stateCityArea = json_decode($_response, true);
		}

		$seller_url = @apiURL . "user/sellerDropdown";

		$seller_response = make_curl_request($seller_url, $headers, 'GET');
		if (isset($seller_response['error'])) {
			echo "cURL Error: " . $seller_response['error'];
		} else {
			$seller_list = json_decode($seller_response, true);
		}

		$category_data = array();
		$category_url = @apiURL . "user/categoryDropdown";
		$category_response = make_curl_request($category_url, $headers, 'GET');
		if (isset($category_response['error'])) {
			echo "cURL Error: " . $category_response['error'];
		} else {
			$category_data = json_decode($category_response, true);
		}

		// echo '<pre>';print_r($city_list);exit;

		return view('Applications\USER\Views\Garden\edit', [
			'response_data' => $response_data,
			'list_data' => $stateCityArea,
			'seller_list' => $seller_list,
			'category_data' => $category_data,
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/garden";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'vacumm_bag' => $this->request->getpost('vacumm_bag'),
			'code' => $this->request->getpost('code'),
			'state_id' => $this->request->getpost('state_id'),
			'seller_id' => $this->request->getpost('seller_id'),
			'category_id' => $this->request->getpost('category_id'),
			'city_id' => $this->request->getpost('city_id'),
			'area_id' => $this->request->getpost('area_id'),
			'gst_no' => $this->request->getpost('gst_no'),
			'fssai_no' => $this->request->getpost('fssai_no'),
			'address' => $this->request->getpost('address'),
			'created_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
		]);

		//== print_r($_POST);exit;

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

	public function update()
	{
		$session_user_id = session()->get('session_user_id');
		$id = $this->request->getpost('id');
		$url = @apiURL . "user/garden/" . $id;
		$token = session()->get('access_token');
		// print_r($_POST);exit;

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'name' => $this->request->getpost('name'),
			'vacumm_bag' => $this->request->getpost('vacumm_bag'),
			'code' => $this->request->getpost('code'),
			'category_id' => $this->request->getpost('category_id'),
			'state_id' => $this->request->getpost('state_id'),
			'seller_id' => $this->request->getpost('seller_id'),
			'city_id' => $this->request->getpost('city_id'),
			'area_id' => $this->request->getpost('area_id'),
			'gst_no' => $this->request->getpost('gst_no'),
			'fssai_no' => $this->request->getpost('fssai_no'),
			'address' => $this->request->getpost('address'),
			'updated_by' => $session_user_id,
			'status' => $this->request->getpost('status'),
		]);

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

		$url = @apiURL . "user/garden/" . $data['id'];
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

	public function AssignGrade()
	{

		$grade_list = array();
		$garden_id = $this->request->getpost('id');
		$category_id = $this->request->getpost('category_id');

		$grade_url = @apiURL . "user/Assigngardengradelist/" . $garden_id;
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$grade_response = make_curl_request($grade_url, $headers, 'GET');

		if (isset($response['error'])) {
			echo "cURL Error: " . $grade_response['error'];
		} else {
			$grade_list = json_decode($grade_response, true);
		}

		return view('Applications\USER\Views\Garden\assignGrade', [
			'grade_list' => $grade_list['garden_grade'],
			'garden_id' => $garden_id,
			'category_id' => $category_id,
		]);
	}


	public function SaveGrade()
	{
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/garden/assigngrade";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);


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

	public function reOrder()
	{

		$url = @apiURL . "user/garden/reOrderGrade";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data = file_get_contents("php://input");
		$response = make_curl_request($url, $headers, 'POST', $data);
		return json_encode(['status' => 200, 'message' => 'Reordered Successfully']);
	}

	public function categoryGarden()
	{

		$response_data = array();

		$url = @apiURL . "user/category";
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

		return view('Applications\USER\Views\Garden\category', [
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function AssignCategoryGrade()
	{

		$grade_list = array();
		$category_id = $this->request->getpost('id');

		$grade_url = @apiURL . "user/showcategorygrade/" . $category_id;
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$grade_response = make_curl_request($grade_url, $headers, 'GET');
		// print_r($grade_response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $grade_response['error'];
		} else {
			$grade_list = json_decode($grade_response, true);
		}
		// print_r($grade_list);exit;

		return view('Applications\USER\Views\Garden\commonGrade', [
			'grade_list' => $grade_list['garden_grade'],
			'category_id' => $category_id,
		]);
	}


	public function reOrderCategoryGrade()
	{
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/garden/reOrderCategoryGrade";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getJSON(), TRUE);
		// print_r($this->request->getJSON(true));exit;

		$response = make_curl_request($url, $headers, 'POST', $data);
		print_r($response);exit;

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
