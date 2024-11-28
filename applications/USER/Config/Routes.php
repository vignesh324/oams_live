<?php
$routes->group('USER', ['namespace' => 'Applications\USER\Controllers'], static function ($routes) {
    $routes->get('access-denied', 'AccessController::accessDenied', ['as' => 'access_denied']);

    //USER Controller
    $routes->get('/', 'USER::index', ['as' => 'user_login']);
    $routes->post('Login', 'USER::Login');
    $routes->get('Logout', 'USER::Logout');

    //Dashboard Controller
    // $routes->get('Dashboard', 'Dashboard::index');
    $routes->get('Dashboard', 'Dashboard::index', ['as' => 'user_dashboard']);

    //Seller Controller
    $routes->group('Seller', function ($routes) {
        $routes->get('', 'Seller::index');
        $routes->post('Add', 'Seller::create');
        $routes->post('Create', 'Seller::store');
        $routes->post('Show', 'Seller::show');
        $routes->post('Update', 'Seller::update');
        $routes->post('Delete', 'Seller::delete');
        $routes->post('SellerGarden', 'Seller::SellerGarden');
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
        $routes->post('AssignGrade', 'Garden::AssignGrade');
        $routes->post('SaveGrade', 'Garden::SaveGrade');
        $routes->post('reOrder', 'Garden::reOrder');
        $routes->post('AssignCategoryGrade', 'Garden::AssignCategoryGrade');
        $routes->post('reOrderCategoryGrade', 'Garden::reOrderCategoryGrade');
        $routes->get('Category', 'Garden::categoryGarden');
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
        $routes->get('', 'State::index', ['as' => 'state', 'filter' => 'roleFilter:1,list']); // List permission
        $routes->post('Add', 'State::create', ['filter' => 'roleFilter:1,create']);           // Create permission
        $routes->post('Create', 'State::store', ['filter' => 'roleFilter:1,create']);         // Create permission
        $routes->post('Show', 'State::show', ['filter' => 'roleFilter:1,list']);              // List permission
        $routes->post('Update', 'State::update', ['filter' => 'roleFilter:1,update']);        // Update permission
        $routes->post('Delete', 'State::delete', ['filter' => 'roleFilter:1,delete']);        // Delete permission
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

    //SampleQuantity Controller
    $routes->group('SampleQuantity', function ($routes) {
        $routes->get('', 'SampleQuantity::index');
        $routes->post('Add', 'SampleQuantity::create');
        $routes->post('Create', 'SampleQuantity::store');
        $routes->post('Show', 'SampleQuantity::show');
        $routes->post('Update', 'SampleQuantity::update');
        $routes->post('Delete', 'SampleQuantity::delete');
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
        $routes->post('AssignGarden', 'Center::AssignGarden');
        $routes->post('SaveGarden', 'Center::SaveGarden');
        $routes->post('reOrder', 'Center::reOrder');
    });

    //CenterGarden Controller

    $routes->get('CenterGarden', 'CenterGarden::index');

    //Inward Controller
    $routes->get('Inward', 'Inward::index');
    $routes->get('Inward/Add', 'Inward::add');
    $routes->post('Inward/AddAjax', 'Inward::addAjax');
    $routes->post('Inward/Store', 'Inward::Store');
    $routes->post('Inward/Update', 'Inward::Update');
    $routes->get('Inward/Edit/(:any)', 'Inward::Edit/$1');
    $routes->get('Inward/View/(:any)', 'Inward::View/$1');
    $routes->post('Inward/Delete', 'Inward::delete');

    //BiddingSession Controller
    $routes->get('BiddingSession', 'BiddingSession::index');
    $routes->get('BiddingSession/Add', 'BiddingSession::add');
    $routes->post('BiddingSession/Store', 'BiddingSession::store');
    $routes->post('BiddingSession/StoreAuctionItems', 'BiddingSession::storeAuctionItems');
    $routes->post('BiddingSession/addToCart', 'BiddingSession::addToCart');
    $routes->post('BiddingSession/closeBidding', 'BiddingSession::closeAuction');
    $routes->post('BiddingSession/Create', 'BiddingSession::create');
    $routes->get('BiddingSession/AddAuctionItems/(:any)', 'BiddingSession::addAuctionItems/$1');
    $routes->get('BiddingSession/Edit/(:any)', 'BiddingSession::edit/$1');
    $routes->post('BiddingSession/Edit', 'BiddingSession::edit');
    $routes->post('BiddingSession/Delete', 'BiddingSession::delete');
    $routes->get('BiddingSession/View/(:any)', 'BiddingSession::view/$1');
    $routes->get('BiddingSession/CompletedAuctions/(:any)', 'BiddingSession::completedAuctions/$1');
    $routes->get('BiddingSession/EditValuation/(:any)', 'BiddingSession::editValuation/$1');
    $routes->get('BiddingSession/EditReserve/(:any)', 'BiddingSession::editReserve/$1');
    $routes->get('BiddingSession/EditReserveBidframe/(:any)', 'BiddingSession::editReserveBidframe/$1');

    $routes->get('BiddingSession/withdraw/(:any)', 'BiddingSession::withDraw/$1');
    $routes->get('BiddingSession/reOrderGarden/(:any)', 'BiddingSession::reOrderGarden/$1');
    $routes->get('BiddingSession/AuctionCartItems/(:any)', 'BiddingSession::cartItems/$1');
    $routes->get('BiddingSession/AuctionCartItems1/(:any)', 'BiddingSession::cartItems1/$1');
    $routes->post('BiddingSession/Update', 'BiddingSession::Update');
    $routes->post('BiddingSession/UpdateValuation', 'BiddingSession::updateValuation');
    $routes->post('BiddingSession/UpdateReservePrice', 'BiddingSession::updateReservePrice');
    $routes->post('BiddingSession/GetInwardItems', 'BiddingSession::GetInwardItems');
    $routes->post('BiddingSession/GetCenterGardens', 'BiddingSession::GetCenterGardens');
    $routes->post('BiddingSession/GetInwardItemDetails', 'BiddingSession::GetInwardItemDetails');
    $routes->post('reOrderBiddingSession', 'BiddingSession::reOrderBiddingSession');
    $routes->post('BiddingSession/deleteCart', 'BiddingSession::deleteCart');
    $routes->post('BiddingSession/cartToAuction', 'BiddingSession::cartToAuction');
    $routes->post('BiddingSession/CloseCurrentAuctionManually', 'BiddingSession::closeCurrentAuctionManually');

    //InwardReturn Controller
    $routes->post('InwardReturn/getInvoiceDetail', 'InwardReturn::getInvoiceDetail');
    $routes->get('InwardReturn', 'InwardReturn::index');
    $routes->get('InwardReturn/Add', 'InwardReturn::add');
    $routes->post('InwardReturn/Show', 'InwardReturn::show');
    $routes->post('InwardReturn/Store', 'InwardReturn::Store');
    $routes->post('InwardReturn/Delete', 'InwardReturn::delete');

    //WarehouseStock Controller
    $routes->get('WarehouseStock', 'WarehouseStock::index');

    //WarehouseArchiveStock Controller
    $routes->get('WarehouseArchiveStock', 'WarehouseArchiveStock::index');

    //SampleReceipt Controller
    $routes->group('SampleReceipt', function ($routes) {
        $routes->get('', 'SampleReceipt::index');
        $routes->post('Add', 'SampleReceipt::create');
        $routes->post('Create', 'SampleReceipt::store');
        $routes->post('Show', 'SampleReceipt::show');
        $routes->post('Update', 'SampleReceipt::update');
        $routes->post('Delete', 'SampleReceipt::delete');
        $routes->post('SalenoWiseLot', 'SampleReceipt::salenoWiseLot');
    });

    //DeliveryManagement Controller
    $routes->group('DeliveryManagement', function ($routes) {
        $routes->get('', 'DeliveryManagement::index');
        $routes->get('Add', 'DeliveryManagement::create');
        $routes->post('StoreItems', 'DeliveryManagement::storeItems');
        $routes->post('GetInvoiceItems', 'DeliveryManagement::getInvoiceItems');
        $routes->post('GetInvoiceByAuctionId', 'DeliveryManagement::getInvoiceByAuctionId');
        $routes->get('GetDeliveryItems/(:any)', 'DeliveryManagement::getDeliveryItems/$1');
        $routes->post('Delete', 'DeliveryManagement::delete');
    });

    //AuctionStock Controller
    $routes->get('AuctionStock', 'AuctionStock::index');

    //SoldStock Controller
    $routes->get('SoldStock', 'SoldStock::index');

    // Settings Controller
    $routes->get('Settings', 'Settings::index');
    $routes->post('Settings/Create', 'Settings::create');

    //AuctionManagement Controller
    $routes->get('AuctionManagement', 'AuctionManagement::index');
    $routes->get('completedAuctions', 'AuctionManagement::completedAuction');
    $routes->post('AuctionManagement/Finalize', 'AuctionManagement::finalize');
    $routes->post('AuctionBiddings/Show', 'AuctionManagement::show');
    $routes->post('AuctionBiddings/Show1', 'AuctionManagement::show1');
    $routes->post('BiddingSession/steponeStore', 'AuctionManagement::storeSession');
    $routes->post('BiddingSession/steptwoStore', 'AuctionManagement::steptwoCheck');
    $routes->post('BiddingSession/stepthreeStore', 'AuctionManagement::stepthreeCheck');
    $routes->post('BiddingSession/GetInwardItemsByWarehouseId', 'BiddingSession::GetInwardItemsByWarehouseId');

    //Invoice Controller
    $routes->get('Invoice', 'Invoice::index');
    $routes->get('AuctionToSellerInvoice', 'AuctionToSellerInvoice::index');
    $routes->get('AuctionToBuyerInvoice', 'AuctionToBuyerInvoice::index');

    //Invoice Controller
    $routes->get('Invoice/View/(:any)', 'Invoice::view/$1');
    $routes->get('AuctionToSellerInvoice/View/(:any)', 'AuctionToSellerInvoice::view/$1');
    $routes->get('AuctionToBuyerInvoice/View/(:any)', 'AuctionToBuyerInvoice::view/$1');
    $routes->get('Invoice/View2', 'Invoice::view2');

    $routes->post('Inward/GetGardenGrades', 'Inward::GetGardenGrades');
    $routes->post('BiddingSession/saveGardenOrder', 'BiddingSession::saveGardenOrder');
    $routes->get('BiddingSession/GetLiveBiddingPrice/(:any)', 'BiddingSession::getLiveBidding/$1');

    $routes->get('ProductLog', 'Log::productLog');
    $routes->get('ActivityLog', 'Log::activityLog');
    $routes->post('ActivityLogByDate', 'Log::activityLogByDate');
    $routes->post('ProductLogByDate', 'Log::productLogByDate');

    //Reports Controller
    $routes->get('InwardReport', 'Reports::inwardReport');
    $routes->post('InwardSearchFilter', 'Reports::inwardSearchFilter');
    $routes->get('SellerReport', 'Reports::sellerReport');
    $routes->get('BuyerPurchaseReport', 'Reports::buyerPurchaseReport');
    $routes->get('GardenBuyerPurchaseReport', 'Reports::gardenBuyerPurchaseReport');
    $routes->get('StateCityBuyerPurchaseReport', 'Reports::stateCityBuyerPurchaseReport');
    $routes->get('BuyerSellerGardenSoldReport', 'Reports::buyerSellerGardenSoldReport');
    $routes->get('BuyerGardenSoldStockReport', 'Reports::buyerGardenSoldStockReport');
    $routes->get('SellerGardenGradeAvgPriceReport', 'Reports::sellerGardenGradeAvgPriceReport');
    $routes->get('PriceRangeWiseReport', 'Reports::priceRangeWiseReport');
    $routes->get('SellerSoldStockReport', 'Reports::sellerSoldStockReport');
    $routes->get('GardenGradeAvgPriceSaleReport', 'Reports::gardenGradeAvgPriceSaleReport');
    $routes->get('GardenCompareSoldStockReport', 'Reports::gardenCompareReport');

    $routes->post('DateWiseSaleno', 'Reports::dateWiseSaleno');
    $routes->post('SalenoWiseSeller', 'Reports::salenoWiseSeller');
    $routes->post('SalenoWiseBuyer', 'Reports::salenoWiseBuyer');
    $routes->post('SalenoWiseGarden', 'Reports::salenoWiseGarden');
    $routes->post('SellerWiseGarden', 'Reports::sellerWiseGarden');
    $routes->post('SellerWiseBuyer', 'Reports::sellerWiseBuyer');
    $routes->get('ManualBidReport', 'Reports::manualBidReport');
    $routes->post('ManualBidReportSubmit', 'Reports::manualBidReportSubmit');
    $routes->get('AutoBidReport', 'Reports::autoBidReport');
    $routes->post('AutoBidReportSubmit', 'Reports::autoBidReportSubmit');
    $routes->post('Reports/SalenoWiseLot', 'Reports::salenoWiseLot');
    $routes->post('SalenoWiseState', 'Reports::salenoWiseState');

    $routes->post('SellerWiseReportSubmit', 'Reports::sellerWiseReportSubmit');
    $routes->post('SaleWiseReportSubmit', 'Reports::saleWiseReportSubmit');
    $routes->post('GardenWiseReportSubmit', 'Reports::gardenWiseReportSubmit');
    $routes->post('StateCityReportSubmit', 'Reports::stateCityReportSubmit');
    $routes->post('BuyerWiseReportSubmit', 'Reports::buyerWiseReportSubmit');
    $routes->post('BuyerSellerWiseReportSubmit', 'Reports::buyerSellerWiseReportSubmit');
    $routes->post('SellerSoldStockReportSubmit', 'Reports::sellerSoldStockReportSubmit');
    $routes->post('PriceRangeWiseReportSubmit', 'Reports::priceRangeWiseReportSubmit');
    $routes->post('GardenGradeAvgPriceReportSubmit', 'Reports::gardenGradeAvgPriceReportSubmit');
    $routes->post('GardenCompareReportSubmit', 'Reports::gardenCompareReportSubmit');

});
