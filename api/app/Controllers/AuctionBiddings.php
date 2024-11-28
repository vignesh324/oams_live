<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionBiddingModel;
use App\Models\AutoBidHistoryModel;
use App\Models\AuctionItemModel;
use App\Models\AuctionGardenOrderModel;
use App\Models\AuctionStockModel;
use App\Models\InwardItemModel;
use App\Models\InwardModel;
use App\Models\CenterGardenModel;
use App\Models\StockModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;


class AuctionBiddings extends ResourceController
{
    use ResponseTrait;
    protected $session; // Define session as a class property

    public function __construct()
    {
        $this->session = Services::session(); // Initialize session in the constructor
    }
   
    public function show($id = null)
    {
        $model = new AuctionBiddingModel();

        $auctionBiddings = $model->select('auction_biddings.*,buyer.name AS buyername')
            ->join('auction_items', 'auction_biddings.auction_item_id = auction_items.id', 'left')
            ->join('buyer', 'auction_biddings.buyer_id = buyer.id', 'left')
            ->where('auction_biddings.auction_item_id', $id)
            ->orderBy('auction_biddings.id','DESC')
            ->findAll();

        $data['auctionBiddings'] = $auctionBiddings;
        return $this->respond($data);
    }
    
    public function show1($id = null)
    {
        $model = new AutoBidHistoryModel();

        $auctionBiddings = $model->select('auto_bid_history.*,buyer.name AS buyername')
            ->join('auction_items', 'auto_bid_history.auction_item_id = auction_items.id', 'left')
            ->join('buyer', 'auto_bid_history.buyer_id = buyer.id', 'left')
            ->where('auto_bid_history.auction_item_id', $id)
            ->where('auto_bid_history.is_upcoming =', 1)
            ->orderBy('auto_bid_history.id','DESC')
            ->findAll();

        $data['auctionBiddings'] = $auctionBiddings;
        return $this->respond($data);
    }

    public function getItemsFinalize($id = null)
    {
        //
    }
}
