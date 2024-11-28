<?php

namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;

class BiddingCenter extends Controller
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
	public function index($id)
	{
		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "buyer/getAuctionItemsByCenter/" . $id . "/" . $session_user_id;

		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');

		$response_data = json_decode($response, true);
		// echo '<pre>';
		// print_r($response_data['auction']);
		// exit;
		if (isset($response_data['auction']) && count($response_data['auction']) == 0) {
			echo 'No live Auctions';
			return redirect()->route("completed-auctions")->with("error", "No live Auctions");
		}

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}

		// echo '<pre>';
		// print_r($response_data['auction']);
		// exit;
		// $auction_id = $response_data['auction'][0]['id'];
		// $my_catalogs_url = @apiURL . "buyer/getMyCatalogs/".$auction_id."/".$session_user_id;
		// $my_catalogue_response = make_curl_request($my_catalogs_url, $headers, 'GET');
		//  //echo '<pre>';print_r($my_catalogue_response);exit;
		//  if (isset($my_catalogue_response['error'])) {
		// 	echo "cURL Error: " . $my_catalogue_response['error'];
		// } else {
		// 	$my_catalogue_response_data = json_decode($my_catalogue_response, true);
		// }
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

		return view('Applications\BUYER\Views\view_centers', [
			"data" => $data,
			"response_data" => $response_data['auction'],
			// "mycatalog_response_data" => $my_catalogue_response_data['auction']
		]);
	}

	public function detail($id)
	{
		$id = base64_decode($id);

		$url = @apiURL . "buyer/getAuctionItemsByCenter/" . $id;
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

		return view('Applications\BUYER\Views\view_centers', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}

	public function storeCatalog()
	{

		$url = @apiURL . "buyer/addtoCatalog";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$session_user_id = session()->get('session_user_id');
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'auction_item_id' => $this->request->getpost('auction_item_id'),
			'buyer_id' => $session_user_id,
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

	public function autoBidding()
	{
		$session_user_id = session()->get('user_id');
		$data = [
			'auctionitem_id' => $this->request->getpost('auctionitem_id'),
			'auction_id' => $this->request->getpost('auction_id'),
			'buyer_id' => $session_user_id,

		];
		$response_data = array();

		$url = @apiURL . "buyer/autoBidding/view";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data));
		// print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}

		return view('Applications\BUYER\Views\auto_bid', [
			'response_data' => $response_data,
			'url' => 	@basePath . "BUYER/AutoBidding",
			'title' => 	"Auto Bidding",
			'data1' => $data
		]);
	}

	public function autoBiddingStore()
	{
		$data = [
			'auctionitem_id' => $this->request->getpost('auctionitem_id'),
			'auction_id' => $this->request->getpost('auction_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'min_price' => $this->request->getpost('min_price'),
			'max_price' => $this->request->getpost('max_price'),
		];
		$response_data = array();

		$url = @apiURL . "buyer/autoBidding";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data));
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

	public function addMinBid()
	{

		$data = [
			'auction_item_id' => $this->request->getpost('auction_item_id'),
			'auction_id' => $this->request->getpost('auction_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'min_price' => $this->request->getpost('min_amt')
		];

		$response_data = array();

		$url = @apiURL . "buyer/addMinAutoBidPrice";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data));

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);

			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) {

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

	public function addMaxBid()
	{

		$data = [
			'auction_item_id' => $this->request->getpost('auction_item_id'),
			'auction_id' => $this->request->getpost('auction_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'max_price' => $this->request->getpost('max_amt')
		];

		$response_data = array();

		$url = @apiURL . "buyer/addMaxAutoBidPrice";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data));
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) {

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
	public function deleteBidData()
	{

		$data = [
			'auction_item_id' => $this->request->getpost('auction_item_id'),
			'auction_id' => $this->request->getpost('auction_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
		];

		$response_data = array();

		$url = @apiURL . "buyer/deleteBidData";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data));
		// echo '<pre>';
		// print_r($response);
		// exit;
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) {

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
