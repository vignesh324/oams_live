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
        // Get the CodeIgniter instance
        $logger = \Config\Services::logger();

        // Log the message as an error
        $logger->error('bid calculation initiated auctionItemId:' . $auction_item_id . ', ' . date('Y-m-d h:i:s'));


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
        
        $autoBidSql=$autobidModel->getLastQuery();
        $logger->error('autoBidSql:' . $autoBidSql . ', ' . date('Y-m-d h:i:s'));


        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];

        if (count($auto_bid) > 0) {
            // Fetch the highest bidding value
            $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
                ->orderBy('bid_price', 'DESC')
                ->limit(1)
                ->first();
            $maxPrices = array_unique(array_column($auto_bid, 'max_price'));
            rsort($maxPrices);
            $firstMaxPrice = (number_format(@$maxPrices[0], 2)) ? number_format(@$maxPrices[0], 2) : 0;
            $secondMaxPrice = (number_format(@$maxPrices[1], 2)) ? number_format(@$maxPrices[1], 2) : 0;
            //$reserve_price = $auction_item_detail['reverse_price'] ?? null;
            $highest_bid_price = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            $bid_type = 1;

            if ($highest_bid_price < $firstMaxPrice) {
                $logger->error('Error code 001. HBP:' . $highest_bid_price . ',HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                if ($highest_bid_price <= $reserve_price) {
                    $logger->error('Error code 002. HBP:' . $highest_bid_price . ',RP: ' . $reserve_price . ', ' . date('Y-m-d h:i:s'));

                    $bid_price = $reserve_price; //reserve price
                    $buyer_id = $auto_bid[0]['buyer_id'];
                } elseif ($highest_bid_price > $reserve_price) {
                    $logger->error('Error code 003. HBP:' . $highest_bid_price . ',RP: ' . $reserve_price . ', ' . date('Y-m-d h:i:s'));

                    if ($highest_bid_price < $secondMaxPrice) {
                        $logger->error('Error code 004. HBP:' . $highest_bid_price . ',SLHABP: ' . $secondMaxPrice . ', ' . date('Y-m-d h:i:s'));
                        if ($secondMaxPrice < $firstMaxPrice) {
                            $bid_price = $secondMaxPrice + $increment_amount;
                        } else {
                            $bid_price = $highest_bid_price + $increment_amount;
                        }
                        $buyer_id = $auto_bid[0]['buyer_id'];
                    } else {
                        $logger->error('Error code 005. HBP:' . $highest_bid_price . ',SLHABP: ' . $secondMaxPrice . ', ' . date('Y-m-d h:i:s'));

                        $bid_price = $highest_bid_price + $increment_amount;
                        $buyer_id = $bidding_value['buyer_id'];
                    }
                }
            } elseif ($highest_bid_price > $firstMaxPrice) {
                $logger->error('Error code 006. HBP:' . $highest_bid_price . ',HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
                $bid_type = 0;
            } elseif ($highest_bid_price == $firstMaxPrice) {
                $logger->error('Error code 007. HBP:' . $highest_bid_price . ',HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
                //Fifo Method
            }

            $bid_data = [
                'auction_item_id' => $auction_item_id,
                'buyer_id' =>  $buyer_id,
                'bid_price' => $bid_price,
                'bid_type' => $bid_type,
                'sq' => 1,
                'bq' => 1,
            ];

            $auctionBiddingModel->insert($bid_data);
        }
        $logger->error('bid calculation Ended auctionItemId:' . $auction_item_id . ', ' . date('Y-m-d h:i:s'));

        return true;
    }

    public function AuctionFloorInitial($auction_item_id)
    {
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
            if ($auto_bid['min_price'] != 0)
                $bidding_value = $auto_bid['min_price'];
        }
        return true;
    }
}
