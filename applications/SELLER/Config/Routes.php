<?php
$routes->group('SELLER', ['namespace' => 'Applications\SELLER\Controllers'], static function ($routes) {
    $routes->get('/', 'SELLER::index');
});
$routes->group('SELLER/Dashboard', ['namespace' => 'Applications\SELLER\Controllers'], static function ($routes) {
    $routes->get('/', 'Dashboard::index');
});
