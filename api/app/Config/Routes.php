<?php

use CodeIgniter\Router\RouteCollection;

/**
 * @var RouteCollection $routes
 */
$routes->get('/', 'Home::index');


// Api

// Login
$routes->post('api/login', 'login::loginAuth');
$routes->post('api/buyerlogin', 'login::loginBuyerAuth');
$routes->get('api/logout', 'login::logout', ['filter' => 'authFilter']);
$routes->get('api/buyerlogout', 'login::buyerlogout', ['filter' => 'authFilter']);

//Buyer Module
// $routes->group('api/buyer', ['filter' => 'authFilter'], ['namespace' => 'App\Controllers'], static function ($routes) {
//     $routes->get('getAuctionItemsByCenter/(:num)', 'Auction::getAuctionItemsByCenter/$1');
// });

$routes->group('api/sa', ['filter' => 'authFilter'], ['namespace' => 'App\Controllers'], static function ($routes) {
    //USER Controller
    $routes->resource("user");
    //$routes->resource("role");
    $routes->resource("modules");
    $routes->post("storeRole", 'Roles::create');
    $routes->delete("deleteRole/(:num)", 'Roles::delete/$1');
    $routes->put('updateRole/(:num)', 'Roles::update/$1', ['as' => 'roles.update']);
    $routes->get("roles", 'Roles::index');
    $routes->get('roles/(:num)', 'Roles::show/$1', ['as' => 'roles.show']);
});

//$routes->post('api/login', 'login::loginAuth');

