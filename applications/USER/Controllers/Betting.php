<?php

namespace Applications\USER\Controllers;

use CodeIgniter\Controller;

class Betting extends Controller
{
    public function index()
    {
        // Load the view
        return view('auction/index');
    }

    public function placeBid()
    {
        // Process bid logic
        
        // Broadcast event to Pusher
        $pusher = new \Pusher\Pusher(
            'YOUR_APP_KEY',
            'YOUR_APP_SECRET',
            'YOUR_APP_ID',
            [
                'cluster' => 'YOUR_APP_CLUSTER',
                'useTLS' => true
            ]
        );

        // Trigger 'new-bid' event on 'auction-channel'
        $pusher->trigger('auction-channel', 'new-bid', $bidData);

        // Return response
        return $this->response->setJSON(['status' => 'success']);
    }
}
