<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class BiddingSession extends Controller
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
		$url = @apiURL . "user/auction";
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

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\index', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}

	public function create()
	{
		$url = @apiURL . "user/centerDropdown";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$center_response = make_curl_request($url, $headers, 'GET');

		if (isset($center_response['error'])) {
			echo "cURL Error: " . $center_response['error'];
		} else {

			$center_data = json_decode($center_response, true);
		}
		return view('Applications\USER\Views\BiddingSession\add', [
			'centers' => $center_data['center'],
		]);
	}
	public function add()
	{
		$response_data = array();
		$session_user_id = session()->get('session_user_id');
		$url = @apiURL . "user/centerDropdown";
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$center_response = make_curl_request($url, $headers, 'GET');
		if (isset($center_response['error'])) {
			echo "cURL Error: " . $center_response['error'];
		} else {

			$center_data = json_decode($center_response, true);
		}

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		session()->remove('header_data');
		session()->remove('auction_data');
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\add', [
			'data' => $data,
			'centers' => $center_data['center'],
			'url' => 	@basePath . "USER/BiddingSession/Store",
			'title' => 	"Add"
		]);
	}

	public function store()
	{
		$session_user_id = session()->get('session_user_id');
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/auction";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = [
			'center_id' => $this->request->getPost('center_id'),
			'date' => $this->request->getPost('date'),
			'start_time' => $this->request->getPost('start_time'),
			'end_time' => $this->request->getPost('end_time'),
			'type' => $this->request->getPost('type'),
			'session_time' => $this->request->getPost('session_time'),
			'lot_count' => $this->request->getPost('lot_count'),
			'created_by' => $session_user_id,
		];
		$data['auction_data'] = session()->get('auction_data_final');
		$data['sequence_data'] = [
			'sequence' => $this->request->getPost('sequence'),
			'gardenId' => $this->request->getPost('step_garden_ids'),
		];

		$data = json_encode($data, TRUE);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// echo "<pre>"; print_r($response);exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}

	public function edit()
	{
		$response_data = array();
		$id = $this->request->getpost('id');
		// echo "<pre>"; print_r($id); echo "</pre>"; exit;
		$url = @apiURL . "user/auction/" . $id;
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

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\edit', [
			'data' => $data,
			'auction_data' => $response_data['auction'],
			'url' => 	@basePath . "USER/BiddingSession/Update",
			'title' => 	"Edit",
			'id' =>  $id
		]);
	}

	public function update()
	{
		$id = $this->request->getpost('id');
		// echo $id;
		// exit;
		$url = @apiURL . "user/auction/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// echo $data;exit;


		$response = make_curl_request($url, $headers, 'PUT', $data);
		// echo "<pre>"; print_r($response);exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}

	public function delete()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		$response_data = array();

		$url = @apiURL . "user/auction/" . $data['id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'DELETE');
		// print_r($response);exit;

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
	public function view($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$data_url = @apiURL . "user/auction/" . $id;

		$url = @apiURL . "user/centerDropdown";
		$center_url = @apiURL . "user/centerGarden";
		$inward_url = @apiURL . "user/auction/biddingSessionViewDetail";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$center_response = make_curl_request($url, $headers, 'GET');
		if (isset($center_response['error'])) {
			echo "cURL Error: " . $center_response['error'];
		} else {

			$center_data = json_decode($center_response, true);
		}


		$data_response = make_curl_request($data_url, $headers, 'GET');
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$centerdata = [
			'center_id' => $auction_data['auction']['center_id'],
		];
		$centerdata = json_encode($centerdata, TRUE);
		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $centerdata);

		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {

			$centergarden_data = json_decode($centergarden_response, true);
		}


		$inwarddata = [
			'center_id' => $auction_data['auction']['center_id'],
			'auction_id' => $auction_data['auction']['id'],
		];

		$inwarddata = json_encode($inwarddata, TRUE);

		$inward_response = make_curl_request($inward_url, $headers, 'POST', $inwarddata);


		if (isset($inward_response['error'])) {
			echo "cURL Error: " . $inward_response['error'];
		} else {

			$inward_data = json_decode($inward_response, true);
		}


		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\view', [
			'data' => $data,
			'centers' => $center_data['center'],
			'auction_data' => $auction_data['auction'],
			'inward_data' => $inward_data['data'],
			'title' => 	"View"
		]);
	}

	public function completedAuctions($id)
	{
		$id = base64_decode($id);

		$data_url = @apiURL . "user/auction/" . $id;

		$completed_url = @apiURL . "user/auction/completedAuctionDetail";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$_data = [
			'center_id' => $auction_data['auction']['center_id'],
			'auction_id' => $auction_data['auction']['id'],
		];

		$_data = json_encode($_data, TRUE);

		$completed_response = make_curl_request($completed_url, $headers, 'POST', $_data);
		//echo '<pre>';print_r($completed_response);exit;

		if (isset($completed_response['error'])) {
			echo "cURL Error: " . $completed_response['error'];
		} else {

			$completed_response_data = json_decode($completed_response, true);
		}
		// echo '<pre>';print_r($completed_response_data);exit;


		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\completed_auctions', [
			'data' => $data,
			'auction_data' => $auction_data['auction'],
			'completed_data' => $completed_response_data['data'],
			'title' => 	"View"
		]);
	}

	public function closeAuction()
	{
		// $id = $this->request->getpost('auction_id');
		// echo $id;
		// exit;
		$url = @apiURL . "user/auction/closeAuction";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// echo $data;exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}
	public function editValuation($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$data_url = @apiURL . "user/auction/" . $id;

		$url = @apiURL . "user/centerDropdown";
		$center_url = @apiURL . "user/centerGarden";
		$inward_url = @apiURL . "user/auction/biddingSessionView1";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$center_response = make_curl_request($url, $headers, 'GET');
		if (isset($center_response['error'])) {
			echo "cURL Error: " . $center_response['error'];
		} else {

			$center_data = json_decode($center_response, true);
		}


		$data_response = make_curl_request($data_url, $headers, 'GET');
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$centerdata = [
			'center_id' => $auction_data['auction']['center_id'],
		];
		$centerdata = json_encode($centerdata, TRUE);
		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $centerdata);

		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {

			$centergarden_data = json_decode($centergarden_response, true);
		}


		$inwarddata = [
			'center_id' => $auction_data['auction']['center_id'],
		];

		$inwarddata = json_encode($inwarddata, TRUE);


		$inward_response = make_curl_request($inward_url, $headers, 'POST', $inwarddata);

		if (isset($inward_response['error'])) {
			echo "cURL Error: " . $inward_response['error'];
		} else {

			$inward_data = json_decode($inward_response, true);
		}
		// echo "<pre>"; print_r($auction_data);exit;

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\edit_valuation_price', [
			'data' => $data,
			'centers' => $center_data['center'],
			'auction_data' => $auction_data['auction'],
			'inward_data' => $inward_data['data'],
			'title' => 	"Edit"
		]);
	}

	public function withDraw($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$data_url = @apiURL . "user/auction/" . $id;

		$url = @apiURL . "user/centerDropdown";
		$center_url = @apiURL . "user/centerGarden";
		$inward_url = @apiURL . "user/auction/biddingSessionView1";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$center_response = make_curl_request($url, $headers, 'GET');
		if (isset($center_response['error'])) {
			echo "cURL Error: " . $center_response['error'];
		} else {

			$center_data = json_decode($center_response, true);
		}


		$data_response = make_curl_request($data_url, $headers, 'GET');
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$centerdata = [
			'center_id' => $auction_data['auction']['center_id'],
		];
		$centerdata = json_encode($centerdata, TRUE);
		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $centerdata);

		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {

			$centergarden_data = json_decode($centergarden_response, true);
		}


		$inwarddata = [
			'center_id' => $auction_data['auction']['center_id'],
		];

		$inwarddata = json_encode($inwarddata, TRUE);


		$inward_response = make_curl_request($inward_url, $headers, 'POST', $inwarddata);

		if (isset($inward_response['error'])) {
			echo "cURL Error: " . $inward_response['error'];
		} else {

			$inward_data = json_decode($inward_response, true);
		}
		// echo "<pre>"; print_r($auction_data);exit;

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\withdraw', [
			'data' => $data,
			'centers' => $center_data['center'],
			'auction_data' => $auction_data['auction'],
			'inward_data' => $inward_data['data'],
			'title' => 	"Edit"
		]);
	}

	public function updateValuation()
	{
		$id = $this->request->getpost('id');
		// echo $id;
		// exit;
		$url = @apiURL . "user/auction/updateValuation";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// echo $data;exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}
	public function updateReservePrice()
	{
		$id = $this->request->getpost('id');
		// echo $id;
		// exit;
		$url = @apiURL . "user/auction/updateReservePrice";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// echo $data;exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}
	public function editReserve($id)
	{
		$response_data = array();
		$id = base64_decode($id);

		$data_url = @apiURL . "user/showAuctionItems/" . $id;

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');
		//echo '<pre>';print_r($data_response);exit;

		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {
			$auction_data = json_decode($data_response, true);
		}

		// echo "<pre>"; print_r($auction_data);exit;

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\edit_reserve_price', [
			'data' => $data,
			'auction_data' => $auction_data['auction'],
			'title' => 	"Edit"
		]);
	}

	public function editReserveBidframe($id)
	{

		$id = base64_decode($id);
		$session_user_id = session()->get('user_id');
		$url = @apiURL . "user/auction/AuctionReserveBidframe";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data = [
			'id' => $id,
		];

		$response = make_curl_request($url, $headers, 'POST', json_encode($data, true));
		// echo '<pre>';print_r($response);exit;

		if (isset($response) && $response == '') {
			echo 'No live Auctions';
			exit;
		}

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo '<pre>';print_r($response_data);exit;

		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\biddingFrameReserve', [
			"data" => $data,
			"response_data" => $response_data['auction']
		]);
	}

	public function GetInwardItems()
	{
		$data = [
			'center_id' => $this->request->getpost('center_id'),
		];
		$data = json_encode($data, TRUE);
		$response_data = array();

		$url = @apiURL . "user/auction/getInvoicesByWarehouseId";
		$token = session()->get('access_token');

		// print_r($token);exit;
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

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
	public function GetInwardItemsByWarehouseId()
	{
		$data = [
			'center_id' => $this->request->getpost('center_id'),
			'type' => $this->request->getpost('type'),
			'warehouse_id' => $this->request->getpost('warehouse_id'),
			'garden_id' => $this->request->getpost('garden_id'),
		];

		// print_r($data);exit;
		$data = json_encode($data, TRUE);
		$response_data = array();

		$url = @apiURL . "user/auction/getInvoicesByWarehouseId";
		$token = session()->get('access_token');

		// print_r($token);exit;
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'POST', $data);
		// echo "<pre>";
		// print_r($response);
		// exit;
		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);
			// print_r($response_data);
			// exit;

			if (isset($response_data['status']) && $response_data['status'] == 200)
				return json_encode($response_data);
			elseif (isset($response_data['status']) && $response_data['status'] == 422) { //change status code here
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

	public function GetCenterGardens()
	{

		$data = [
			'center_id' => $this->request->getpost('center_id'),
		];
		$data = json_encode($data, TRUE);
		$response_data = array();

		$url = @apiURL . "user/centerGarden";
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

	public function GetInwardItemDetails()
	{

		$data = [
			'invoice_id' => $this->request->getpost('invoice_id'),
		];

		$response_data = array();

		$url = @apiURL . "user/inward/item/" . $data['invoice_id'];
		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');

		//print_r($response);exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {

			$response_data = json_decode($response, true);

			if (!empty($response_data))
				return json_encode($response_data);
			else {
				$this->response->setStatusCode(500);
				return json_encode(array('error' => "Unexpected status code "));
			}
		}
	}


	public function reOrderBiddingSession()
	{
		// echo 'hii';
		// exit;
		$url = @apiURL . "user/reOrderBiddingSession";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data = file_get_contents("php://input");
		$response = make_curl_request($url, $headers, 'POST', $data);

		// print_r($response);exit;
		return json_encode(['status' => 200, 'message' => 'Reordered Successfully', 'response' => $response]);
	}

	public function storeEachSession()
	{
		$auction_items = session()->get('auction_data');
		if (!is_array($auction_items)) {
			$auction_items = [];
		}

		$total_quantity_array = $this->request->getPost('total_quantity');
		$auction_quantity_array = $this->request->getPost('auction_quantity');
		$base_price_array = $this->request->getPost('base_price');
		$reverse_price_array = $this->request->getPost('reverse_price');
		$high_price_array = $this->request->getPost('high_price');
		$inward_item_array = $this->request->getPost('inward_item');
		$inward_item_garden_array = $this->request->getPost('inward_item_garden');
		$inward_item_grade_array = $this->request->getPost('inward_item_grade');
		$inward_item_garden_id_array = $this->request->getPost('inward_item_garden_id');
		$inward_item_grade_id_array = $this->request->getPost('grade_id');

		$auction_item = [
			'total_quantity' => $total_quantity_array,
			'auction_quantity' => $auction_quantity_array,
			'base_price' => $base_price_array,
			'reverse_price' => $reverse_price_array,
			'high_price' => $high_price_array,
			'inward_item_id' => $inward_item_array,
			'garden_name' => $inward_item_garden_array,
			'grade_name' => $inward_item_grade_array,
			'garden_id' => $inward_item_garden_id_array,
			'grade_id' => $inward_item_grade_id_array,
			'user_id' => session()->get('session_user_id'),
			"each_nett" => '',
			"total_qty" => ''
		];
		// $url = @apiURL . "user/auction/tempsessionstore";
		// $token = session()->get('access_token');


		// $headers = [
		// 	'Content-Type: application/json',
		// 	'Authorization: Bearer ' . $token,
		// ];

		// $response = make_curl_request($url, $headers, 'POST', $auction_item);

		// $auction_item['total_quantity'] = $total_quantity_array;
		// $auction_item['auction_quantity'] = $auction_quantity_array;
		// $auction_item['base_price'] = $base_price_array;
		// $auction_item['reverse_price'] = $reverse_price_array;
		// $auction_item['high_price'] = $high_price_array;
		// $auction_item['inward_item_id'] = $inward_item_array;
		// $auction_item['garden_name'] = $inward_item_garden_array;
		// $auction_item['grade_name'] = $inward_item_grade_array;
		// $auction_item['garden_id'] = $inward_item_grade_id_array;
		// $auction_items[] = $auction_item;
		// array_push($auction_items, $auction_item);

		// session()->set('auction_data', $auction_items);

		// $response_data = array();


		// if (isset($response['error'])) {
		// 	$this->response->setStatusCode(500);
		// 	return "cURL Error: " . $response['error'];
		// } else {

		// 	$response_data = json_decode($response, true);
		// }


		//echo '<pre>';print_r($auction_items);exit;
		return view(
			'Applications\USER\Views\BiddingSession\ajax_row',
			[
				"auction_data" => $auction_items,
			]
		);
	}

	public function tempDelete()
	{
		$id = $this->request->getpost('id');

		$auction_items = session()->get('auction_data');
		$item_removed = false;
		foreach ($auction_items as $key => $item) {
			if ($item['unique_id'] === $id) {
				unset($auction_items[$key]);
				$item_removed = true;
				break;
			}
		}
		if ($item_removed)
			session()->set('auction_data', $auction_items);
		return view(
			'Applications\USER\Views\BiddingSession\ajax_row',
			[
				"auction_data" => $auction_items
			]
		);
	}
	public function storeSession()
	{
		$auction_items = session()->get('auction_data');
		// return $auction_items;
		if (!is_array($auction_items)) {
			$auction_items = [];
		}
		$total_quantity_array = $this->request->getPost('total_qty');
		$auction_quantity_array = $this->request->getPost('auction_quantity');
		$base_price_array = $this->request->getPost('base_price');
		$reverse_price_array = $this->request->getPost('reverse_price');
		$inward_item_array = $this->request->getPost('inward_item');
		$inward_item_garden_array = $this->request->getPost('garden_name');
		$inward_item_grade_array = $this->request->getPost('grade_name');
		$inward_item_garden_id_array = $this->request->getPost('garden_id');
		$inward_item_grade_id_array = $this->request->getPost('grade_id');
		$unique_id = uniqid();

		$auction_item = [
			'unique_id' => $unique_id,
			'total_quantity' => $total_quantity_array,
			'auction_quantity' => $auction_quantity_array,
			'base_price' => $base_price_array,
			'reverse_price' => $reverse_price_array,
			'inward_item_id' => $inward_item_array,
			'garden_name' => $inward_item_garden_array,
			'grade_name' => $inward_item_grade_array,
			'garden_id' => $inward_item_garden_id_array,
			'grade_id' => $inward_item_grade_id_array,
			'user_id' => session()->get('session_user_id'),
			"each_nett" => '',
			"total_qty" => ''
		];

		array_push($auction_items, $auction_item);
		session()->set('auction_data', $auction_items);
		return view(
			'Applications\USER\Views\BiddingSession\ajax_row',
			[
				"auction_data" => $auction_items,
			]
		);
	}

	public function addAuctionItems($id)
	{
		$response_data = array();
		$ids = $id;
		$id = base64_decode($id);

		$data_url = @apiURL . "user/auction/" . $id;
		$center_url = @apiURL . "user/centerGarden";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');

		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$centerdata = [
			'center_id' => $auction_data['auction']['center_id'],
		];
		$centerdata = json_encode($centerdata, TRUE);
		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $centerdata);

		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {
			$centergarden_data = json_decode($centergarden_response, true);
		}

		$warehouse_response_data = array();
		$warehouse_url = @apiURL . "user/warehouseDropdown";
		$warehouse_response = make_curl_request($warehouse_url, $headers, 'GET');

		if (isset($response['error'])) {
			echo "cURL Error: " . $warehouse_response['error'];
		} else {
			$warehouse_response_data = json_decode($warehouse_response, true);
		}

		$data_inv = json_encode([
			'center_id' => $auction_data['auction']['center_id'],
			'type' => $auction_data['auction']['type'],
			'warehouse_id' => 'all',
			'garden_id' => 'all',
		]);
		$auction_items_url = @apiURL . "user/auction/getInvoicesByWarehouseId";
		$auction_items_response = make_curl_request($auction_items_url, $headers, 'POST', $data_inv);

		if (isset($response['error'])) {
			echo "cURL Error: " . $auction_items_response['error'];
		} else {
			$auction_items_response_data = json_decode($auction_items_response, true);
		}
		// echo '<pre>';print_r($auction_items_response_data);exit;


		// $data_url1 = @apiURL . "user/cart/inwarddetail/" . $id;
		// $data_response1 = make_curl_request($data_url1, $headers, 'GET');


		// if (isset($data_response1['error'])) {
		// 	echo "cURL Error: " . $data_response1['error'];
		// } else {
		// 	$auction_data1 = json_decode($data_response1, true);
		// }

		// $center_url = @apiURL . "user/AuctionGardenList";

		// $centergarden_response1 = make_curl_request($center_url, $headers, 'POST', $data_response1);
		// echo '<pre>';print_r($centergarden_response1);exit;

		// if (isset($centergarden_response1['error'])) {
		// 	echo "cURL Error: " . $centergarden_response1['error'];
		// } else {
		// 	$centergarden_data1 = json_decode($centergarden_response1, true);
		// }

		// echo '<pre>';print_r($auction_data);exit;

		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\add_items', [
			'data' => $data,
			'id' => $ids,
			'auction_data' => $auction_data['auction'],
			'centergarden_data' => $centergarden_data,
			'warehouse_response_data' => $warehouse_response_data,
			'auction_items' => $auction_items_response_data['data'],
			'title' => 	"Add Auction Items",
			'url' => 	@basePath . "USER/BiddingSession/addToCart",
		]);
	}


	public function storeAuctionItems()
	{
		$session_user_id = session()->get('session_user_id');
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/auction/createAuctionItem";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// print_r($data);
		// exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}
	public function addToCart()
	{
		$session_user_id = session()->get('session_user_id');
		// echo '<pre>';
		// echo ;exit;
		$url = @apiURL . "user/auction/addToCart";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);
		// print_r($data);
		// exit;

		$response = make_curl_request($url, $headers, 'POST', $data);
		echo '<pre>';
		print_r($response);
		exit;

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
				return json_encode(array('error' => "Unexpected status code: " . $response_data));
			}
		}
	}
	public function reOrderGarden($id)
	{
		$id = base64_decode($id);
		$response_data = array();

		$data_url = @apiURL . "user/auction/" . $id;

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');

		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}


		$center_url1 = @apiURL . "user/AuctionGardenList";
		$data_r = json_encode([
			'id' => $auction_data['auction']['id']
		]);
		// echo '<pre>';print_r($data_r);exit;

		$centergarden_response1 = make_curl_request($center_url1, $headers, 'POST', $data_r);
		// echo '<pre>';
		// print_r($centergarden_response1);
		// exit;

		if (isset($centergarden_response1['error'])) {
			echo "cURL Error: " . $centergarden_response1['error'];
		} else {
			$centergarden_data1 = json_decode($centergarden_response1, true);
		}


		// $centerdata = json_encode($centerdata, TRUE);

		// $data_url1 = @apiURL . "user/cart/inwarddetail/" . $id;

		// $data_response1 = make_curl_request($data_url1, $headers, 'GET');

		// if (isset($data_response1['error'])) {
		// 	echo "cURL Error: " . $data_response1['error'];
		// } else {
		// 	$auction_data1 = json_decode($data_response1, true);
		// }

		//echo '<pre>';print_r($centergarden_data1);exit;


		// $center_url = @apiURL . "user/centerGarden";

		// $centerdata = json_encode([
		// 	'center_id' => $auction_data['auction']['center_id'],
		// ]);
		// $centergarden_response = make_curl_request($center_url, $headers, 'POST', $centerdata);
		// if (isset($centergarden_response['error'])) {
		// 	echo "cURL Error: " . $centergarden_response['error'];
		// } else {
		// 	$centergarden_data = json_decode($centergarden_response, true);
		// }

		// echo '<pre>';print_r($centergarden_data);exit;
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);
		//echo 'Hii';exit;

		return view('Applications\USER\Views\BiddingSession\center_gardens', [
			'data' => $data,
			'auction_data' => $auction_data['auction'],
			'centergarden_data' => $centergarden_data1,
			'title' => 	"Reorder Gardens",
			'url' => 	@basePath . "USER/BiddingSession/saveGardenOrder",
		]);
	}
	public function cartItems($id)
	{
		$ids = $id;
		$response_data = array();
		$id = base64_decode($id);
		// echo $id;exit;
		$data_url = @apiURL . "user/cart/detail/" . $id;

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');
		// echo '<pre>';print_r($data_response);exit;
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$cart_garden_ids = array();
		foreach ($auction_data['auction'] as $auction_item) {
			$cart_garden_ids[] = $auction_item['garden_id'];
		}
		sort($cart_garden_ids);


		$auction_order_url = @apiURL . "user/AuctionGardenOrder/" . $id;
		$auction_order_response = make_curl_request($auction_order_url, $headers, 'GET');

		if (isset($auction_order_response['error'])) {
			echo "cURL Error: " . $auction_order_response['error'];
		} else {
			$auction_order_data = json_decode($auction_order_response, true);
		}
		$garden_ids = array();
		foreach ($auction_order_data as $item) {
			$garden_ids[] = $item['garden_id'];
		}
		sort($garden_ids);
		$is_same = empty(array_diff($cart_garden_ids, $garden_ids)) && empty(array_diff($garden_ids, $cart_garden_ids));


		//echo implode(",",$garden_ids);exit;
		//echo '<pre>';print_r($garden_ids);exit;
		if ($is_same) {

			if (isset($auction_order_data)) {

				// Extract garden IDs and garden grade IDs
				$ids1 = array_column($auction_order_data, 'garden_id');
				$ids2 = array_column($auction_order_data, 'garden_grade');

				// Custom order based on garden IDs and corresponding grade IDs
				$custom_order = $ids1;
				$grade_order = $ids2;
				// print_r($custom_order);exit;
				// echo '<pre>';print_r($grade_order);exit;

				if (count($custom_order)) {
					$sorted_auction = [];
					foreach ($custom_order as $garden_id_index => $garden_id) {
						// Collect auction items for this garden
						$garden_auction = [];
						foreach ($auction_data['auction'] as $auction_item) {
							if ($auction_item['garden_id'] == $garden_id) {
								$garden_auction[] = $auction_item;
							}
						}

						// Sort the auction items for this garden by grade ID
						usort($garden_auction, function ($a, $b) use ($grade_order, $garden_id_index) {
							$grade_a = array_search($a['grade_id'], explode(',', $grade_order[$garden_id_index]));
							$grade_b = array_search($b['grade_id'], explode(',', $grade_order[$garden_id_index]));
							return $grade_a - $grade_b;
						});
						// Append the sorted auction items for this garden to the main array
						$sorted_auction = array_merge($sorted_auction, $garden_auction);
					}

					// Update the auction data with the sorted array
					$auction_data['auction'] = $sorted_auction;
				}
			}
		}

		$data_url1 = @apiURL . "user/cart/inwarddetail/" . $id;
		$data_response1 = make_curl_request($data_url1, $headers, 'GET');
		if (isset($data_response1['error'])) {
			echo "cURL Error: " . $data_response1['error'];
		} else {
			$auction_data1 = json_decode($data_response1, true);
		}
		// echo '<pre>';print_r($auction_data);exit;

		// $centerdata = [
		// 	'center_id' => $auction_data['auction']['center_id'],
		// ];

		$center_url = @apiURL . "user/AuctionGardenList";

		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $data_response1);


		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {
			$centergarden_data = json_decode($centergarden_response, true);
		}
		// echo '<pre>';print_r($ids);exit;

		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\cart_items', [
			'data' => $data,
			'auction_data' => $auction_data['auction'],
			'id' => $ids,
			'centergarden_data' => $centergarden_data,
			'title' => 	"Manage Auction cart",
			'url' => 	@basePath . "USER/BiddingSession/cartToAuction",
		]);
	}

	public function cartItems1($id)
	{
		$ids = $id;
		$response_data = array();
		$id = base64_decode($id);

		$data_url = @apiURL . "user/cart/detail/" . $id;

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data_response = make_curl_request($data_url, $headers, 'GET');
		// echo '<pre>';print_r($data_response);exit;
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data = json_decode($data_response, true);
		}

		$cart_garden_ids = array();
		foreach ($auction_data['auction'] as $auction_item) {
			$cart_garden_ids[] = $auction_item['garden_id'];
		}
		sort($cart_garden_ids);




		$auction_order_url = @apiURL . "user/AuctionGardenOrder/" . $id;
		$auction_order_response = make_curl_request($auction_order_url, $headers, 'GET');

		if (isset($auction_order_response['error'])) {
			echo "cURL Error: " . $auction_order_response['error'];
		} else {
			$auction_order_data = json_decode($auction_order_response, true);
		}
		$garden_ids = array();
		foreach ($auction_order_data as $item) {
			$garden_ids[] = $item['garden_id'];
		}
		sort($garden_ids);
		$is_same = empty(array_diff($cart_garden_ids, $garden_ids)) && empty(array_diff($garden_ids, $cart_garden_ids));


		//echo implode(",",$garden_ids);exit;
		//echo '<pre>';print_r($garden_ids);exit;
		if ($is_same) {

			if (isset($auction_order_data)) {

				// Extract garden IDs and garden grade IDs
				$ids1 = array_column($auction_order_data, 'garden_id');
				$ids2 = array_column($auction_order_data, 'garden_grade');

				// Custom order based on garden IDs and corresponding grade IDs
				$custom_order = $ids1;
				$grade_order = $ids2;
				// print_r($custom_order);exit;

				if (count($custom_order)) {
					$sorted_auction = [];
					foreach ($custom_order as $garden_id_index => $garden_id) {
						// Collect auction items for this garden
						$garden_auction = [];
						foreach ($auction_data['auction'] as $auction_item) {
							if ($auction_item['garden_id'] == $garden_id) {
								$garden_auction[] = $auction_item;
							}
						}

						// Sort the auction items for this garden by grade ID
						usort($garden_auction, function ($a, $b) use ($grade_order, $garden_id_index) {
							$grade_a = array_search($a['grade_id'], explode(',', $grade_order[$garden_id_index]));
							$grade_b = array_search($b['grade_id'], explode(',', $grade_order[$garden_id_index]));
							return $grade_a - $grade_b;
						});

						// Append the sorted auction items for this garden to the main array
						$sorted_auction = array_merge($sorted_auction, $garden_auction);
					}

					// Update the auction data with the sorted array
					$auction_data['auction'] = $sorted_auction;
				}
			}
		}

		$data_url1 = @apiURL . "user/cart/inwarddetail/" . $id;
		$data_response1 = make_curl_request($data_url1, $headers, 'GET');
		if (isset($data_response1['error'])) {
			echo "cURL Error: " . $data_response1['error'];
		} else {
			$auction_data1 = json_decode($data_response1, true);
		}
		// echo '<pre>';print_r($data_response1);exit;

		// $centerdata = [
		// 	'center_id' => $auction_data['auction']['center_id'],
		// ];

		$center_url = @apiURL . "user/AuctionGardenList";

		$centergarden_response = make_curl_request($center_url, $headers, 'POST', $data_response1);


		if (isset($centergarden_response['error'])) {
			echo "cURL Error: " . $centergarden_response['error'];
		} else {
			$centergarden_data = json_decode($centergarden_response, true);
		}
		// echo '<pre>';print_r($centergarden_response);exit;

		$data_url3 = @apiURL . "user/auction/" . $id;
		$data_response = make_curl_request($data_url3, $headers, 'GET');
		if (isset($data_response['error'])) {
			echo "cURL Error: " . $data_response['error'];
		} else {

			$auction_data3 = json_decode($data_response, true);
		}
		// echo '<pre>';print_r($auction_data3);exit;


		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\BiddingSession\cart_items1', [
			'data' => $data,
			'auction_data' => $auction_data['auction'],
			'auction_data3' => $auction_data3['auction'],
			'id' => $ids,
			'centergarden_data' => $centergarden_data,
			'title' => 	"Manage Auction cart",
			'url' => 	@basePath . "USER/BiddingSession/cartToAuction",
		]);
	}
	public function  deleteCart()
	{
		$id = $this->request->getPost('id');
		// echo $id;exit;


		$url = @apiURL . "user/cart/delete/" . $id;
		$token = session()->get('access_token');


		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$response = make_curl_request($url, $headers, 'GET');

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

	public function  cartToAuction()
	{

		$url = @apiURL . "user/cart/movetoAuction";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);
		// exit;

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

	public function saveGardenOrder()
	{
		$url = @apiURL . "user/cart/reorderAuctionGarden";
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
	public function getLiveBidding($id)
	{

		$url = @apiURL . "user/highestBidding/" . $id;
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];



		$response = make_curl_request($url, $headers, 'GET');
		echo $response;
	}

	public function closeCurrentAuctionManually()
	{
		// echo 'ggg';exit;
		$url = @apiURL . "user/auction/closeCurrentAuctionManually";
		$token = session()->get('access_token');

		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode($this->request->getpost(), TRUE);

		$response = make_curl_request($url, $headers, 'POST', $data);
		print_r($response);
		exit;

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
