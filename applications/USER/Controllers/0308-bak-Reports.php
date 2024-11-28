<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Reports extends Controller
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

	public function inwardReport()
	{
		// print_r('hii');exit;

		$response_data = array();

		$url = @apiURL . "user/inwardreport";
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

		$garden_url = @apiURL . "user/gardenDropdown";
		$garden_response = make_curl_request($garden_url, $headers, 'GET');
		if (isset($_response['error'])) {
			echo "cURL Error: " . $garden_response['error'];
		} else {
			$garden_list = json_decode($garden_response, true);
		}

		$grade_url = @apiURL . "user/gradeDropdown";
		$grade_response = make_curl_request($grade_url, $headers, 'GET');
		// print_r($grade_response);exit;

		if (isset($grade_response['error'])) {
			echo "cURL Error: " . $grade_response['error'];
		} else {
			$grade_list = json_decode($grade_response, true);
		}

		$warehouse_url = @apiURL . "user/warehouseDropdown";
		$warehouse_response = make_curl_request($warehouse_url, $headers, 'GET');
		// print_r($grade_response);exit;

		if (isset($warehouse_response['error'])) {
			echo "cURL Error: " . $warehouse_response['error'];
		} else {
			$warehouse_list = json_decode($warehouse_response, true);
		}
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "InwardReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\inward_reports', [
			'data' => $data,
			'garden_list' => $garden_list,
			'grade_list' => $grade_list,
			'warehouse_list' => $warehouse_list,
			'response_data' => $response_data
		]);
	}

	public function inwardSearchFilter()
	{
		$response_data = array();

		$url = @apiURL . "user/inwardSearchFilter";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
			'warehouse_id' => $this->request->getpost('warehouse_id'),
			'garden_id' => $this->request->getpost('garden_id'),
			'grade_id' => $this->request->getpost('grade_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}


	public function sellerReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "SellerReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\sellerwise_reports', [
			'data' => $data
		]);
	}
	public function buyerPurchaseReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "BuyerPurchaseReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\salewise_reports', [
			'data' => $data
		]);
	}
	public function gardenBuyerPurchaseReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "GardenBuyerPurchaseReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\gardenwise_reports', [
			'data' => $data
		]);
	}
	public function buyerSellerGardenSoldReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "BuyerSellerGardenSoldReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\buyersellgardenwise_reports', [
			'data' => $data
		]);
	}
	public function buyerGardenSoldStockReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "BuyerGardenSoldStockReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\buyergardenwise_reports', [
			'data' => $data
		]);
	}
	public function gardenGradeAvgPriceSaleReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r('jjj'); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "GardenGradeAvgPriceSaleReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\gardengradeavgprice_reports', [
			'data' => $data
		]);
	}
	public function sellerGardenGradeAvgPriceReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "SellerGardenGradeAvgPriceReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\sellergardenwise_reports', [
			'data' => $data
		]);
	}
	public function priceRangeWiseReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "PriceRangeWiseReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\pricerangewise_reports', [
			'data' => $data
		]);
	}
	public function sellerSoldStockReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "SellerSoldStockReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\sellersoldstock_reports', [
			'data' => $data
		]);
	}
	public function stateCityBuyerPurchaseReport()
	{
		$state_url = @apiURL . "user/stateDropdown";

		$token = session()->get('access_token');

		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$state_response = make_curl_request($state_url, $headers, 'GET');
		if (isset($response['error'])) {
			echo "cURL Error: " . $state_response['error'];
		} else {
			$state_list = json_decode($state_response, true);
		}


		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "StateCityBuyerPurchaseReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\statecitywise_reports', [
			'data' => $data,
			'state_list' => $state_list
		]);
	}
	public function manualBidReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "ManualBidReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\manualbid_reports', [
			'data' => $data
		]);
	}

	public function autoBidReport()
	{
		//Index
		//Get Active Menu
		$router = service('router');
		// echo "<pre>"; print_r($warehouse_list); echo "</pre>"; exit;

		$data_sidebar['activemenu'] = "AutoBidReport";

		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\Reports\autobid_reports', [
			'data' => $data
		]);
	}

	public function dateWiseSaleno()
	{
		$response_data = array();

		$url = @apiURL . "user/datewisesaleno";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date')
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}

	public function salenoWiseLot()
	{
		// echo 'hii';exit;
		$data = [
			'auction_id' => $this->request->getpost('auction_id'),
		];
		$response_data = array();

		$url = @apiURL . "user/reports/salenoWiseLot";
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
			return json_encode($response_data);
		}
	}

	public function salenoWiseSeller()
	{
		$response_data = array();

		$url = @apiURL . "user/salenowiseseller";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function sellerWiseBuyer()
	{
		$response_data = array();

		$url = @apiURL . "user/sellerWiseBuyer";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'seller_id' => $this->request->getpost('seller_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function salenoWiseBuyer()
	{
		$response_data = array();

		$url = @apiURL . "user/salenoWiseBuyer";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function salenoWiseGarden()
	{
		$response_data = array();

		$url = @apiURL . "user/salenowisegarden";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
	public function salenoWiseState()
	{
		$response_data = array();

		$url = @apiURL . "user/salenowiselot";
		$token = session()->get('access_token');
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];

		$data1 = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data1);
		// print_r($response);
		// exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}

	public function sellerWiseGarden()
	{
		$response_data = array();

		$url = @apiURL . "user/sellerwisegarden";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'seller_id' => $this->request->getpost('seller_id'),
		]);

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

	public function sellerWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/sellerwisereportsubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'seller_id' => $this->request->getpost('seller_id'),
			'garden_id' => $this->request->getpost('garden_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview', ['response_data' => $response_data]);
	}
	public function priceRangeWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/priceRangeWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\pricerangeview', ['response_data' => $response_data]);
	}
	public function sellerSoldStockReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/sellerSoldStockReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;


		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview1', ['response_data' => $response_data]);
	}
	public function saleWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/saleWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			// 'seller_id' => $this->request->getpost('seller_id'),
			// 'garden_id' => $this->request->getpost('garden_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;


		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview', ['response_data' => $response_data]);
	}
	public function gardenWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/gardenWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			// 'seller_id' => $this->request->getpost('seller_id'),
			'garden_id' => $this->request->getpost('garden_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview', ['response_data' => $response_data]);
	}
	public function stateCityReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/stateCityReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'state_id' => $this->request->getpost('state_id'),
			'city_id' => $this->request->getpost('city_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;


		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview', ['response_data' => $response_data]);
	}
	public function buyerWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/buyerWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'seller_id' => $this->request->getpost('seller_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;


		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\commonview', ['response_data' => $response_data]);
	}
	public function buyerSellerWiseReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/buyerSellerWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'seller_id' => $this->request->getpost('seller_id'),
			'buyer_id' => $this->request->getpost('buyer_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\buyergardenview', ['response_data' => $response_data]);
	}
	public function gardenGradeAvgPriceReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/buyerSellerWiseReportSubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			// 'seller_id' => $this->request->getpost('seller_id'),
			// 'buyer_id' => $this->request->getpost('buyer_id'),
			'garden_id' => $this->request->getpost('garden_id'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
			'is_upsale' => 1
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		// print_r($response);exit;

		if (isset($response['error'])) {
			echo "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
		}
		// echo '<pre>';print_r($response_data);exit;

		return view('Applications\USER\Views\Reports\views\buyergardenview1', ['response_data' => $response_data]);
	}
	public function manualBidReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/manualbidreportsubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'lot_no' => $this->request->getpost('lot_no'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

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
	public function autoBidReportSubmit()
	{
		$response_data = array();

		$url = @apiURL . "user/autobidreportsubmit";
		$token = session()->get('access_token');
		// Include the token in the headers
		$headers = [
			'Content-Type: application/json',
			'Authorization: Bearer ' . $token,
		];
		$data = json_encode([
			'auction_id' => $this->request->getpost('auction_id'),
			'lot_no' => $this->request->getpost('lot_no'),
			'from_date' => $this->request->getpost('from_date'),
			'to_date' => $this->request->getpost('to_date'),
		]);

		$response = make_curl_request($url, $headers, 'POST', $data);
		print_r($response);
		exit;

		if (isset($response['error'])) {
			$this->response->setStatusCode(500);
			return "cURL Error: " . $response['error'];
		} else {
			$response_data = json_decode($response, true);
			return json_encode($response_data);
		}
	}
}
