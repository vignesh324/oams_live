<?php 
namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;

class BUYER extends Controller
{
	public function __construct()
    {
        helper(['url','curl']);
		$session = session();
    }
    public function index()
	{
		//Index
		return view('Applications\BUYER\Views\index');
	}
	
	public function Login()
	{
		
		$url = @apiURL . "buyerlogin";
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer YourAccessTokenHere',
		];
		$data = json_encode([
			'email' => $this->request->getpost('email'),
			'password' => $this->request->getpost('password')
		]);

		$token = session()->get('access_token');

		$response = make_curl_request($url, $headers, 'POST', $data);
		
		//echo '<pre>';print_r($response);exit;
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			

			if (isset($response_data['status']) && $response_data['status'] == 200) {
				// Store the token and role ID in the session
				$token = $response_data['data']['token'];
				session()->set('access_token', $token);
				$user_id = $response_data['data']['user_id'];
				session()->set('user_id', $user_id);
				session()->set('session_user_id', $user_id);
				$buyer_name = $response_data['data']['buyer_name'];
				session()->set('buyer_name', $buyer_name);
				session()->set('access_token_role', 'buyer');

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

		$url = @apiURL . "buyerlogout";
		$token = session()->get('access_token');


		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		// echo '<pre>';print_r($response);exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);

			if (isset($response_data['status']) && $response_data['status'] == 200) {
				// echo '<pre>';print_r($response_data);print_r($token);exit;
				session()->set('access_token', '');
				session()->set('access_token_role', '');
				session()->set('buyer_name', '');
				session()->set('session_user_id', '');
				session()->set('user_id', '');
				redirect()->route('buyer_login')->send();
				exit();
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