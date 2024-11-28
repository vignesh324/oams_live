<?php 
namespace Applications\SA\Controllers;

use CodeIgniter\Controller;

class Roll extends Controller
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
		$data['header'] = view('Applications\SA\Views\header');
		$data['sidebar'] = view('Applications\SA\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\SA\Views\footer');

		return view('Applications\SA\Views\roll', $data);
	}
}