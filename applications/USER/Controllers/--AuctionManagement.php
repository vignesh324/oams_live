<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class AuctionManagement extends Controller
{
	public function __construct()
    {
        helper(['url','curl']);
		$session = session();
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
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\AuctionManagement\index',
		[
			'data' => $data,
			"response_data" => $response_data['auction']
		]);
	}

	public function show()
	{
		$data = [
			'id' => $this->request->getpost('id'),
		];
		
		$url = @apiURL . "user/auctionBiddings/".$data['id'];
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

		return view('Applications\USER\Views\AuctionManagement\show',
		[
			'data' => $data,
			"response_data" => $response_data['auctionBiddings']
		]);
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

		return view('Applications\USER\Views\AuctionManagement\index',
		[
			'data' => $data,
			"response_data" => $response_data['auction']
		]);
	}
	public function storeSession() {

		
		$header_data = [];
		$header_data = [
				'date' => $this->request->getpost('date'),
				'center_id' => $this->request->getpost('center_id'),
				'sale_no' => $this->request->getpost('sale_no'),
				'lot_count' => $this->request->getpost('lot_count'),
				'start_time' => $this->request->getpost('start_time'),
				'end_time' => $this->request->getpost('end_time'),
				'session_time' => $this->request->getpost('session_time'),
		];
		session()->remove('header_data');
		session()->set('header_data', $header_data);
		
		$auction_items = session()->get('auction_data');
		return view('Applications\USER\Views\BiddingSession\ajax_final_page',
		[
			'header_data' => $header_data,
			"auction_data" => $auction_items
		]);

	}

	public function steptwoCheck(){
		$auction_items = session()->get('auction_data');

		$header_data = session()->get('header_data');
		$grade_ids = $this->request->getPost('garden_id');
		$desired_sequence = $grade_ids;
		


		usort($auction_items, function ($a, $b) use ($desired_sequence) {
			$a_index = array_search($a['garden_id'], $desired_sequence);
			$b_index = array_search($b['garden_id'], $desired_sequence);
			
			return $a_index - $b_index;
		});
		
		session()->set('auction_data', $auction_items);
		return view('Applications\USER\Views\BiddingSession\ajax_final_page',
		[
			'header_data' => $header_data,
			"auction_data" => $auction_items
		]);
	}
}