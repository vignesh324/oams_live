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

use App\Helpers\AuctionBiddingHelper;



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
        $auto_bid_history = new AutoBidHistoryModel();
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

            $existing_history = $auto_bid_history->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
            ])
                ->orderBy('created_at', 'DESC')
                ->first();

            $maxPrice = 0;
            if (isset($existing_history)) {
                if ($existing_history['status'] != 0) {
                    $maxPrice = $existing_history['max_price'];
                }
            }

            $data1 = [
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
                'min_price' => $this->request->getVar('min_price'),
                'max_price' => $maxPrice,
                'is_upcoming'=> 1
            ];

            $auto_bid_history->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('buyer_id', $this->request->getVar('buyer_id'))
                ->set('flag', 0)
                ->update();

            $auto_bid_history->insert($data1);

            $model->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('min_price', 0)
                ->where('max_price', 0)
                ->delete();

            $auto_bid_history->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('min_price', 0)
                ->where('max_price', 0)
                ->delete();

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
            // 'max_price' => 'required|numeric',
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
                'max_price' => $this->request->getVar('max_price') ?? 0
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

            if ($this->request->getVar('max_price') > 0 && $this->request->getVar('max_price') <= $auctionitem['base_price']) {
                $validationErrors = ['max_price' => 'The Max Price field must contain a number greater than Min Price.'];
                return $this->fail($validationErrors, 422);
            }

            if ($existing) {
                $model->where('auction_item_id', $this->request->getVar('auction_item_id'))
                    ->where('auction_id', $this->request->getVar('auction_id'))
                    ->where('buyer_id', $this->request->getVar('buyer_id'))
                    ->set($data)->update();
            } else {
                $model->insert($data);
            }

            $existing_history = $auto_bid_history->where([
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
            ])
                ->orderBy('created_at', 'DESC')
                ->first();

            $minPrice = 0;
            if (isset($existing_history)) {
                if ($existing_history['status'] != 0) {
                    $minPrice = $existing_history['min_price'];
                }
            }
            $data1 = [
                'auction_id' => $this->request->getVar('auction_id'),
                'auction_item_id' => $this->request->getVar('auction_item_id'),
                'buyer_id' => $this->request->getVar('buyer_id'),
                'min_price' => $minPrice,
                'max_price' => $this->request->getVar('max_price'),
                'is_upcoming'=> 1
            ];
            $auto_bid_history->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('buyer_id', $this->request->getVar('buyer_id'))
                ->set('flag', 0)
                ->update();
            $auto_bid_history->insert($data1);

            $model->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('min_price', 0)
                ->where('max_price', 0)
                ->delete();

            $auto_bid_history->where('auction_item_id', $this->request->getVar('auction_item_id'))
                ->where('min_price', 0)
                ->where('max_price', 0)
                ->delete();

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Updated Successfully'
            ];
            return $this->respond($response);
        }
    }
    public function deleteBidData()
    {
        $auctionItemId = $this->request->getVar('auction_item_id');
        $auctionId = $this->request->getVar('auction_id');
        $buyerId = $this->request->getVar('buyer_id');

        // Load the necessary models
        $model = new AutoBiddingModel();
        $auto_bid_history = new AutoBidHistoryModel();

        // Delete records from AutoBiddingModel based on provided parameters
        $model->where('auction_item_id', $auctionItemId)
            ->where('auction_id', $auctionId)
            ->where('buyer_id', $buyerId)
            ->delete();

        // Fetch all matching auto bid history records in an array
        // $bidHistoryRecords = $auto_bid_history
        //     ->where('auction_item_id', $auctionItemId)
        //     ->where('auction_id', $auctionId)
        //     ->where('buyer_id', $buyerId)
        //     ->findAll();

        $existing_history = $auto_bid_history->where([
            'auction_id' => $this->request->getVar('auction_id'),
            'auction_item_id' => $this->request->getVar('auction_item_id'),
            'buyer_id' => $this->request->getVar('buyer_id'),
            'status' => 1
        ])
            ->orderBy('created_at', 'DESC')
            ->first();


        if (!empty($existing_history)) {
            $updateData = ['status' => 0];
            $auto_bid_history->update($existing_history['id'], $updateData);
        }
        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Updated Successfully'
        ];
        return $this->respond($response);
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

            //AuctionBiddingHelper::auctionBiddingCalculate($value['id'],$value['reverse_price']);

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
