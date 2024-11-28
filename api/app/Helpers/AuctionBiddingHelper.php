<?php

namespace App\Helpers;

use App\Models\AuctionBiddingModel;
use App\Models\AutoBiddingModel;
use App\Models\AuctionItemModel;
use App\Models\SettingsModel;
use CodeIgniter\Database\BaseBuilder;

class AuctionBiddingHelper
{
    public static function calcUpcoming($auction_item_id)
    {
        $logger = \Config\Services::logger();
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();

        // Fetch auction item data
        $auctionItemData = $auctionItemModel->where('id', $auction_item_id)->first();

        // Fetch auto bids sorted by max_price and updated_at
        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->orderBy('max_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->findAll();

        if (empty($auto_bid)) {
            return true; // No autobids found, nothing to calculate
        }

        // Fetch settings for increment amount
        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'] ?? 0;

        // Fetch highest bidding value for this auction item
        $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
            ->orderBy('bid_price', 'DESC')
            ->first();

        $maxPrices = array_column($auto_bid, 'max_price');
        $minPrices = array_column($auto_bid, 'min_price');
        rsort($minPrices);
        rsort($maxPrices);

        $habp = $maxPrices[0] ?? 0;  // Highest auto bid price
        $shabp = $maxPrices[1] ?? 0; // Second highest auto bid price

        if ($bidding_value) {
            $mbp = $bidding_value['bid_price'];  // Max bidding price
            $buy_id = $bidding_value['buyer_id'];
        } elseif (!empty($minPrices) && $minPrices[0] > 0) {
            $mbp = $minPrices[0];
            $buy_id = $autobidModel->where('auction_item_id', $auction_item_id)
                ->orderBy('min_price', 'DESC')
                ->first()['buyer_id'];
        } else {
            $mbp = 0;
            $buy_id = 0;
        }

        $bp = $auctionItemData['base_price'] ?? 0;
        $rp = $auctionItemData['reverse_price'] ?? 0;
        $bid_type = 0; // Default to manual bid
        $hbp = 0;  // Highest bid price
        $buyer_id = 0;

        // Only manual bid exists
        if ($habp <= 0 && $mbp > 0) {
            $logger->error('Manual bid only');
            $hbp = $mbp;
            $buyer_id = $buy_id;
            $bid_type = 0; // Manual bid

            // Only auto bid exists
        } elseif ($habp > 0 && $mbp <= 0) {
            $logger->error('Auto bid only');
            if ($shabp > 0 && $shabp > $bp) {
                $logger->error('Auto bid only:1');

                $hbp = ($shabp == $habp) ? $shabp : $shabp + $increment_amount;
            } elseif ($habp > $bp) {
                $logger->error('Auto bid only:2');

                $hbp = $bp + $increment_amount;
            } else {
                $logger->error('Auto bid only:3');

                $hbp = $habp;
            }
            $buyer_id = $auto_bid[0]['buyer_id'];
            $bid_type = 1; // Auto bid

            // Both auto and manual bids exist
        } else {
            $logger->error('Both manual and auto bids exist');

            if ($mbp >= $habp) {
                // Manual bid exceeds or matches the highest auto bid
                $hbp = $mbp;
                $buyer_id = $buy_id;
            } elseif ($mbp > $shabp) {
                // Manual bid is higher than second highest auto bid
                if ($mbp == $habp) {
                    $hbp = $habp;
                } elseif ($mbp < $habp && $mbp < $bp && $bp < $habp) {
                    $hbp = $bp + $increment_amount;
                } else {
                    $hbp = $mbp + $increment_amount;
                }
                $buyer_id = $auto_bid[0]['buyer_id'];
                $bid_type = 1; // Auto bid
            } else {
                // Auto bid wins
                $hbp = ($shabp == $habp) ? $shabp : $shabp + $increment_amount;
                $buyer_id = $auto_bid[0]['buyer_id'];
                $bid_type = 1; // Auto bid
            }
        }

        // Prepare bid data
        $bid_data = [
            'auction_item_id' => $auction_item_id,
            'buyer_id' => $buyer_id,
            'bid_price' => $hbp,
            'bid_type' => $bid_type,
            'sq' => 1,
            'bq' => 1,
        ];

        // Insert new bid record
        $auctionBiddingModel->insert($bid_data);

        return true;
    }

    public static function calcLiveBidding($auction_item_id, $manual_bid_price, $flag)
    {
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();

        $auctionItemData = $auctionItemModel->where('id', $auction_item_id)->first();

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
                ->first();

            $maxPrices = array_column($auto_bid, 'max_price');
            rsort($maxPrices);
            $habp = (@$maxPrices[0]) ? $maxPrices[0] : 0;
            $shabp = (@$maxPrices[1]) ? $maxPrices[1] : 0;
            $mbp = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            $bp = (isset($auctionItemData['base_price'])) ? $auctionItemData['base_price'] : 0;

            $bid_type = 0;
            $hbp = 0;


            if ($mbp >= $habp) {   //edited
                $hbp = $mbp;
                $buyer_id = $bidding_value['buyer_id'];
                $bid_type = 0;
            } else if ($mbp < $habp) {
                if ($shabp == $habp) {
                    $hbp = $habp;
                    $buyer_id = $auto_bid[0]['buyer_id'];
                    $bid_type = 1;
                } else if ($shabp < $habp) {
                    if ($mbp >= $shabp) {    //edited
                        $hbp = $mbp + 1;
                        $buyer_id = $auto_bid[0]['buyer_id'];
                        $bid_type = 1;
                    } else if ($mbp < $shabp) {
                        $hbp = $shabp + 1;
                        $buyer_id = $auto_bid[0]['buyer_id'];
                        $bid_type = 1;
                    }
                }
            }

            $bid_data = [
                'auction_item_id' => $auction_item_id,
                'buyer_id' =>  $buyer_id,
                'bid_price' => $hbp,
                'bid_type' => $bid_type,
                'sq' => 1,
                'bq' => 1,
            ];
            $auctionItemData1 = $auctionBiddingModel
                ->where('auction_item_id', $auction_item_id)
                ->where('bid_type !=', 3)
                ->where('bid_price', $hbp)
                ->where('buyer_id', $buyer_id)
                ->countAllResults();
            if ($auctionItemData1 == 0) {
                $auctionBiddingModel->insert($bid_data);
            }
        }
        return true;
    }
    public static function calcAutoBidding($auction_item_id)
    {
        // return 'hiii';
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();

        $auctionItemData = $auctionItemModel->where('id', $auction_item_id)->first();

        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->where('max_price !=', 0)
            ->orderBy('max_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->findAll();

        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];

        if (count($auto_bid) > 0) {
            // Fetch the highest bidding value
            $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
                ->orderBy('bid_price', 'DESC')
                ->first();

            $maxPrices = array_column($auto_bid, 'max_price');
            rsort($maxPrices);
            $habp = (@$maxPrices[0]) ? $maxPrices[0] : 0;
            $shabp = (@$maxPrices[1]) ? $maxPrices[1] : 0;
            $mbp = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            $abp = (isset($bidding_value['auto_bid_price'])) ? $bidding_value['auto_bid_price'] : 0;
            $bp = (isset($auctionItemData['base_price'])) ? $auctionItemData['base_price'] : 0;
            $rp = (isset($auctionItemData['reverse_price'])) ? $auctionItemData['reverse_price'] : 0;

            $bid_type = 0;
            $hbp = 0;
            // echo 'mbp:' . $mbp;
            // echo 'habp:' . $habp;
            // echo 'shabp:' . $shabp;
            // echo 'bp:' . $bp;
            // echo 'abp:' . $abp;
            // echo 'rp:' . $rp;
            if ($auto_bid >= $habp) {
                echo '1';
                $hbp = $rp + $increment_amount;
                $buyer_id = $bidding_value['buyer_id'];
                $bid_type = 0;
            } else {
                echo '2';

                if ($abp >= $habp) {
                    echo '2.1';

                    $hbp = $mbp;
                    $buyer_id = $bidding_value['buyer_id'];
                    $bid_type = 0;
                } else {
                    echo '2.2';

                    $hbp = $habp + $increment_amount;
                    $buyer_id = $bidding_value['buyer_id'];
                    $bid_type = 0;
                }
            }

            $bid_data = [
                'auction_item_id' => $auction_item_id,
                'buyer_id' =>  $buyer_id,
                'bid_price' => $hbp,
                'bid_type' => $bid_type,
                'sq' => 1,
                'bq' => 1,
            ];
            // print_r($bid_data);
            // exit;
            $auctionBiddingModel->insert($bid_data);
        }
        return true;
    }

