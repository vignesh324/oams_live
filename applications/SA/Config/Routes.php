<?php

$routes->group('SA', ['namespace' => 'Applications\SA\Controllers'], static function ($routes) {
    $routes->get('/', 'SA::index',['as' => 'admin_login']);
    $routes->post('Login', 'SA::Login');
    $routes->get('Logout', 'SA::Logout');
    $routes->get('DashboardSA', 'DashboardSA::index',['as' => 'sa_dashboard']);
    $routes->get('roles', 'roles::index',['as' => 'roles.index']);
    $routes->post('roles/edit', 'roles::edit',['as' => 'roles.edit']);
    $routes->post('StoreRole', 'roles::store');
    $routes->post('UpdateRole', 'roles::update');
    $routes->post('DeleteRole', 'roles::delete');



    $routes->get('Users', 'Users::index');
    $routes->get('Users', 'Users::index',['as' => 'users']);
    $routes->post('Users/Add', 'Users::create');
    $routes->post('Users/Create', 'Users::store');
    $routes->post('Users/Show', 'Users::show');
    $routes->post('Users/Update', 'Users::update');
    $routes->post('Users/Delete', 'Users::delete');
});
// $routes->group('SA', ['namespace' => 'Applications\SA\Controllers'], static function ($routes) {
   
// });


// $routes->group('DashboardSA', ['namespace' => 'Applications\SA\Controllers'], static function ($routes) {
//     $routes->get('/', 'DashboardSA::index',['as' => 'sa_dashboard']);
// });
// $routes->group('Users', ['namespace' => 'Applications\SA\Controllers'], static function ($routes) {
//     $routes->get('/', 'Users::index');
// });
// $routes->group('Roll', ['namespace' => 'Applications\SA\Controllers'], static function ($routes) {
//     $routes->get('/', 'Roll::index');
// });