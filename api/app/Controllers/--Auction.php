<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
use App\Models\CenterModel;
use App\Models\SellerModel;
use App\Models\GardenModel;
use App\Models\WarehouseModel;
use App\Models\GradeModel;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use App\Models\CenterGardenModel;
use App\Models\StockModel;
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
        $auctions = $model->select('auction.*,center.name AS center_name')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->findAll();

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
            $last_row_id = $last_id['id'] + 1;
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
            list($session_hours, $session_minutes, $session_seconds) = explode(':', $session_time);

            $session_time_seconds = (($session_hours * 3600) + ($session_minutes * 60)) * $this->request->getVar('lot_count');
            $start_time_seconds = strtotime($start_time);
            $end_time_seconds = ($start_time_seconds + $session_time_seconds);
            $end_time = date('H:i', $end_time_seconds);

            // Extracting data from the request
            $data = [
                'center_id' => $this->request->getVar('center_id'),
                'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                'sale_no' => $last_row_id . "/" . date("y"),
                'start_time' => $this->request->getVar('start_time'),
                'end_time' => $end_time,
                'lot_count' => $this->request->getVar('lot_count'),
                'session_time' => $this->request->getVar('session_time'),
                'created_by' => $session_user_id,
            ];

            // Inserting auction data
            $model->insert($data);
            $auctionId = $model->getInsertID(); // Get the last inserted ID

            // Inserting auction items
            $auctionItemModel = new AuctionItemModel();
            $invoiceNos = $this->request->getVar('inward_item');
            $q = 1;
            foreach ($invoiceNos as $i => $invoiceNo) {
                if (!empty($this->request->getVar('auction_quantity')[$i]) && !empty($this->request->getVar('base_price')[$i]) && !empty($this->request->getVar('reverse_price')[$i]) && !empty($this->request->getVar('high_price')[$i])) {
                    $lot_no = $auctionId . $invoiceNo . $i;
                    $auctionItemData = [
                        'inward_invoice_id' => $this->request->getVar('inward_item')[$i],
                        'inward_item_id' => $this->request->getVar('inward_item')[$i],
                        'auction_id' => $auctionId,
                        'lot_no' => (1000 + $q),
                        'auction_quantity' => $this->request->getVar('auction_quantity')[$i],
                        'base_price' => $this->request->getVar('base_price')[$i],
                        'reverse_price' => $this->request->getVar('reverse_price')[$i],
                        'high_price' => $this->request->getVar('high_price')[$i],
                        'created_by' => $session_user_id,
                    ];
                    $auctionItemModel->insert($auctionItemData);
                    $auctionitemId = $auctionItemModel->getInsertID();

                    $inward_item_detail = new InwardItemModel();
                    $inward_item_details = $inward_item_detail->where('id', $invoiceNo)->first();

                    $stock_model = new StockModel();
                    $stock_detail = $stock_model->where('inward_item_id', $invoiceNo)->first();
                    $existing_stock = $stock_detail['qty'];

                    /***** Update stock qty */
                    $current_stock = $existing_stock - $this->request->getVar('auction_quantity')[$i];
                    $stock_data = ['qty' => $current_stock];
                    $stock_model->where('inward_item_id', $invoiceNo)->set('qty', $current_stock)->update();



                    /**** end of update stock qty */



                    $auction_stock_data = [
                        'inward_id' => $inward_item_details['inward_id'],
                        'inward_item_id' => $invoiceNo,
                        'auction_id' => $auctionId,
                        'auction_item_id' => $auctionitemId,
                        'qty' => $this->request->getVar('auction_quantity')[$i],
                    ];
                    $auction_stock_model = new AuctionStockModel();
                    $auction_stock_model->insert($auction_stock_data);
                }
            }

            // Inserting auction garden orders
            $gardenIds = $this->request->getVar('garden_id');
            $orderSeqs = $this->request->getVar('sequence');
            $auctionGardenOrderModel = new AuctionGardenOrderModel();
            foreach ($gardenIds as $key => $gardenId) {
                // print_r($orderSeqs);
                $auctionGardenOrderData = [
                    'auction_id' => $auctionId,
                    'garden_id' => $gardenId,
                    'center_id' => $data['center_id'],
                    'order_seq' => $orderSeqs[$key],
                ];
                $auctionGardenOrderModel->insert($auctionGardenOrderData);
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


    public function show($id = null)
    {
        $model = new AuctionModel();
        $auction = $model->find($id);

        if ($auction) {
            $auctionItemModel = new AuctionItemModel();
            $auctionItems = $auctionItemModel->select('inward_items.*,auction_items.*,grade.name AS grade_name,garden.name AS garden_name,warehouse.name AS warehouse_name')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward.id = inward_items.inward_id', 'left')
                ->join('garden', 'garden.id = inward.garden_id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
                ->where('auction_id', $auction['id'])
                ->findAll();

            $auction['auctionItems'] = $auctionItems;

            $data['auction'] = $auction;
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
        // return $id;
        $model = new AuctionModel();

        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'date' => 'required',
            'reason' => 'required',
        ];

        $messages = [
            'date' => [
                'required' => 'The date is required.',
            ],
            'reason' => [
                'required' => 'The Reason field is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            $data = [
                'date' => date("Y-m-d", strtotime($this->request->getVar('date'))),
                'reason' => $this->request->getVar('reason'),
                'updated_by' => $this->request->getVar('session_user_id'),
            ];
            $model->update($id, $data);

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully'
            ];
            return $this->respond($response);
        }
    }
    public function delete($id = null)
    {
        $model = new AuctionModel();
        $auctionItemModel = new AuctionItemModel();
        $auctionItems = $auctionItemModel->where('auction_id', $id)->findAll();

        if ($auctionItems) {
            $model->update($id, ['status' => 0]);

            foreach ($auctionItems as $auctionItem) {
                $auctionItemModel->update($auctionItem['id'], ['status' => 0]);
            }

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted Successfully',
                'data' => $auctionItems // Return the deleted auction items
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
        $data = $model->select('inward_items.*,grade.name AS grade_name,garden.name AS garden_name,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)
            ->where('inward.center_id', $center_id)->findAll();

        if (count($data)) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $center_id);
        }
    }

    public function getInvoicesByWarehouseId()
    {
        $center_id = $this->request->getVar('center_id');
        $warehouse_id = $this->request->getVar('warehouse_id');
        $model = new StockModel();
        $data = $model->select('inward_items.*,grade.name AS grade_name,garden.name AS garden_name,warehouse.name AS warehouse_name,stock.qty AS stock_qty')
            ->join('inward_items', 'inward_items.id = stock.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = stock.warehouse_id', 'left')
            ->where('stock.qty !=', 0)
            ->where('inward.center_id', $center_id)
            ->where('inward.warehouse_id', $warehouse_id)
            ->findAll();

        if (count($data)) {

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Success',
                'data' => $data
            ];

            // print_r($response);exit;

            return $this->respond($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $center_id);
        }
    }
    public function centerGarden()
    {
        $center_id = $this->request->getVar('center_id');
        $centerGarden = new CenterGardenModel();
        $centerGarden = $centerGarden->select('garden.name AS garden_name,garden.id')
            ->join('garden', 'garden.id = center_garden.garden_id', 'left')
            ->join('center', 'center.id = center_garden.center_id', 'left')
            ->where('center_id', $center_id)->findAll();

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
        $auction_items = $model->select('auction_items.*,inward_items.invoice_id AS invoice_no,grade.name AS gradename,inward_items.bag_type,inward_items.weight_gross,
        (SELECT SUM(qty) FROM auction_stock WHERE auction_stock.auction_item_id = auction_items.id) AS total_count')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.status', 1)
            ->orderBy('id')->findAll();

        // print_r($auction_item['auction']);exit;
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

        $auction = $model->select('auction_items.*,inward_items.weight_net,inward_items.total_net,grade.name AS gradename,garden.name AS gardenname,inward_items.bag_type')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
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
    /**** End of auction item */
}
