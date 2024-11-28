<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;
use CodeIgniter\HTTP\Request;

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
		
		$url = "http://localhost/OAMS-API/public/api/user/state";
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
		// print_r($response_data['state']);exit;
		
		//Index
		//Get Active Menu
		//Get cActive Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($response_data); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		// print_r($data['footer']);exit;

		return view('Applications\USER\Views\State\index',[
			'data' => $data,
			'response_data' => $response_data
		]);
	}

	public function create()
    {
		// print_r('hiii');
        $data = new State;
        $method = 'POST';
        return view('Applications\USER\Views\State\create');
    }
	public function show()
    {
		$data = ['id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = "http://localhost:8080/api/user/state/".$data['id'];
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
		// print_r($response_data);exit;

        return view('Applications\USER\Views\State\edit', ['response_data' => $response_data]);
    }

	public function store()
    {
		$url = "http://localhost:8080/api/user/state";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' .$token,
		];
		$data = json_encode(['name' => $this->request->getpost('name'),
		 'code' => $this->request->getpost('code'), 
		 'status' => $this->request->getpost('status'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		if (isset($response['error'])) {
			echo "cURL Error: " . $data['error'];
		} else {
			$response_data = json_decode($response, true);
			// echo '<pre>';print_r($response_data);exit;
			// Store the token in the session
			if($response_data['status']==200){
				return $response;
			}else{
				return $response['error'];
			}

		}
    }

}