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
	public function LoginPost() {

		$data=array(
			'email' => $this->request->getPost('email'),
			'password' => $this->request->getPost('password')
		);
		$response_data=array();
		
		$url = "http://localhost:8080/api/login";
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer YourAccessTokenHere',
		];

		$response = make_curl_request($url, $headers, 'POST',$data);
		echo '<pre>';print_r($response);exit;
		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
	}
}