    public function UpcomingtoLive($current_auction)
    {

        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];

        $auctionBiddingModel = new AuctionBiddingModel();
        $bid_type = 0;
        $logger = \Config\Services::logger();
        foreach ($current_auction as $key => $auc) {
            $auction_biddings = $auctionBiddingModel->where('auction_item_id', $auc['id'])->countAllResults();
            if ($auction_biddings == 0) {
                $auction_item_id = $auc['id'];

                $auto_bidding = new AutoBiddingModel();
                $max_manual_bidding_amt = $auto_bidding->where('auction_item_id', $auction_item_id)->where('min_price !=', 0)->orderBy('min_price', 'DESC')->first();
                $max_auto_bidding_amt = $auto_bidding->where('auction_item_id', $auction_item_id)->where('max_price !=', 0)->orderBy('max_price', 'DESC')->first();
                $high_manual_price = (isset($max_manual_bidding_amt)) ? $max_manual_bidding_amt['min_price'] : 0;
                $high_auto_price = (isset($max_auto_bidding_amt)) ? $max_auto_bidding_amt['max_price'] : 0;


                if ($high_manual_price > 0 && $high_auto_price > 0) {
                    $logger->error('E001 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                    if ($high_manual_price == $high_auto_price) {
                        $logger->error('E002 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                        $bid_amt = $high_manual_price;
                        $buyers_id = $max_manual_bidding_amt['buyer_id'];
                        $bid_type = 0;
                    } elseif ($high_manual_price > $high_auto_price) {
                        $logger->error('E003 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                        $bid_amt = $high_manual_price;
                        $buyers_id = $max_manual_bidding_amt['buyer_id'];
                        $bid_type = 0;
                    } elseif ($high_manual_price < $high_auto_price) {
                        $logger->error('E004 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                        $bid_amt = $high_manual_price + $increment_amount;
                        $buyers_id = $max_auto_bidding_amt['buyer_id'];
                        $bid_type = 1;

                        //$max_manual_bidding_amt['min_price']+1//Highst auto bidden assign
                    }
                } elseif ($high_manual_price > 0) {
                    $logger->error('E005 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                    $bid_amt = $high_manual_price; //Assign higgest manul bidder
                    $buyers_id = $max_manual_bidding_amt['buyer_id'];
                    $bid_type = 0;
                } elseif ($high_auto_price > 0) {
                    $logger->error('E006 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                    $bid_amt = $auc['reverse_price']; //Assign to higgest auto bidder 
                    $buyers_id = $max_auto_bidding_amt['buyer_id'];
                    $bid_type = 1;
                } else {
                    $logger->error('E007 HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                    $bid_amt = 0;
                    $buyers_id = 0;
                    $bid_type = 0;
                }
                $logger->error('auction floor ended HBP:' . $high_manual_price . ', HABP:' . $high_auto_price . ', ' . date('Y-m-d h:i:s'));

                $auctionBiddingModel = new AuctionBiddingModel();
                if ($buyers_id != 0) {
                    $bid_data = [
                        'auction_item_id' => $auction_item_id,
                        'buyer_id' => $buyers_id,
                        'bid_price' => $bid_amt,
                        'bid_type' => $bid_type,
                        'sq' => 1,
                        'bq' => 1,
                    ];
                    $logger->error('auction start value:' . json_encode($bid_data) . ', ' . date('Y-m-d h:i:s'));

                    //echo '<pre>';print_r($bid_data);exit;
                    $auctionBiddingModel->insert($bid_data);
                }
            }
        }
    }

    public static function auctionBiddingCalculate($auction_item_id, $reserve_price)
    {
        // Get the CodeIgniter instance
        $logger = \Config\Services::logger();

        // Log the message as an error

        // Fetch auto-bid data
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();

        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->orderBy('max_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->distinct()
            ->findAll();

        // $autoBidSql=$autobidModel->getLastQuery();
        // $logger->error('autoBidSql:' . $autoBidSql . ', ' . date('Y-m-d h:i:s'));


        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];
        $logger->error('bid calculation initiated auctionItemId:' . $auction_item_id . ', ' . count($auto_bid));
        if (count($auto_bid) > 0) {
            // Fetch the highest bidding value
            $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
                ->where('bid_type !=', 3)
                ->orderBy('bid_price', 'DESC')
                ->limit(1)
                ->first();

            $logger->error('bidding value array:' .  json_encode($bidding_value));

            $maxPrices = array_column($auto_bid, 'max_price');
            rsort($maxPrices);
            $firstMaxPrice = (@$maxPrices[0]) ? $maxPrices[0] : 0;
            $secondMaxPrice = (@$maxPrices[1]) ? $maxPrices[1] : 0;
            $highest_bid_price = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            $reserve_price = isset($reserve_price) ? $reserve_price : 0;
            $bid_type = 0;


            if ($highest_bid_price < $firstMaxPrice) {
                $logger->error('Error code 001. HBP:' . $highest_bid_price . ', HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                if ($highest_bid_price <= $reserve_price) {
                    $logger->error('Error code 002. HBP:' . $highest_bid_price . ', RP: ' . $reserve_price . ', ' . date('Y-m-d h:i:s'));

                    // $bid_price = $reserve_price + $increment_amount;
                    $bid_price = $reserve_price;
                    if ($bid_price >= $firstMaxPrice) {
                        $bid_price = $firstMaxPrice;
                    }
                    $buyer_id = $auto_bid[0]['buyer_id'];
                    $bid_type = 1;
                } elseif ($highest_bid_price > $reserve_price) {
                    $logger->error('Error code 003. HBP:' . $highest_bid_price . ', RP: ' . $reserve_price . ', ' . date('Y-m-d h:i:s'));

                    if ($highest_bid_price < $secondMaxPrice) {
                        $logger->error('Error code 004. HBP:' . $highest_bid_price . ', SLHABP: ' . $secondMaxPrice . ', ' . date('Y-m-d h:i:s'));
                        if ($secondMaxPrice < $firstMaxPrice) {
                            $bid_price = $secondMaxPrice + $increment_amount;
                        } else {
                            $bid_price = $highest_bid_price;
                        }
                        $buyer_id = $auto_bid[0]['buyer_id'];
                        $bid_type = 1;
                    } else {
                        $logger->error('Error code 005. HBP:' . $highest_bid_price . ', SLHABP: ' . $secondMaxPrice . ', ' . date('Y-m-d h:i:s'));

                        $bid_price = $highest_bid_price;
                        $buyer_id = $bidding_value['buyer_id'];
                        $bid_type = 1;
                    }
                }

                // Log the buyer_id and bid_price
                $logger->info('Buyer ID: ' . $buyer_id . ', Bid Price: ' . $bid_price);
            } elseif ($highest_bid_price > $firstMaxPrice) {
                $logger->error('Error code 006. HBP:' . $highest_bid_price . ', HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
                $bid_type = 0;

                // Log the buyer_id and bid_price
                $logger->info('Buyer ID: ' . $buyer_id . ', Bid Price: ' . $bid_price);
            } elseif ($highest_bid_price == $firstMaxPrice) {
                $logger->error('Error code 007. HBP:' . $highest_bid_price . ', HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));

                $bid_price = $highest_bid_price;
                $buyer_id = $bidding_value['buyer_id'];
                // Fifo Method
                $bid_type = 0;

                // Log the buyer_id and bid_price
                $logger->info('Buyer ID: ' . $buyer_id . ', Bid Price: ' . $bid_price);
            } elseif ($firstMaxPrice == $secondMaxPrice) {
                $logger->error('Error code 007. HBP:' . $firstMaxPrice . ', HABP: ' . $secondMaxPrice . ', ' . date('Y-m-d h:i:s'));

                $bid_price = $highest_bid_price;
                $buyer_id = $auto_bid[0]['buyer_id'];
                // Fifo Method
                $bid_type = 0;

                // Log the buyer_id and bid_price
                $logger->info('Buyer ID: ' . $buyer_id . ', Bid Price: ' . $bid_price);
            } else {
                $logger->error('Error code ELSE. HBP:' . $highest_bid_price . ', HABP: ' . $firstMaxPrice . ', ' . date('Y-m-d h:i:s'));
            }


            $bid_data = [
                'auction_item_id' => $auction_item_id,
                'buyer_id' =>  $buyer_id,
                'bid_price' => $bid_price,
                'bid_type' => $bid_type,
                'sq' => 1,
                'bq' => 1,
            ];

            $auctionItemData1 = $auctionBiddingModel
                ->where('auction_item_id', $auction_item_id)
                ->where('bid_type !=', 3)
                ->where('bid_price', $bid_price)
                ->where('buyer_id', $buyer_id)
                ->countAllResults();
            if ($auctionItemData1 == 0) {
                $auctionBiddingModel->insert($bid_data);
            }
        }
        $logger->error('bid calculation Ended auctionItemId:' . $auction_item_id . ', ' . date('Y-m-d h:i:s'));

        return true;
    }

    public static function auctionBiddingCalculateWithoutInc($auction_item_id, $reserve_price)
    {
        // Get the CodeIgniter instance
        $logger = \Config\Services::logger();

        // Log the message as an error

        // Fetch auto-bid data
        $autobidModel = new AutoBiddingModel();
        $auctionBiddingModel = new AuctionBiddingModel();
        $auctionItemModel = new AuctionItemModel();

        $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
            ->orderBy('max_price', 'DESC')
            ->orderBy('updated_at', 'ASC')
            ->distinct()
            ->findAll();

        // $autoBidSql=$autobidModel->getLastQuery();
        // $logger->error('autoBidSql:' . $autoBidSql . ', ' . date('Y-m-d h:i:s'));


        $settingModel = new SettingsModel();
        $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
        $increment_amount = $settings['increment_amount'];
        $logger->error('bid calculation initiated auctionItemId:' . $auction_item_id . ', ' . count($auto_bid));
        if (count($auto_bid) > 0) {
            // Fetch the highest bidding value
            $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
                ->orderBy('bid_price', 'DESC')
                ->limit(1)
                ->first();

            $logger->error('bidding value array:' .  json_encode($bidding_value));

            $maxPrices = array_column($auto_bid, 'max_price');
            rsort($maxPrices);
            $firstMaxPrice = (@$maxPrices[0]) ? $maxPrices[0] : 0;
            $secondMaxPrice = (@$maxPrices[1]) ? $maxPrices[1] : 0;
            $highest_bid_price = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
            $reserve_price = isset($reserve_price) ? $reserve_price : 0;
            $bid_type = 0;


            if (isset($firstMaxPrice) && $highest_bid_price < $reserve_price && $firstMaxPrice > $reserve_price) {

                // Determine the bid price
                if (isset($secondMaxPrice) && $secondMaxPrice >= $reserve_price) {
                    // Set bid price based on second max price, considering increment
                    $bid_price = ($highest_bid_price < $secondMaxPrice)
                        ? ($secondMaxPrice == $firstMaxPrice ? $firstMaxPrice : $secondMaxPrice + $increment_amount)
                        : null;
                } else {
                    // If second max price is not set or does not meet conditions, set bid price to reserve price
                    $bid_price = $reserve_price;
                }

                // If bid price is determined, insert bid data
                if (isset($bid_price)) {
                    $bid_data = [
                        'auction_item_id' => $auction_item_id,
                        'buyer_id' => $auto_bid[0]['buyer_id'],
                        'bid_price' => $bid_price,
                        'bid_type' => $bid_type,
                        'sq' => 1,
                        'bq' => 1,
                    ];

                    $auctionBiddingModel->insert($bid_data);
                }
            }
        }

        return true;
    }

    // public static function calcAutoBiddingLive($auction_item_id)
    // {
    //     // return 'hiii';
    //     $autobidModel = new AutoBiddingModel();
    //     $auctionBiddingModel = new AuctionBiddingModel();
    //     $auctionItemModel = new AuctionItemModel();

    //     $auctionItemData = $auctionItemModel->where('id', $auction_item_id)->first();

    //     $auto_bid = $autobidModel->where('auction_item_id', $auction_item_id)
    //         ->where('max_price !=', 0)
    //         ->orderBy('max_price', 'DESC')
    //         ->orderBy('updated_at', 'ASC')
    //         ->findAll();

    //     $settingModel = new SettingsModel();
    //     $settings = $settingModel->orderBy('id', 'DESC')->limit(1)->first();
    //     $increment_amount = $settings['increment_amount'];

    //     if (count($auto_bid) > 0) {
    //         // Fetch the highest bidding value
    //         $bidding_value = $auctionBiddingModel->where('auction_item_id', $auction_item_id)
    //             ->orderBy('bid_price', 'DESC')
    //             ->first();

    //         $maxPrices = array_column($auto_bid, 'max_price');
    //         rsort($maxPrices);
    //         $habp = (@$maxPrices[0]) ? $maxPrices[0] : 0;
    //         $shabp = (@$maxPrices[1]) ? $maxPrices[1] : 0;
    //         $mbp = (isset($bidding_value['bid_price'])) ? $bidding_value['bid_price'] : 0;
    //         $abp = (isset($bidding_value['auto_bid_price'])) ? $bidding_value['auto_bid_price'] : 0;
    //         $bp = (isset($auctionItemData['base_price'])) ? $auctionItemData['base_price'] : 0;
    //         $rp = (isset($auctionItemData['reverse_price'])) ? $auctionItemData['reverse_price'] : 0;

    //         $bid_type = 0;
    //         $hbp = 0;
    //         echo 'mbp:' . $mbp;
    //         echo 'habp:' . $habp;
    //         echo 'shabp:' . $shabp;
    //         echo 'bp:' . $bp;
    //         echo 'abp:' . $abp;
    //         echo 'rp:' . $rp;
    //         if ($auto_bid >= $habp) {
    //             echo '1';
    //             $hbp = $bp + $increment_amount;
    //             $buyer_id = $bidding_value['buyer_id'];
    //             $bid_type = 0;
    //         } else {
    //             echo '2';

    //             if ($abp >= $habp) {
    //                 echo '2.1';

    //                 $hbp = $mbp;
    //                 $buyer_id = $bidding_value['buyer_id'];
    //                 $bid_type = 0;
    //             } else {
    //                 echo '2.2';

    //                 $hbp = $habp + $increment_amount;
    //                 $buyer_id = $bidding_value['buyer_id'];
    //                 $bid_type = 0;
    //             }
    //         }

    //         $bid_data = [
    //             'auction_item_id' => $auction_item_id,
    //             'buyer_id' =>  $buyer_id,
    //             'bid_price' => $hbp,
    //             'bid_type' => $bid_type,
    //             'sq' => 1,
    //             'bq' => 1,
    //         ];
    //         print_r($bid_data);
    //         exit;
    //         $auctionBiddingModel->insert($bid_data);
    //     }
    //     return true;
    // }


}
