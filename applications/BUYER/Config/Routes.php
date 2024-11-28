<?php
$routes->group('BUYER', ['namespace' => 'Applications\BUYER\Controllers'], static function ($routes) {
    //BUYER Controller
    $routes->get('/', 'BUYER::index', ['as' => 'buyer_login']);
    $routes->post('Login', 'BUYER::Login');
    //$routes->post('Login', 'BUYER::Login');
    $routes->get('Logout', 'BUYER::Logout');

    //Dashboard Controller
    $routes->get('Dashboard', 'Dashboard::index');
    $routes->get('Profile', 'Dashboard::profile');
    $routes->post('ProfileUpdate', 'Dashboard::profileUpdate');

    //Bidding Center Controller
    $routes->get('BiddingCenter/(:any)', 'BiddingCenter::index/$1');

    //Bidding Center Filter Controller
    $routes->get('BiddingCenter1', 'BiddingCenter1::index');
    $routes->get('upcoming-auctions', 'Auctions::upcoming');
    $routes->get('completed-auctions', 'Auctions::completedAuctions', ["as" => "completed-auctions"]);
    $routes->get('AuctionDetails/(:any)', 'Auctions::detail/$1');
    $routes->get('AuctionDetailsCompleted/(:any)', 'Auctions::completedDetail/$1');
    $routes->post('addtoCatalog', 'Auctions::addtoCatalog');
    $routes->post('movetoreview', 'Auctions::movetoreview');
    $routes->post('AutoBidding/View', 'BiddingCenter::autoBidding');
    $routes->post('AutoBidding', 'BiddingCenter::autoBiddingStore');
    $routes->post('addMinBid', 'BiddingCenter::addMinBid');
    $routes->post('addMaxBid', 'BiddingCenter::addMaxBid');
    $routes->post('deleteBidData', 'BiddingCenter::deleteBidData');
    $routes->get('myCatalog/table/(:any)', 'Auctions::myCatalogTable/$1');
    $routes->get('auctionLots/(:any)', 'Auctions::auctionLots/$1');
    $routes->get('mybidBook/(:any)', 'Auctions::mybidBook/$1');
    $routes->post('movetoclosed', 'Auctions::movetoClosed');
    $routes->post('getBidTiming', 'Auctions::getBidTiming');
    $routes->post('completeManual', 'Auctions::completeManualTime');

    $routes->post('AutoBidLog', 'Auctions::autoBidLog');

    //Bidding Controller
    $routes->get('Bid', 'Bid::index');
    $routes->get('websocket', 'WebSocket::startWebSocketServer');
});
