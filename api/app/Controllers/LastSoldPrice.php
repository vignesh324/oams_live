<?php

namespace App\Controllers;

use CodeIgniter\RESTful\ResourceController;
use CodeIgniter\HTTP\Request;
use App\Models\AuctionModel;
use App\Models\GardenModel;
use App\Models\GradeModel;
use App\Models\AuctionItemModel;
use CodeIgniter\API\ResponseTrait;
use \Firebase\JWT\JWT;
use Config\Services;
use App\Helpers\AutoDelete;
use CodeIgniter\Database\Query;


class LastSoldPrice extends ResourceController
{
    use ResponseTrait;
    protected $session;

    public function __construct()
    {
        $this->session = Services::session();
    }
    public function lastSoldPrice()
    {
        $auctionitem_model = new AuctionItemModel();
        $grade_model = new GradeModel();

        // Fetch all active grades
        $grades = $grade_model->where('status !=', 0)->orderBy('id', 'ASC')->findAll();

        $currentDate = date('Y-m-d');
        $lastSoldPrices = [];

        // Fetch the latest auction items for each grade where the current date is greater than the auction date
        foreach ($grades as $grade) {
            $gradeId = $grade['id'];

            // Fetch the latest 3 auction items for the grade that have been sold
            $recentAuctionItems = $auctionitem_model->select('auction_items.id, auction_items.auction_id, auction_biddings.bid_price')
                ->join('auction_biddings', 'auction_biddings.auction_item_id = auction_items.id', 'left')
                ->join('auction', 'auction.id = auction_items.auction_id', 'left')
                ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                ->where('inward_items.grade_id', $gradeId)
                // ->where('auction_items.grade_id', $gradeId)
                ->where('auction.date <', $currentDate)
                ->where('auction_biddings.bid_type !=', 3)
                ->orderBy('auction.id', 'DESC')
                ->limit(3)
                ->get()
                ->getResultArray();

            if (!empty($recentAuctionItems)) {
                // The last sold price is the bid_price of the most recent auction item
                $lastSoldPrice = $recentAuctionItems[0]['bid_price'];
                $lastSoldPrices[$gradeId] = [
                    'auction_id' => $recentAuctionItems[0]['auction_id'],
                    'auction_item_id' => $recentAuctionItems[0]['id'],
                    'grade_id' => $gradeId,
                    'bid_price' => $lastSoldPrice
                ];

                // Fetch all auction items for the current grade that are still active
                $auctionItems = $auctionitem_model->select('auction_items.id')
                    ->join('inward_items', 'inward_items.id = auction_items.inward_item_id', 'left')
                    ->join('auction', 'auction.id = auction_items.auction_id', 'left')
                    ->where('inward_items.grade_id', $gradeId)
                    ->where('auction.status', 1) // Only active auctions
                    ->findAll();

                // Update last_sold_price for all auction items with the current grade ID
                foreach ($auctionItems as $auctionItem) {
                    $auctionitem_model->where('id', $auctionItem['id'])
                        ->set('last_sold_price', $lastSoldPrice)
                        ->update();
                }
            }
        }

        // Prepare the output in the desired format
        $data['lastSoldPrices'] = array_values($lastSoldPrices);

        return $this->respond($data);
    }
}
