<?php

namespace App\Controllers;

use App\Models\BuyerModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\InwardItemModel;
use App\Models\AuctionModel;
use App\Models\AuctionBiddingModel;
use App\Models\AuctionItemModel;
use App\Models\AutoBidHistoryModel;
use App\Models\AutoBiddingModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use App\Models\GardenModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Helpers\CodeHelper;
use App\Models\GradeModel;

class Reports extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function inwardReport()
    {
        // echo 'jii';exit;
        $inwardItemModel = new InwardItemModel();
        $inwardItems = $inwardItemModel->select('inward_items.*,garden.name AS garden_name,
            grade.name AS grade_name,warehouse.name AS warehouse_name')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->where('inward_items.status', 1)->findAll();

        $data['inwardItems'] = $inwardItems;

        return $this->respond($data);
    }

    public function inwardSearchFilter()
    {
        $warehouse_id = $this->request->getVar('warehouse_id');
        $garden_id = $this->request->getVar('garden_id');
        $grade_id = $this->request->getVar('grade_id');
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');

        $inwardItemModel = new InwardItemModel();
        $inwardItems = $inwardItemModel->select('inward_items.*,garden.name AS garden_name,
            grade.name AS grade_name,warehouse.name AS warehouse_name')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->where('inward_items.status', 1);

        if ($warehouse_id !== null) {
            $inwardItems->where('inward.warehouse_id', $warehouse_id);
        }
        if ($garden_id !== null) {
            $inwardItems->where('inward.garden_id', $garden_id);
        }
        if ($grade_id !== null) {
            $inwardItems->where('inward_items.grade_id', $grade_id);
        }
        if ($fromDate !== null && $toDate !== null) {
            $inwardItems->where('DATE(inward_items.created_at) >=', $fromDate);
            $inwardItems->where('DATE(inward_items.created_at) <=', $toDate);
        }

        $data = $inwardItems->findAll();

        // $lastQuery = $inwardItemModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($data);
        // exit;

        return $this->respond($response);
    }

    public function dateWiseSaleno()
    {
        $fromDate = $this->request->getVar('from_date');
        $toDate = $this->request->getVar('to_date');

        // return 'hii';
        $model = new AuctionModel();
        $auctions = $model->select('auction.id,auction.sale_no')
            ->where('DATE(auction.date) >=', $fromDate)
            ->where('DATE(auction.date) <=', $toDate)
            ->where('auction.status !=', 1)
            ->findAll();

        $data['auction'] = $auctions;
        return $this->respond($data);
    }

    public function salenoWiseSeller()
    {
        $auction_id = $this->request->getVar('auction_id');

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        // return 'hii';
        $model = new AuctionModel();
        $auctions = $model->select('seller.id,seller.name')
            ->join('auction_items', 'auction_items.auction_id = auction.id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('seller', 'seller.id = inward.seller_id', 'left')
            ->whereIn('auction.id', $auction_id)
            ->where('auction.status !=', 1)
            ->where('seller.status', 1)
            ->groupBy('seller.id')
            ->findAll();

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['seller'] = $auctions;
        return $this->respond($data);
    }
    public function salenoWiseBuyer()
    {
        $auction_id = $this->request->getVar('auction_id');

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        // return 'hii';
        $model = new AuctionModel();
        $auctions = $model->select('buyer.id,buyer.name')
            ->join('auction_items', 'auction_items.auction_id = auction.id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('invoice_item', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->whereIn('auction.id', $auction_id)
            ->where('auction.status !=', 1)
            ->where('buyer.status', 1)
            ->groupBy('buyer.id')
            ->findAll();

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['buyer'] = $auctions;
        return $this->respond($data);
    }
    public function salenoWiseGarden()
    {
        $auction_id = $this->request->getVar('auction_id');

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        // return 'hii';
        $model = new AuctionModel();
        $auctions = $model->select('garden.id,garden.name')
            ->join('auction_items', 'auction_items.auction_id = auction.id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->whereIn('auction.id', $auction_id)
            ->where('auction.status !=', 1)
            ->where('garden.status', 1)
            ->groupBy('garden.id')
            ->findAll();

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['garden'] = $auctions;
        return $this->respond($data);
    }

    public function salenoWiseLot()
    {
        $model = new AuctionItemModel();
        $auction_id = $this->request->getVar('auction_id');

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        // print_r($data1['auction_id']);exit;
        $data['lot_no'] = $model->select('auction_items.id, auction_items.lot_no')
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.status !=', 1)
            ->findAll();

        return $this->respond($data);
    }
    public function salenoWiseState()
    {
        $auctionItemModel = new AuctionItemModel();
        $auction_id = $this->request->getVar('auction_id');

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        // print_r($data1['auction_id']);exit;
        $data['state'] = $auctionItemModel->select('state.id, state.name')
            ->join('invoice_item', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('state', 'state.id = buyer.state_id', 'left')
            ->whereIn('auction.id', $auction_id)
            ->where('auction.status', 2)
            ->groupBy(['state.id', 'state.name'])
            ->findAll();

        // $lastQuery = $auctionItemModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        return $this->respond($data);
    }

    public function sellerWiseGarden()
    {
        $seller_id = $this->request->getVar('seller_id');

        $model = new GardenModel();

        $data['sellerGarden'] = $model->select('garden.id,garden.name,garden.vacumm_bag')
            ->where('garden.seller_id', $seller_id)
            ->where('garden.status', 1)
            ->findAll();


        if ($data['sellerGarden']) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $seller_id);
        }
    }
    public function sellerWiseBuyer()
    {
        $seller_id = $this->request->getVar('seller_id');

        $auctionModel = new AuctionModel();

        $data['buyer'] = $auctionModel->select('buyer.id,buyer.name')
            ->join('auction_items', 'auction_items.auction_id = auction.id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('invoice_item', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->where('inward.seller_id', $seller_id)
            ->where('buyer.status', 1)
            ->groupBy('buyer.id')
            ->findAll();

        // $lastQuery = $auctionModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        return $this->respond($data);
    }
    public function sellerWiseReportSubmit()
    {
        $seller_id = $this->request->getVar('seller_id');
        $auction_id = $this->request->getVar('auction_id');
        $garden_id = $this->request->getVar('garden_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        // print_r($auction_id);exit;

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItem = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
    ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->where('seller.id', $seller_id);


        if ($garden_id) {
            $invoiceItem->where('garden.id', $garden_id);
        }

        $invoiceItem = $invoiceItem
            ->groupBy(['buyer.id', 'auction.id'])
            ->findAll();

        // $lastQuery = $invoiceItemModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['invoiceItems'] = $invoiceItem;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
    public function stateCityReportSubmit()
    {
        $state_id = $this->request->getVar('state_id');
        $city_id = $this->request->getVar('city_id');
        $auction_id = $this->request->getVar('auction_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        // print_r($auction_id);exit;

        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItem = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            buyer.state_id,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
            ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->where('buyer.state_id', $state_id);


        if ($city_id) {
            $invoiceItem->where('buyer.city_id', $city_id);
        }

        $invoiceItem = $invoiceItem
            ->groupBy(['buyer.id', 'auction.id'])
            ->findAll();

        // $lastQuery = $invoiceItemModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['invoiceItems'] = $invoiceItem;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
    public function saleWiseReportSubmit()
    {
        $auction_id = $this->request->getVar('auction_id');
        $buyer_id = $this->request->getVar('buyer_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        $garden_id = $this->request->getVar('garden_id');
        // print_r($auction_id);exit;
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItem = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->groupBy(['buyer.id', 'auction.id'])
            ->orderBy('auction.id')
            ->findAll();

        $data['invoiceItems'] = $invoiceItem;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }

    public function priceRangeWiseReportSubmit()
    {
        // print_r('hii');exit;
        $auction_id = $this->request->getVar('auction_id');
        $buyer_id = $this->request->getVar('buyer_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItems = $invoiceItemModel->select('
        buyer.name AS buyer_name,
        auction.sale_no,
        invoice_item.qty,
        invoice_item.bid_price,
        grade.type AS grade_type
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->findAll();

        // Define price ranges
        $priceRanges = [
            '50-59' => [50, 59],
            '60-69' => [60, 69],
            '70-79' => [70, 79],
            '80-89' => [80, 89],
            '90-99' => [90, 99],
            '100-109' => [100, 109],
            '110-119' => [110, 119],
            '120-129' => [120, 129],
            '130-139' => [130, 139],
            '140-150' => [140, 150],
            '>150' => [151, PHP_INT_MAX],
        ];

        $data = [
            'Dust' => [
                'sold_quantity' => [],
                'sold_percentage' => []
            ],
            'Leaf' => [
                'sold_quantity' => [],
                'sold_percentage' => []
            ]
        ];

        foreach ($priceRanges as $range => $limits) {
            // print_r($limits);exit;
            $dustTotalQty = 0;
            $leafTotalQty = 0;
            foreach ($invoiceItems as $item) {
                if ($item['bid_price'] >= $limits[0] && $item['bid_price'] <= $limits[1]) {
                    if ($item['grade_type'] == 1) {
                        $leafTotalQty += $item['qty'];
                    } else if ($item['grade_type'] == 2) {
                        $dustTotalQty += $item['qty'];
                    }
                }
            }
            $data['Dust']['sold_quantity'][$range] = $dustTotalQty;
            $data['Leaf']['sold_quantity'][$range] = $leafTotalQty;
        }

        $dustGrandTotal = array_sum($data['Dust']['sold_quantity']);
        $leafGrandTotal = array_sum($data['Leaf']['sold_quantity']);

        foreach ($priceRanges as $range => $limits) {
            $data['Dust']['sold_percentage'][$range] = ($dustGrandTotal > 0) ? ($data['Dust']['sold_quantity'][$range] / $dustGrandTotal) * 100 : 0;
            $data['Leaf']['sold_percentage'][$range] = ($leafGrandTotal > 0) ? ($data['Leaf']['sold_quantity'][$range] / $leafGrandTotal) * 100 : 0;
        }

        $data['Dust']['grand_total'] = $dustGrandTotal;
        $data['Leaf']['grand_total'] = $leafGrandTotal;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }

    public function sellerSoldStockReportSubmit()
    {
        $auction_id = $this->request->getVar('auction_id');
        $buyer_id = $this->request->getVar('buyer_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        $garden_id = $this->request->getVar('garden_id');
        // print_r($auction_id);exit;
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItem = $invoiceItemModel->select('
            seller.name AS seller_name,
            auction.sale_no,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->groupBy(['seller.id', 'auction.id'])
            ->orderBy('auction.id')
            ->findAll();

        $data['invoiceItems'] = $invoiceItem;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
    public function buyerWiseReportSubmit()
    {
        $auction_id = $this->request->getVar('auction_id');
        $garden_id = $this->request->getVar('garden_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        // print_r($auction_id);exit;
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItem = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->groupBy(['buyer.id', 'auction.id'])
            ->orderBy('auction.id')
            ->findAll();

        $data['invoiceItems'] = $invoiceItem;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
    public function buyerSellerWiseReportSubmit()
    {
        $auction_id = $this->request->getVar('auction_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        $buyer_id = $this->request->getVar('buyer_id');
        $seller_id = $this->request->getVar('seller_id');
    
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
    
        $invoiceItemModel = new InvoiceItemModel();
    
        $invoiceItems = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            garden.id AS garden_id,
            garden.name AS garden_name,
            auction.id AS auction_id,
            grade.id AS grade_id,
            grade.name AS grade_name,
            grade.type AS grade_type,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->where('seller.id', $seller_id)
            ->where('buyer.id', $buyer_id)
            ->groupBy(['buyer.id', 'auction.id', 'garden.id', 'grade.id'])
            ->orderBy('auction.id')
            ->findAll();
    
        $data = [];
        $gardenMap = [];
    
        foreach ($invoiceItems as $item) {
            if (!isset($gardenMap[$item['garden_id']])) {
                $gardenMap[$item['garden_id']] = [
                    'id' => $item['garden_id'],
                    'name' => $item['garden_name'],
                    'grades' => []
                ];
            }
    
            $garden = &$gardenMap[$item['garden_id']];
    
            $gradeExists = false;
            foreach ($garden['grades'] as &$grade) {
                if ($grade['id'] == $item['grade_id']) {
                    $gradeExists = true;
                    break;
                }
            }
    
            if (!$gradeExists) {
                $garden['grades'][] = [
                    'id' => $item['grade_id'],
                    'name' => $item['grade_name'],
                    'leaf_total_quantity' => 0,
                    'leaf_total_bid_price' => 0,
                    'dust_total_quantity' => 0,
                    'dust_total_bid_price' => 0,
                    'leaf_avg_price' => 0,
                    'dust_avg_price' => 0,
                ];
            }
    
            $gradeIndex = array_search($item['grade_id'], array_column($garden['grades'], 'id'));
    
            $grade = &$garden['grades'][$gradeIndex];
    
            if ($item['grade_type'] == 1) {
                $grade['leaf_total_quantity'] += $item['leaf_total_quantity'];
                $grade['leaf_total_bid_price'] += $item['leaf_total_bid_price'];
                if ($grade['leaf_total_quantity'] > 0) {
                    $grade['leaf_avg_price'] = $grade['leaf_total_bid_price'] / $grade['leaf_total_quantity'];
                }
            } elseif ($item['grade_type'] == 2) {
                $grade['dust_total_quantity'] += $item['dust_total_quantity'];
                $grade['dust_total_bid_price'] += $item['dust_total_bid_price'];
                if ($grade['dust_total_quantity'] > 0) {
                    $grade['dust_avg_price'] = $grade['dust_total_bid_price'] / $grade['dust_total_quantity'];
                }
            }
        }
    
        $data['gardens'] = array_values($gardenMap);
    
        if (!empty($data)) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }
       

    public function gardenWiseReportSubmit()
    {
        $auction_id = $this->request->getVar('auction_id');
        $garden_id = $this->request->getVar('garden_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $invoiceItemModel = new InvoiceItemModel();
        $invoiceItems = $invoiceItemModel->select('
            buyer.name AS buyer_name,
            auction.sale_no,
            garden.name AS garden_name,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END) AS leaf_total_quantity,
            SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) AS leaf_total_bid_price,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END) AS dust_total_quantity,
            SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) AS dust_total_bid_price,
            COALESCE((SUM(CASE WHEN grade.type = 1 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 1 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS leaf_avg_price,
            COALESCE((SUM(CASE WHEN grade.type = 2 THEN invoice_item.bid_price ELSE 0 END) / NULLIF(SUM(CASE WHEN grade.type = 2 THEN invoice_item.qty ELSE 0 END), 0)), 0) AS dust_avg_price
        ')
            ->join('invoice', 'invoice.id = invoice_item.invoice_id', 'left')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('auction_items', 'auction_items.id = invoice_item.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('DATE(auction.date) >=', $from_date)
            ->where('DATE(auction.date) <=', $to_date)
            ->whereIn('auction.id', $auction_id)
            ->where('garden.id', $garden_id)
            ->groupBy(['buyer.id', 'auction.id'])
            ->orderBy('auction.id')
            ->findAll();

        // $lastQuery = $invoiceItemModel->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['invoiceItems'] = $invoiceItems;

        if ($invoiceItems) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found');
        }
    }

    public function manualBidReportSubmit()
    {

        $lot_no = $this->request->getVar('lot_no');
        $auction_id = $this->request->getVar('auction_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $model = new AuctionBiddingModel();

        $auctionBiddings = $model->select('auction_biddings.*,auction_items.lot_no,buyer.name AS buyername')
            ->join('auction_items', 'auction_biddings.auction_item_id = auction_items.id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->join('buyer', 'auction_biddings.buyer_id = buyer.id', 'left')
            ->where('DATE(auction.created_at) >=', $from_date)
            ->where('DATE(auction.created_at) <=', $to_date)
            ->where('auction_biddings.bid_type', 1)
            ->whereIn('auction_biddings.auction_item_id', $lot_no)
            ->orderBy('auction_biddings.id', 'DESC')
            ->findAll();

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['auctionBiddings'] = $auctionBiddings;
        return $this->respond($data);
    }

    public function autoBidReportSubmit()
    {
        // print_r('hii');
        // exit;

        $lot_no = $this->request->getVar('lot_no');
        $auction_id = $this->request->getVar('auction_id');
        $from_date = $this->request->getVar('from_date');
        $to_date = $this->request->getVar('to_date');
        if (!is_array($auction_id)) {
            $auction_id = [$auction_id];
        }
        $model = new AutoBidHistoryModel();

        $auctionBiddings = $model->select('auto_bid_history.*,auction_items.lot_no,buyer.name AS buyername')
            ->join('auction_items', 'auto_bid_history.auction_item_id = auction_items.id', 'left')
            ->join('auction', 'auction.id = auto_bid_history.auction_id', 'left')
            ->join('buyer', 'auto_bid_history.buyer_id = buyer.id', 'left')
            ->where('DATE(auction.created_at) >=', $from_date)
            ->where('DATE(auction.created_at) <=', $to_date)
            ->whereIn('auto_bid_history.auction_item_id', $lot_no)
            ->orderBy('auto_bid_history.id', 'DESC')
            ->findAll();

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $data['autoBiddings'] = $auctionBiddings;
        return $this->respond($data);
    }
}
