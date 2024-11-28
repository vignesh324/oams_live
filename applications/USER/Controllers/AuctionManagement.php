<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class AuctionManagement extends Controller
{
	public function __construct()
	{
		helper(['url', 'curl']);
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

		$url = @apiURL . "user/auction/getAuctionItemsUser";
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
		// echo "<pre>";
		// print_r($response_data);
		// exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view(
			'Applications\USER\Views\AuctionManagement\index',
			[
				'data' => $data,
				"response_data" => $response_data['auction']
			]
		);
	}

	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];

		$url = @apiURL . "user/auctionBiddings/" . $data['id'];
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
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view(
			'Applications\USER\Views\AuctionManagement\show',
			[
				'data' => $data,
				"response_data" => $response_data['auctionBiddings']
			]
		);
	}

	public function show1()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];

		$url = @apiURL . "user/auctionBiddings1/" . $data['id'];
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
		// echo "<pre>"; print_r($response_data); echo "</pre>"; exit;


		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view(
			'Applications\USER\Views\AuctionManagement\show1',
			[
				'data' => $data,
				"response_data" => $response_data['auctionBiddings']
			]
		);
	}

	public function finalize()
	{

		$url = @apiURL . "user/auctionBiddings/getItemsFinalize";
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

		return view(
			'Applications\USER\Views\AuctionManagement\index',
			[
				'data' => $data,
				"response_data" => $response_data['auction']
			]
		);
	}
	public function storeSession()
	{
		$header_data = [];
		$header_data = [
			'date' => $this->request->getpost('date'),
			'center_id' => $this->request->getpost('center_id'),
			'center_name' => $this->request->getpost('center_name'),
			'sale_no' => $this->request->getpost('sale_no'),
			'lot_count' => $this->request->getpost('lot_count'),
			'start_time' => $this->request->getpost('start_time'),
			'end_time' => $this->request->getpost('end_time'),
			'session_time' => $this->request->getpost('session_time'),
		];

		session()->remove('header_data');
		session()->set('header_data', $header_data);


		$data = $_POST;
		$auction_items = [];

		foreach ($data['total_quantitysss'] as $key => $val) {
			$auction_items[] = [
				'check_auctionitem' => $data['check-auctionitem'][$key],
				'total_quantity' => $data['total_quantitysss'][$key],
				'inward_item_garden' => $data['inward_item_garden'][$key],
				'inward_item_warehouse' => $data['inward_item_warehouse'][$key],
				'inward_item_id' => $data['inward_item_id'][$key],
				'inward_item_garden_id' => $data['inward_item_garden_id'][$key],
				'inward_item_grade_id' => $data['inward_item_grade_id'][$key],
				'inward_item_grade' => $data['inward_item_grade'][$key],
				'inward_item' => $data['inward_item'][$key],
				'each_nett' => $data['each_nett'][$key],
				'total_nett' => $data['total_nett'][$key],
			];
		}

		session()->remove('auction_data');
		session()->set('auction_data', $auction_items);

		// echo '<pre>';print_r(session()->get('auction_data'));exit;

		return true;

		// return view(
		// 	'Applications\USER\Views\BiddingSession\ajax_price_page',
		// 	[
		// 		'header_data' => session()->get('header_data'),
		// 		"auction_data" => session()->get('auction_data')
		// 	]
		// );
	}

	public function steptwoCheck()
	{
		$auction_items = session()->get('auction_data');

		$header_data = session()->get('header_data');
		$grade_ids = $this->request->getPost('garden_id');
		$desired_sequence = $grade_ids;

		// echo '<pre>';print_r($auction_items);exit;


		usort($auction_items, function ($a, $b) use ($desired_sequence) {
			$a_index = array_search($a['inward_item_garden_id'], $desired_sequence);
			$b_index = array_search($b['inward_item_garden_id'], $desired_sequence);

			// echo '<pre>';print_r($a_index);

			return $a_index - $b_index;
		});



		return view(
			'Applications\USER\Views\BiddingSession\ajax_price_page',
			[
				'header_data' => $header_data,
				"auction_data" => $auction_items
			]
		);
	}


	public function stepthreeCheck()
	{
		$auction_items = session()->get('auction_data');
		$header_data = session()->get('header_data');
		$grade_ids = $this->request->getPost('garden_id');
		$desired_sequence = $grade_ids;


		$data = $_POST;

		$auction_items_final = [];

		foreach ($data['check-auctionitem'] as $key => $val) {
			$auction_items_final[] = [
				'check_auctionitem' => $data['check-auctionitem'][$key],
				'total_quantity' => $data['total_quantitysss_final'][$key],
				'inward_item_garden' => $data['inward_item_garden_final'][$key],
				'inward_item_warehouse' => $data['inward_item_warehouse_final'][$key],
				'inward_item_id' => $data['inward_item_id_final'][$key],
				'inward_item_garden_id' => $data['inward_item_garden_id_final'][$key],
				'inward_item_grade_id' => $data['inward_item_grade_id_final'][$key],
				'inward_item_grade' => $data['inward_item_grade_final'][$key],
				'inward_item' => $data['inward_item_final'][$key],
				'each_nett' => $data['each_nett_final'][$key],
				'total_nett' => $data['total_nett_final'][$key],
				'auction_quantity' => $data['auction_quantity_final'][$key],
				'base_price' => $data['base_price_final'][$key],
				'reserve_price' => $data['reserve_price_final'][$key],
			];
		}
		// echo '<pre>';print_r($auction_items_final);exit;


		session()->set('auction_data_final', $auction_items_final);

		usort($auction_items_final, function ($a, $b) use ($desired_sequence) {
			$a_index = array_search($a['inward_item_garden_id'], $desired_sequence);
			$b_index = array_search($b['inward_item_garden_id'], $desired_sequence);

			// echo '<pre>';print_r($a_index);

			return $a_index - $b_index;
		});



		return view(
			'Applications\USER\Views\BiddingSession\ajax_final_page',
			[
				'header_data' => $header_data,
				"auction_data" => $auction_items_final
			]
		);
	}
	public function completedAuction()
	{

		$url = @apiURL . "user/auction/getAuctionItemsUser";
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
		// echo "<pre>";
		// print_r($response_data);
		// exit;

		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view(
			'Applications\USER\Views\AuctionManagement\completed_auction',
			[
				'data' => $data,
				"response_data" => $response_data['auction']
			]
		);
	}
}
