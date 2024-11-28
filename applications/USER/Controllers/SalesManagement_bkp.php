<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class SalesManagement extends Controller
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
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\SalesManagement\index',$data);
	}
}