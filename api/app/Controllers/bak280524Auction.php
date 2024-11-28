<?php

namespace App\Controllers;

use App\Models\AuctionBiddingModel;
use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
use App\Models\SoldStockModel;
use App\Models\AuctionItemhistoryModel;
use App\Models\CartModel;
use App\Models\CenterModel;
use App\Models\SellerModel;
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
            'type' => 'required',
            'lot_count' => 'required|numeric',
            'session_time' => 'required',
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
        } else {
            $last_id = $model->orderBy('id', 'DESC')->first();
            if (!isset($last_id))
                $last_row_id = '001';
            else
                $last_row_id = @$last_id['id'] + 1;
            if ($last_id && $last_row_id < 10) {
                $last_row_id = '00' . $last_row_id;
            } elseif ($last_id && $last_row_id >= 10 && $last_row_id < 100) {
                $last_row_id = '0' . $last_row_id;
            }
            $start_time = $this->request->getVar('start_time'); // 16:00
            $session_time = $this->request->getVar('session_time'); // 2:23
            // $start_time_seconds = strtotime($start_time);
            // $session_time_seconds = strtotime('+' . $session_time, 0) - strtotime('00:00:00');
            // $end_time_seconds = ($start_time_seconds + $session_time_seconds) * $this->request->getVar('lot_count');
            // $end_time = date('H:i', $end_time_seconds);
            list($session_hours, $session_minutes) = explode(':', $session_time);

            $session_time_seconds = (($session_hours * 3600) + ($session_minutes * 60)) * $this->request->getVar('lot_count');
            $start_time_seconds = strtotime($start_time);
            $end_time_seconds = ($start_time_seconds + $session_time_seconds);
            $end_time = date('H:i', $end_time_seconds);
            $date = date("Y-m-d", strtotime($this->request->getVar('date')));

            $existingAuction = $model->where('date', $date)
                ->where('is_publish', 1)
                ->where("TIME('$start_time') >= start_time")
                ->where("TIME('$start_time') <= end_time")
                ->findAll();



            // $date = date("Y-m-d", strtotime($this->request->getVar('date')));
            // // Check for overlapping auctions
            // $existingAuction = $model
            //     ->where('date', $date)
            //     ->where('is_publish', 1)
            //     // ->where("(start_time BETWEEN '$start_time' AND '$end_time' 
            //     // OR end_time BETWEEN '$start_time' AND '$end_time'
            //     // OR '$start_time' <= start_time AND '$end_time' >= end_time
            //     // )")
            //     ->findAll();

            // $sql = $model->getLastQuery();
            // echo $sql;
            // exit;
            if ($existingAuction) {
                $validationErrors = ['start_time' => 'Auction already exists between the given start time and end time'];
                return $this->fail($validationErrors, 422); // Bad request
            } else {
                // Extracting data from the request
                $data = [
                    'center_id' => $this->request->getVar('center_id'),
                    'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                    'sale_no' => $last_row_id . "/" . date("y"),
                    'start_time' => $this->request->getVar('start_time'),
                    'type' => $this->request->getVar('type'),
                    'lot_count' => $this->request->getVar('lot_count'),
                    'session_time' => $this->request->getVar('session_time'),
                    'created_by' => $this->request->getVar('session_user_id'),
                ];

                $model->insert($data);

                // $auctionId = $model->getInsertID();

                // $auction_data = $this->request->getVar('auction_data');

                // // Inserting auction items


                // $auctionItemModel = new AuctionItemModel();
                // foreach ($auction_data as $i => $a) {
                //     $auctionItemData = [
                //         'inward_invoice_id' => $a->inward_item_id,
                //         'inward_item_id' => $a->inward_item_id,
                //         'auction_id' => $auctionId,
                //         'lot_no' => (1001 + $i),
                //         'auction_quantity' => $a->auction_quantity,
                //         'base_price' => $a->base_price,
                //         'reverse_price' => $a->reserve_price,
                //         'high_price' => $a->inward_item_id,
                //         'created_by' => $session_user_id,
                //     ];


                //     $auctionItemModel->insert($auctionItemData);
                //     $auctionitemId = $auctionItemModel->getInsertID();

                //     $inward_item_detail = new InwardItemModel();
                //     $inward_item_details = $inward_item_detail->where('id', $auctionItemData['inward_item_id'])->first();

                //     $stock_model = new StockModel();
                //     $stock_detail = $stock_model->where('inward_item_id', $auctionItemData['inward_item_id'])->first();
                //     $existing_stock = $stock_detail['qty'];

                //     /***** Update stock qty */
                //     $current_stock = $existing_stock - $auctionItemData['auction_quantity'];
                //     $stock_data = ['qty' => $current_stock];
                //     $stock_model->where('inward_item_id', $auctionItemData['inward_item_id'])->set('qty', $current_stock)->update();

                //     /**** end of update stock qty */

                //     $auction_stock_data = [
                //         'inward_id' => $inward_item_details['inward_id'],
                //         'inward_item_id' => $auctionItemData['inward_item_id'],
                //         'auction_id' => $auctionId,
                //         'auction_item_id' => $auctionitemId,
                //         'qty' => $auctionItemData['auction_quantity'],
                //     ];

                //     $auction_stock_model = new AuctionStockModel();
                //     $auction_stock_model->insert($auction_stock_data);
                // }

                // $sequence_data = $this->request->getVar('sequence_data');

                // $auctionGardenOrderModel = new AuctionGardenOrderModel();
                // foreach ($sequence_data->sequence as $key => $seq) {
                //     $auctionGardenOrderData = [
                //         'garden_id' => $sequence_data->gardenId[$key],
                //         'center_id' => $this->request->getVar('center_id'),
                //         'order_seq' => $seq,
                //     ];

                //     $auctionGardenOrderModel->insert($auctionGardenOrderData);
                // }
            }
        }
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
                'created_by' => $this->request->getVar('session_user_id'),
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
        $auction = $model->select('auction.*,center.name AS center_name')
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

    // public function update($id = null)
    // {
    //     $model = new AuctionModel();

    //     $session_user_id = $this->session->get('session_user_id');

    //     $rules = [
    //         'center_id' => 'required|numeric',
    //         'date' => 'required',
    //         'sale_no' => 'required|numeric',
    //         'start_time' => 'required',
    //         'end_time' => 'required',
    //         'lot_count' => 'required|numeric',
    //         'session_time' => 'required',
    //     ];

    //     $messages = [
    //         'center_id' => [
    //             'required' => 'Please select enter.',
    //             'numeric' => 'The center ID must be numeric.',
    //         ],
    //         'date' => [
    //             'required' => 'The date is required.',
    //         ],
    //         'sale_no' => [
    //             'required' => 'The sale number is required.',
    //             'numeric' => 'The sale number must be numeric.',
    //         ],
    //         'start_time' => [
    //             'required' => 'The start time is required.',
    //         ],
    //         'end_time' => [
    //             'required' => 'The end time is required.',
    //         ],
    //         'lot_count' => [
    //             'required' => 'The lot count is required.',
    //             'numeric' => 'The lot count must be numeric.',
    //         ],
    //         'session_time' => [
    //             'required' => 'The session time is required.',
    //         ],
    //         'invoice_no.*' => [
    //             'required' => 'Please select Invoice Number.',
    //         ]
    //     ];

    //     if (!$this->validate($rules, $messages)) {
    //         $validationErrors = $this->validator->getErrors();
    //         return $this->fail($validationErrors, 422); // Bad request
    //     } else {
    //         $data = [
    //             'center_id' => $this->request->getVar('center_id'),
    //             'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
    //             'sale_no' => $this->request->getVar('sale_no'),
    //             'start_time' => $this->request->getVar('start_time'),
    //             'end_time' => $this->request->getVar('end_time'),
    //             'lot_count' => $this->request->getVar('lot_count'),
    //             'session_time' => $this->request->getVar('session_time'),
    //             'updated_by' => $session_user_id, // Update the 'updated_by' field
    //         ];
    //         // Update the auction record with the provided ID
    //         $model->update($id, $data);

    //         $model = new AuctionItemModel();

    //         // Get the count of items related to this auction
    //         $itemsCount = count($this->request->getVar('invoice_no'));

    //         // Update or insert auction items
    //         for ($i = 0; $i < $itemsCount; $i++) {
    //             if ($this->request->getVar('invoice_no')[$i] != '') {
    //                 $auctionItemModel = new AuctionItemModel();
    //                 $inward_item_id = $this->request->getVar('inward_item_id')[$i];
    //                 $auctionItemData = [
    //                     'inward_invoice_id' => $this->request->getVar('invoice_no')[$i],
    //                     'auction_quantity' => $this->request->getVar('auction_quantity')[$i],
    //                     'base_price' => $this->request->getVar('base_price')[$i],
    //                     'reverse_price' => $this->request->getVar('reverse_price')[$i],
    //                     'high_price' => $this->request->getVar('high_price')[$i],
    //                     'created_by' => $session_user_id,
    //                 ];

    //                 // Update or insert auction item
    //                 $auctionItemModel
    //                     ->where('auction_id', $id)
    //                     ->where('inward_item_id', $inward_item_id)
    //                     ->set($auctionItemData)
    //                     ->update();
    //             }
    //         }

    //         // Inserting auction garden orders
    //         $gardenIds = $this->request->getVar('garden_id');
    //         $orderSeqs = $this->request->getVar('sequence');

    //         foreach ($gardenIds as $key => $gardenId) {
    //             // print_r($orderSeqs);

    //             $auctionGardenOrderModel = new AuctionGardenOrderModel();
    //             $auctionGardenOrderData = [
    //                 'garden_id' => $gardenId,
    //                 'order_seq' => $orderSeqs[$key],
    //             ];
    //             // print_r($auctionGardenOrderData);
    //             $auctionGardenOrderModel
    //                 ->where('auction_id', $id)
    //                 ->where('center_id', $data['center_id'])
    //                 ->where('garden_id', $gardenId)
    //                 ->set($auctionGardenOrderData)
    //                 ->update();
    //         }

    //         $response = [
    //             'status' => 200,
    //             'error' => false,
    //             'message' => 'Updated Successfully'
    //         ];

    //         return $this->respond($response);
    //     }
    // }

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
            'lot_count' => 'required|numeric',
            'ex_lot_count' => 'required|numeric',
            'session_time' => 'required',
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
            // Validation passed
            $start_time = $this->request->getVar('start_time'); // 16:00
            $auctionitem_count = $auctionitem_model->where('auction_id', $id)->countAllResults();
            // echo $auctionitem_count;exit;
            $start_time_seconds = strtotime($start_time);
            $session_count = ceil($auctionitem_count / $this->request->getVar('lot_count'));
            list($session_hours, $session_minutes) = explode(':', $this->request->getVar('session_time'));
            $session_time_seconds = (($session_hours * 3600) + ($session_minutes * 60)) * $session_count;
            $start_time_seconds = strtotime($start_time);
            $end_time_seconds = ($start_time_seconds + $session_time_seconds);
            $end_time = date('H:i', $end_time_seconds);
            // print_r($end_time);exit;

            $date = date("Y-m-d", strtotime($this->request->getVar('date')));

            // if ($this->request->getVar('lot_count') > $auctionitem_count) {
            //     // Auction already exists for the given time period
            //     $validationErrors = ['lot_count' => 'Auction lot count should less than Auction item count.'];
            //     return $this->fail($validationErrors, 422); // Bad request
            // }
            // Check if start time has changed
            $currentAuction = $model->find($id);
            // print_r(strtotime($currentAuction['start_time']));exit;

            $auction_items = $auctionitem_model->where('auction_id', $id)->findAll();

            if ($currentAuction['lot_count'] != $this->request->getVar('lot_count')) {
                $auctionTimingModel = new AuctionLotTimingsModel();
                $auctionTimingModel->where(['auction_id' => $id])->delete();
                $m = 0;
                $f = 0;
                foreach ($auction_items as $key => $items) {
                    $lot_count_new = $this->request->getVar('lot_count');
                    if ($m % $lot_count_new === 0) {
                        $f++;
                        $start_time_seconds = strtotime($currentAuction['start_time']);
                        list($session_hours, $session_minutes) = explode(':', $this->request->getVar('session_time'));
                        $session_time_seconds = (($session_hours * 3600) + ($session_minutes * 60)) * $f;
                        $lot_session_time_seconds = (($session_hours * 3600) + ($session_minutes * 60)) * ($f - 1);
                        $end_time_seconds = ($start_time_seconds + $session_time_seconds);
                        $lot_start_time_seconds = ($start_time_seconds + $lot_session_time_seconds);
                        $end_time = date('H:i', $end_time_seconds);
                        $lot_start_time = date('H:i', $lot_start_time_seconds);
                        session()->set('lot_end_time', $end_time);
                        session()->set('lot_start_time', $lot_start_time);
                    }
                    $auctionTimingModel = new AuctionLotTimingsModel();
                    $auctionItemTimeData = [
                        'auction_id' => $id,
                        'auction_item_id' => $items['id'],
                        'created_by' => $this->request->getVar('session_user_id'),
                        'start_time' => session()->get('lot_start_time'),
                        'end_time' => session()->get('lot_end_time'),
                        'lot_set' => $f,
                    ];
                    $auctionTimingModel->insert($auctionItemTimeData);
                    $m++;
                }
            }

            // Start time has changed, perform validation for overlapping auctions
            // $existingAuction = $model
            //     ->where('id !=', $id)
            //     ->where('date', $date)
            //     ->where('is_publish', 1)
            //     // ->where("(start_time BETWEEN '$start_time' AND '$end_time' 
            //     //         OR end_time BETWEEN '$start_time' AND '$end_time'
            //     //         OR '$start_time' <= start_time AND '$end_time' >= end_time
            //     //         )")
            //     ->findAll();
            $existingAuction = $model->where('id !=', $id)
                ->where('date', $date)
                ->where('is_publish', 1)
                ->where("TIME('$start_time') >= start_time")
                ->where("TIME('$start_time') <= end_time")
                ->findAll();

            // $sql = $model->getLastQuery();
            // echo $sql;
            // exit;

            if ($existingAuction) {
                // Auction already exists for the given time period
                $validationErrors = ['start_time' => 'Auction already exists between the given start time and end time'];
                return $this->fail($validationErrors, 422); // Bad request
            }

            // Update auction
            $data = [
                'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                'start_time' => $start_time,
                'end_time' => $end_time,
                'session_time' => $this->request->getVar('session_time'),
                'reason' => $this->request->getVar('reason'),
                'lot_count' => $this->request->getVar('lot_count'),
                'updated_by' => $this->request->getVar('session_user_id'),
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
                    'updated_by' => $this->request->getVar('session_user_id'),
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
        $data = $model->select('inward_items.*,auction_items.id AS auctionitem_id,auction.id AS auction_id,grade.name AS grade_name,garden.name AS garden_name,garden.id AS garden_id,grade.id AS grade_id,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
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
              bid_info.buyer_name AS highest_bidder_name')
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
        $auction_id = $this->request->getVar('auction_id');

        $auction_stock = $auction_itemmodel->select('auction_items.*,inward.seller_id,auction.sale_no')
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

                if ($value['reverse_price'] < $auction_biddings['bid_price']) {

                    $bidding_array[] = [
                        'auction_item_id' => $value['id'],
                        'auction_id' => $value['auction_id'],
                        'inward_item_id' => $value['inward_item_id'],
                        'qty' => $value['auction_quantity'],
                        'sale_no' => $value['sale_no'],
                        'seller_id' => $value['seller_id'],
                        'bid_price' => @$auction_biddings['bid_price'],
                        'buyer_id' => @$auction_biddings['buyer_id'],
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

                        $auction_stockmodel->delete($auction_stock['id']);
                    }
                } else {
                    $auction_stock = $auction_stockmodel
                        ->select('auction_stock.inward_item_id,auction_stock.qty,inward_items.inward_id,inward.warehouse_id')
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
                    }
                }
            } else {
                $auction_stock = $auction_stockmodel
                    ->select('auction_stock.inward_item_id,auction_stock.qty,inward_items.inward_id,inward.warehouse_id')
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
                }
            }
        }

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

        foreach ($grouped_auction_items as $key => $buyer_array) {
            $invoice_model = new InvoiceModel();
            $invoiceitem_model = new InvoiceItemModel();
            foreach ($buyer_array as $seller => $seller_array) {
                $invoice_data = [
                    'date' => date('Y-m-d'),
                    'invoice_no' => 'RCPL/' . $seller_array[0]['sale_no'] . "/" . rand(10000, 90000),
                    'buyer_id' => $key,
                    'seller_id' => $seller,
                    'auction_id' => $seller_array[0]['auction_id'],
                ];
                $invoice_model->insert($invoice_data);
                $invoice_id = $invoice_model->getInsertID();
                foreach ($seller_array as $key => $value) {
                    $invoice_item_data = [
                        'invoice_id' => $invoice_id,
                        'auction_item_id' => $value['auction_item_id'],
                        'inward_item_id' => $value['inward_item_id'],
                        'qty' => $value['qty'],
                        'bid_price' => $value['bid_price'],
                    ];
                    $invoiceitem_model->insert($invoice_item_data);
                }
            }
        }

        // auctioner to buyer
        $grouped_auction_items_auctioneer = array();
        foreach ($bidding_array as $item) {
            $buyer_id = $item['buyer_id'];
            if (!isset($grouped_auction_items_auctioneer[$buyer_id])) {
                $grouped_auction_items_auctioneer[$buyer_id] = [];
            }
            $grouped_auction_items_auctioneer[$buyer_id][] = $item;
        }

        foreach ($grouped_auction_items_auctioneer as $key => $buyer_auctioneer_array) {
            $invoice_model_auctioneer = new AuctionToBuyerInvoiceModel();
            $invoiceitem_model_auctioneer = new AuctionToBuyerInvoiceItemModel();
            $invoice_data_auctioneer = [
                'date' => date('Y-m-d'),
                'invoice_no' => 'RCPL/' . $buyer_auctioneer_array[0]['sale_no'] . "/" . rand(10000, 90000),
                'buyer_id' => $key,
                'auction_id' => $buyer_auctioneer_array[0]['auction_id'],
            ];
            // echo '<pre>';print_r($invoice_data);exit;

            $invoice_model_auctioneer->insert($invoice_data_auctioneer);
            $invoice_id_auctioneer = $invoice_model_auctioneer->getInsertID();
            foreach ($buyer_auctioneer_array as $key => $value) {
                $invoice_item_data_auctioneer = [
                    'invoice_id' => $invoice_id_auctioneer,
                    'auction_item_id' => $value['auction_item_id'],
                    'inward_item_id' => $value['inward_item_id'],
                    'qty' => $value['qty'],
                    'bid_price' => $value['bid_price'],
                ];
                $invoiceitem_model_auctioneer->insert($invoice_item_data_auctioneer);
            }
        }

        // auctioner to seller
        $grouped_auction_items_seller = array();
        foreach ($bidding_array as $item) {
            $seller_id = $item['seller_id'];
            if (!isset($grouped_auction_items_seller[$buyer_id])) {
                $grouped_auction_items_seller[$buyer_id] = [];
            }
            $grouped_auction_items_seller[$buyer_id][] = $item;
        }

        foreach ($grouped_auction_items_seller as $key => $buyer_seller_array) {
            $invoice_model_seller = new AuctionToSellerInvoiceModel();
            $invoiceitem_model_seller = new AuctionToSellerInvoiceItemModel();
            $invoice_data_seller = [
                'date' => date('Y-m-d'),
                'invoice_no' => 'RCPL/' . $buyer_seller_array[0]['sale_no'] . "/" . rand(10000, 90000),
                'seller_id' => $seller,
                'auction_id' => $buyer_seller_array[0]['auction_id'],
            ];
            // echo '<pre>';print_r($invoice_data);exit;

            $invoice_model_seller->insert($invoice_data_seller);
            $invoice_id_seller = $invoice_model_seller->getInsertID();
            foreach ($buyer_seller_array as $key => $value) {
                $invoice_item_data_seller = [
                    'invoice_id' => $invoice_id_seller,
                    'auction_item_id' => $value['auction_item_id'],
                    'inward_item_id' => $value['inward_item_id'],
                    'qty' => $value['qty'],
                    'bid_price' => $value['bid_price'],
                ];
                $invoiceitem_model_seller->insert($invoice_item_data_seller);
            }
        }


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
        $invoices = $invoice_model->select('invoice.*,seller.name AS seller_name,buyer.name AS buyer_name,buyer.contact_person_number')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->findAll();

        $data['invoices'] = $invoices;
        return $this->respond($data);
    }
    public function invoiceDetails($id)
    {

        $invoiceModel = new InvoiceModel();
        $invoice = $invoiceModel->select('invoice.*,auction.date AS auction_date,
        buyer.fssai_no AS b_fssai,buyer.tea_board_no AS b_tea,buyer.gst_no AS b_gst,
        buyer.address AS b_address,auction.sale_no,seller.name AS seller_name,
        buyer.name AS buyer_name,buyer.contact_person_number,seller.fssai_no AS s_fssai,
        seller.tea_board_no AS s_tea,seller.gst_no AS s_gst,seller.address AS s_address')
            ->join('auction', 'auction.id = invoice.auction_id', 'left')
            ->join('seller', 'seller.id = invoice.seller_id', 'left')
            ->join('buyer', 'buyer.id = invoice.buyer_id', 'left')
            ->where('invoice.id', $id)
            ->first();

        if ($invoice) {

            $invoiceitems_model = new InvoiceItemModel();
            $invoices = $invoiceitems_model->select('invoice_item.bid_price,auction_items.id AS auction_id,auction_items.*,inward_items.id AS inward_id,inward_items.*,grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                ->join('auction_items', 'invoice_item.auction_item_id = auction_items.id', 'left')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
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
        $invoices = $invoice_model->select('auction_buyer_invoice.*,buyer.name AS buyer_name,buyer.contact_person_number')
            ->join('buyer', 'buyer.id = auction_buyer_invoice.buyer_id', 'left')
            ->findAll();

        $data['invoices'] = $invoices;
        // print_r($data);exit;

        return $this->respond($data);
    }
    public function auctionbuyerinvoiceDetails($id)
    {

        $invoiceModel = new AuctionToBuyerInvoiceModel();
        $invoice = $invoiceModel->select('auction_buyer_invoice.*,auction.date AS auction_date,buyer.fssai_no AS b_fssai,buyer.tea_board_no AS b_tea,buyer.gst_no AS b_gst,buyer.address AS b_address,auction.sale_no,buyer.name AS buyer_name,buyer.contact_person_number')
            ->join('auction', 'auction.id = auction_buyer_invoice.auction_id', 'left')
            ->join('buyer', 'buyer.id = auction_buyer_invoice.buyer_id', 'left')
            ->where('auction_buyer_invoice.id', $id)
            ->first();

        if ($invoice) {

            $invoiceitems_model = new AuctionToBuyerInvoiceItemModel();
            $invoices = $invoiceitems_model->select('auction_buyer_invoice_item.bid_price,auction_items.*,grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                ->join('auction_items', 'auction_buyer_invoice_item.auction_item_id = auction_items.id', 'left')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
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
        $invoices = $invoice_model->select('auction_seller_invoice.*,seller.name AS seller_name,seller.address')
            ->join('seller', 'seller.id = auction_seller_invoice.seller_id', 'left')
            ->findAll();

        $data['invoices'] = $invoices;
        return $this->respond($data);
    }
    public function auctionsellerinvoiceDetails($id)
    {

        $invoiceModel = new AuctionToSellerInvoiceModel();
        $invoice = $invoiceModel->select('auction_seller_invoice.*,auction.date AS auction_date,seller.fssai_no AS s_fssai,seller.tea_board_no AS s_tea,seller.gst_no AS s_gst,seller.address AS s_address,auction.sale_no,seller.name AS seller_name')
            ->join('auction', 'auction.id = auction_seller_invoice.auction_id', 'left')
            ->join('seller', 'seller.id = auction_seller_invoice.seller_id', 'left')
            ->where('auction_seller_invoice.id', $id)
            ->first();

        if ($invoice) {
            $invoiceitems_model = new AuctionToSellerInvoiceItemModel();
            $invoices = $invoiceitems_model->select('auction_seller_invoice_item.bid_price,auction_items.*,grade.name AS grade_name,center.name AS center_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                ->join('auction_items', 'auction_seller_invoice_item.auction_item_id = auction_items.id', 'left')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->join('center', 'center.id = inward.center_id', 'left')
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

    /**** End of auction item */
}
