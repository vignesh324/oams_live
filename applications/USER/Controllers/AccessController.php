<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class AccessController extends Controller
{
	public function accessDenied()
    {
        return view('Applications\USER\Views\access_denied');
    }
}
