<?php

namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;
use Symfony\Component\Routing\Annotation\Route;

class Auctions extends Controller
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
	public function upcoming()
	{

		$url = @apiURL . "buyer/getUpcomingAuctions";
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
		//  echo '<pre>';print_r($response_data['auction']);exit;

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

		return view('Applications\BUYER\Views\upcoming_auctions', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}
	public function completedAuctions()
	{

		$url = @apiURL . "buyer/getCompletedAuctions";
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
		// echo '<pre>';print_r($response_data['auction']);exit;

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

		return view('Applications\BUYER\Views\completed_auctions', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}
	public function detail($id)
	{
		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');

		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];


		$auct_url = @apiURL . "user/auction/" . $id;
		$auct_response = make_curl_request($auct_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $auct_response['error'];
		} else {
			$auct_response_data = json_decode($auct_response, true);
		}
		// echo '<pre>';print_r($auct_response_data['auction']);exit;


		$url = @apiURL . "buyer/getAuctionItemDetails/" . $id . "/" . $session_user_id;

		$response = make_curl_request($url, $headers, 'GET');
		// echo '<pre>';print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		//  echo '<pre>';print_r($response_data['auction']);exit;

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

		if ($auct_response_data['auction']['min_hour_over'] == 1) {
			return redirect()->route('BUYER/Dashboard');
		} else {
			return view('Applications\BUYER\Views\auction_details', [
				"data" => $data,
				"response_data" => $response_data['auction'],
				"flag" => 0
			]);
		}		
	}

	public function completedDetail($id)
	{
		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];


		$auct_url = @apiURL . "user/auction/" . $id;
		$auct_response = make_curl_request($auct_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $auct_response['error'];
		} else {
			$auct_response_data = json_decode($auct_response, true);
		}
		// echo '<pre>';print_r($auct_response_data);exit;


		$url = @apiURL . "buyer/getAuctionItemDetails/" . $id . "/" . $session_user_id;

		//$url = @apiURL . "buyer/getAuctionItemDetails/" . $id;


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

		return view('Applications\BUYER\Views\auction_details', [
			"data" => $data,
			"response_data" => $response_data['auction'],
			'flag' => 1
		]);
	}

	public function addtoCatalog()
	{
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/addtoCatalog";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

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
	public function myCatalogTable($id)
	{
		// print_r($id);exit;

		$session_user_id = session()->get('user_id');

		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$my_catalogs_url = @apiURL . "buyer/getMyCatalogs/" . $id . "/" . $session_user_id;
		$my_catalogue_response = make_curl_request($my_catalogs_url, $headers, 'GET');
		// echo '<pre>';print_r($my_catalogue_response);exit;

		if (isset($my_catalogue_response['error'])) {
			echo "cURL Error: " . $my_catalogue_response['error'];
		} else {
			$my_catalogue_response_data = json_decode($my_catalogue_response, true);
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

		return view('Applications\BUYER\Views\my_catalog', [
			"data" => $data,
			"mycatalog_response_data" => $my_catalogue_response_data['auction']
		]);
	}

	public function auctionLots($id)
	{

		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/getAuctionItemsByAuction/" . $id . "/" . $session_user_id;

		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');
		 //echo '<pre>';print_r($response);exit;

		if (isset($response) && $response == '') {
			echo 'No live Auctions';
			exit;
		}

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		 //echo '<pre>';print_r($response_data);exit;

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

		return view('Applications\BUYER\Views\biddingFrame', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}
	public function mybidBook($id)
	{

		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/getmyBidbookByAuction/" . $id . "/" . $session_user_id;

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

		return view('Applications\BUYER\Views\my_bidbook', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}

	public function movetoclosed()
	{
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/movetoclosed";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

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

	public function movetoreview()
	{
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/movetoreview";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

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

	public function getBidTiming()
	{
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/getBidTiming";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}

	public function completeManualTime()
	{

		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/completeMinTime";
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
			return json_encode($response_data);
		}
	}
	public function autoBidLog()
	{

		$url = @apiURL . "buyer/autoBidLog";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
}