$routes->group('api/user', ['filter' => 'authFilter'], ['namespace' => 'App\Controllers'], static function ($routes) {
    //Master Controller
    // $routes->post('state/delete', 'state::delete');
    $routes->resource("state");
    $routes->get("stateCityArea", 'State::stateCityArea');
    $routes->get("stateDropdown", 'State::stateDropdown');

    //$routes->resource("sampleqty");
    $routes->resource("samplequantity");
    $routes->get("samplequantityDropdown", 'Samplequantity::samplequantityDropdown');

    $routes->resource("deliverymanagement");
    $routes->post("deliverymanagement/getInvoices", 'Deliverymanagement::getInvoices');
    $routes->post("deliverymanagement/create", 'Deliverymanagement::create');
    $routes->post("deliverymanagement/getinvoiceitems", 'Deliverymanagement::getInvoiceItems');
    $routes->post("deliverymanagement/getdeliveryitems", 'Deliverymanagement::getDeliveryItems');
    $routes->post('Delete', 'DeliveryManagement::delete');

    $routes->resource("samplereceipt");
    $routes->get("auctionSaleNo", 'Samplereceipt::auctionSaleNo');
    $routes->post("salenoWiseLot", 'Samplereceipt::salenoWiseLot');
    $routes->get("salenoWiseLotnoSelect", 'Samplereceipt::salenoWiseLotnoSelect');


    $routes->resource("area");
    //city wise area
    $routes->post("cityarea/(:num)", 'Area::cityArea/$1');
    $routes->resource("city");
    //state wise city
    $routes->post("statecity/(:num)", 'City::stateCity/$1');

    $routes->resource("seller");
    $routes->get("sellerDropdown", 'Seller::sellerDropdown');
    $routes->resource("garden");
    $routes->get("gardenDropdown", 'Garden::gardenDropdown');
    $routes->post("seller/sellergarden/(:num)", 'Seller::sellergarden/$1');
    $routes->post("garden/assigngrade", 'Garden::assignGrade');
    $routes->get("showassignedgrade/(:num)", 'Garden::showGradesByGarden/$1');
    $routes->get("showcategorygrade/(:num)", 'Garden::showGradesByCategory/$1');
    $routes->get("gardengradelist", 'Garden::gardengradelist');
    $routes->get("Assigngardengradelist/(:num)", 'Garden::assignGardengradelist/$1');
    $routes->get("Assigncentergardenlist/(:num)", 'Center::assignCenterGardenlist/$1');
    $routes->post("garden/reOrderGrade", 'Garden::reOrderGrade');
    $routes->post("center/reOrderGarden", 'Center::reOrderGarden');
    $routes->post("garden/reOrderCategoryGrade", 'Garden::reOrderCategoryGrade');
    $routes->post("AuctionGardenList", 'Cart::auctiongardenlist');
    $routes->get("AuctionGardenOrder/(:num)", 'Cart::auctionGardenOrder/$1');

    $routes->resource("buyer");
    $routes->get("buyerDropdown", 'Buyer::buyerDropdown');
    $routes->resource("center");
    $routes->get("centerDropdown", 'Center::centerDropdown');
    $routes->post("center/assigngarden", 'Center::assignGarden');
    $routes->get("showassignedgarden/(:num)", 'Center::showGardensByCenter/$1');
    $routes->get("centergardenlist", 'Center::centergardenlist');


    $routes->resource("warehouse");
    $routes->get("warehouseDropdown", 'Warehouse::warehouseDropdown');

    $routes->resource("category");
    $routes->get("categoryDropdown", 'Category::categoryDropdown');

    $routes->resource("grade");

    $routes->resource("hsn");

    $routes->resource("package");

    $routes->get("inward/addDatas", 'Inward::addDatas');
    $routes->get("inward/inwardItems", 'Inward::InwardItemDetail');
    $routes->get("inward/inwardItems1", 'Inward::InwardItemDetail1');
    $routes->get("inward/item/(:any)", 'Inward::ItemDetail/$1');
    $routes->get("inward/detail/(:any)", 'Inward::Detail/$1');

    $routes->get("auction/getAuctionItemsUser", 'Auction::getAuctionItemsUser');
    $routes->get("showAuctionItems/(:any)", 'Auction::showAuctionItems/$1');

    $routes->post("auction/getInvoices", 'Auction::getInvoices');
    $routes->post("auction/createAuctionItem", 'Auction::createAuctionItem');
    $routes->post("auction/addToCart", 'Cart::addTocart');
    $routes->post("auction/biddingSessionView", 'Auction::biddingSessionView');
    $routes->post("auction/biddingSessionViewDetail", 'Auction::biddingSessionViewDetail');
    $routes->post("auction/biddingSessionView1", 'Auction::biddingSessionView1');
    $routes->post("auction/closeCurrentAuctionManually", 'Auction::closeCurrentAuctionManually');

    $routes->post("auction/completedAuctionDetail", 'Auction::completedAuctionDetail');
    $routes->get("auction/item/(:any)", 'Auction::AuctionItemDetail/$1');
    $routes->get("auction/getAuctionItems", 'Auction::getAuctionItems');
    $routes->post("auction/closeAuction", 'Auction::closeAuction');
    $routes->get("getAuctionStock", 'Auction::auctionStock');
    $routes->post("centerGarden", 'Auction::centerGarden');
    $routes->post("centerGardenBidding", 'Auction::centerGardenBidding');
    $routes->post("gardenGrade", 'Inward::gardenGrade');
    //Warehouse Management Controller
    $routes->resource("inward");
    $routes->resource("auction");
    $routes->post("auction/updateValuation", 'Auction::updateValuation');
    $routes->post("auction/updateReservePrice", 'Auction::updateReservePrice');
    $routes->post("auction/AuctionReserveBidframe", 'Auction::getResesrveBidframe');

    $routes->resource("auctionitems");
    //$routes->resource("cart");

    $routes->resource("inwarditems");
    $routes->resource("inwardreturn");
    $routes->resource("warehousestock");
    $routes->resource("warehousearchivestock");
    $routes->resource("settings");
    $routes->get("settings/viewData", 'Settings::show');


    $routes->resource("auctionBiddings");
    $routes->get("auctionBiddings1/(:any)", 'AuctionBiddings::show1/$1');
    $routes->get("auctionBiddings/getItemsFinalize", 'AuctionBiddings::getItemsFinalize');
    $routes->get("auctionstockbiddingsession", 'Warehousestock::auctionstockbiddingsession');
    $routes->post("auction/getInvoicesByWarehouseId", 'Auction::getInvoicesByWarehouseId');
    $routes->post("cart/storeCart", 'Cart::create');
    $routes->post("cart/movetoAuction", 'Cart::movetoAuction');
    $routes->get("cart/detail/(:any)", 'Cart::detail/$1');
    $routes->get("cart/inwarddetail/(:any)", 'Cart::inwarddetail/$1');
    $routes->get("cart/delete/(:any)", 'Cart::delete/$1');
    $routes->get("soldStock", 'SoldStock::index');
    $routes->get("invoice", 'Auction::invoices');
    $routes->get("auctionbuyerinvoice", 'Auction::auctionBuyerInvoice');
    $routes->get("auctionsellerinvoice", 'Auction::auctionSellerInvoice');
    $routes->get("invoice/(:any)", 'Auction::invoiceDetails/$1');
    $routes->get("auctionbuyerinvoice/(:any)", 'Auction::auctionbuyerinvoiceDetails/$1');
    $routes->get("auctionsellerinvoice/(:any)", 'Auction::auctionsellerinvoiceDetails/$1');
    $routes->post("cart/reorderAuctionGarden", 'Cart::reorderAuctionGarden');
    $routes->get("highestBidding/(:any)", 'Auction::highestBidding/$1');

    // product log
    $routes->get("productlog", 'Log::productLog');
    $routes->post("productlogbydate", 'Log::productLogByDate');
    $routes->get("activitylog", 'Log::activityLog');
    $routes->post("activitylogbydate", 'Log::activityLogByDate');

    //Reports
    $routes->get("inwardreport", 'Reports::inwardReport');
    $routes->post("inwardSearchFilter", 'Reports::inwardSearchFilter');
    $routes->post("datewisesaleno", 'Reports::dateWiseSaleno');
    $routes->post("salenowiseseller", 'Reports::salenoWiseSeller');
    $routes->post("salenoWiseBuyer", 'Reports::salenoWiseBuyer');
    $routes->post("salenowisegarden", 'Reports::salenoWiseGarden');
    $routes->post("salenowiselot", 'Reports::salenoWiseState');
    $routes->post("sellerWiseBuyer", 'Reports::sellerWiseBuyer');
    $routes->post("reports/salenoWiseLot", 'Reports::salenoWiseLot');
    $routes->post("sellerwisegarden", 'Reports::sellerWiseGarden');
    $routes->post("manualbidreportsubmit", 'Reports::manualBidReportSubmit');
    $routes->post("autobidreportsubmit", 'Reports::autoBidReportSubmit');
    $routes->post("sellerwisereportsubmit", 'Reports::sellerWiseReportSubmit');
    $routes->post("saleWiseReportSubmit", 'Reports::saleWiseReportSubmit');
    $routes->post("gardenWiseReportSubmit", 'Reports::gardenWiseReportSubmit');
    $routes->post("stateCityReportSubmit", 'Reports::stateCityReportSubmit');
    $routes->post("buyerWiseReportSubmit", 'Reports::buyerWiseReportSubmit');
    $routes->post("buyerSellerWiseReportSubmit", 'Reports::buyerSellerWiseReportSubmit');
    $routes->post("sellerSoldStockReportSubmit", 'Reports::sellerSoldStockReportSubmit');
    $routes->post("priceRangeWiseReportSubmit", 'Reports::priceRangeWiseReportSubmit');
    $routes->post("gardenCompareReportSubmit", 'Reports::gardenCompareReportSubmit');
});
$routes->group('api/buyer', ['namespace' => 'App\Controllers'], static function ($routes) {
    $routes->get("dashboard", 'BuyerDashboard::index');
    $routes->get("profile/(:num)", 'BuyerDashboard::profile/$1');
    $routes->post("profileupdate", 'BuyerDashboard::profileUpdate');
    $routes->get("getUpcomingAuctions", 'BuyerAuctions::upcomingAuctions');
    $routes->get("getCompletedAuctions", 'BuyerAuctions::completedAuctions');
    $routes->post("movetoclosed", 'BuyerDashboard::movetoclosed');
    $routes->post('getBidTiming', 'BuyerDashboard::getBidTiming');
    $routes->get('getAuctionItemsByCenter/(:num)/(:num)', 'BuyerDashboard::getAuctionItemsByCenter/$1/$2');
    $routes->get('getAuctionItemsByAuction/(:num)/(:num)', 'BuyerDashboard::getAuctionItemsByAuction/$1/$2');
    $routes->get('getmyBidbookByAuction/(:num)/(:num)', 'BuyerDashboard::getmyBidbookByAuction/$1/$2');
    $routes->get('getMyCatalogs/(:num)/(:num)', 'BuyerDashboard::getMyCatalogs/$1/$2');
    $routes->get('getAuctionItemDetails/(:num)/(:any)', 'BuyerDashboard::getAuctionItemDetails/$1/$2');
    $routes->post('addtoCatalog', 'BuyerDashboard::addtoCatalog');
    $routes->post('autoBidding', 'BuyerAuctions::autoBidding');
    $routes->post('autoBidding/view', 'BuyerAuctions::autoBiddingShow');
    $routes->post('movetoreview', 'BuyerAuctions::movetoReview');
    $routes->post('addMinAutoBidPrice', 'BuyerAuctions::addMinAutoBidPrice');
    $routes->post('addMaxAutoBidPrice', 'BuyerAuctions::addMaxAutoBidPrice');
    $routes->post('deleteBidData', 'BuyerAuctions::deleteBidData');
    $routes->post('completeMinTime', 'BuyerAuctions::completeMinimumTime');
    $routes->post('autoBidLog', 'BuyerDashboard::InitialAutoCall');
});

$routes->get('api/lastSoldPrice', 'LastSoldPrice::lastSoldPrice');
