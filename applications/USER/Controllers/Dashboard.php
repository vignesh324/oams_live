<?php 
namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Dashboard extends Controller
{
	public function __construct()
    {
        helper('url');
        helper('curl');
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
		
		//Index
		//Get Active Menu
		$router = service('router');
		$active_main_arr  = explode("\\", $router->controllerName());
		//$active_main_arr  = explode("\\", $router->methodName());
		//echo "<pre>"; print_r($active_main_arr); echo "</pre>"; exit;
		
		$data_sidebar['activemenu'] = $active_main_arr[4];
		$data['header'] = view('Applications\USER\Views\header', $data_sidebar);
		$data['sidebar'] = view('Applications\USER\Views\sidebar', $data_sidebar);
		$data['footer'] = view('Applications\USER\Views\footer', $data);

		return view('Applications\USER\Views\dashboard',$data);
	}
}