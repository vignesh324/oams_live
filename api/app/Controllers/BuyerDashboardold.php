<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AreaModel;
use App\Models\AuctionModel;
use App\Models\AuctionItemModel;
use App\Models\CenterModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;


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
            // print_r($current_time);exit;
            $auctions = $auction_model->select('id')
                ->where('center_id', $center['id'])
                ->where('date', $current_date)
                ->where('start_time <=', $current_time)
                ->where('end_time >=', $current_time)
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

    public function getAuctionItemsByCenter($id = null)
    {
        $model = new AuctionModel();
        $current_date = date('Y-m-d');
        $current_time = date("H:i:s");

        $auction = $model->where('center_id', $id)
            ->where('date', $current_date)
            ->where('start_time <=', $current_time)
            ->where('end_time >=', $current_time)
            ->orderBy('id')
            ->findAll();

        foreach ($auction as &$a) {
            $auctionItemsModel = new AuctionItemModel();
            $auctionItems = $auctionItemsModel
                ->select('auction_items.*, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->join('inward', 'inward_items.inward_id = inward.id', 'left')
                ->join('garden', 'inward.garden_id = garden.id', 'left')
                ->join('grade', 'grade.id = inward_items.grade_id', 'left')
                ->where('auction_id', $a['id'])
                ->findAll();

            $a['auction_items'] = $auctionItems;
        }

        $data['auction'] = $auction;
        return $this->respond($data);
    }
    public function getAuctionItemDetails($id)
    {
        $auctionItemsModel = new AuctionItemModel();
        $auctionItems = $auctionItemsModel
            ->select('auction_items.*, inward_items.weight_net, inward_items.total_net, grade.name AS gradename, garden.name AS gardenname, inward_items.bag_type')
            ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
            ->join('inward', 'inward_items.inward_id = inward.id', 'left')
            ->join('garden', 'inward.garden_id = garden.id', 'left')
            ->join('grade', 'grade.id = inward_items.grade_id', 'left')
            ->where('auction_items.id', $id)
            ->find();
        $data['auction'] = $auctionItems;
        return $this->respond($data);
    }
}
