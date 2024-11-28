<?php

namespace App\Controllers;

use App\Models\DeliveryItems;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\DeliveryManagementModel;
use App\Models\DeliveryItemsModel;
use App\Models\SoldStockModel;
use App\Models\InvoiceModel;
use App\Models\InvoiceItemModel;
use CodeIgniter\API\ResponseTrait;
use CodeIgniter\Controller;
use \Firebase\JWT\JWT;
use Config\Services;


class Deliverymanagement extends Controller
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        // echo 'hii';exit;
        $delivery_model = new DeliveryManagementModel();
        $data['deliveryManagement'] = $delivery_model->select('delivery_receipt.*,invoice.invoice_no,auction.sale_no,seller.name AS seller_name,buyer.name AS buyer_name,buyer.contact_person_number')
            ->join('invoice', 'invoice.id = delivery_receipt.invoice_id', 'left')
            ->join('auction', 'auction.id = delivery_receipt.auction_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->findAll();

        return $this->respond($data);
    }
    public function getDeliveryItems()
    {
        $id = $this->request->getVar('receipt_id');

        $delivery_model = new DeliveryManagementModel();
        $deliveryManagement = $delivery_model->select('delivery_receipt.*,invoice.invoice_no,auction.sale_no,
            buyer.fssai_no AS b_fssai,buyer.tea_board_no AS b_tea,buyer.gst_no AS b_gst,
            buyer.state_id AS b_state_id,buyer.city_id AS b_city_id,buyer.area_id AS b_area_id,
            buyer.address AS b_address,auction.sale_no,seller.name AS seller_name,
            buyer.name AS buyer_name,buyer.email AS buyer_email,buyer.contact_person_number,seller.fssai_no AS s_fssai,
            seller.tea_board_no AS s_tea,seller.gst_no AS s_gst,seller.address AS s_address,
            seller.state_id AS s_state_id,seller.city_id AS s_city_id,seller.area_id AS s_area_id,
            (SELECT delivery_time FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS settings_delivery_time')
            ->join('invoice', 'invoice.id = delivery_receipt.invoice_id', 'left')
            ->join('auction', 'auction.id = delivery_receipt.auction_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->where('delivery_receipt.id', $id)
            ->first();

        if ($deliveryManagement) {

            $deliveryitems_model = new DeliveryItemsModel();
            $deliveryitems = $deliveryitems_model->select('delivery_items.*,invoice_item.bid_price,invoice_item.qty AS pkgs,
                auction_items.sample_quantity,auction_items.lot_no,inward_items.weight_net,
                inward_items.total_net,inward_items.total_gross,grade.name AS grade_name,grade.type AS grade_type,
                center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name,
                (SELECT leaf_hsn FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS leaf_hsn,
                (SELECT dust_hsn FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS dust_hsn,
                (SELECT leaf_sq FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS leaf_sq,
                (SELECT dust_sq FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS dust_sq')
                ->join('auction_items', 'delivery_items.auction_item_id = auction_items.id', 'left')
                ->join('invoice_item', 'invoice_item.auction_item_id = auction_items.id', 'left')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
                ->where('delivery_items.receipt_id', $id)
                ->findAll();

            $deliveryManagement['deliveryItems'] = $deliveryitems;
        }

        $data['delivery'] = $deliveryManagement;
        // print_r($data);exit;

        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function create()
    {
        $soldStockModel = new SoldStockModel();
        $deliveryManagementModel = new DeliveryManagementModel();
        $deliveryItemsModel = new DeliveryItemsModel();

        $rules = [
            'qty.*' => 'required|numeric',
        ];

        $messages = [
            "qty.*" => [
                "required" => "Delivery Quantity is required.",
                "numeric" => "Delivery Quantity must be numeric."
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $auctionItemIds = $this->request->getVar('auction_item_id');
            $invoiceId = $this->request->getVar('invoice_id');
            $auctionId = $this->request->getVar('auction_id');
            $quantities = $this->request->getVar('qty');
            $checkAuctionItems = $this->request->getVar('check-auctionitem');

            $receiptNo = 'DRNO/' . $auctionId . '/' . date('y') . '/' . rand(10000, 90000);

            $data = [
                'invoice_id' => $invoiceId,
                'date' => date('Y-m-d'),
                'receipt_no' => $receiptNo,
                'auction_id' => $auctionId,
            ];

            $deliveryManagementModel->insert($data);
            $receiptId = $deliveryManagementModel->getInsertID();

            foreach ($checkAuctionItems as $i => $checkAuctionItem) {
                $auctionItemId = $auctionItemIds[$i];
                $quantity = $quantities[$i];

                $soldStock = $soldStockModel->where('auction_item_id', $auctionItemId)->first();

                if ($soldStock) {
                    $changedQty = $soldStock['qty'] - $quantity;
                    $soldStockData = [
                        'auction_item_id' => $auctionItemId,
                        'qty' => $changedQty,
                    ];

                    $itemsData = [
                        'auction_item_id' => $auctionItemId,
                        'receipt_id' => $receiptId,
                        'auction_id' => $auctionId,
                        'qty' => $quantity,
                    ];

                    $soldStockModel->where('auction_item_id', $auctionItemId)->set($soldStockData)->update();
                    $deliveryItemsModel->insert($itemsData);
                }
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully',
            ];

            return $this->respond($response);
        }
    }
    public function getInvoices()
    {
        $id = $this->request->getVar('auction_id');
        // echo $id;exit;
        $invoice_model = new InvoiceModel();
        $invoices = $invoice_model->select('invoice.*,seller.name AS seller_name,buyer.name AS buyer_name,buyer.contact_person_number,(SELECT delivery_time FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS settings_delivery_time')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->where('auction_id', $id)
            ->findAll();

        $data['invoices'] = $invoices;
        return $this->respond($data);
    }
    public function getInvoiceItems()
    {
        $id = $this->request->getVar('invoice_id');

        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->select('invoice.*,auction.sale_no,seller.name AS seller_name,
        buyer.name AS buyer_name,buyer.contact_person_number,
        (SELECT delivery_time FROM settings ORDER BY id DESC LIMIT 1) AS settings_delivery_time')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->where('invoice.id', $id)
            ->first();
        if ($invoice) {

            $invoiceitems_model = new InvoiceItemModel();
            $invoices = $invoiceitems_model->select('invoice_item.*,sold_stock.qty AS stock_qty,
            auction_items.sample_quantity,auction_items.lot_no,inward_items.weight_net,
            inward_items.total_net,inward_items.total_gross,grade.name AS grade_name,
            center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name,
            (SELECT delivery_time FROM settings ORDER BY id DESC LIMIT 1) AS settings_delivery_time')
                ->join('auction_items', 'invoice_item.auction_item_id = auction_items.id', 'left')
                ->join('sold_stock', 'sold_stock.auction_item_id = auction_items.id', 'left')
                ->join('inward_items', 'inward_items.id = invoice_item.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
                ->where('invoice_item.invoice_id', $id)
                ->where('sold_stock.qty !=', 0)
                ->findAll();

            $invoice['invoiceItems'] = $invoices;
        }

        $data['invoices'] = $invoice;

        if ($data) {
            $response = [
                'status' => 200,
                'error' => false,
                'data' => $data
            ];
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function delete($id = null)
    {
        // echo 'hii';exit;
        $soldStockModel = new SoldStockModel();
        $deliveryManagementModel = new DeliveryManagementModel();
        $deliveryItemsModel = new DeliveryItemsModel();

        // Find delivery management data by ID
        $data = $deliveryManagementModel->find($id);

        if (!$data) {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }

        // Find all delivery items associated with the receipt ID
        $deliveryItems = $deliveryItemsModel->where('receipt_id', $id)->findAll();

        if (!empty($deliveryItems)) {
            foreach ($deliveryItems as $item) {
                // Get the sold stock for the auction item
                $soldStock = $soldStockModel->where('auction_item_id', $item['auction_item_id'])->first();

                if ($soldStock) {
                    // Update the quantity of sold stock
                    $soldStockModel->update($soldStock['id'], ['qty' => $soldStock['qty'] + $item['qty']]);
                }
            }

            // Delete delivery items associated with the receipt ID
            $deliveryItemsModel->where('receipt_id', $id)->delete();
        }

        // Delete the delivery management entry
        $deliveryManagementModel->delete($id);

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Deleted Successfully',
            'data' => $data
        ];

        return $this->respondDeleted($response);
    }
}
