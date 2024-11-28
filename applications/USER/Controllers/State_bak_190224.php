<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class State extends Controller
{
	public function __construct()
    {
        helper('url');
        helper('curl');
		$session = session();
    }
    public function index()
	{
		
		$response_data=array();
		
		$url = "http://localhost:8080/api/user/state";
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer YourAccessTokenHere',
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

		return view('Applications\USER\Views\State\index',[
			'data' => $data,
			'response_data' => $response_data
		]);
	}
}