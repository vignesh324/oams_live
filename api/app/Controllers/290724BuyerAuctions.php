<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\SettingsModel;
use App\Models\AutoBiddingModel;
use App\Models\AuctionBiddingModel;
use App\Models\AutoBidHistoryModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
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
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class BuyerAuctions extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
    public function upcomingAuctions()
    {
        //echo '<pre>';print_r("hhh");exit;
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date("H:i:s");
        $auctions = $model->select('auction.*, center.name AS center_name')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->where('auction.status', 1)
            ->where('auction.is_publish', 1)
            ->findAll();
        $data['auction'] = $auctions;
        return $this->respond($data);
    }

    public function completedAuctions()
    {
        //echo '<pre>';print_r("hhh");exit;
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date("H:i:s");
        $auctions = $model->select('auction.*, center.name AS center_name')
            ->join('center', 'center.id = auction.center_id', 'left')
            ->where('auction.status !=', 1)
            ->where('is_publish', 1)
            ->orderBy('id', 'DESC')
            ->findAll();
        $data['auction'] = $auctions;
        return $this->respond($data);
    }


    public function getAuctionItems($id = null)
    {

        $model = new AuctionItemModel();

        $auction = $model->select('auction_items.*,inward_items.weight_net,inward_items.total_net,grade.name AS gradename,garden.name AS gardenname,inward_items.bag_type')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_id', $id)->findAll();
        $data['auction'] = $auction;
        return $this->respond($data);
    }
    public function autoBidding()
    {
        $model = new AutoBiddingModel();
        $auto_bid_history = new AutoBidHistoryModel();

        $rules = [
            'min_price' => 'required|numeric',
            'max_price' => 'required|numeric',
        ];

        $messages = [
            'min_price' => [
                'required' => 'The Min Price is required.',
                'numeric' => 'The Min Price must be numeric.',
            ],
            'max_price' => [
                'required' => 'The Max Price is required.',
                'numeric' => 'The Max Price must be numeric.',
                'greater_than' => 'The Max Price field must contain a number greater than Min Price.',
            ],
        ];
        // print_r($rules['max_price']);exit;

        if (!$this->validate($rules, $messages)) {
            // Validation failed
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {
            if ($this->request->getVar('min_price') > $this->request->getVar('max_price')) {
                $validationErrors = ['max_price' => 'The Max Price field must contain a number greater than Min Price.'];
                return $this->fail($validationErrors, 422);
            } else {
                $data = [
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $this->request->getVar('auctionitem_id'),
                    'buyer_id' => $this->request->getVar('buyer_id'),
                    'min_price' => $this->request->getVar('min_price'),
                    'max_price' => $this->request->getVar('max_price'),
                ];
                // print_r($this->request->getVar('auction_id'));exit;
                $existing = $model->where([
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $this->request->getVar('auctionitem_id'),
                    'buyer_id' => $this->request->getVar('buyer_id'),
                ])->first();
                if ($existing) {
                    $model->where('auction_item_id', $this->request->getVar('auction_item_id'))
                        ->where('auction_id', $this->request->getVar('auction_id'))
                        ->where('buyer_id', $this->request->getVar('buyer_id'))
                        ->set($data)->update();

                    $auto_bid_history->insert($data);
                } else {
                    $model->insert($data);

                    $auto_bid_history->insert($data);
                }
                // Prepare and return response
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Updated Successfully'
                ];
                return $this->respond($response);
            }
        }
    }
    public function autoBiddingShow()
    {
        $model = new AutoBiddingModel();
        $data = $model->where([
            'auction_id' => $this->request->getVar('auction_id'),
            'auction_item_id' => $this->request->getVar('auctionitem_id'),
            'buyer_id' => $this->request->getVar('buyer_id'),
        ])->first();

        $auction_item = new AuctionItemModel();
        $data['auctionitem'] = $auction_item->find($this->request->getVar('auctionitem_id'));
        // print_r($data);exit;

        // $lastQuery = $model->getLastQuery();
        // echo "Last Query: " . $lastQuery . "<br>";exit;
        if ($data) {
            // print_r('hii');
            // exit;
            return $this->respond($data);
        } else {
            // print_r('bye');
            // exit;
            return $this->failNotFound('No Data Found with id');
        }
    }
    public function movetoReview()
    {

        $model = new AuctionModel();
        $auction_id = $this->request->getVar('auction_id');
        $update_data = [
            'status' => 3
        ];
        $data = $model->update($auction_id, $update_data);
        if ($data) {
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Inserted Successfully'
            ];
        } else {
            $response = [
                'status' => 404,
                'error' => false,
                'message' => 'Inserted Successfully'
            ];
        }
        return $this->respondCreated($response);
    }
    public function addMinAutoBidPrice()
    {
        $model = new AutoBiddingModel();
        $auctionItemsModel = new AuctionItemModel();

        $rules = [
            'buyer_id' => 'required',
            'auction_item_id' => 'required|numeric',
            'auction_id' => 'required|numeric',
            'min_price' => 'required|numeric',
        ];

        $messages = [
            'min_price' => [
                'required' => 'The Min Price is required.',
                'numeric' => 'The Min Price must be numeric.',
            ],
            'buyer_id' => [
                'required' => 'The Buyer is required.',
                'numeric' => 'The Buyer id must be numeric.',
            ],
            'auction_id' => [
                'required' => 'The Auction id is required.',
                'numeric' => 'The Auction id must be numeric.',
            ],
            'auction_item_id' => [
                'required' => 'The Auction item id is required.',
                'numeric' => 'The Auction item id must be numeric.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422);
        } else {

            $data = [
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
                'min_price' => $this->request->getVar('min_price'),
            ];

            $existing = $model->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
            ])->findAll();

            $auctionitem = $auctionItemsModel->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'id' => $this->request->getVar('auction_item_id'),
            ])->first();

            if ($this->request->getVar('min_price') <= $auctionitem['base_price']) {
                $validationErrors = ['min_price' => 'The Max Price field must contain a number greater than Min Price.'];
                return $this->fail($validationErrors, 422);
            }

            if ($existing) {
                $model->where([
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $this->request->getVar('auction_item_id'),
                    'buyer_id' => $this->request->getVar('buyer_id'),
                ])->set($data)->update();
            } else {
                // Insert new auction stock
                $model->insert($data);
            }

            // Prepare and return response
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully'
            ];
            return $this->respond($response);
        }
    }
    public function addMaxAutoBidPrice()
    {
        $model = new AutoBiddingModel();
        $auto_bid_history = new AutoBidHistoryModel();
        $auctionItemsModel = new AuctionItemModel();

        $rules = [
            'buyer_id' => 'required',
            'auction_item_id' => 'required|numeric',
            'auction_id' => 'required|numeric',
            'max_price' => 'required|numeric',
        ];

        $messages = [
            'max_price' => [
                'required' => 'The Max Price is required.',
                'numeric' => 'The Max Price must be numeric.',
            ],
            'buyer_id' => [
                'required' => 'The Buyer is required.',
                'numeric' => 'The Buyer id must be numeric.',
            ],
            'auction_id' => [
                'required' => 'The Auction id is required.',
                'numeric' => 'The Auction id must be numeric.',
            ],
            'auction_item_id' => [
                'required' => 'The Auction item id is required.',
                'numeric' => 'The Auction item id must be numeric.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422);
        } else {

            $data = [
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
                'max_price' => $this->request->getVar('max_price')
            ];
            $existing = $model->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
            ])->findAll();

            $auctionitem = $auctionItemsModel->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'id' => $this->request->getVar('auction_item_id'),
            ])->first();

            if ($this->request->getVar('max_price') <= $auctionitem['base_price']) {
                $validationErrors = ['max_price' => 'The Max Price field must contain a number greater than Min Price.'];
                return $this->fail($validationErrors, 422);
            }

            if ($existing) {
                $model->where('auction_item_id', $this->request->getVar('auction_item_id'))
                    ->where('auction_id', $this->request->getVar('auction_id'))
                    ->where('buyer_id', $this->request->getVar('buyer_id'))
                    ->set($data)->update();

                $auto_bid_history->insert($data);
            } else {
                $model->insert($data);

                $auto_bid_history->insert($data);
            }
            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully'
            ];
            return $this->respond($response);
        }
    }
    public function completeMinimumTime()
    {
        $model = new AuctionModel();
        $data = [
            'min_hour_over' => 1,
        ];
        $model->where('id', $this->request->getVar('id'))->set($data)->update();
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Updated Successfully'
        ];
        return $this->respond($response);
    }
    public function autoBidLog()
    {
        $auctionItemsModel = new AuctionItemModel();
        $autobidModel = new AutoBiddingModel();
        $settingModel = new SettingsModel();
        $auction_id = $this->request->getVar('id');
        $lot_no = $this->request->getVar('lot_set');
        // print_r($auction_id.'-'.$lot_no);exit;

        $auctionItems = $auctionItemsModel
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.status', 1)
            ->where('auction_items.min_bid_added', 0)
            ->where('auction_items.lot_set', $lot_no)
            ->orderBy('auction_items.id');

        $current_auction = $auctionItems->findAll();

        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];
        foreach ($current_auction as $key => $value) {
            $auto_bid = $autobidModel->where('auction_item_id', $value['id'])->orderBy('max_price', 'DESC')->distinct()->findAll();
            //echo '<pre>';print_r($auto_bid);exit;
            if ($auto_bid) {

                $maxPrices = array_column($auto_bid, 'max_price');
                //echo '<pre>';print_r($auto_bid);exit;
                rsort($maxPrices);
                $secondMaxPrice = isset($maxPrices[1]) ? $maxPrices[1] : null;
                $auto_bid_value =  $secondMaxPrice + $increment_amount;
                $auctionBiddingModel = new AuctionBiddingModel();

                $bidding_value =  $auctionBiddingModel->where('auction_item_id', $value['id'])->orderBy('bid_price', 'DESC')->limit(1)->first();

                if (@$bidding_value['bid_price'] > @$secondMaxPrice) {
                    $auto_bid_value =  $bidding_value['bid_price'] + $increment_amount;
                }

                $bid_data = [
                    'auction_item_id' => $value['id'],
                    'buyer_id' => $auto_bid[0]['buyer_id'],
                    'bid_price' => $auto_bid_value,
                    'sq' => 1,
                    'bq' => 1,
                ];
                $catalog = $auctionBiddingModel->insert($bid_data);
            }
            $update_data = array(
                'min_bid_added' => 1
            );
            $auctionItems->where('id', $value['id'])->set($update_data)->update();
        }

        $response = [
            'message' => 'Log Updated Successfully',
        ];
        return $this->respond($response);
    }
}
