<?php

namespace App\Controllers;

use App\Models\AuctionBiddingModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\ProductLogModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
use App\Models\SoldStockModel;
use App\Models\AuctionItemhistoryModel;
use App\Models\CartModel;
use App\Models\CenterModel;
use App\Models\SellerModel;
use App\Models\SettingsModel;
use App\Models\GardenModel;
use App\Models\WarehouseModel;
use App\Models\GradeModel;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use App\Models\CenterGardenModel;
use App\Models\StockModel;
use App\Models\AuctionLotTimingsModel;
use App\Models\InvoiceItemModel;
use App\Models\AuctionToSellerInvoiceItemModel;
use App\Models\AuctionToBuyerInvoiceModel;
use App\Models\AuctionToBuyerInvoiceItemModel;
use App\Models\InvoiceModel;
use App\Models\AuctionToSellerInvoiceModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use App\Helpers\ProductLog;
use App\Models\BuyerModel;
use Config\Services;


class Auction extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function index()
    {
        $model = new AuctionModel();
        $auctions = $model->select('auction.*, center.name AS center_name')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->where('auction.status !=', 0)
            ->orderBy('auction.id', 'DESC')
            ->findAll();

        if ($auctions) {
            $auctionItemModel = new AuctionItemModel();
            foreach ($auctions as &$auction) {
                $auctionItems = $auctionItemModel->select('inward_items.*,auction_items.id AS auctionitem_id,auction_items.*,grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                    ->join('garden', 'garden.id = inward.garden_id', 'left')
                    ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                    ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                    ->join('center', 'center.id = inward.center_id', 'left')
                    ->where('auction_id', $auction['id'])
                    ->findAll();

                $auction['auctionItems'] = $auctionItems;
                $auction['auctionItemsCount'] = count($auctionItems);
            }
        }

        $data['auction'] = $auctions;
        return $this->respond($data);
    }

    public function create()
    {
        $model = new AuctionModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'center_id' => 'required|numeric',
            'date' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
            'type' => 'required',
        ];

        $messages = [
            'center_id' => [
                'required' => 'Please select center.',
                'numeric' => 'The center ID must be numeric.',
            ],
            'date' => [
                'required' => 'The date is required.',
            ],
            'sale_no' => [
                'required' => 'The sale number is required.',
                'numeric' => 'The sale number must be numeric.',
            ],
            'start_time' => [
                'required' => 'The start time is required.',
            ],
            'type' => [
                'required' => 'The type is required.',
            ],
            'end_time' => [
                'required' => 'The end time is required.',
            ],
            'lot_count' => [
                'required' => 'The lot count is required.',
                'numeric' => 'The lot count must be numeric.',
            ],
            'session_time' => [
                'required' => 'The session time is required.',
            ],
            'invoice_no.*' => [
                'required' => 'Please select Invoice Number.',
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        }

        // Check if an auction already exists on the same date
        $existingAuction = $model->where('status', 1)->where('date', date("Y-m-d", strtotime($this->request->getVar('date'))))
            ->first();

        $current_date = date("Y-m-d");
        $current_time = date("H:i:s");

        $existingAuction1 = $model->where('status', 1)
            ->where('date', $current_date)
            ->where('start_time <=', $current_time)
            ->first();

        if (!$existingAuction1) {
            $files = ['min_bid_timer.txt', 'session1_timer.txt', 'session2_timer.txt'];
            $directory = '/var/www/eazyhosting.in/public_html/timer/';
            foreach ($files as $file) {
                $filePath = $directory . $file;
                if (file_exists($filePath)) {
                    if (file_put_contents($filePath, '') === false) {
                        $success = false;
                        $errors[] = "Failed to clear $file";
                    }
                } else {
                    $success = false;
                    $errors[] = "$file does not exist";
                }
            }
        }

        if ($existingAuction) {
            $validationErrors = ['date' => 'An auction already exists on this date.'];
            return $this->fail($validationErrors, 422); // Conflict
        }

        $last_id = $model->orderBy('id', 'DESC')->first();
        if (!isset($last_id)) {
            $last_row_id = '001';
        } else {
            $last_row_id = @$last_id['id'] + 1;
        }

        if ($last_id && $last_row_id < 10) {
            $last_row_id = '00' . $last_row_id;
        } elseif ($last_id && $last_row_id >= 10 && $last_row_id < 100) {
            $last_row_id = '0' . $last_row_id;
        }

        if (strtotime($this->request->getVar('start_time')) >= strtotime($this->request->getVar('end_time'))) {
            $validationErrors = ['end_time' => 'End time must be greater than start time'];
            return $this->fail($validationErrors, 422); // Bad request
        }

        // Extracting data from the request
        $data = [
            'center_id' => $this->request->getVar('center_id'),
            'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
            'sale_no' => $last_row_id . "/" . date("y"),
            'start_time' => $this->request->getVar('start_time'),
            'end_time' => $this->request->getVar('end_time'),
            'type' => $this->request->getVar('type'),
            'created_by' => $this->request->getHeaderLine('Authorization1'),
        ];

        $model->insert($data);

        // Prepare response
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }

    public function createAuctionItem()
    {
        $auctionItemModel = new AuctionItemModel();
        $inwardItemModel = new InwardItemModel();

        for ($i = 0; $i < count($this->request->getVar('check-auctionitem')); $i++) {
            $auctionItemData = [
                'inward_invoice_id' => $this->request->getVar('invoice_id')[$i],
                'inward_item_id' => $this->request->getVar('inward_item_id')[$i],
                'lot_no' => (1001 + $i),
                'auction_id' => $this->request->getVar('auction_id'),
                'created_by' => $this->request->getHeaderLine('Authorization1'),
            ];
            $auctionItemModel->insert($auctionItemData);
            // echo $auctionItemData['inward_item_id'];exit;
            $inwardItemModel->update($auctionItemData['inward_item_id'], ['is_assigned' => 1]);

            // $lastQuery = $inwardItemModel->getLastQuery();
            // echo "Last Query: " . $lastQuery . "<br>";exit;
        }

        $auctionGardenOrderModel = new AuctionGardenOrderModel();
        for ($i = 0; $i < count($this->request->getVar('garden_id')); $i++) {
            $auctionGardenOrderData = [
                'order_seq' => $this->request->getVar('sequence')[$i],
                'garden_id' => $this->request->getVar('garden_id')[$i],
                'center_id' => $this->request->getVar('center_id'),
            ];
            // print_r($auctionGardenOrderData);
            $auctionGardenOrderModel->update($auctionGardenOrderData);
        }

        // Prepare response
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }

    public function show($id = null)
    {
        $model = new AuctionModel();
        $auction = $model->select('auction.*,center.name AS center_name,
        (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show
        ')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->find($id);

        // print_r($auction);exit;

        if ($auction) {
            $auctionItemModel = new AuctionItemModel();
            $auctionItems = $auctionItemModel->select('inward_items.*,auction_items.id AS auctionitem_id,auction_items.*,grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
                ->where('auction_id', $auction['id'])
                ->findAll();

            $auction['auctionItems'] = $auctionItems;

            $data['auction'] = $auction;
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function showAuctionItems($id = null)
    {
        $model = new AuctionModel();
        $auction = $model->select('auction.*,center.name AS center_name,
            (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->find($id);

        if ($auction) {
            $auctionItemModel = new AuctionItemModel();
            $auctionItems = $auctionItemModel->select('inward_items.*,auction_items.id AS auctionitem_id,auction_items.*,
                auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,
                grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name,
                bid_info.bid_price AS highest_bid_price, 
                bid_info.buyer_id, 
                bid_info.buyer_name AS highest_bidder_name,
                (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
                ->join(
                    '(SELECT ab.auction_item_id, 
                     ab.bid_price, 
                     ab.buyer_id, 
                     b.name AS buyer_name 
              FROM auction_biddings ab 
              LEFT JOIN buyer b ON ab.buyer_id = b.id 
              WHERE ab.bid_price = (
                  SELECT MAX(ab2.bid_price) 
                  FROM auction_biddings ab2 
                  WHERE ab2.auction_item_id = ab.auction_item_id
              ) 
              GROUP BY ab.auction_item_id) AS bid_info',
                    'bid_info.auction_item_id = auction_items.id',
                    'left'
                )
                ->where('auction_items.auction_id', $auction['id'])
                ->findAll();

            $auction['auctionItems'] = $auctionItems;

            $data['auction'] = $auction;

            // print_r($auction);exit;

            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function update($id = null)
    {
        $id = $this->request->getVar('id');
        $model = new AuctionModel();
        $auctionitem_model = new AuctionItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'date' => 'required',
            'reason' => 'required',
            'start_time' => 'required',
            'end_time' => 'required',
        ];

        $messages = [
            'date' => [
                'required' => 'The date is required.',
            ],
            'reason' => [
                'required' => 'The Reason field is required.',
            ],
            'start_time' => [
                'required' => 'The start time is required.',
            ],
            'lot_count' => [
                'required' => 'The lot count is required.',
                'numeric' => 'The lot count must be numeric.',
                'less_than_equal_to' => 'The lot count should not exceed auction item count.',
            ],
            'session_time' => [
                'required' => 'The session time is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            // Validation failed
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $existingAuction = $model->where('id !=', $id)->where('status', 1)->where('date', date("Y-m-d", strtotime($this->request->getVar('date'))))
                ->first();

            if ($existingAuction) {
                $validationErrors = ['date' => 'An auction already exists on this date.'];
                return $this->fail($validationErrors, 422); // Conflict
            }

            if (strtotime($this->request->getVar('start_time')) >= strtotime($this->request->getVar('end_time'))) {
                $validationErrors = ['end_time' => 'End time must be greater than start time'];
                return $this->fail($validationErrors, 422); // Bad request
            }

            // Update auction
            $data = [
                'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                'start_time' => $this->request->getVar('start_time'),
                'end_time' => $this->request->getVar('end_time'),
                'reason' => $this->request->getVar('reason'),
                'updated_by' => $this->request->getHeaderLine('Authorization1'),
            ];
            $model->update($id, $data);

            // Prepare and return response
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully'
            ];
            return $this->respond($response);
        }
    }

    public function updateValuation()
    {
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'reserve_price.*' => 'required|numeric',
            'valuation_price.*' => 'required|numeric',
        ];

        $messages = [
            'reserve_price.*' => [
                'required' => 'The reverse price is required.',
                "numeric" => "Reverse Price must be numeric.",
            ],
            'valuation_price.*' => [
                'required' => 'The valuation price is required.',
                "numeric" => "Valuation Price must be numeric.",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            for ($i = 0; $i < count($this->request->getVar('auction_quantity')); $i++) {
                $model = new AuctionItemModel();
                $id = $this->request->getVar('auctionitem_id')[$i];
                $inward_item_id = $this->request->getVar('inward_item_id')[$i];
                // echo $id;
                $isWithdrawnvalue = $this->request->getVar('is_withdrawn')[$i];
                // echo $isWithdrawnvalue;

                $data = [
                    'base_price' => $this->request->getVar('base_price')[$i],
                    'auction_quantity' => $this->request->getVar('auction_quantity')[$i],
                    'reverse_price' => $this->request->getVar('reserve_price')[$i],
                    'valuation_price' => $this->request->getVar('valuation_price')[$i],
                    'is_withdrawn' => $isWithdrawnvalue,
                    'updated_by' => $this->request->getHeaderLine('Authorization1'),
                ];

                $auction_history_model = new AuctionItemhistoryModel();
                $data_history = [
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $id,
                    'base_price' => $this->request->getVar('base_price')[$i],
                    'reverse_price' => $this->request->getVar('reserve_price')[$i],
                    'valuation_price' => $this->request->getVar('valuation_price')[$i],
                ];
                $auction_history_existing = $auction_history_model->where([
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $id,
                ])->first();
                if ($auction_history_existing) {
                    // Update existing auction stock
                    $auction_history_model->where('auction_item_id', $id)->where('auction_id', $this->request->getVar('auction_id'))->set($data_history)->update();
                } else {
                    // Insert new auction stock
                    $auction_history_model->insert($data_history);
                }

                // Delete existing auction stock if is_withdrawn is 1
                if ($isWithdrawnvalue == 1) {
                    $auction_stock_model = new AuctionStockModel();
                    $auction_stock_model->where([
                        'inward_item_id' => $inward_item_id,
                        'auction_id' => $this->request->getVar('auction_id'),
                        'auction_item_id' => $id,
                    ])->delete();
                } else {
                    // Insert into auction stock table
                    $inward_item_detail = new InwardItemModel();
                    $inward_item_details = $inward_item_detail->where('id', $inward_item_id)->first();

                    $auction_stock_model = new AuctionStockModel();
                    $auction_stock_existing = $auction_stock_model->where([
                        'inward_item_id' => $inward_item_id,
                        'auction_id' => $this->request->getVar('auction_id'),
                        'auction_item_id' => $id,
                    ])->first();

                    $auction_stock_data = [
                        'inward_id' => $inward_item_details['inward_id'],
                        'inward_item_id' => $inward_item_id,
                        'auction_id' => $this->request->getVar('auction_id'),
                        'auction_item_id' => $id,
                        'qty' => $data['auction_quantity'],
                    ];

                    if ($auction_stock_existing) {
                        // Update existing auction stock
                        $auction_stock_model->update($auction_stock_existing['id'], $auction_stock_data);
                    } else {
                        // Insert new auction stock
                        $auction_stock_model->insert($auction_stock_data);
                    }
                }

                $existingModel = $model->find($id);
                if ($existingModel['is_withdrawn'] != $isWithdrawnvalue) {
                    // Calculate current stock only if is_withdrawn value changed
                    $inward_item_detail = new InwardItemModel();
                    $inward_item_details = $inward_item_detail->where('id', $inward_item_id)->first();

                    $stock_model = new StockModel();
                    $stock_detail = $stock_model->where('inward_item_id', $inward_item_id)->first();
                    $existing_stock = $stock_detail['qty'];
                    // print_r($isWithdrawnvalue);exit;

                    if ($isWithdrawnvalue == 1) {
                        $current_stock = $existing_stock + $data['auction_quantity'];
                        $stock_data = ['qty' => $current_stock];
                        $stock_model->where('inward_item_id', $inward_item_id)->set($stock_data)->update();
                    } elseif ($isWithdrawnvalue == 0) {
                        $current_stock = $existing_stock - $data['auction_quantity'];
                        $stock_data = ['qty' => $current_stock];
                        $stock_model->where('inward_item_id', $inward_item_id)->set($stock_data)->update();
                    }
                }
                // Update auction item
                $model->update($id, $data);
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully',
                'data' => $data
            ];
            return $this->respond($response);
        }
    }

    public function updateReservePrice()
    {
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'reserve_price' => 'required|numeric',
        ];

        $messages = [
            'reserve_price' => [
                'required' => 'The reverse price is required.',
                "numeric" => "Reverse Price must be numeric.",
            ],
            'valuation_price.*' => [
                'required' => 'The valuation price is required.',
                "numeric" => "Valuation Price must be numeric.",
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            // print_r(count($this->request->getVar('auctionitem_id')));exit;
            // for ($i = 0; $i < count($this->request->getVar('auctionitem_id')); $i++) {

            $model = new AuctionItemModel();
            $id = $this->request->getVar('auctionitem_id');
            // echo $id;

            $data = [
                'base_price' => $this->request->getVar('base_price'),
                'reverse_price' => $this->request->getVar('reserve_price'),
                'valuation_price' => $this->request->getVar('valuation_price'),
            ];

            $auction_history_model = new AuctionItemhistoryModel();
            $data_history = [
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $id,
                'base_price' => $this->request->getVar('base_price'),
                'reverse_price' => $this->request->getVar('reserve_price'),
                'valuation_price' => $this->request->getVar('valuation_price'),
            ];

            $auction_history_existing = $auction_history_model->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $id,
            ])->first();
            if ($auction_history_existing) {
                // Update existing auction stock
                $auction_history_model->where('auction_item_id', $id)->where('auction_id', $this->request->getVar('auction_id'))->set($data_history)->update();
            } else {
                // Insert new auction stock
                $auction_history_model->insert($data_history);
            }
            // Update auction item
            $model->update($id, $data);

            // }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully',
                'data' => $data
            ];
            return $this->respond($response);
        }
    }

    public function delete($id = null)
    {
        $model = new AuctionModel();
        $auctionItemModel = new AuctionItemModel();
        $stockModel = new StockModel();
        $auction_stockmodel = new AuctionStockModel();
        $auctionItems = $auctionItemModel->where('auction_id', $id)
            ->where('status !=', 0)
            ->findAll();
        $auction_stock = $auction_stockmodel->where('auction_id', $id)
            ->delete();

        if ($auctionItems) {
            $model->update($id, ['status' => 0]);

            foreach ($auctionItems as $auctionItem) {
                $stockItems = $stockModel->where('inward_item_id', $auctionItem['inward_item_id'])->findAll();
                if ($stockItems) {
                    foreach ($stockItems as $stockItem) {
                        $auctionItemModel->update($auctionItem['id'], ['status' => 0]);
                        $stockModel->update($stockItem['id'], ['qty' => $stockItem['qty'] + $auctionItem['auction_quantity']]);
                    }
                }
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted Successfully',
                'data' => $auctionItems,
                'data1' => $stockItems,
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function getInvoices()
    {
        $center_id = $this->request->getVar('center_id');
        $model = new StockModel();
        $data = $model->select('inward_items.*,auction_items.id AS auctionitem_id,
            auction.id AS auction_id,grade.name AS grade_name,garden.name AS garden_name,
            garden.id AS garden_id,grade.id AS grade_id,warehouse.name AS warehouse_name,
            stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('auction_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)
            ->where('inward_items.status !=', 0)
            ->where('inward.center_id', $center_id)
            ->findAll();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }

    public function biddingSessionView()
    {
        $center_id = $this->request->getVar('center_id');
        $model = new StockModel();
        $data = $model->select('inward_items.*,auction_items.*,auction.*,grade.name AS grade_name,garden.name AS garden_name,garden.id AS garden_id,grade.id AS grade_id,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('inward_items.status !=', 0)
            ->where('inward.center_id', $center_id)->findAll();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }
    public function biddingSessionView1()
    {
        $center_id = $this->request->getVar('center_id');
        $model = new StockModel();
        $data = $model->select('inward_items.weight_gross,auction_items.*,grade.name AS grade_name,garden.name AS garden_name,garden.id AS garden_id,grade.id AS grade_id,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('auction_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('inward_items.is_assigned !=', 0)
            ->where('inward_items.status !=', 0)
            ->where('inward.center_id', $center_id)->findAll();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }

    public function getInvoicesByWarehouseId()
    {
        $center_id = $this->request->getVar('center_id');
        $type = $this->request->getVar('type');
        $warehouse_id = $this->request->getVar('warehouse_id');
        $garden_id = $this->request->getVar('garden_id');
        // echo $warehouse_id;exit;
        $model = new StockModel();
        $query = $model->select('inward_items.*,grade.name AS grade_name,garden.name AS garden_name,garden.id AS garden_id,garden.vacumm_bag,grade.id AS grade_id,warehouse.name AS warehouse_name,stock.qty AS stock_qty,
            (SELECT leaf_sq FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS leaf_sq,
            (SELECT dust_sq FROM settings WHERE status = 1 ORDER BY id DESC LIMIT 1) AS dust_sq')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)
            ->where('inward_items.status !=', 0)
            ->where('inward.center_id', $center_id)
            ->where('grade.type', $type)
            ->where('inward_items.status', 1);

        if ($warehouse_id !== 'all') {
            $query->where('inward.warehouse_id', $warehouse_id);
        }
        if ($garden_id !== 'all') {
            $query->where('inward.garden_id', $garden_id);
        }

        $data = $query->findAll();
        // echo 'hii';exit;

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }

    public function centerGarden()
    {
        $center_id = $this->request->getVar('center_id');
        $centerGarden = new CenterGardenModel();
        $centerGarden = $centerGarden->select('garden.name AS garden_name,garden.id')
            ->join('garden', 'garden.id = center_garden.garden_id', 'left')
            ->join('center', 'center.id = center_garden.center_id', 'left')
            ->where('center_id', $center_id)
            ->where('garden.status', 1)
            ->findAll();

        if (count($centerGarden)) {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $centerGarden
            ];
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $center_id);
        }
    }

    public function getAuctionItems()
    {
        $model = new AuctionItemModel();
        $auction_items = $model->where('status', 2)->orderBy('id')->findAll();

        foreach ($auction_items as &$auction_item) {
            $auction_item['auction'] = (new AuctionModel())->where('id', $auction_item['auction_id'])->findAll();
        }
        // print_r($auction_item['auction']);exit;
        $data['auctionitems'] = $auction_items;
        return $this->respond($data);
    }


    public function getAuctionItemsByCenter($id = null)
    {
        $model = new AuctionModel();
        $auction = $model->where('center_id', $id)->orderBy('id')->findAll();

        foreach ($auction as &$a) {
            $a['auction_items'] = (new AuctionItemModel())
                ->select('auction_items.*,inward_items.weight_net,inward_items.total_net,grade.name AS gradename,garden.name AS gardenname,inward_items.bag_type')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                ->join('garden', 'inward.garden_id = garden.id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->where('auction_id', $a['id'])->findAll();
        }
        // echo '<pre>';print_r($auction);exit;
        $data['auction'] = $auction;
        return $this->respond($data);
    }
    public function auctionStock()
    {
        $model = new AuctionItemModel();
        $auction_items = $model->select('auction_items.*,auction.sale_no AS sale_no,inward_items.invoice_id AS invoice_no,grade.name AS gradename,warehouse.name AS warehouse_name,garden.name AS gardenname,inward_items.bag_type,inward_items.weight_gross,inward_items.weight_net,
        (SELECT SUM(qty) FROM auction_stock WHERE auction_stock.auction_item_id = auction_items.id) AS total_count')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->where('auction_items.status', 1)
            ->orderBy('id')->findAll();
        $auction_items = array_filter($auction_items, function ($item) {
            return $item['total_count'] > 0;
        });
        //  $sql = $model->getLastQuery();
        //      echo $sql;exit;
        //echo '<pre>'; print_r($auction_items);exit;
        $data['auctionstock'] = $auction_items;
        return $this->respond($data);
    }

    public function reOrderBiddingSession()
    {
        $input_data = file_get_contents("php://input");
        // echo 'hii';
        // exit;
        // Decode the JSON data
        $requestData = json_decode($input_data, true);

        foreach ($requestData as $item) {

            $sequence = $item['sequence'];
            $id = $item['id'];
            // $center_id = $item['center_id'];

            $model = new AuctionGardenOrderModel();
            $data = ['order_seq' => $sequence];


            $model->update($id, $data);
        }

        return $this->respond($data);
    }

    public function centerGardenBidding()
    {
        $center_id = $this->request->getVar('center_id');
        $centerGarden = new CenterGardenModel();
        $centerGarden = $centerGarden->select('garden.name AS garden_name,garden.id,auction_garden_order.order_seq AS sequ')
            ->join('garden', 'garden.id = center_garden.garden_id', 'left')
            ->join('center', 'center.id = center_garden.center_id', 'left')
            ->join('auction_garden_order', 'garden.id = auction_garden_order.garden_id', 'left')
            ->where('center_garden.center_id', $center_id)
            ->orderBy('auction_garden_order.order_seq')
            ->findAll();

        if (count($centerGarden)) {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $centerGarden
            ];
            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $center_id);
        }
    }
    /******* Auction Item management */
    public function getAuctionItemsUser()
    {
        $model = new AuctionItemModel();

        $auction = $model->select('auction_items.*,auction_items.id AS auction_item_id,auction.*,inward_items.weight_net,inward_items.total_net,grade.name AS gradename,garden.name AS gardenname,inward_items.bag_type')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('auction', 'auction_items.auction_id = auction.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->findAll();


        $data['auction'] = $auction;
        return $this->respond($data);
    }


    public function AuctionItemDetail($id)
    {
        $model = new StockModel();
        $data = $model->select('inward_items.*,grade.name AS grade_name,garden.name AS garden_name,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)
            ->where('inward_items.id', $id)->first();
        return $this->respond($data);
    }
    public function biddingSessionViewDetail()
    {
        $center_id = $this->request->getVar('center_id');
        $auction_id = $this->request->getVar('auction_id');
        $auctionItemModel = new AuctionItemModel();
        $data = $auctionItemModel->select('inward_items.*, 
              auction_items.id AS auctionitem_id, 
              auction_items.*, 
              grade.name AS grade_name, 
              center.name AS center_name, 
              garden.name AS garden_name, 
              warehouse.name AS warehouse_name, 
              bid_info.bid_price AS highest_bid_price, 
              bid_info.buyer_id, 
              bid_info.buyer_name AS highest_bidder_name,
              auction_session_times.start_time,auction_session_times.end_time,
              (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join(
                '(SELECT ab.auction_item_id, 
                 ab.bid_price, 
                 ab.buyer_id, 
                 b.name AS buyer_name 
          FROM auction_biddings ab 
          LEFT JOIN buyer b ON ab.buyer_id = b.id 
          WHERE ab.bid_price = (
              SELECT MAX(ab2.bid_price) 
              FROM auction_biddings ab2 
              WHERE ab2.auction_item_id = ab.auction_item_id
          ) 
          GROUP BY ab.auction_item_id) AS bid_info',
                'bid_info.auction_item_id = auction_items.id',
                'left'
            )
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->where('auction_items.auction_id', $auction_id)
            ->groupBy('auction_items.id')
            ->findAll();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }
    public function completedAuctionDetail()
    {
        $center_id = $this->request->getVar('center_id');
        $auction_id = $this->request->getVar('auction_id');
        $auctionItemModel = new AuctionItemModel();
        $data = $auctionItemModel
            ->select('inward_items.*, 
              auction_items.id AS auctionitem_id, 
              auction_items.*, 
              grade.name AS grade_name, 
              center.name AS center_name, 
              garden.name AS garden_name, 
              warehouse.name AS warehouse_name, 
              bid_info.bid_price AS highest_bid_price, 
              bid_info.buyer_id, 
              bid_info.buyer_name AS highest_bidder_name
              ')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join(
                '(SELECT ab.auction_item_id, 
                 ab.bid_price, 
                 ab.buyer_id, 
                 b.name AS buyer_name 
          FROM auction_biddings ab 
          LEFT JOIN buyer b ON ab.buyer_id = b.id 
          WHERE ab.bid_price = (
              SELECT MAX(ab2.bid_price) 
              FROM auction_biddings ab2 
              WHERE ab2.auction_item_id = ab.auction_item_id
          ) 
          GROUP BY ab.auction_item_id) AS bid_info',
                'bid_info.auction_item_id = auction_items.id',
                'left'
            )
            ->where('auction_items.auction_id', $auction_id)
            ->groupBy('auction_items.id')
            ->findAll();


        //$lastQuery = $auctionItemModel->getLastQuery();
        //echo "Last Query: " . $lastQuery . "<br>";exit;
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Success',
            'data' => $data
        ];

        // print_r($response);exit;

        return $this->respond($response);
    }

    public function closeAuction()
    {
        $model = new AuctionModel();
        $auction_stockmodel = new AuctionStockModel();
        $auction_itemmodel = new AuctionItemModel();
        $sold_stockmodel = new SoldStockModel();
        $auction_biddingmodel = new AuctionBiddingModel();
        $stock_model = new StockModel();
        $settings_model = new SettingsModel();
        $auction_id = $this->request->getVar('auction_id');

        $auction_stock = $auction_itemmodel->select('auction_items.*,inward.seller_id,auction.sale_no,auction.type')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auction', 'auction.id = auction_items.auction_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.is_withdrawn', '!=0')
            ->findAll();
        // print_r($auction_stock);exit;
        $bidding_array = array();
        foreach ($auction_stock as $key => $value) {

            $auction_biddings = $auction_biddingmodel->where('auction_item_id', $value['id'])->countAllResults();

            if ($auction_biddings > 0) {

                $auction_biddings = $auction_biddingmodel->select('bid_price,buyer_id')->where('auction_item_id', $value['id'])->orderBy('bid_price', 'DESC')->first();

                if ($value['reverse_price'] <= $auction_biddings['bid_price']) {
                    $auction_itemmodel->where('id', $value['id'])->set(['is_sold' => 1])->update();
                    $bidding_array[] = [
                        'auction_item_id' => $value['id'],
                        'auction_id' => $value['auction_id'],
                        'inward_item_id' => $value['inward_item_id'],
                        'qty' => $value['auction_quantity'],
                        'sale_no' => $value['sale_no'],
                        'seller_id' => $value['seller_id'],
                        'bid_price' => @$auction_biddings['bid_price'],
                        'buyer_id' => @$auction_biddings['buyer_id'],
                        'grade_type' => @$value['type'],
                        'each_net' => @$value['auction_each_net'],
                    ];
                    $auction_stock = $auction_stockmodel->where('auction_id', $auction_id)->where('auction_item_id', $value['id'])->first();
                    if ($auction_stock) {
                        $data = [
                            'inward_id' => $auction_stock['inward_id'],
                            'inward_item_id' => $auction_stock['inward_item_id'],
                            'auction_item_id' => $auction_stock['auction_item_id'],
                            'auction_id' => $auction_stock['auction_id'],
                            'qty' => $auction_stock['qty'],
                        ];
                        $sold_stockmodel->insert($data);

                        $result = ProductLog::logProductAction($auction_stock['inward_item_id'], 'Moved to Sold Stock', $auction_stock['qty'], $this->request->getHeaderLine('Authorization1'));

                        $auction_stockmodel->delete($auction_stock['id']);
                    }
                } else {
                    $auction_stock = $auction_stockmodel
                        ->select('auction_stock.inward_item_id,auction_stock.id,auction_stock.qty,inward_items.inward_id,inward.warehouse_id')
                        ->join('auction_items', 'auction_stock.auction_item_id = auction_items.id', 'left')
                        ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                        ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                        ->where('auction_stock.auction_id', $auction_id)->where('auction_stock.auction_item_id', $value['id'])->first();

                    if ($auction_stock) {
                        $stock = $stock_model->where('inward_id', $auction_stock['inward_id'])->where('inward_item_id', $auction_stock['inward_item_id'])->first();
                        if (count($stock) > 0) {
                            $existing_qty = $stock['qty'];
                            $in_stock = [
                                'qty' => $auction_stock['qty'] + $existing_qty,
                            ];
                            $stock_model->where('inward_item_id', $auction_stock['inward_item_id'])->set($in_stock)->update();
                        } else {
                            $in_stock = [
                                'inward_id' => $auction_stock['inward_id'],
                                'inward_item_id' => $auction_stock['inward_item_id'],
                                'warehouse_id' => $auction_stock['warehouse_id'],
                                'qty' => $auction_stock['qty'],
                            ];
                            $stock_model->insert($in_stock);
                        }
                        $auction_stockmodel->delete($auction_stock['id']);
                    }
                }
            } else {
                $auction_stock = $auction_stockmodel
                    ->select('auction_stock.inward_item_id,auction_stock.id,auction_stock.qty,inward_items.inward_id,inward.warehouse_id')
                    ->join('auction_items', 'auction_stock.auction_item_id = auction_items.id', 'left')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                    ->where('auction_stock.auction_id', $auction_id)->where('auction_stock.auction_item_id', $value['id'])->first();
                if ($auction_stock) {

                    $stock = $stock_model->where('inward_id', $auction_stock['inward_id'])->where('inward_item_id', $auction_stock['inward_item_id'])->first();
                    if (count($stock) > 0) {
                        $existing_qty = $stock['qty'];
                        $in_stock = [
                            'qty' => $auction_stock['qty'] + $existing_qty,
                        ];
                        $stock_model->where('inward_item_id', $auction_stock['inward_item_id'])->set($in_stock)->update();
                    } else {
                        $in_stock = [
                            'inward_id' => $auction_stock['inward_id'],
                            'inward_item_id' => $auction_stock['inward_item_id'],
                            'warehouse_id' => $auction_stock['warehouse_id'],
                            'qty' => $auction_stock['qty'],
                        ];
                        $stock_model->insert($in_stock);
                    }
                    $auction_stockmodel->delete($auction_stock['id']);
                }
            }
        }

        $settingsData = $settings_model->orderBy('id', 'DESC')->first();
        // seller to buyer
        $grouped_auction_items = array();
        foreach ($bidding_array as $item) {
            $buyer_id = $item['buyer_id'];
            $seller_id = $item['seller_id'];
            if (!isset($grouped_auction_items[$buyer_id])) {
                $grouped_auction_items[$buyer_id] = [];
            }
            if (!isset($grouped_auction_items[$buyer_id][$seller_id])) {
                $grouped_auction_items[$buyer_id][$seller_id] = [];
            }
            $grouped_auction_items[$buyer_id][$seller_id][] = $item;
        }


        foreach ($grouped_auction_items as $buyer_id => $buyer_array) {
            $invoice_model = new InvoiceModel();
            $invoiceitem_model = new InvoiceItemModel();
            $seller_model = new SellerModel();
            $buyer_model = new BuyerModel();
            foreach ($buyer_array as $seller_id => $seller_array) {
                $buyerData = $buyer_model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
                    ->join('city', 'city.id = buyer.city_id', 'left')
                    ->join('state', 'state.id = buyer.state_id', 'left')
                    ->join('area', 'area.id = buyer.area_id', 'left')
                    ->where('buyer.id', $buyer_id)->where('buyer.status !=', 0)->first();

                $sellerData = $seller_model->select('seller.*, city.name as city_name, state.name as state_name, area.name as area_name')
                    ->join('city', 'city.id = seller.city_id', 'left')
                    ->join('state', 'state.id = seller.state_id', 'left')
                    ->join('area', 'area.id = seller.area_id', 'left')
                    ->where('seller.status !=', 0)
                    ->where('seller.id', $seller_id)->first();

                if ($seller_array[0]['grade_type'] == 1) {
                    $hsn = $settingsData['leaf_hsn'];
                } else {
                    $hsn = $settingsData['dust_hsn'];
                }
                $invoice_data = [
                    'date' => date('Y-m-d'),
                    'invoice_no' => ucwords($sellerData['seller_prefix']) . '/' . $seller_array[0]['sale_no'] . "/" . rand(10000, 90000),
                    'buyer_id' => $buyer_id,
                    'seller_id' => $seller_id,
                    'auction_id' => $seller_array[0]['auction_id'],
                    'hsn_code' => $hsn,
                    'prompt_days' => $settingsData['delivery_time'],
                    'buyer_charges' => $settingsData['buyer_charges'],
                    'seller_charges' => $settingsData['seller_charges'],
                    's_name' => @$sellerData['name'],
                    's_state' => @$sellerData['state_name'],
                    's_state_id' => @$sellerData['state_id'],
                    's_city_id' => @$sellerData['city_id'],
                    's_city' => @$sellerData['city_name'],
                    's_area' => @$sellerData['area_name'],
                    's_gst' => @$sellerData['gst_no'],
                    's_fssai' => @$sellerData['fssai_no'],
                    's_tea' => @$sellerData['tea_board_no'],
                    's_address' => @$sellerData['address'],

                    'b_address' => @$buyerData['address'],
                    'b_tea' => @$buyerData['tea_board_no'],
                    'b_name' => @$buyerData['name'],
                    'b_state' => @$buyerData['state_name'],
                    'b_fssai' => @$buyerData['fssai_no'],
                    'b_gst' => @$buyerData['gst_no'],
                    'b_area' => @$buyerData['area_name'],
                    'b_city' => @$buyerData['city_name'],
                    'b_state_id' => @$buyerData['state_id'],
                    'b_city_id' => @$buyerData['city_id'],
                ];

                $invoice_model->insert($invoice_data);
                $invoice_id = $invoice_model->getInsertID();
                foreach ($seller_array as $item_key => $item_value) {
                    $itemsDataModel = new AuctionItemModel();

                    $itemsData = $itemsDataModel->select('auction_items.lot_no,auction_items.auction_each_net,
                    auction_items.id AS auction_item_id,grade.name AS grade_name,auction_items.sample_quantity,
                    inward_items.weight_gross,center.name AS center_name,garden.name AS garden_name,
                    warehouse.name AS warehouse_name,garden.id AS garden_id,grade.id AS grade_id,
                    grade.type AS grade_type')
                        ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                        ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                        ->join('garden', 'inward.garden_id = garden.id', 'left')
                        ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                        ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                        ->join('center', 'center.id = inward.center_id', 'left')
                        ->where('auction_items.id', $item_value['auction_item_id'])
                        ->first();

                    $invoice_item_data = [
                        'invoice_id' => $invoice_id,
                        'auction_item_id' => $item_value['auction_item_id'],
                        'inward_item_id' => $item_value['inward_item_id'],
                        'qty' => $item_value['qty'],
                        'bid_price' => $item_value['bid_price'],
                        'each_net' => $item_value['each_net'],
                        'weight_gross' => $itemsData['weight_gross'],
                        'sample_quantity' => $itemsData['sample_quantity'],
                        'garden_name' => $itemsData['garden_name'],
                        'warehouse_name' => $itemsData['warehouse_name'],
                        'grade_name' => $itemsData['grade_name'],
                        'center_name' => $itemsData['center_name'],
                        'lot_no' => $itemsData['lot_no'],
                        'garden_id' => $itemsData['garden_id'],
                        'grade_id' => $itemsData['grade_id'],
                        'grade_type' => $itemsData['grade_type'],
                    ];
                    $invoiceitem_model->insert($invoice_item_data);
                }
            }
        }

        // auctioner to buyer
        $grouped_auction_items_auctioneer = [];

        // Group items by buyer_id
        foreach ($bidding_array as $item) {
            $buyer_id = $item['buyer_id'];
            if (!isset($grouped_auction_items_auctioneer[$buyer_id])) {
                $grouped_auction_items_auctioneer[$buyer_id] = [];
            }
            $grouped_auction_items_auctioneer[$buyer_id][] = $item;
        }

        // Process each group to create invoices and invoice items
        foreach ($grouped_auction_items_auctioneer as $buyer_id => $buyer_auctioneer_array) {
            $invoice_model_auctioneer = new AuctionToBuyerInvoiceModel();
            $invoiceitem_model_auctioneer = new AuctionToBuyerInvoiceItemModel();
            $buyerData = $buyer_model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
                ->join('city', 'city.id = buyer.city_id', 'left')
                ->join('state', 'state.id = buyer.state_id', 'left')
                ->join('area', 'area.id = buyer.area_id', 'left')
                ->where('buyer.id', $buyer_id)->where('buyer.status !=', 0)->first();

            $invoice_data_auctioneer = [
                'date' => date('Y-m-d'),
                'invoice_no' => 'RCPL/' . $buyer_auctioneer_array[0]['sale_no'] . "/" . rand(10000, 90000),
                'buyer_id' => $buyer_id,
                'auction_id' => $buyer_auctioneer_array[0]['auction_id'],
                'hsn_code' => $hsn,
                'prompt_days' => $settingsData['delivery_time'],
                'buyer_charges' => $settingsData['buyer_charges'],
                'seller_charges' => $settingsData['seller_charges'],
                'b_name' => @$buyerData['name'],
                'b_state' => @$buyerData['state_name'],
                'b_state_id' => @$buyerData['state_id'],
                'b_city_id' => @$buyerData['city_id'],
                'b_city' => @$buyerData['city_name'],
                'b_area' => @$buyerData['area_name'],
                'b_gst' => @$buyerData['gst_no'],
                'b_fssai' => @$buyerData['fssai_no'],
                'b_tea' => @$buyerData['tea_board_no'],
                'b_address' => @$buyerData['address'],
            ];

            // Insert the invoice and get the inserted ID
            $invoice_model_auctioneer->insert($invoice_data_auctioneer);
            $invoice_id_auctioneer = $invoice_model_auctioneer->getInsertID();

            // Insert each item for the current buyer
            foreach ($buyer_auctioneer_array as $item_value) {
                $itemsDataModel = new AuctionItemModel();

                $itemsData = $itemsDataModel->select('auction_items.lot_no,auction_items.auction_each_net,
                auction_items.id AS auction_item_id,grade.name AS grade_name,auction_items.sample_quantity,
                inward_items.weight_gross,center.name AS center_name,garden.name AS garden_name,
                warehouse.name AS warehouse_name,garden.id AS garden_id,grade.id AS grade_id,
                grade.type AS grade_type')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                    ->join('garden', 'inward.garden_id = garden.id', 'left')
                    ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                    ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                    ->join('center', 'center.id = inward.center_id', 'left')
                    ->where('auction_items.id', $item_value['auction_item_id'])
                    ->first();

                $invoice_item_data_auctioneer = [
                    'invoice_id' => $invoice_id_auctioneer,
                    'auction_item_id' => $item_value['auction_item_id'],
                    'inward_item_id' => $item_value['inward_item_id'],
                    'qty' => $item_value['qty'],
                    'bid_price' => $item_value['bid_price'],
                    'each_net' => $item_value['each_net'],
                    'weight_gross' => $itemsData['weight_gross'],
                    'sample_quantity' => $itemsData['sample_quantity'],
                    'garden_name' => $itemsData['garden_name'],
                    'warehouse_name' => $itemsData['warehouse_name'],
                    'grade_name' => $itemsData['grade_name'],
                    'center_name' => $itemsData['center_name'],
                    'lot_no' => $itemsData['lot_no'],
                    'garden_id' => $itemsData['garden_id'],
                    'grade_id' => $itemsData['grade_id'],
                    'grade_type' => $itemsData['grade_type'],
                ];
                $invoiceitem_model_auctioneer->insert($invoice_item_data_auctioneer);
            }
        }


        // auctioner to seller
        $grouped_auction_items_seller = [];

        // Group items by seller_id
        foreach ($bidding_array as $item) {
            $seller_id = $item['seller_id'];
            if (!isset($grouped_auction_items_seller[$seller_id])) {
                $grouped_auction_items_seller[$seller_id] = [];
            }
            $grouped_auction_items_seller[$seller_id][] = $item;
        }

        // Process each group to create invoices and invoice items
        foreach ($grouped_auction_items_seller as $seller_id => $seller_auctioneer_array) {
            $invoice_model_seller = new AuctionToSellerInvoiceModel();
            $invoiceitem_model_seller = new AuctionToSellerInvoiceItemModel();
            $seller_model = new SellerModel();

            $sellerData = $seller_model->select('seller.*, city.name as city_name, state.name as state_name, area.name as area_name')
                ->join('city', 'city.id = seller.city_id', 'left')
                ->join('state', 'state.id = seller.state_id', 'left')
                ->join('area', 'area.id = seller.area_id', 'left')
                ->where('seller.status !=', 0)
                ->where('seller.id', $seller_id)->first();

            $invoice_data_seller = [
                'date' => date('Y-m-d'),
                'invoice_no' => ucwords($sellerData['seller_prefix']) . '/' . $seller_auctioneer_array[0]['sale_no'] . "/" . rand(10000, 90000),
                'seller_id' => $seller_id,
                'auction_id' => $seller_auctioneer_array[0]['auction_id'],
                'hsn_code' => $hsn,
                'prompt_days' => $settingsData['delivery_time'],
                'buyer_charges' => $settingsData['buyer_charges'],
                'seller_charges' => $settingsData['seller_charges'],
                's_name' => @$sellerData['name'],
                's_state' => @$sellerData['state_name'],
                's_state_id' => @$sellerData['state_id'],
                's_city_id' => @$sellerData['city_id'],
                's_city' => @$sellerData['city_name'],
                's_area' => @$sellerData['area_name'],
                's_gst' => @$sellerData['gst_no'],
                's_fssai' => @$sellerData['fssai_no'],
                's_tea' => @$sellerData['tea_board_no'],
                's_address' => @$sellerData['address'],
            ];

            // Insert the invoice and get the inserted ID
            $invoice_model_seller->insert($invoice_data_seller);
            $invoice_id_seller = $invoice_model_seller->getInsertID();

            // Insert each item for the current seller
            foreach ($seller_auctioneer_array as $item_value) {
                $itemsDataModel = new AuctionItemModel();

                $itemsData = $itemsDataModel->select('auction_items.lot_no,auction_items.auction_each_net,
                auction_items.id AS auction_item_id,grade.name AS grade_name,auction_items.sample_quantity,
                inward_items.weight_gross,center.name AS center_name,garden.name AS garden_name,
                warehouse.name AS warehouse_name,garden.id AS garden_id,grade.id AS grade_id,
                grade.type AS grade_type')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                    ->join('garden', 'inward.garden_id = garden.id', 'left')
                    ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                    ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                    ->join('center', 'center.id = inward.center_id', 'left')
                    ->where('auction_items.id', $item_value['auction_item_id'])
                    ->first();

                $invoice_item_data_seller = [
                    'invoice_id' => $invoice_id_seller,
                    'auction_item_id' => $item_value['auction_item_id'],
                    'inward_item_id' => $item_value['inward_item_id'],
                    'qty' => $item_value['qty'],
                    'bid_price' => $item_value['bid_price'],
                    'each_net' => $item_value['each_net'],
                    'weight_gross' => $itemsData['weight_gross'],
                    'sample_quantity' => $itemsData['sample_quantity'],
                    'garden_name' => $itemsData['garden_name'],
                    'warehouse_name' => $itemsData['warehouse_name'],
                    'grade_name' => $itemsData['grade_name'],
                    'center_name' => $itemsData['center_name'],
                    'lot_no' => $itemsData['lot_no'],
                    'garden_id' => $itemsData['garden_id'],
                    'grade_id' => $itemsData['grade_id'],
                    'grade_type' => $itemsData['grade_type'],
                ];
                $invoiceitem_model_seller->insert($invoice_item_data_seller);
            }
        }

        // print_r($invoice_data);echo '<pre>';print_r($invoice_data_auctioneer);echo '<pre>';print_r($invoice_data_seller);
        // exit;

        $update_data = [
            'status' => 2
        ];
        // print_r($auctionGardenOrderData);
        $model->update($auction_id, $update_data);

        $session_user_id = $this->session->get('session_user_id');

        // Prepare response
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }



    public function invoices()
    {
        $invoice_model = new InvoiceModel();
        $invoices = $invoice_model->findAll();

        $data['invoices'] = $invoices;
        return $this->respond($data);
    }
    public function invoiceDetails($id)
    {

        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->select('invoice.*,auction.date AS auction_date,auction.sale_no')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->where('invoice.id', $id)
            ->first();

        if ($invoice) {

            $invoiceitems_model = new InvoiceItemModel();
            $invoices = $invoiceitems_model->select('invoice_item.*')
                ->where('invoice_item.invoice_id', $id)
                ->findAll();

            $invoice['invoiceItems'] = $invoices;
        }

        $data['invoices'] = $invoice;


        return $this->respond($data);
    }

    public function auctionBuyerInvoice()
    {
        $invoice_model = new AuctionToBuyerInvoiceModel();
        $invoices = $invoice_model->findAll();

        $data['invoices'] = $invoices;
        // print_r($data);exit;

        return $this->respond($data);
    }
    public function auctionbuyerinvoiceDetails($id)
    {

        $invoiceModel = new AuctionToBuyerInvoiceModel();
        $invoice = $invoiceModel->select('auction_buyer_invoice.*,auction.date AS auction_date,auction.sale_no')
            ->join('auction', 'auction.id = auction_buyer_invoice.auction_id', 'left')
            ->where('auction_buyer_invoice.id', $id)
            ->first();

        if ($invoice) {

            $invoiceitems_model = new AuctionToBuyerInvoiceItemModel();
            $invoices = $invoiceitems_model->select('auction_buyer_invoice_item.*')
                ->where('auction_buyer_invoice_item.invoice_id', $id)
                ->findAll();

            $invoice['invoiceItems'] = $invoices;
        }

        $data['invoices'] = $invoice;


        return $this->respond($data);
    }
    public function auctionSellerInvoice()
    {
        $invoice_model = new AuctionToSellerInvoiceModel();
        $invoices = $invoice_model->findAll();

        $data['invoices'] = $invoices;
        return $this->respond($data);
    }
    public function auctionsellerinvoiceDetails($id)
    {

        $invoiceModel = new AuctionToSellerInvoiceModel();
        $invoice = $invoiceModel->select('auction_seller_invoice.*,auction.date AS auction_date,auction.sale_no')
            ->join('auction', 'auction.id = auction_seller_invoice.auction_id', 'left')
            ->where('auction_seller_invoice.id', $id)
            ->first();

        if ($invoice) {
            $invoiceitems_model = new AuctionToSellerInvoiceItemModel();
            $invoices = $invoiceitems_model->select('auction_seller_invoice_item.*')
                ->where('auction_seller_invoice_item.invoice_id', $id)
                ->findAll();

            $invoice['invoiceItems'] = $invoices;
        }

        $data['invoices'] = $invoice;


        return $this->respond($data);
    }
    public function highestBidding($id)
    {
        $model = new AuctionBiddingModel();
        $invoices = $model->select('bid_price,buyer.name As buyer_name')
            ->join('buyer', 'buyer.id = auction_biddings.buyer_id', 'left')
            ->where('auction_item_id', $id)
            ->orderBy('auction_biddings.id', 'DESC')
            ->first();
        if (count($invoices) > 0)
            return $this->respond($invoices);
        else {
            $invoices = array(['bid_price' => '', 'buyer_name' => '']);
            return $this->respond($invoices);
        }
    }


    public function getResesrveBidframe()
    {
        // echo '<pre>';print_r("hh");exit;

        $id = $this->request->getVar('id');
        //echo '<pre>';print_r("hh");exit;
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date("H:i:s");
        //echo 'Hiii';exit;

        $auction = $model->where('id', $id)->findAll();
        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select(
                'auction_items.*,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
        (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
        (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
        (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.auction_id', $auction[0]['id'])
            ->where('auction_items.status', 1)
            ->orderBy('auction_items.id')
            ->limit(2);

        $current_auction = $auctionItems->get()->getResultArray();



        $auctionBiddingModel = new AuctionBiddingModel();


        // foreach ($current_auction as $key => $auc) {
        //     $auction_biddings = $auctionBiddingModel->where('auction_item_id', $auc['id'])->countAllResults();
        //     if ($auction_biddings == 0) {
        //         $auction_item_id = $auc['id'];
        //         $auto_bidding = new AutoBiddingModel();
        //         $auto_bidding = $auto_bidding->where('auction_item_id', $auction_item_id)->orderBy('min_price')->findAll();
        //         if (count($auto_bidding) > 0) {
        //             foreach ($auto_bidding as $key => $autobid) {
        //                 $auctionBiddingModel = new AuctionBiddingModel();
        //                 $bid_data = [
        //                     'auction_item_id' => $autobid['auction_item_id'],
        //                     'buyer_id' => $autobid['buyer_id'],
        //                     'bid_price' => $autobid['min_price'],
        //                     'sq' => 1,
        //                     'bq' => 1,
        //                 ];
        //                 $catalog = $auctionBiddingModel->insert($bid_data);
        //             }
        //         }
        //     }
        // }

        $auction = $model->where('id', $id)->findAll();
        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select(
                'auction_items.*,auction_items.lot_set AS auctLotSet,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
         (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
                         bid_info.bid_price AS highest_bid_price, 
                bid_info.buyer_id, 
                bid_info.buyer_name AS highest_bidder_name,
         (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
         (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"current" AS auction_status,
                     (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join(
                '(SELECT ab.auction_item_id, 
                     ab.bid_price, 
                     ab.buyer_id, 
                     b.name AS buyer_name 
              FROM auction_biddings ab 
              LEFT JOIN buyer b ON ab.buyer_id = b.id 
              WHERE ab.bid_price = (
                  SELECT MAX(ab2.bid_price) 
                  FROM auction_biddings ab2 
                  WHERE ab2.auction_item_id = ab.auction_item_id
              ) 
              GROUP BY ab.auction_item_id) AS bid_info',
                'bid_info.auction_item_id = auction_items.id',
                'left'
            )
            ->where('auction_items.auction_id', $auction[0]['id'])->where('auction_items.status', 1)
            ->orderBy('auction_items.id')
            ->limit(2);
        $current_auction = $auctionItems->get()->getResultArray();




        $auctionItemsModel = new AuctionItemModel();
        $upcoming_auctions = $auctionItemsModel
            ->select(
                'auction_items.*,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
            (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
            (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
            (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"pending" AS auction_status,
                        (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.auction_id', $auction[0]['id'])
            ->where('auction_items.status', 1)
            ->orderBy('auction_items.id');
        $upcoming_auctions = $upcoming_auctions->get()->getResultArray();

        $sql = $auctionItemsModel->getLastQuery();
        $upcoming_auctions = array_slice($upcoming_auctions, 2);
        // echo '<pre>';print_r($upcoming_auctions);exit;

        $auctionItemsModel = new AuctionItemModel();
        $completed_auctions = $auctionItemsModel
            ->select(
                'auction_items.*,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
            (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
            bid_info.bid_price AS highest_bid_price, 
                bid_info.buyer_id, 
                bid_info.buyer_name AS highest_bidder_name,
            (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
            (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"completed" AS auction_status,
                        (SELECT buyer_show FROM settings ORDER BY id DESC LIMIT 1) AS settings_buyer_show'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join(
                '(SELECT ab.auction_item_id, 
                     ab.bid_price, 
                     ab.buyer_id, 
                     b.name AS buyer_name 
              FROM auction_biddings ab 
              LEFT JOIN buyer b ON ab.buyer_id = b.id 
              WHERE ab.bid_price = (
                  SELECT MAX(ab2.bid_price) 
                  FROM auction_biddings ab2 
                  WHERE ab2.auction_item_id = ab.auction_item_id
              ) 
              GROUP BY ab.auction_item_id) AS bid_info',
                'bid_info.auction_item_id = auction_items.id',
                'left'
            )
            ->where('auction_items.auction_id', $auction[0]['id'])
            ->where('auction_items.status', 2)
            ->orderBy('auction_items.id');
        $completed_auctions = $completed_auctions->get()->getResultArray();
        $all_auctions = array_merge($current_auction, $upcoming_auctions, $completed_auctions);


        $sql = $auctionItemsModel->getLastQuery();


        foreach ($auction as &$a) {

            $a['auction_items'] = $all_auctions;
        }



        $data['auction'] = $auction;
        return $this->respond($data);
    }
    /**** End of auction item */
}
