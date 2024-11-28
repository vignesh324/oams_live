<?php 
namespace Applications\SELLER\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
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
		$data['header'] = view('Applications\SELLER\Views\header');
		$data['sidebar'] = view('Applications\SELLER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\SELLER\Views\footer');

		return view('Applications\SELLER\Views\dashboard', $data);
	}
}