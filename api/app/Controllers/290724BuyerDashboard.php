<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AreaModel;
use App\Models\BuyerModel;
use App\Models\AuctionModel;
use App\Models\BidTimingModel;
use App\Models\AutoBiddingModel;
use App\Models\AuctionItemModel;
use App\Models\CenterModel;
use App\Models\SettingsModel;
use App\Models\BuyerCatalogModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use App\Models\AuctionBiddingModel;
use CodeIgniter\Database\Query;


class BuyerDashboard extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session();
    }
    public function index()
    {
        $model = new CenterModel();

        $data['centers'] = $model->select('center.*')
            ->where('center.status', 1)
            ->orderBy('center.id')
            ->findAll();

        foreach ($data['centers'] as $key => &$center) {
            $auction_model = new AuctionModel();
            $auctionitem_model = new AuctionItemModel();
            $current_date = date('Y-m-d');
            $current_time = date("H:i:s");
            $auctions = $auction_model->select('id')
                ->where('center_id', $center['id'])
                ->where('date', $current_date)
                ->where('start_time <=', $current_time)
                ->where('status', 1)
                ->findAll();

            $auction_ids = array_column($auctions, 'id');
            if (!empty($auction_ids)) {
                $auction_items = $auctionitem_model->select('SUM(auction_items.auction_quantity) as total_auction_qty,category.name AS category_name,grade.type AS gradetype')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                    ->join('garden', 'inward.garden_id = garden.id', 'left')
                    ->join('category', 'garden.category_id = category.id', 'left')
                    ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                    ->whereIn('auction_id', $auction_ids)
                    ->groupBy('garden.category_id,grade.type')
                    ->findAll();
                $center['auctionItems'] = $auction_items;
            } else {

                $center['auctionItems'] = [];
            }
        }


        return $this->respond($data);
    }

    public function getAuctionItemsByCenter($id = null, $buyer_id)
    {
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date('H:i:s');

        $auction = $model->where('center_id', $id)
            ->where('date', $current_date)
            ->where('start_time <=', $current_time)
            ->where('status', 1)
            ->orderBy('id')
            ->findAll();

        foreach ($auction as &$a) {
            $auctionItemsModel = new AuctionItemModel();
            $auctionItems = $auctionItemsModel
                ->select(
                    'auction_items.*, auto_bidding.min_price, auto_bidding.max_price, auction_session_times.lot_set, auction_session_times.start_time, auction_session_times.end_time, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type, 
                        (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.buyer_id = ' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
                        (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
                        (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 1) AS settings'
                )
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('auto_bidding', 'auto_bidding.auction_item_id = auction_items.id AND auto_bidding.buyer_id = ' . $buyer_id, 'left')
                ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
                ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                ->join('garden', 'inward.garden_id = garden.id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->where('auction_items.auction_id', $a['id']);

            $orderExpression = 'CASE 
                    WHEN TIME(auction_session_times.start_time) <= CURTIME() AND TIME(auction_session_times.end_time) >= CURTIME() THEN 0 
                    ELSE 1 
                END';

            $auctionItems->orderBy($orderExpression)
                ->orderBy('auction_session_times.start_time', 'ASC');

            $results = $auctionItems->findAll();
            $a['auction_items'] = $results;
        }

        $data['auction'] = $auction;
        return $this->respond($data);
    }

    public function getAuctionItemDetails($id, $buyer_id)
    {
        $auctionItemsModel = new AuctionItemModel();

        // $auctionItems = $auctionItemsModel
        // ->select('auction_items.*, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
        // (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
        //     (SELECT status FROM buyer_catalog WHERE buyer_catalog.buyer_id='.$buyer_id.' AND buyer_catalog.auction_item_id = auction_items.id) AS is_catalog')
        // ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
        // ->join('inward', 'inward_items.inward_id = inward.id', 'left')
        // ->join('garden', 'inward.garden_id = garden.id', 'left')
        // ->join('grade', 'grade.id = inward_items.grade_id', 'left')
        //     ->join(
        //         '(SELECT ab.auction_item_id, 
        //          ab.bid_price, 
        //          ab.buyer_id, 
        //          b.name AS buyer_name 
        //   FROM auction_biddings ab 
        //   LEFT JOIN buyer b ON ab.buyer_id = b.id 
        //   WHERE ab.bid_price = (
        //       SELECT MAX(ab2.bid_price) 
        //       FROM auction_biddings ab2 
        //       WHERE ab2.auction_item_id = ab.auction_item_id
        //   ) 
        //   GROUP BY ab.auction_item_id) AS bid_info',
        //         'bid_info.auction_item_id = auction_items.id',
        //         'left'
        //     )
        //     ->where('auction_items.auction_id', $id)
        //     ->find();


        $auctionItems = $auctionItemsModel
            ->select('auction_items.*, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
        (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
            (SELECT status FROM buyer_catalog WHERE buyer_catalog.buyer_id=' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS is_catalog,bid_info.buyer_id, 
            bid_info.buyer_name AS highest_bidder_name,(SELECT min_price FROM auto_bidding WHERE auto_bidding.buyer_id=' . $buyer_id . ' AND auto_bidding.auction_item_id = auction_items.id) AS min_price,(SELECT max_price FROM auto_bidding WHERE auto_bidding.buyer_id=' . $buyer_id . ' AND auto_bidding.auction_item_id = auction_items.id) AS max_price')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
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
            )->where('auction_items.auction_id', $id)
            ->find();
        // $sql = $auctionItemsModel->getLastQuery();
        // echo $sql;
        // exit;
        $data['auction'] = $auctionItems;
        return $this->respond($data);
    }

    public function addtoCatalog()
    {
        $model = new BuyerCatalogModel();

        // Check if data already exists based on auction_item_id
        $existingData = $model->where('auction_item_id', $this->request->getVar('auction_item_id'))->where('buyer_id', $this->request->getVar('buyer_id'))->first();

        if ($this->request->getVar('is_checked') == 1) {
            if (!isset($existingData)) {
                $data = [
                    'auction_id' => $this->request->getVar('auction_id'),
                    'auction_item_id' => $this->request->getVar('auction_item_id'),
                    'buyer_id' => $this->request->getVar('buyer_id'),
                    'status' => 1
                ];
                $catalog = $model->insert($data);
                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Inserted',
                ];
            } else {
                $model->where('auction_item_id', $this->request->getVar('auction_item_id'))->where('buyer_id', $this->request->getVar('buyer_id'))->delete();

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Deleted',
                ];
            }
        } else {
            $model->where('auction_item_id', $this->request->getVar('auction_item_id'))->where('buyer_id', $this->request->getVar('buyer_id'))->delete();

            $response = [
                'status' => 200,
                'error' => false,
                'message' => 'Deleted',
            ];
        }

        return $this->respondCreated($response);
    }


    public function deleteCatalog()
    {
        $model = new BuyerCatalogModel();

        $auction_item_id = $this->request->getVar('auction_item_id');

        $data = $model->where('auction_item_id', $auction_item_id)->delete();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Deleted Successfully',
            'data' => $data
        ];

        return $this->respondDeleted($response);
    }

    public function getMyCatalogs($id, $buyer_id)
    {
        $auctionItemsModel = new BuyerCatalogModel();
        $auctionItems = $auctionItemsModel
            ->select('auction_items.*, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
                (SELECT status FROM buyer_catalog WHERE buyer_catalog.auction_item_id = auction_items.id AND buyer_catalog.buyer_id=' . $buyer_id . ') AS is_catalog,
                (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price')
            ->join('auction_items', 'auction_items.id = buyer_catalog.auction_item_id', 'left')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('buyer_catalog.auction_id', $id)
            ->where('buyer_catalog.buyer_id', $buyer_id)
            ->find();
        // $sql = $auctionItemsModel->getLastQuery();
        // echo $sql;
        $data['auction'] = $auctionItems;
        return $this->respond($data);
    }

    public function getAuctionItemsByAuction($id = null, $buyer_id)
    {
        //echo '<pre>';print_r("hh");exit;
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date("H:i:s");
        //echo 'Hiii';exit;

        $auction = $model->where('id', $id)->findAll();
        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select(
                'auction_items.*,auto_bidding.min_price,auto_bidding.max_price,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
        (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.buyer_id=' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
        (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
        (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auto_bidding', 'auto_bidding.auction_item_id = auction_items.id AND auto_bidding.buyer_id = ' . $buyer_id, 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.auction_id', $auction[0]['id'])
            ->where('auction_items.status', 1)
            ->orderBy('auction_items.id')
            ->groupBy('auction_items.id')
            ->limit(2);

        $current_auction = $auctionItems->get()->getResultArray();



        $auctionBiddingModel = new AuctionBiddingModel();


        foreach ($current_auction as $key => $auc) {
            $auction_biddings = $auctionBiddingModel->where('auction_item_id', $auc['id'])->countAllResults();
            if ($auction_biddings == 0) {
                $auction_item_id = $auc['id'];
                $auto_bidding = new AutoBiddingModel();
                $auto_bidding = $auto_bidding->where('auction_item_id', $auction_item_id)->where('min_price !=', 0)->orderBy('min_price')->findAll();
                if (count($auto_bidding) > 0 && $auction[0]['min_hour_over'] == 1) {
                    foreach ($auto_bidding as $key => $autobid) {
                        if ($autobid['min_price'] >= $auc['base_price']) {
                            $auctionBiddingModel = new AuctionBiddingModel();
                            $bid_data = [
                                'auction_item_id' => $autobid['auction_item_id'],
                                'buyer_id' => $autobid['buyer_id'],
                                'bid_price' => $autobid['min_price'],
                                'bid_type' => 1,
                                'sq' => 1,
                                'bq' => 1,
                            ];
                            $catalog = $auctionBiddingModel->insert($bid_data);
                        }
                    }
                }
            }
        }

        $auction = $model->where('id', $id)->findAll();
        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select(
                'auction_items.*,auction_items.lot_set AS auctLotSet,auto_bidding.min_price,auto_bidding.max_price,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
         (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.buyer_id=' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
         (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
         (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"current" AS auction_status'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auto_bidding', 'auto_bidding.auction_item_id = auction_items.id AND auto_bidding.buyer_id = ' . $buyer_id, 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.auction_id', $auction[0]['id'])->where('auction_items.status', 1)
            ->orderBy('auction_items.id')
            ->limit(2);
        $current_auction = $auctionItems->get()->getResultArray();




        $auctionItemsModel = new AuctionItemModel();
        $upcoming_auctions = $auctionItemsModel
            ->select(
                'auction_items.*,auto_bidding.min_price,auto_bidding.max_price,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
            (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.buyer_id=' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
            (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
            (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"pending" AS auction_status'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auto_bidding', 'auto_bidding.auction_item_id = auction_items.id AND auto_bidding.buyer_id = ' . $buyer_id, 'left')
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
                'auction_items.*,auto_bidding.min_price,auto_bidding.max_price,auction_session_times.lot_set,auction_session_times.start_time,auction_session_times.end_time,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
            (SELECT COUNT(buyer_catalog.id) FROM buyer_catalog WHERE buyer_catalog.buyer_id=' . $buyer_id . ' AND buyer_catalog.auction_item_id = auction_items.id) AS buyer_catalog_count,
            (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price,
            (SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 0,1) AS settings,"completed" AS auction_status'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('auto_bidding', 'auto_bidding.auction_item_id = auction_items.id AND auto_bidding.buyer_id = ' . $buyer_id, 'left')
            ->join('auction_session_times', 'auction_session_times.auction_item_id = auction_items.id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
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

    public function movetoclosed()
    {
        //echo $this->request->getVar('auction_id');exit;

        $auction_id = $this->request->getVar('auction_id');
        $lot_no = $this->request->getVar('lot_no');
        $auctionItemsModel = new AuctionItemModel();
        $autobidModel = new AutoBiddingModel();
        $settingModel = new SettingsModel();

        $fetch_last_completed = $auctionItemsModel
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.status', 1)
            ->orderBy('auction_items.id', "DESC")->first();


        $auctionItems = $auctionItemsModel
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.status', 1)
            ->where('auction_items.lot_set', $lot_no)
            ->orderBy('auction_items.id');



        $current_auction = $auctionItems->findAll();

        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];


        $auction_itmes_count = $auctionItemsModel->where('auction_items.auction_id', $auction_id)->countAllResults();
        foreach ($current_auction as $key => $value) {

            /**** Auto Bid check rules */

            $auto_bid = $autobidModel->where('auction_item_id', $value['id'])->where('max_price !=', 0)->orderBy('max_price', 'DESC')->orderBy('updated_at', 'ASC')->distinct()->findAll();
            // echo '<pre>';print_r($auto_bid);exit;

            if (count($auto_bid) > 0) {
                $auctionBiddingModel = new AuctionBiddingModel();
                $bidding_value =  $auctionBiddingModel->where('auction_item_id', $value['id'])->orderBy('bid_price', 'DESC')->limit(1)->first();

                $maxPrices = array_unique(array_column($auto_bid, 'max_price'));
                rsort($maxPrices);
                $firstMaxPrice = isset($maxPrices[0]) ? $maxPrices[0] : null;
                $secondMaxPrice = isset($maxPrices[1]) ? $maxPrices[1] : $bidding_value['bid_price'];
                $auto_bid_value =  $secondMaxPrice + $increment_amount;


                if (@$bidding_value['bid_price'] >= @$firstMaxPrice) {
                    $auto_bid_value =  $bidding_value['bid_price'];
                } else {
                    if ($bidding_value['bid_price'] >= $secondMaxPrice) {
                        // $temp_price =  $bidding_value['bid_price'] + $increment_amount;
                        // echo 'sec if';
                        if (($bidding_value['bid_price'] <= $value['base_price'])) {
                            // echo 'sf';
                            $auto_bid_value = $value['base_price'] + $increment_amount;
                        } else {
                            // echo 'sf2';
                            $auto_bid_value =  $bidding_value['bid_price'] + $increment_amount;
                        }
                    }
                    $bid_data = [
                        'auction_item_id' => $value['id'],
                        'buyer_id' => $auto_bid[0]['buyer_id'],
                        'bid_price' => $auto_bid_value,
                        'sq' => 1,
                        'bq' => 1,
                    ];
                    $catalog = $auctionBiddingModel->insert($bid_data);
                    // echo 'sec';
                }

                // echo '<pre>';
                // print_r($value['base_price']);
                // echo '-';
                // print_r($auto_bid_value);


                // echo '<pre>';
                // print_r($bid_data);
                // exit;


            }

            $update_data = array(
                'status' => 2
            );
            $auctionItems->where('id', $value['id'])->set($update_data)->update();
            $sql = $auctionItems->getLastQuery();


            $auction_itmes_count1 = $auctionItemsModel->where('auction_items.auction_id', $auction_id)->where('auction_items.status', 2)->countAllResults();

            if ($auction_itmes_count1 == $auction_itmes_count) {
                $auctmodel = new AuctionModel();
                //$auction_id = $this->request->getVar('auction_id');
                $update_data = [
                    'status' => 3
                ];
                $data = $auctmodel->update($auction_id, $update_data);

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


            /* End Auto Bid check */
        }


        $upcoming_auctions = $auctionItemsModel
            ->select('auction_items.*')
            ->where('auction_items.auction_id', $auction_id)
            ->where('auction_items.status', 1);
        $upcoming_auctions = $upcoming_auctions->countAllResults();

        $response = [
            'status' => 200,
            'error' => false,
            'message' => 'Deleted Successfully',
            'data' => [],
            'upcomingdata' => $upcoming_auctions,
        ];

        //return $this->respondDeleted($response);
        return $this->respond($response);
    }

    public function getmyBidbookByAuction($id = null, $buyer_id)
    {


        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select(
                'auction_items.*,inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type,
                (SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id) AS bid_price'
            )
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.auction_id', $id)
            ->having('(SELECT MAX(bid_price) FROM auction_biddings WHERE auction_biddings.auction_item_id = auction_items.id AND auction_biddings.buyer_id = ' . $buyer_id . ') = bid_price')
            ->findAll();
        // $sql = $auctionItemsModel->getLastQuery();
        //  echo $sql;





        $data['auction'] = $auctionItems;
        return $this->respond($data);
    }

    public function getBidTiming()
    {
        $model = new BidTimingModel();
        $data['auction'] = $model->orderBy('id', 'DESC')->first();
        return $this->respond($data);
    }


    public function profile($id = null)
    {
        $model = new BuyerModel();
        $data = $model->select('buyer.*, city.name as city_name, state.name as state_name, area.name as area_name')
            ->join('city', 'city.id = buyer.city_id', 'left')
            ->join('state', 'state.id = buyer.state_id', 'left')
            ->join('area', 'area.id = buyer.area_id', 'left')
            ->orderBy('buyer.id')
            ->where('buyer.id', $id)->where('buyer.status !=', 0)->first();
        if ($data) {
            return $this->respond($data);
        } else {
            return $this->failNotFound('No Data Found with id : ' . $id);
        }
    }

    public function profileUpdate()
    {
        $model = new BuyerModel();
        $id = $this->request->getVar('id');

        $rules = [
            'name' => 'required|regex_match[/^[a-zA-Z0-9][a-zA-Z0-9\s]*$/]|is_unique[buyer.name,id,' . $id . ']',
            'gst_no' => 'required',
            'address' => 'required',
        ];
        // echo '<pre>';print_r($rules);exit;

        $messages = [
            "name" => [
                "required" => "The Buyer Name field is required.",
                'is_unique' => 'The Buyer Name field must be unique.',
                'regex_match' => 'The Buyer Name field contains invalid characters.',
            ],
            "state_id" => [
                "required" => "The State field is required."
            ],
            "city_id" => [
                "required" => "The City field is required."
            ],
            "area_id" => [
                "required" => "The Area field is required."
            ],
            "gst_no" => [
                "required" => "The GST Number field is required."
            ],
            "fssai_no" => [
                "required" => "The FSSAI Number field is required."
            ],
            "address" => [
                "required" => "The Address field is required."
            ],
            "tea_board_no" => [
                "required" => "The Tea Board Number field is required."
            ],
            "email" => [
                "required" => "The Email field is required.",
                'is_unique' => 'The Email field must be unique.',
                "valid_email" => "Please enter a valid Email address."
            ],
            "contact_person_name" => [
                "required" => "The Contact Person Name field is required."
            ],
            "contact_person_number" => [
                "required" => "The Contact Person Number field is required.",
                "numeric" => "The Contact Person Number must be a number."
            ],
            'charges' => [
                'required' => 'The charges field is required.',
            ],
        ];

        if (!$this->validate($rules, $messages)) {
            $validationErrors = $this->validator->getErrors();
            return $this->fail($validationErrors, 422); // Bad request
        } else {

            $password = $this->request->getVar('password');

            $data = [
                'name' => $this->request->getVar('name'),
                'gst_no' => $this->request->getVar('gst_no'),
                'address' => $this->request->getVar('address'),
                'updated_by' => $this->request->getHeaderLine('Authorization1'),
            ];

            if ($password != '')
                $data['password'] = password_hash($password, PASSWORD_DEFAULT);

            $data1 = $model->find($id);

            if ($data1) {
                $model->update($id, $data);

                $response = [
                    'status' => 200,
                    'error' => false,
                    'message' => 'Updated Successfully',
                    'data' => $data
                ];

                return $this->respond($response);
            } else {
                return $this->failNotFound('No Data Found with id : ' . $id);
            }
        }
    }
}
