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
		if ((empty($token) || empty($token_role) || empty($role_id)) && $token_role != 'user' && $role_id !=2) {
			redirect()->route('user_login')->send();
			exit();
		}
    }
    public function index()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\index');
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


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Inward\add',[
			'data' => $data,
			'response_data' => $load_data]);
	}
}