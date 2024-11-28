<?php

namespace App\Helpers;

use App\Models\AuctionBiddingModel;
use App\Models\AutoBiddingModel;
use App\Models\AuctionItemModel;
use App\Models\SettingsModel;
use CodeIgniter\Database\BaseBuilder;

class AuctionBiddingHelper
{

    public static function auctionBiddingCalculate($auction_item_id, $reserve_price)
    {
        isset($reserve_price) ? $reserve_price : 0;
        // Fetch auto-bid data
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();
       
        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->where('max_price !=', 0)
            ->orderBy('max_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->distinct()
            ->findAll();

        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];

        if (count($auto_bid) > 0) {
            // Fetch the highest bidding value
            $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
                ->orderBy('bid_price', 'DESC')
                ->limit(1)
                ->first();
            $maxPrices = array_column($auto_bid, 'max_price');
            rsort($maxPrices);
            $firstMaxPrice = (number_format(@$maxPrices[0],2)) ? number_format(@$maxPrices[0],2) : 0;
            $secondMaxPrice = (number_format(@$maxPrices[1],2)) ? number_format(@$maxPrices[1],2) : 0;
            //$reserve_price = $auction_item_detail['reverse_price'] ?? null;
            $highest_bid_price = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            

            if($highest_bid_price < $firstMaxPrice)
            {
                
                if($highest_bid_price <= $reserve_price)
                {
                    $bid_price = 1;//reserve price
                    $buyer_id = $auto_bid[0]['buyer_id'];
                }
                elseif($highest_bid_price > $reserve_price)
                {
                    
                    if($highest_bid_price <= $secondMaxPrice)
                    {
                        
                        $bid_price = $secondMaxPrice+$increment_amount;
                        $buyer_id = $auto_bid[0]['buyer_id'];
                    }
                    else
                    {
                        $bid_price = $highest_bid_price;
                        $buyer_id = $bidding_value['buyer_id'];
                    }
                }
            }
            elseif($highest_bid_price > $firstMaxPrice)
            {
                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
            }
            elseif($highest_bid_price == $firstMaxPrice)
            {
                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
                //Fifo Method
            }

            $bid_data = [
                'auction_item_id' => $auction_item_id,
                'buyer_id' =>  $buyer_id,
                'bid_price' => $bid_price,
                'sq' => 1,
                'bq' => 1,
            ];

            $auctionBiddingModel->insert($bid_data);

        }
        return true;
    }

    public function AuctionFloorInitial($auction_item_id){
        isset($reserve_price) ? $reserve_price : 0;
        // Fetch auto-bid data
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->where('min_price !=', 0)
            ->orderBy('min_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->distinct()
            ->findAll();

        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];

        if (count($auto_bid) > 0) {
            if($auto_bid['min_price']!=0 )
                    $bidding_value = $auto_bid['min_price'];
        }
        return true;
    }
}
