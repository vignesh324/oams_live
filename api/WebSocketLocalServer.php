<?php

use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;
use Ratchet\Server\IoServer;
use Ratchet\Http\HttpServer;
use Ratchet\WebSocket\WsServer;

require __DIR__ . '/vendor/autoload.php';

date_default_timezone_set('Asia/Kolkata');

class WebSocketLocalServer implements MessageComponentInterface
{
    protected $clients;

    public function __construct()
    {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn)
    {
        // Store the new connection to send messages to later
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onClose(ConnectionInterface $conn)
    {
        // The connection is closed, remove it
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e)
    {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function onMessage(ConnectionInterface $from, $msg)
    {
        $data = json_decode($msg, true);
        // print_r($data);exit;
        if (!empty($data)) {
            $servername = 'localhost';
            $username = 'root';
            $password = 'kLHg@$2654#';
            $dbname = 'oams_staging';

            // Create connection
            $conn = new mysqli($servername, $username, $password, $dbname);

            // Check connection
            if ($conn->connect_error) {
                die("Connection failed: " . $conn->connect_error);
            }

            try {
                // Insert into auction_biddings table
                $auctionItemId = $data['auction_item_value'];
                $buyerId = $data['buyer_id'];
                $bidPrice = $data['bid_value'];
                $bidPlaceType = $data['bid_type'];
                $sq = 1;
                $bq = 1;

                if ($bidPlaceType == 1) {
                    $sql = "INSERT INTO auction_biddings (auction_item_id, buyer_id, bid_type, bid_price, sq, bq) 
                    VALUES ('$auctionItemId', '$buyerId', '0', '$bidPrice', '$sq', '$bq')";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Error: " . $conn->error;
                    }
                } else {
                    $sql = "INSERT INTO auction_biddings (auction_item_id, buyer_id, bid_type, bid_price, sq, bq) 
                    VALUES ('$auctionItemId', '$buyerId', '3', '$bidPrice', '$sq', '$bq')";
                    if ($conn->query($sql) !== TRUE) {
                        echo "Error: " . $conn->error;
                    }
                }
                $delete_sql = "DELETE FROM auto_bidding 
                                WHERE auction_item_id = '$auctionItemId' 
                                AND min_price = 0 
                                AND max_price = 0";
                $conn->query($delete_sql);

                $bid_value_sql = "SELECT * FROM auction_biddings WHERE auction_item_id ='$auctionItemId' AND bid_type !='3' ORDER BY bid_price DESC LIMIT 1";
                $bid_value_result = $conn->query($bid_value_sql);
                $bid_value_array = $bid_value_result->fetch_assoc();
                $bidvaluebidprice = $bid_value_array['bid_price'] ?? 0;



                // Auto bidding logic
                $auto_sql = "SELECT * FROM auto_bidding 
                             WHERE auction_item_id = '$auctionItemId' 
                             ORDER BY max_price DESC";
                $auto_result = $conn->query($auto_sql);


                $settings_sql = "SELECT increment_amount FROM settings ORDER BY id DESC LIMIT 1";
                $settings_result = $conn->query($settings_sql)->fetch_assoc();
                $increment_amount = isset($settings_result['increment_amount']) ? $settings_result['increment_amount'] : 0;

                // if ($auto_result->num_rows > 0) {
                $highestBidder = $auto_result->fetch_all(MYSQLI_ASSOC);
                $habp = isset($highestBidder[0]['max_price']) ? $highestBidder[0]['max_price'] : 0;
                $highest_bidder_id = isset($highestBidder[0]['buyer_id']) ? $highestBidder[0]['buyer_id'] : 0;
                $shabp = isset($highestBidder[1]['max_price']) ? $highestBidder[1]['max_price'] : 0;
                $secondhighest_bidder_id = isset($highestBidder[1]['buyer_id']) ? $highestBidder[1]['buyer_id'] : 0;

                // Determine bid values
                $mbp = $bidPrice;

                $auction_items_sql = "SELECT * FROM `auction_items` WHERE id='$auctionItemId' LIMIT 1";
                $auction_items_result = $conn->query($auction_items_sql);
                $auctionItemData = $auction_items_result->fetch_assoc();
                $bp = isset($auctionItemData['base_price']) ? $auctionItemData['base_price'] : 0;
                $rp = isset($auctionItemData['reverse_price']) ? $auctionItemData['reverse_price'] : 0;
                // print_r($bp);
                // exit;

                if ($bidPlaceType == 1) {
                    $bid_type_msg = "Manual";
                    // Manual Bid Logic
                    if ($mbp > $habp) {
                        $hbp = $mbp;
                        $buyer_id = $buyerId;
                        $bid_type = 0;
                        $msg_block = "Manual bid accepted: $hbp<br>";
                    } elseif ($mbp == $habp) {
                        $hbp = $habp;
                        $buyer_id = $highest_bidder_id;
                        $bid_type = 1;
                        $msg_block = "Auto bid accepted1: $hbp<br>";
                    } else {
                        if ($shabp == $habp) {
                            $hbp = $habp;
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Matching highest bid: $hbp<br>";
                        } elseif ($mbp >= $shabp) {
                            $hbp = $mbp + $increment_amount;
                            if ($hbp >= $habp) {
                                $hbp = $habp;
                            }
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Adjusting bid to: $hbp , $highest_bidder_id (above second highest)<br>";
                        } else {
                            $hbp = $shabp + $increment_amount;
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Adjusting bid to: $hbp (below second highest)<br>";
                        }
                    }
                    if ($mbp == $habp) {
                        $sql = "UPDATE auction_biddings 
                                SET buyer_id = '$buyer_id', bid_type = '$bid_type' 
                                WHERE auction_item_id = '$auctionItemId' AND bid_price = '$hbp' AND bid_type !=3";
                        if ($conn->query($sql) !== TRUE) {
                            echo "Error: " . $conn->error;
                        }
                        $msg_block = "mbp == habp: $mbp == $habp<br>";
                    } else {
                        $auction_items_sql1 = "SELECT * FROM `auction_biddings` WHERE auction_item_id='$auctionItemId' AND bid_type !='3' AND bid_price = '$hbp' AND buyer_id='$buyer_id'";
                        $auction_items_result1 = $conn->query($auction_items_sql1);
                        $auctionItemData1 = $auction_items_result1->num_rows;
                        $msg_last = $auction_items_sql1;
                        if ($auctionItemData1 == 0) {
                            $sql = "INSERT INTO auction_biddings (auction_item_id, buyer_id, bid_type, bid_price, sq, bq) 
                                VALUES ('$auctionItemId', '$buyer_id', '$bid_type', '$hbp', '$sq', '$bq')";
                            if ($conn->query($sql) !== TRUE) {
                                echo "Error: " . $conn->error;
                            }
                        }
                        $msg_block = "else block mbp == habp: $mbp == $habp<br>";
                    }
                } else {
                    // Auto Bid Logic
                    // print_r("auction_id: " . $auctionItemData['auction_id']);

                    // Prepare data for auto bidding and history
                    $data1 = [
                        'auction_id' => $auctionItemData['auction_id'],
                        'auction_item_id' => $auctionItemId,
                        'buyer_id' => $buyerId,
                        'max_price' => $bidPrice
                    ];
                    $data_set_str = implode(', ', array_map(fn($k, $v) => "$k = '$v'", array_keys($data1), $data1));

                    // Insert or update auto bidding
                    $result_existing = $conn->query("SELECT * FROM auto_bidding WHERE auction_item_id = '$auctionItemId' AND buyer_id = '$buyerId'");
                    if ($result_existing && $result_existing->num_rows > 0) {
                        $sql_update = "UPDATE auto_bidding SET $data_set_str WHERE auction_item_id = '$auctionItemId' AND buyer_id = '$buyerId'";
                        if (!$conn->query($sql_update)) {
                            echo "Error updating auto_bidding: " . $conn->error;
                        }
                    } else {
                        $sql_insert = "INSERT INTO auto_bidding SET $data_set_str";
                        if (!$conn->query($sql_insert)) {
                            echo "Error inserting into auto_bidding: " . $conn->error;
                        }
                    }

                    $sql_update_flag = "UPDATE auto_bid_history SET flag = 0 WHERE auction_item_id = '$auctionItemId' AND buyer_id = '$buyerId'";
                    if (!$conn->query($sql_update_flag)) {
                        echo "Error updating flag in auto_bid_history: " . $conn->error;
                    }
                    // Insert into auto bid history
                    if (!$conn->query("INSERT INTO auto_bid_history SET $data_set_str")) {
                        echo "Error inserting into auto_bid_history: " . $conn->error;
                    }

                    // Fetch highest and second highest auto bid prices
                    $auto_result1 = $conn->query($auto_sql);
                    $highestBidder1 = $auto_result1->fetch_all(MYSQLI_ASSOC);
                    $habp1 = $highestBidder1[0]['max_price'] ?? 0;
                    $shabp1 = $highestBidder1[1]['max_price'] ?? 0;
                    $highest_bidder_id = isset($highestBidder1[0]['buyer_id']) ? $highestBidder1[0]['buyer_id'] : 0;

                    $bid_type_msg = "Auto";

                    // Determine bid price and conditions
                    if ($habp1 <= $bp) {
                        $hbp = $mbp;
                        $buyer_id = $buyerId;
                        $bid_type = 1;
                        $msg_block = "Condition 2: shabp1 == 0 OR shabp1 < bp, hbp = $hbp, bp = $bp";
                    } elseif ($shabp1 == 0 || $shabp1 < $bp) {
                        if ($bidvaluebidprice >= $shabp1 && $bidvaluebidprice > $bp && $bidvaluebidprice < $habp1) {
                            $hbp =  $bidvaluebidprice + $increment_amount;
                            $buyer_id = $buyerId;
                            $bid_type = 1;
                            $msg_block = "Condition 1: bidvaluebidprice between shabp1 and habp1, hbp = $hbp";
                        } else {
                            $hbp = $bp + $increment_amount;
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Condition 2: shabp1 == 0 OR shabp1 < bp, hbp = $hbp, bp = $bp";
                        }
                    } elseif ($shabp1 < $bidvaluebidprice) {
                        if ($bidvaluebidprice >= $habp1) {
                            $hbp = $bidvaluebidprice;
                            $buyer_id = $buyerId;
                            $bid_type = 0;
                            $msg_block = "Condition 3: bidvaluebidprice >= habp1, hbp = $hbp";
                        } else {
                            $hbp = $bidvaluebidprice + $increment_amount;
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Condition 4: bidvaluebidprice < habp1, hbp = $hbp";
                        }
                    } elseif ($bidvaluebidprice == 0 || $bidvaluebidprice <= $bp) {
                        $hbp = ($shabp1 == $habp1) ? $habp1 : ($bp + $increment_amount);
                        $buyer_id = $highest_bidder_id;
                        $bid_type = 1;
                        $msg_block = "Condition 5: bidvaluebidprice == 0 OR <= bp, hbp = $hbp, bp = $bp";
                    } else {
                        if ($bidvaluebidprice >= $habp1) {
                            $hbp = $bidvaluebidprice;
                            $buyer_id = $buyerId;
                            $bid_type = 1;
                            $msg_block = "Condition 6: bidvaluebidprice >= habp1, hbp = $hbp";
                        } elseif ($bidvaluebidprice >= $shabp1) {
                            $hbp = $bidvaluebidprice + $increment_amount;
                            $buyer_id = $highest_bidder_id;
                            $bid_type = 1;
                            $msg_block = "Condition 7: bidvaluebidprice < habp1 AND >= shabp1, hbp = $hbp";
                        } else {
                            $hbp = $shabp1 + $increment_amount;
                            // Ensure hbp does not exceed habp1
                            if ($hbp >= $habp1) {
                                $hbp = $habp1;
                            }
                            $buyer_id = $highest_bidder_id;
                            if ($hbp == $shabp1) {

                                $fifo = $conn->query("SELECT * FROM auto_bid_history WHERE auction_item_id = '$auctionItemId' AND max_price='$hbp' AND flag=1 ORDER BY created_at LIMIT 1");
                                $fifo_array = $fifo->fetch_assoc();
                                $buyer_id = $fifo_array['buyer_id'];
                            }
                            $bid_type = 1;
                            $msg_block = "Condition 8: bidvaluebidprice < shabp1, hbp = $hbp,shbp =$buyer_id";
                        }
                    }

                    // Insert auto bid

                    $auction_items_sql1 = "SELECT * FROM `auction_biddings` WHERE auction_item_id='$auctionItemId' AND bid_type !='3' AND bid_price = '$hbp' AND buyer_id='$buyer_id'";
                    $auction_items_result1 = $conn->query($auction_items_sql1);
                    $auctionItemData1 = $auction_items_result1->num_rows;
                    $msg_last = $auctionItemData1;
                    if ($auctionItemData1 == 0) {
                        $auto_insert_sql = "INSERT INTO auction_biddings (auction_item_id, buyer_id, bid_price, bid_type, sq, bq) 
                        VALUES ('$auctionItemId', '$buyer_id', '$hbp', '$bid_type', '$sq', '$bq')";
                        if ($conn->query($auto_insert_sql) !== TRUE) {
                            echo "Error: " . $conn->error;
                        }
                    }
                }

                $auction_items_sql2 = "SELECT * FROM `auction_items` WHERE id='$auctionItemId'";
                $auction_items_result2 = $conn->query($auction_items_sql2);
                $auctionItemData2 = $auction_items_result2->fetch_assoc();
                
                $currentTime = date('Y-m-d H:i:s');

                $auction_update_last_log = "UPDATE auction 
                                            SET last_log_value = '{$currentTime}' 
                                            WHERE id = '{$auctionItemData2['auction_id']}'";
                $auction_update_last_log_result = $conn->query($auction_update_last_log);                
                
                $auction_update_last_log_sel = "SELECT * FROM auction
                                                WHERE id = '{$auctionItemData2['auction_id']}'";
                $auction_update_last_log_sel_result = $conn->query($auction_update_last_log_sel);
                $auction_update_last_log_sel_result_array = $auction_update_last_log_sel_result->fetch_assoc();
                
                // Get the latest bid after processing
                $response_sql = "SELECT * FROM auction_biddings WHERE auction_item_id = '$auctionItemId' AND bid_type !='3' ORDER BY bid_price DESC, created_at DESC LIMIT 1";
                $response_result = $conn->query($response_sql);
                $response_array = $response_result->fetch_assoc();

                // print_r($response_array);exit;

                // Fetch buyer name
                $buyer_sql = "SELECT name FROM buyer WHERE id =" . $response_array['buyer_id'];
                $buyer_result = $conn->query($buyer_sql);
                $buyer_name = '';
                if ($buyer_result->num_rows > 0) {
                    $buyer_name = $buyer_result->fetch_assoc()['name'];
                }

                // Set values for further processing
                $auctionItemId = $data['auction_item_value'];
                $buyerId = $response_array['buyer_id'];
                $bidPrice = $response_array['bid_price'];
                $bidPlaceType = $data['bid_type'];
            } catch (Exception $e) {
                echo "Error: " . $e->getMessage();
            }


            // Close the database connection
            $conn->close();
        } else {
            echo "Invalid message format\n";
        }



        $responseMessage = json_encode([
            'buyer_id' => $buyerId,
            'bid_value' => $bidPrice,
            'auction_item_value' => $auctionItemId,
            'buyer_name' => $buyer_name,
            'bid_type' => $bidPlaceType,
            'updated_time' => date("H:i:s"),
            'placed_bid_type' => $bid_type_msg,
            'bid_msg' => $msg_block,
            'msg_last' => $msg_last,
            'current_time' => $auction_update_last_log_sel_result_array['last_log_value'],
            'current_time_plus_10' => time() + 10,
            // 'bid_section' => $bid_section,
        ]);
        //print_r($responseMessage);
        // exit;
        // Broadcast the message to all connected clients
        foreach ($this->clients as $client) {
            //if ($from !== $client) {
            // The sender should not receive their own message
            $client->send("Message received: {$responseMessage}");
            //}
        }
    }
}

$server = IoServer::factory(
    new HttpServer(
        new WsServer(
            new WebSocketLocalServer()
        )
    ),
    8081
);

$server->run();
