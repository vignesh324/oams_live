<?php
$routes->group('USER', ['namespace' => 'Applications\USER\Controllers'], static function ($routes) {
    $routes->get('Access-Denied', 'AccessController::accessDenied', ['as' => 'access_denied']);

    //USER Controller
    $routes->get('/', 'USER::index', ['as' => 'user_login']);
    $routes->post('Login', 'USER::Login');
    $routes->get('Logout', 'USER::Logout');

    //Dashboard Controller
    // $routes->get('Dashboard', 'Dashboard::index');
    $routes->get('Dashboard', 'Dashboard::index', ['as' => 'user_dashboard']);

    //Seller Controller
    $routes->group('Seller', function ($routes) {
        $routes->get('', 'Seller::index', ['filter' => 'roleFilter:4,index']);
        $routes->post('Add', 'Seller::create', ['filter' => 'roleFilter:4,create']);
        $routes->post('Create', 'Seller::store', ['filter' => 'roleFilter:4,create']);
        $routes->post('Show', 'Seller::show', ['filter' => 'roleFilter:4,update']);
        $routes->post('Update', 'Seller::update', ['filter' => 'roleFilter:4,update']);
        $routes->post('Delete', 'Seller::delete', ['filter' => 'roleFilter:4,delete']);
        $routes->post('SellerGarden', 'Seller::SellerGarden', ['filter' => 'roleFilter:5,list']);
    });

    //GardenCenter Controller
    $routes->get('GardenCenter', 'GardenCenter::index');

    //Garden Controller
    $routes->group('Garden', function ($routes) {
        $routes->get('', 'Garden::index',['filter' => 'roleFilter:5,index']);
        $routes->post('Add', 'Garden::create',['filter' => 'roleFilter:5,create']);
        $routes->post('Create', 'Garden::store',['filter' => 'roleFilter:5,create']);
        $routes->post('Show', 'Garden::show',['filter' => 'roleFilter:5,update']);
        $routes->post('Update', 'Garden::update',['filter' => 'roleFilter:5,update']);
        $routes->post('Delete', 'Garden::delete',['filter' => 'roleFilter:5,delete']);
        $routes->post('AssignGrade', 'Garden::AssignGrade',['filter' => 'roleFilter:10,update']);
        $routes->post('SaveGrade', 'Garden::SaveGrade',['filter' => 'roleFilter:10,create']);
        $routes->post('reOrder', 'Garden::reOrder',['filter' => 'roleFilter:5,update']);
        $routes->post('AssignCategoryGrade', 'Garden::AssignCategoryGrade',['filter' => 'roleFilter:9,update']);
        $routes->post('reOrderCategoryGrade', 'Garden::reOrderCategoryGrade',['filter' => 'roleFilter:10,update']);
        $routes->get('Category', 'Garden::categoryGarden',['filter' => 'roleFilter:9,list']);
    });

    //Area Controller
    $routes->group('Area', function ($routes) {
        $routes->get('', 'Area::index',['filter' => 'roleFilter:3,list']);
        $routes->post('Add', 'Area::create',['filter' => 'roleFilter:3,create']);
        $routes->post('Create', 'Area::store',['filter' => 'roleFilter:3,create']);
        $routes->post('Show', 'Area::show',['filter' => 'roleFilter:3,update']);
        $routes->post('Update', 'Area::update',['filter' => 'roleFilter:3,update']);
        $routes->post('Delete', 'Area::delete',['filter' => 'roleFilter:3,delete']);
        $routes->post('CityArea', 'Area::cityArea',['filter' => 'roleFilter:3,list']);
    });

    //City Controller
    $routes->group('City', function ($routes) {
        $routes->get('', 'City::index',['filter' => 'roleFilter:2,list']);
        $routes->post('Add', 'City::create',['filter' => 'roleFilter:2,create']);
        $routes->post('Create', 'City::store',['filter' => 'roleFilter:2,create']);
        $routes->post('Show', 'City::show',['filter' => 'roleFilter:2,update']);
        $routes->post('Update', 'City::update',['filter' => 'roleFilter:2,update']);
        $routes->post('Delete', 'City::delete',['filter' => 'roleFilter:2,delete']);
        $routes->post('StateCity', 'City::stateCity',['filter' => 'roleFilter:2,list']);
    });

    //State Controller
    $routes->group('State', function ($routes) {
        $routes->get('', 'State::index', ['as' => 'state', 'filter' => 'roleFilter:1,list']);
        $routes->post('Add', 'State::create', ['filter' => 'roleFilter:1,create']);           
        $routes->post('Create', 'State::store', ['filter' => 'roleFilter:1,create']);         
        $routes->post('Show', 'State::show', ['filter' => 'roleFilter:1,update']);             
        $routes->post('Delete', 'State::delete', ['filter' => 'roleFilter:1,delete']);        
    }); 


    //Buyer Controller
    $routes->group('Buyer', function ($routes) {
        $routes->get('', 'Buyer::index', ['filter' => 'roleFilter:6,list']);
        $routes->post('Add', 'Buyer::create', ['filter' => 'roleFilter:6,create']);
        $routes->post('Create', 'Buyer::store', ['filter' => 'roleFilter:6,create']);
        $routes->post('Show', 'Buyer::show', ['filter' => 'roleFilter:6,update']);
        $routes->post('Update', 'Buyer::update', ['filter' => 'roleFilter:6,update']);
        $routes->post('Delete', 'Buyer::delete', ['filter' => 'roleFilter:6,delete']);
    });

    //SampleQuantity Controller
    $routes->group('SampleQuantity', function ($routes) {
        $routes->get('', 'SampleQuantity::index', ['filter' => 'roleFilter:25,list']);
        $routes->post('Add', 'SampleQuantity::create', ['filter' => 'roleFilter:25,create']);
        $routes->post('Create', 'SampleQuantity::store', ['filter' => 'roleFilter:25,create']);
        $routes->post('Show', 'SampleQuantity::show', ['filter' => 'roleFilter:25,update']);
        $routes->post('Update', 'SampleQuantity::update', ['filter' => 'roleFilter:25,update']);
        $routes->post('Delete', 'SampleQuantity::delete', ['filter' => 'roleFilter:25,delete']);
    });

    //Grade Controller
    $routes->group('Grade', function ($routes) {
        $routes->get('', 'Grade::index', ['filter' => 'roleFilter:10,list']);
        $routes->post('Add', 'Grade::create', ['filter' => 'roleFilter:10,create']);
        $routes->post('Create', 'Grade::store', ['filter' => 'roleFilter:10,create']);
        $routes->post('Show', 'Grade::show', ['filter' => 'roleFilter:10,update']);
        $routes->post('Update', 'Grade::update', ['filter' => 'roleFilter:10,update']);
        $routes->post('Delete', 'Grade::delete', ['filter' => 'roleFilter:10,delete']);
    });
    //Category Controller
    $routes->group('Category', function ($routes) {
        $routes->get('', 'Category::index', ['filter' => 'roleFilter:9,list']);
        $routes->post('Add', 'Category::create', ['filter' => 'roleFilter:9,create']);
        $routes->post('Create', 'Category::store', ['filter' => 'roleFilter:9,create']);
        $routes->post('Show', 'Category::show', ['filter' => 'roleFilter:9,update']);
        $routes->post('Update', 'Category::update', ['filter' => 'roleFilter:9,update']);
        $routes->post('Delete', 'Category::delete', ['filter' => 'roleFilter:9,delete']);
    });

    //Warehouse Controller
    $routes->group('Warehouse', function ($routes) {
        $routes->get('', 'Warehouse::index', ['filter' => 'roleFilter:8,list']);
        $routes->post('Add', 'Warehouse::create', ['filter' => 'roleFilter:8,create']);
        $routes->post('Create', 'Warehouse::store', ['filter' => 'roleFilter:8,create']);
        $routes->post('Show', 'Warehouse::show', ['filter' => 'roleFilter:8,update']);
        $routes->post('Update', 'Warehouse::update', ['filter' => 'roleFilter:8,update']);
        $routes->post('Delete', 'Warehouse::delete', ['filter' => 'roleFilter:8,delete']);
    });

    //HSN Controller
    $routes->group('Hsn', function ($routes) {
        $routes->get('', 'Hsn::index', ['filter' => 'roleFilter:11,list']);
        $routes->post('Add', 'Hsn::create', ['filter' => 'roleFilter:11,create']);
        $routes->post('Create', 'Hsn::store', ['filter' => 'roleFilter:11,create']);
        $routes->post('Show', 'Hsn::show', ['filter' => 'roleFilter:11,update']);
        $routes->post('Update', 'Hsn::update', ['filter' => 'roleFilter:11,update']);
        $routes->post('Delete', 'Hsn::delete', ['filter' => 'roleFilter:11,delete']);
    });

    //Package Controller
    $routes->group('Package', function ($routes) {
        $routes->get('', 'Package::index', ['filter' => 'roleFilter:12,list']);
        $routes->post('Add', 'Package::create', ['filter' => 'roleFilter:12,create']);
        $routes->post('Create', 'Package::store', ['filter' => 'roleFilter:12,create']);
        $routes->post('Show', 'Package::show', ['filter' => 'roleFilter:12,update']);
        $routes->post('Update', 'Package::update', ['filter' => 'roleFilter:12,update']);
        $routes->post('Delete', 'Package::delete', ['filter' => 'roleFilter:12,delete']);
    });

    //Center Controller
    $routes->group('Center', function ($routes) {
        $routes->get('', 'Center::index', ['filter' => 'roleFilter:7,list']);
        $routes->post('Add', 'Center::create', ['filter' => 'roleFilter:7,create']);
        $routes->post('Create', 'Center::store', ['filter' => 'roleFilter:7,create']);
        $routes->post('Show', 'Center::show', ['filter' => 'roleFilter:7,update']);
        $routes->post('Update', 'Center::update', ['filter' => 'roleFilter:7,update']);
        $routes->post('Delete', 'Center::delete', ['filter' => 'roleFilter:7,delete']);
        $routes->post('AssignGarden', 'Center::AssignGarden', ['filter' => 'roleFilter:7,list']);
        $routes->post('SaveGarden', 'Center::SaveGarden', ['filter' => 'roleFilter:7,list']);
        $routes->post('reOrder', 'Center::reOrder', ['filter' => 'roleFilter:7,list']);
    });

    //CenterGarden Controller

    $routes->get('CenterGarden', 'CenterGarden::index');

    //Inward Controller
    $routes->get('Inward', 'Inward::index', ['filter' => 'roleFilter:17,list']);
    $routes->get('Inward/Add', 'Inward::add', ['filter' => 'roleFilter:17,create']);
    $routes->post('Inward/AddAjax', 'Inward::addAjax', ['filter' => 'roleFilter:17,create']);
    $routes->post('Inward/Store', 'Inward::Store', ['filter' => 'roleFilter:17,create']);
    $routes->post('Inward/Update', 'Inward::Update', ['filter' => 'roleFilter:17,update']);
    $routes->get('Inward/Edit/(:any)', 'Inward::Edit/$1', ['filter' => 'roleFilter:17,update']);
    $routes->get('Inward/View/(:any)', 'Inward::View/$1', ['filter' => 'roleFilter:17,list']);
    $routes->post('Inward/Delete', 'Inward::delete', ['filter' => 'roleFilter:17,delete']);

    //BiddingSession Controller
    $routes->get('BiddingSession', 'BiddingSession::index', ['filter' => 'roleFilter:19,list']);
    $routes->get('BiddingSession/Add', 'BiddingSession::add', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/Store', 'BiddingSession::store', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/StoreAuctionItems', 'BiddingSession::storeAuctionItems', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/addToCart', 'BiddingSession::addToCart');
    $routes->post('BiddingSession/closeBidding', 'BiddingSession::closeAuction', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/Create', 'BiddingSession::create', ['filter' => 'roleFilter:19,create']);
    $routes->get('BiddingSession/AddAuctionItems/(:any)', 'BiddingSession::addAuctionItems/$1', ['filter' => 'roleFilter:19,create']);
    $routes->get('BiddingSession/Edit/(:any)', 'BiddingSession::edit/$1', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/Edit', 'BiddingSession::edit', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/Delete', 'BiddingSession::delete', ['filter' => 'roleFilter:19,delete']);
    $routes->get('BiddingSession/View/(:any)', 'BiddingSession::view/$1', ['filter' => 'roleFilter:19,list']);
    $routes->get('BiddingSession/CompletedAuctions/(:any)', 'BiddingSession::completedAuctions/$1');
    $routes->get('BiddingSession/EditValuation/(:any)', 'BiddingSession::editValuation/$1', ['filter' => 'roleFilter:19,update']);
    $routes->get('BiddingSession/EditReserve/(:any)', 'BiddingSession::editReserve/$1', ['filter' => 'roleFilter:19,update']);
    $routes->get('BiddingSession/EditReserveBidframe/(:any)', 'BiddingSession::editReserveBidframe/$1', ['filter' => 'roleFilter:19,update']);

    $routes->get('BiddingSession/withdraw/(:any)', 'BiddingSession::withDraw/$1');
    $routes->get('BiddingSession/reOrderGarden/(:any)', 'BiddingSession::reOrderGarden/$1');
    $routes->get('BiddingSession/AuctionCartItems/(:any)', 'BiddingSession::cartItems/$1');
    $routes->get('BiddingSession/AuctionCartItems1/(:any)', 'BiddingSession::cartItems1/$1');
    $routes->post('BiddingSession/Update', 'BiddingSession::Update', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/UpdateValuation', 'BiddingSession::updateValuation', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/UpdateReservePrice', 'BiddingSession::updateReservePrice', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/GetInwardItems', 'BiddingSession::GetInwardItems');
    $routes->post('BiddingSession/GetCenterGardens', 'BiddingSession::GetCenterGardens');
    $routes->post('BiddingSession/GetInwardItemDetails', 'BiddingSession::GetInwardItemDetails');
    $routes->post('reOrderBiddingSession', 'BiddingSession::reOrderBiddingSession');
    $routes->post('BiddingSession/deleteCart', 'BiddingSession::deleteCart');
    $routes->post('BiddingSession/cartToAuction', 'BiddingSession::cartToAuction');
    $routes->post('BiddingSession/CloseCurrentAuctionManually', 'BiddingSession::closeCurrentAuctionManually');

    //InwardReturn Controller
    $routes->post('InwardReturn/getInvoiceDetail', 'InwardReturn::getInvoiceDetail', ['filter' => 'roleFilter:18,list']);
    $routes->get('InwardReturn', 'InwardReturn::index', ['filter' => 'roleFilter:18,list']);
    $routes->get('InwardReturn/Add', 'InwardReturn::add', ['filter' => 'roleFilter:18,create']);
    $routes->post('InwardReturn/Show', 'InwardReturn::show', ['filter' => 'roleFilter:18,update']);
    $routes->post('InwardReturn/Store', 'InwardReturn::Store', ['filter' => 'roleFilter:18,update']);
    $routes->post('InwardReturn/Delete', 'InwardReturn::delete', ['filter' => 'roleFilter:18,update']);

    //WarehouseStock Controller
    $routes->get('WarehouseStock', 'WarehouseStock::index', ['filter' => 'roleFilter:17,list']);

    //WarehouseArchiveStock Controller
    $routes->get('WarehouseArchiveStock', 'WarehouseArchiveStock::index');

    //SampleReceipt Controller
    $routes->group('SampleReceipt', function ($routes) {
        $routes->get('', 'SampleReceipt::index', ['filter' => 'roleFilter:20,list']);
        $routes->post('Add', 'SampleReceipt::create', ['filter' => 'roleFilter:20,create']);
        $routes->post('Create', 'SampleReceipt::store', ['filter' => 'roleFilter:20,create']);
        $routes->post('Show', 'SampleReceipt::show', ['filter' => 'roleFilter:20,update']);
        $routes->post('Update', 'SampleReceipt::update', ['filter' => 'roleFilter:20,update']);
        $routes->post('Delete', 'SampleReceipt::delete', ['filter' => 'roleFilter:20,delete']);
        $routes->post('SalenoWiseLot', 'SampleReceipt::salenoWiseLot');
    });

    //DeliveryManagement Controller
    $routes->group('DeliveryManagement', function ($routes) {
        $routes->get('', 'DeliveryManagement::index', ['filter' => 'roleFilter:21,list']);
        $routes->get('Add', 'DeliveryManagement::create', ['filter' => 'roleFilter:21,create']);
        $routes->post('StoreItems', 'DeliveryManagement::storeItems', ['filter' => 'roleFilter:21,create']);
        $routes->post('GetInvoiceItems', 'DeliveryManagement::getInvoiceItems', ['filter' => 'roleFilter:21,list']);
        $routes->post('GetInvoiceByAuctionId', 'DeliveryManagement::getInvoiceByAuctionId', ['filter' => 'roleFilter:21,list']);
        $routes->get('GetDeliveryItems/(:any)', 'DeliveryManagement::getDeliveryItems/$1', ['filter' => 'roleFilter:21,list']);
        $routes->post('Delete', 'DeliveryManagement::delete', ['filter' => 'roleFilter:21,delete']);
    });

    //AuctionStock Controller
    $routes->get('AuctionStock', 'AuctionStock::index', ['filter' => 'roleFilter:19,list']);

    //SoldStock Controller
    $routes->get('SoldStock', 'SoldStock::index', ['filter' => 'roleFilter:19,list']);

    // Settings Controller
    $routes->get('Settings', 'Settings::index', ['filter' => 'roleFilter:16,list']);
    $routes->post('Settings/Create', 'Settings::create', ['filter' => 'roleFilter:16,update']);

    //AuctionManagement Controller
    $routes->get('AuctionManagement', 'AuctionManagement::index', ['filter' => 'roleFilter:19,list']);
    $routes->get('completedAuctions', 'AuctionManagement::completedAuction');
    $routes->post('AuctionManagement/Finalize', 'AuctionManagement::finalize');
    $routes->post('AuctionBiddings/Show', 'AuctionManagement::show', ['filter' => 'roleFilter:19,update']);
    $routes->post('AuctionBiddings/Show1', 'AuctionManagement::show1', ['filter' => 'roleFilter:19,update']);
    $routes->post('BiddingSession/steponeStore', 'AuctionManagement::storeSession', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/steptwoStore', 'AuctionManagement::steptwoCheck', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/stepthreeStore', 'AuctionManagement::stepthreeCheck', ['filter' => 'roleFilter:19,create']);
    $routes->post('BiddingSession/GetInwardItemsByWarehouseId', 'BiddingSession::GetInwardItemsByWarehouseId');

    //Invoice Controller
    $routes->get('Invoice', 'Invoice::index', ['filter' => 'roleFilter:24,list']);
    $routes->get('AuctionToSellerInvoice', 'AuctionToSellerInvoice::index', ['filter' => 'roleFilter:24,list']);
    $routes->get('AuctionToBuyerInvoice', 'AuctionToBuyerInvoice::index', ['filter' => 'roleFilter:23,list']);

    //Invoice Controller
    $routes->get('Invoice/View/(:any)', 'Invoice::view/$1', ['filter' => 'roleFilter:24,list']);
    $routes->get('AuctionToSellerInvoice/View/(:any)', 'AuctionToSellerInvoice::view/$1', ['filter' => 'roleFilter:24,list']);
    $routes->get('AuctionToBuyerInvoice/View/(:any)', 'AuctionToBuyerInvoice::view/$1', ['filter' => 'roleFilter:23,list']);
    $routes->get('Invoice/View2', 'Invoice::view2', ['filter' => 'roleFilter:24,list']);

    $routes->post('Inward/GetGardenGrades', 'Inward::GetGardenGrades', ['filter' => 'roleFilter:10,list']);
    $routes->post('BiddingSession/saveGardenOrder', 'BiddingSession::saveGardenOrder', ['filter' => 'roleFilter:5,create']);
    $routes->get('BiddingSession/GetLiveBiddingPrice/(:any)', 'BiddingSession::getLiveBidding/$1', ['filter' => 'roleFilter:19,list']);

    $routes->get('ProductLog', 'Log::productLog');
    $routes->get('ActivityLog', 'Log::activityLog');
    $routes->post('ActivityLogByDate', 'Log::activityLogByDate');
    $routes->post('ProductLogByDate', 'Log::productLogByDate');

    //Reports Controller
    $routes->get('InwardReport', 'Reports::inwardReport', ['filter' => 'roleFilter:26,list']);
    $routes->post('InwardSearchFilter', 'Reports::inwardSearchFilter', ['filter' => 'roleFilter:26,list']);
    $routes->get('SellerReport', 'Reports::sellerReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('BuyerPurchaseReport', 'Reports::buyerPurchaseReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('GardenBuyerPurchaseReport', 'Reports::gardenBuyerPurchaseReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('StateCityBuyerPurchaseReport', 'Reports::stateCityBuyerPurchaseReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('BuyerSellerGardenSoldReport', 'Reports::buyerSellerGardenSoldReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('BuyerGardenSoldStockReport', 'Reports::buyerGardenSoldStockReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('SellerGardenGradeAvgPriceReport', 'Reports::sellerGardenGradeAvgPriceReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('PriceRangeWiseReport', 'Reports::priceRangeWiseReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('SellerSoldStockReport', 'Reports::sellerSoldStockReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('GardenGradeAvgPriceSaleReport', 'Reports::gardenGradeAvgPriceSaleReport', ['filter' => 'roleFilter:26,list']);
    $routes->get('GardenCompareSoldStockReport', 'Reports::gardenCompareReport', ['filter' => 'roleFilter:26,list']);

    $routes->post('DateWiseSaleno', 'Reports::dateWiseSaleno', ['filter' => 'roleFilter:26,list']);
    $routes->post('SalenoWiseSeller', 'Reports::salenoWiseSeller', ['filter' => 'roleFilter:26,list']);
    $routes->post('SalenoWiseBuyer', 'Reports::salenoWiseBuyer', ['filter' => 'roleFilter:26,list']);
    $routes->post('SalenoWiseGarden', 'Reports::salenoWiseGarden', ['filter' => 'roleFilter:26,list']);
    $routes->post('SellerWiseGarden', 'Reports::sellerWiseGarden', ['filter' => 'roleFilter:26,list']);
    $routes->post('SellerWiseBuyer', 'Reports::sellerWiseBuyer', ['filter' => 'roleFilter:26,list']);
    $routes->get('ManualBidReport', 'Reports::manualBidReport', ['filter' => 'roleFilter:26,list']);
    $routes->post('ManualBidReportSubmit', 'Reports::manualBidReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->get('AutoBidReport', 'Reports::autoBidReport', ['filter' => 'roleFilter:26,list']);
    $routes->post('AutoBidReportSubmit', 'Reports::autoBidReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('Reports/SalenoWiseLot', 'Reports::salenoWiseLot', ['filter' => 'roleFilter:26,list']);
    $routes->post('SalenoWiseState', 'Reports::salenoWiseState', ['filter' => 'roleFilter:26,list']);

    $routes->post('SellerWiseReportSubmit', 'Reports::sellerWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('SaleWiseReportSubmit', 'Reports::saleWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('GardenWiseReportSubmit', 'Reports::gardenWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('StateCityReportSubmit', 'Reports::stateCityReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('BuyerWiseReportSubmit', 'Reports::buyerWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('BuyerSellerWiseReportSubmit', 'Reports::buyerSellerWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('SellerSoldStockReportSubmit', 'Reports::sellerSoldStockReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('PriceRangeWiseReportSubmit', 'Reports::priceRangeWiseReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('GardenGradeAvgPriceReportSubmit', 'Reports::gardenGradeAvgPriceReportSubmit', ['filter' => 'roleFilter:26,list']);
    $routes->post('GardenCompareReportSubmit', 'Reports::gardenCompareReportSubmit', ['filter' => 'roleFilter:26,list']);

});
