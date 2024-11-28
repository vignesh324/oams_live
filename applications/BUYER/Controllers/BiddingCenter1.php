<?php 
namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;

class BiddingCenter1 extends Controller
{
	public function __construct()
    {
        helper('url');
		$session = session();
    }
    public function index()
	{
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

		return view('Applications\BUYER\Views\view_centers1',$data);
	}
}