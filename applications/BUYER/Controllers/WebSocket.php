<?php

namespace Applications\BUYER\Controllers;

use CodeIgniter\Controller;

class WebSocket extends Controller
{
    public function startWebSocketServer()
    {
        return view('Applications\BUYER\Views\view_centers_livebid');
    }
}
