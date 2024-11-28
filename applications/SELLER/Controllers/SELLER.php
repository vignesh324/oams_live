<?php 
namespace Applications\SELLER\Controllers;

use CodeIgniter\Controller;

class SELLER extends Controller
{
	public function __construct()
    {
        helper('url');
		$session = session();
    }
    public function index()
	{
		//Index
		return view('Applications\SELLER\Views\index');
	}
}