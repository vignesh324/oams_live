<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
use App\Models\CenterGardenModel;
use App\Models\GardenGradeModel;
use App\Models\CartModel;
use App\Models\StockModel;
use App\Models\InwardModel;
use App\Models\InwardItemModel;
use App\Models\ProductLogModel;
use App\Models\AuctionLotTimingsModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use App\Helpers\ProductLog;

use Config\Services;


class Cart extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function detail($id)
    {

        $cart_model = new CartModel();
        $auctiongarden_model = new AuctionGardenOrderModel();

        $check_count = $auctiongarden_model->where('auction_id', $id)->findAll();

        $cartItems = $cart_model->select('auction.lot_count AS auction_lot_count,cart.id as cart_id,cart.qty AS cart_qty,cart.auction_id as auction_id,cart.sample_quantity,inward_items.*,grade.name AS grade_name,center.name AS center_name,garden.id AS garden_id,garden.name AS garden_name,warehouse.name AS warehouse_name')
            ->join('inward_items', 'inward_items.id = cart.inward_item_id', 'left')
            ->join('inward', 'inward.id = inward_items.inward_id', 'left')
            ->join('garden', 'garden.id = inward.garden_id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->join('warehouse', 'warehouse.id = inward.warehouse_id', 'left')
            ->join('center', 'center.id = inward.center_id', 'left')
            ->join('auction', 'auction.id = cart.auction_id', 'left')
            ->where('cart.auction_id', $id)
            ->orderBy('cart.garden_id')
            ->findAll();

        $data['auction'] = $cartItems;
        //  $lastQuery = $cart_model->getLastQuery();
        //  echo "Last Query: " . $lastQuery . "<br>";exit;
        return $this->respond($data);
    }

    public function auctionGardenOrder($id)
    {
        $model = new AuctionGardenOrderModel();

        $data = $model->select('auction_garden_order.garden_id,auction_garden_order.garden_grade')
            ->where('auction_garden_order.auction_id', $id)
            ->orderBy('auction_garden_order.order_seq')
            ->findAll();

        return $this->respond($data);
    }

    public function inwarddetail($id)
    {
        $cart_model = new CartModel();
        $cartItems = $cart_model->select('cart.*, auction.lot_count')
            ->join('inward_items', 'inward_items.id = cart.inward_item_id', 'left')
            ->join('auction', 'auction.id = cart.auction_id', 'left')
            ->where('auction_id', $id)
            ->where('inward_items.status', 1)
            ->whereIn('cart.inward_id', function ($subQuery) use ($id) {
                $subQuery->select('MIN(inward_items.id)')
                    ->from('inward_items')
                    ->join('cart', 'cart.inward_item_id = inward_items.id', 'left')
                    ->where('cart.auction_id', $id)
                    ->groupBy('cart.inward_id');
            })
            ->findAll();


        $data['auction'] = $cartItems;
        return $this->respond($data);
    }

    public function create()
    {
        $model = new CartModel();
        $inwardItemModel = new InwardItemModel();
        $session_user_id = $this->session->get('session_user_id');

        $rules = [
            'inward_id' => 'required|numeric',
            'inward_item_id' => 'required',
            'qty' => 'required'
        ];

        $messages = [
            'inward_id' => [
                'required' => 'Please enter inward.',
                'numeric' => 'The inward ID must be numeric.',
            ],
            'date' => [
                'required' => 'The inward item id is required.',
            ],
            'qty' => [
                'required' => 'The quantity is required.'
            ]
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $auction_data = $this->request->getVar('auction_data');
            foreach ($auction_data as $i => $a) {
                $data = [
                    'inward_id' => $a->inward_id,
                    'inward_item_id' => $a->inward_item_id,
                    'qty' => $a->qty,
                    'created_by' => $this->request->getVar('session_user_id'),
                ];
                $model->insert($data);
            }
        }

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }

    public function reorderAuctionGarden()
    {
        $auctionGardenOrderModel = new AuctionGardenOrderModel();
        $auctionGradeOrderModel = new GardenGradeModel();
        $garden_id = $this->request->getVar('garden_id');
        if (isset($garden_id)) {

            $auction_stock = $auctionGardenOrderModel->where('auction_id', $this->request->getVar('auction_id'))
                ->delete();
            for ($i = 0; $i < count($this->request->getVar('garden_id')); $i++) {
                $garden_grade = $auctionGradeOrderModel->select("GROUP_CONCAT(grade_id ORDER BY order_seq ASC) AS grade_id")
                    ->where('garden_grade.garden_id', $this->request->getVar('garden_id')[$i])
                    ->first();

                $auctionGardenOrderData = [
                    'order_seq' => $this->request->getVar('sequence')[$i],
                    'garden_id' => $this->request->getVar('garden_id')[$i],
                    'center_id' => $this->request->getVar('center_id'),
                    'auction_id' => $this->request->getVar('auction_id'),
                    'garden_grade' => $garden_grade['grade_id'],
                ];

                // print_r($auctionGardenOrderData);exit;
                $auctionGardenOrderModel->insert($auctionGardenOrderData);
            }
        }

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }

    public function addTocart()
    {
        $auctionItemModel = new AuctionItemModel();
        $inwardItemModel = new InwardItemModel();
        $auctiongardenModel = new AuctionGardenOrderModel();

        $auctionGradeOrderModel = new GardenGradeModel();
        $auctionGardenOrderModel = new AuctionGardenOrderModel();
        $cart_model = new CartModel();
        $stock_model = new StockModel();
        $count = 1;
        $auction_id = $this->request->getVar('auction_id');

        $cartdata = $cart_model->distinct()->select('garden_id')->where('auction_id', $auction_id)->findAll();

        // print_r($auction_garden_count);exit;

        for ($i = 0; $i < count($this->request->getVar('check-auctionitem')); $i++) {
            $auction_garden_count1 = $auctiongardenModel->where('garden_id', $this->request->getVar('garden_id')[$i])->where('auction_id', $auction_id)->countAllResults();
            $auction_garden_count = $auctiongardenModel->where('auction_id', $auction_id)->countAllResults();
            //echo '<pre>';print_r($auction_garden_count1);exit;
            $inward_item_id = $this->request->getVar('inward_item_id')[$i];
            $auction_id = $this->request->getVar('auction_id');
            $inward_id = $this->request->getVar('inward_id')[$i];
            $qty = $this->request->getVar('qty')[$i];
            $sample_quantity = $this->request->getVar('sample_quantity')[$i];
            $garden_id = $this->request->getVar('garden_id')[$i];
            $session_user_id = $this->request->getVar('session_user_id');

            // print_r($this->request->getVar('qty'));exit;
            $cart_where = [
                'auction_id' => $auction_id,
                'inward_id' => $inward_id,
                'inward_item_id' => $inward_item_id,
            ];

            $cart_detail = $cart_model->where($cart_where)->first();

            if ($cart_detail) {
                // echo 'if';exit;
                $existing_qty = $cart_detail['qty'];
                $id = $cart_detail['id'];
                $new_qty = $existing_qty + $qty;

                $auctionItemData = [
                    'qty' => $new_qty,
                    'sample_quantity' => $sample_quantity,
                    'created_by' => $session_user_id,
                ];

                $cart_model->update($id, $auctionItemData);

                $stock_detail = $stock_model->where('inward_item_id', $inward_item_id)->first();
                $stock_qty = $stock_detail['qty'] - $qty;

                $stock_data = ['qty' => $stock_qty];
                $stock_model->where('inward_item_id', $inward_item_id)->set($stock_data)->update();
            } else {
                //echo 'else';exit;
                $auctionItemData = [
                    'inward_id' => $inward_id,
                    'inward_item_id' => $inward_item_id,
                    'auction_id' => $auction_id,
                    'qty' => $qty,
                    'sample_quantity' => $sample_quantity,
                    'garden_id' => $garden_id,
                    'created_by' => $session_user_id,
                ];

                $cart_model->insert($auctionItemData);

                $result = ProductLog::logProductAction($inward_item_id, 'Moved to Cart', $qty, $this->request->getVar('session_user_id'));

                $stock_detail = $stock_model->where('inward_item_id', $inward_item_id)->first();
                $stock_qty = $stock_detail['qty'] - $qty;
                $inwardItemModel->update($auctionItemData['inward_item_id'], ['is_addedtocart' => 1]);

                $stock_data = ['qty' => $stock_qty];
                $stock_model->where('inward_item_id', $inward_item_id)->set($stock_data)->update();

                if ($auction_garden_count1 <= 0) {
                    $garden_grade = $auctionGradeOrderModel->select("GROUP_CONCAT(grade_id ORDER BY order_seq ASC) AS grade_id")
                        ->where('garden_grade.garden_id', $this->request->getVar('garden_id')[$i])
                        ->first();

                    $auctionGardenOrderData = [
                        'order_seq' => $auction_garden_count + 1,
                        'garden_id' => $this->request->getVar('garden_id')[$i],
                        'center_id' => $this->request->getVar('center_id'),
                        'auction_id' => $this->request->getVar('auction_id'),
                        'garden_grade' => $garden_grade['grade_id'],
                    ];


                    $auctionGardenOrderModel->insert($auctionGardenOrderData);
                    $count++;
                }
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

    public function delete($id = null)
    {
        $model = new CartModel();
        $inwardItemModel = new InwardItemModel();
        $session_user_id = $this->request->getHeaderLine('Authorization1');

        $data = $model->find($id);
        // print_r($data);exit;
        if ($data) {
            $stock_model = new StockModel();
            $garden_id = $data['garden_id'];

            $existing_stock = $stock_model->where('inward_item_id', $data['inward_item_id'])->first();
            $existing_qty = $existing_stock['qty'];
            $inward_item_id = $data['inward_item_id'];
            $cart_qty = $existing_qty + $data['qty'];
            $stock_data = ['qty' => $cart_qty];
            $stock_model->where('inward_item_id', $inward_item_id)->set('qty', $cart_qty)->update();

            $result = ProductLog::logProductAction($inward_item_id, 'Moved to Stock', $cart_qty, $session_user_id);

            $inwardItemModel->update($inward_item_id, ['is_addedtocart' => 0]);

            $auction_stock = $model->where('id', $id)
                ->delete();

            $cart_count = $model->where('garden_id', $garden_id)->where('auction_id', $data['auction_id'])->countAllResults();

            // If count is less than or equal to 0, delete entries from auction_garden_order table
            if ($cart_count <= 0) {
                $auction_model = new AuctionGardenOrderModel();
                $auction_model->where('auction_id', $data['auction_id'])->where('garden_id', $garden_id)->delete();
            }
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted Successfully',
                'data' => $data
            ];

            return $this->respondDeleted($response);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function movetoAuction()
    {
        $auctionModel = new AuctionModel();
        $auctionItemModel = new AuctionItemModel();
        $inwardItemModel = new InwardItemModel();
        $auctiondata = $auctionModel->find($this->request->getVar('auction_id')[0]);

        //$auctionModel->where('id', $this->request->getVar('auction_id')[0])->set(['is_publish' => 1, 'end_time' => $end_time])->update();
        $auctionModel->where('id', $this->request->getVar('auction_id')[0])->set(['is_publish' => 1])->update();
        //echo '<pre>';print_r();exit;
        $m = 0;
        $f = 0;
        for ($i = 0; $i < count($this->request->getVar('inward_item_id')); $i++) {
            if ($auctiondata['type'] == 1) {
                $lot_inc = 1;
            } else {
                $lot_inc = 1001;
            }
           $lot_set = ceil(($i + 1) / 2);

            // print_r($lot_set);

            $inward_details = new InwardItemModel();
            $inward_details = $inward_details->where("id", $this->request->getVar('inward_item_id')[$i])->first();

            $auctionItemData = [
                'inward_invoice_id' => $this->request->getVar('invoice_id')[$i],
                'inward_item_id' => $this->request->getVar('inward_item_id')[$i],
                'lot_no' => ($lot_inc + $i),
                'lot_set' => $lot_set,
                'auction_id' => $this->request->getVar('auction_id')[0],
                'auction_quantity' => $this->request->getVar('qty')[$i],
                'sample_quantity' => $this->request->getVar('sample_quantity')[$i],
                'created_by' => $this->request->getVar('session_user_id'),
                'auction_each_net' => $this->request->getVar('auction_each_net')[$i],
                'grade_id' => $inward_details['grade_id']
            ];

            $auctionItemModel->insert($auctionItemData);
            $auctionItemId = $auctionItemModel->getInsertID();

            $result = ProductLog::logProductAction($this->request->getVar('inward_item_id')[$i], 'Moved to Auction Stock', $this->request->getVar('qty')[$i], $this->request->getVar('session_user_id'));

            $inwardItemModel->update($auctionItemData['inward_item_id'], ['is_assigned' => 1]);

            $auction_stock_model = new AuctionStockModel();
            $auction_stock_data = [
                'inward_id' => $this->request->getVar('invoice_id')[$i],
                'inward_item_id' => $this->request->getVar('inward_item_id')[$i],
                'auction_id' => $this->request->getVar('auction_id')[0],
                'auction_item_id' => $auctionItemId,
                'qty' => $this->request->getVar('qty')[$i],
            ];
            $auction_stock_model->insert($auction_stock_data);

            // $lastQuery = $auctionModel->getLastQuery();
            // echo "Last Query: " . $lastQuery . "<br>";exit;
            $m++;
        }



        // Prepare response
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Inserted Successfully'
        ];

        return $this->respondCreated($response);
    }

    public function auctiongardenlist()
    {
        $auction_id = $this->request->getVar('id');

        // $auction_id = $auction_data[0]->auction_id;
        // echo '<pre>';print_r('hii');exit;


        $model = new CenterGardenModel();
        $auctiongarden_model = new AuctionGardenOrderModel();
        $check_count = $auctiongarden_model->select('auction_garden_order.*,garden.name as garden_name')
            ->join('garden', 'garden.id = auction_garden_order.garden_id', 'left')
            ->where('auction_id', $auction_id)->findAll();
        if (isset($check_count) && count($check_count) > 0) {

            $data['center_garden'] = $check_count;
        } else {

            foreach ($auction_data as $key => $value) {
                $sql = "SELECT center_garden.*,garden.name AS garden_name 
                FROM center_garden
                LEFT JOIN garden ON center_garden.garden_id = garden.id
                WHERE garden_id IN (SELECT DISTINCT(inward.garden_id) 
                                    FROM cart
                                    LEFT JOIN inward ON cart.inward_id = inward.id 
                                    WHERE auction_id=?)
                AND center_id=(SELECT DISTINCT(inward.center_id) 
                               FROM cart
                               LEFT JOIN inward ON cart.inward_id = inward.id 
                               WHERE auction_id=? 
                               LIMIT 1)";

                $data['center_garden'] = $model->query($sql, [$value->auction_id, $value->auction_id])->getResult();

                // $lastQuery = $model->getLastQuery();
                // echo "Last Query: " . $lastQuery . "<br>";
                // exit;
            }
        }



        return $this->respond($data);
    }
    public function customSort($a, $b)
    {
        global $sequence;
        $posA = array_search($a["garden_id"], $sequence);
        $posB = array_search($b["garden_id"], $sequence);
        return $posA - $posB;
    }
}
