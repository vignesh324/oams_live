<?php
$routes->group('USER', ['namespace' => 'Applications\USER\Controllers'], static function ($routes) {
    //USER Controller
    $routes->get('/', 'USER::index',['as' => 'user_login']);
    $routes->post('Login', 'USER::Login');
    $routes->get('Logout', 'USER::Logout');

    //Dashboard Controller
    // $routes->get('Dashboard', 'Dashboard::index');
    $routes->get('Dashboard', 'Dashboard::index',['as' => 'user_dashboard']);

    //Seller Controller
    $routes->group('Seller', function ($routes) {
    $routes->get('', 'Seller::index');
    $routes->post('Add', 'Seller::create');
    $routes->post('Create', 'Seller::store');
    $routes->post('Show', 'Seller::show');
    $routes->post('Update', 'Seller::update');
    $routes->post('Delete', 'Seller::delete');
    });

    //GardenCenter Controller
    $routes->get('GardenCenter', 'GardenCenter::index');

    //Garden Controller
    $routes->group('Garden', function ($routes) {
        $routes->get('', 'Garden::index');
        $routes->post('Add', 'Garden::create');
        $routes->post('Create', 'Garden::store');
        $routes->post('Show', 'Garden::show');
        $routes->post('Update', 'Garden::update');
        $routes->post('Delete', 'Garden::delete');

    });

    //Area Controller
    $routes->group('Area', function ($routes) {
        $routes->get('', 'Area::index');
        $routes->post('Add', 'Area::create');
        $routes->post('Create', 'Area::store');
        $routes->post('Show', 'Area::show');
        $routes->post('Update', 'Area::update');
        $routes->post('Delete', 'Area::delete');
        $routes->post('CityArea', 'Area::cityArea');
    });

    //City Controller
    $routes->group('City', function ($routes) {
        $routes->get('', 'City::index');
        $routes->post('Add', 'City::create');
        $routes->post('Create', 'City::store');
        $routes->post('Show', 'City::show');
        $routes->post('Update', 'City::update');
        $routes->post('Delete', 'City::delete');
        $routes->post('StateCity', 'City::stateCity');
    });

    //State Controller
    $routes->group('State', function ($routes) {
        $routes->get('', 'State::index',['as' => 'state']);
        $routes->post('Add', 'State::create');
        $routes->post('Create', 'State::store');
        $routes->post('Show', 'State::show');
        $routes->post('Update', 'State::update');
        $routes->post('Delete', 'State::delete');
    });


    //Buyer Controller
    $routes->group('Buyer', function ($routes) {
        $routes->get('', 'Buyer::index');
        $routes->post('Add', 'Buyer::create');
        $routes->post('Create', 'Buyer::store');
        $routes->post('Show', 'Buyer::show');
        $routes->post('Update', 'Buyer::update');
        $routes->post('Delete', 'Buyer::delete');
    });

    //Grade Controller
    $routes->group('Grade', function ($routes) {
        $routes->get('', 'Grade::index');
        $routes->post('Add', 'Grade::create');
        $routes->post('Create', 'Grade::store');
        $routes->post('Show', 'Grade::show');
        $routes->post('Update', 'Grade::update');
        $routes->post('Delete', 'Grade::delete');
    });
    //Category Controller
    $routes->group('Category', function ($routes) {
        $routes->get('', 'Category::index');
        $routes->post('Add', 'Category::create');
        $routes->post('Create', 'Category::store');
        $routes->post('Show', 'Category::show');
        $routes->post('Update', 'Category::update');
        $routes->post('Delete', 'Category::delete');
    });

    //Warehouse Controller
    $routes->group('Warehouse', function ($routes) {
        $routes->get('', 'Warehouse::index');
        $routes->post('Add', 'Warehouse::create');
        $routes->post('Create', 'Warehouse::store');
        $routes->post('Show', 'Warehouse::show');
        $routes->post('Update', 'Warehouse::update');
        $routes->post('Delete', 'Warehouse::delete');
    });

    //HSN Controller
    $routes->group('Hsn', function ($routes) {
        $routes->get('', 'Hsn::index');
        $routes->post('Add', 'Hsn::create');
        $routes->post('Create', 'Hsn::store');
        $routes->post('Show', 'Hsn::show');
        $routes->post('Update', 'Hsn::update');
        $routes->post('Delete', 'Hsn::delete');
    });

    //Package Controller
    $routes->group('Package', function ($routes) {
        $routes->get('', 'Package::index');
        $routes->post('Add', 'Package::create');
        $routes->post('Create', 'Package::store');
        $routes->post('Show', 'Package::show');
        $routes->post('Update', 'Package::update');
        $routes->post('Delete', 'Package::delete');
    });

    //Center Controller
    $routes->group('Center', function ($routes) {
        $routes->get('', 'Center::index');
        $routes->post('Add', 'Center::create');
        $routes->post('Create', 'Center::store');
        $routes->post('Show', 'Center::show');
        $routes->post('Update', 'Center::update');
        $routes->post('Delete', 'Center::delete');
        $routes->post('AssignGrade', 'Center::AssignGarden');
    });

    //CenterGarden Controller
    
    $routes->get('CenterGarden', 'CenterGarden::index');

    //Inward Controller
    $routes->get('Inward', 'Inward::index');
    $routes->get('Inward/Add', 'Inward::add');
    $routes->post('Inward/Store', 'Inward::Store');
    $routes->post('Inward/Update', 'Inward::Update');
    $routes->get('Inward/Edit/(:any)', 'Inward::Edit/$1');

    //BiddingSession Controller
    $routes->get('BiddingSession', 'BiddingSession::index');
    $routes->get('BiddingSession/Add', 'BiddingSession::add');
    $routes->get('BiddingSession/View', 'BiddingSession::view');
    $routes->post('BiddingSession/Store', 'BiddingSession::store');
    $routes->get('BiddingSession/Edit/(:any)', 'BiddingSession::edit/$1');
    $routes->post('BiddingSession/Update', 'BiddingSession::Update');
    $routes->post('BiddingSession/GetInwardItems', 'BiddingSession::GetInwardItems');
    $routes->post('BiddingSession/GetCenterGardens', 'BiddingSession::GetCenterGardens');
    $routes->post('BiddingSession/GetInwardItemDetails', 'BiddingSession::GetInwardItemDetails');


    //InwardReturn Controller
    $routes->post('InwardReturn/getInvoiceDetail', 'InwardReturn::getInvoiceDetail');
    $routes->get('InwardReturn', 'InwardReturn::index');
    $routes->get('InwardReturn/Add', 'InwardReturn::add');
    $routes->post('InwardReturn/Store', 'InwardReturn::Store');

    //WarehouseStock Controller
    $routes->get('WarehouseStock', 'WarehouseStock::index');

    //WarehouseArchiveStock Controller
    $routes->get('WarehouseArchiveStock', 'WarehouseArchiveStock::index');

    //SalesManagement Controller
    $routes->get('SalesManagement', 'SalesManagement::index');

    //DeliveryManagement Controller
    $routes->get('DeliveryManagement', 'DeliveryManagement::index');

    //AuctionStock Controller
    $routes->get('AuctionStock', 'AuctionStock::index');

    //SoldStock Controller
    $routes->get('SoldStock', 'SoldStock::index');

    // Settings Controller
    $routes->get('Settings', 'Settings::index');

    //AuctionManagement Controller
    $routes->get('AuctionManagement', 'AuctionManagement::index');

    //Invoice Controller
    $routes->get('Invoice', 'Invoice::index');

    //Invoice Controller
    $routes->get('Invoice/View', 'Invoice::view');
});
