<?php

// WebSocketServer.php
use Ratchet\MessageComponentInterface;
use Ratchet\ConnectionInterface;


require __DIR__ . '/vendor/autoload.php'; // Adjust this path as necessary

class WebSocketServer implements MessageComponentInterface {
    protected $clients;
    protected $value = 0;

    public function __construct() {
        $this->clients = new \SplObjectStorage;
    }

    public function onOpen(ConnectionInterface $conn) {
        $this->clients->attach($conn);
        echo "New connection! ({$conn->resourceId})\n";
    }

    public function onMessage(ConnectionInterface $from, $msg) {
        
    $data = json_decode($msg, true);
    if (!empty($data)) {
        
        $dsn = 'mysql:host=localhost;dbname=oams';
        $username = 'root';
        $password = 'kLHg@$2654#';

        try {
            $pdo = new PDO($dsn, $username, $password);
            $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

            $stmt = $pdo->prepare('INSERT INTO auction_biddings (auction_item_id, buyer_id, bid_price, sq, bq) VALUES (?, ?, ?, ?, ?)');
            
            
            $auctionItemId = 1;
            $buyerId = $data['buyer_id']; 
            $bidPrice = $data['bid_value']; 
            $sq = 1; 
            $bq = 1;
            $stmt->bindParam(1, $auctionItemId);
            $stmt->bindParam(2, $buyerId);
            $stmt->bindParam(3, $bidPrice);
            $stmt->bindParam(4, $sq);
            $stmt->bindParam(5, $bq);
            
            $stmt->execute();
            echo "Data inserted successfully\n";
        } catch(PDOException $e) {
            echo "Error: " . $e->getMessage();
        }

        // Close the database connection
        $pdo = null;
    } else {
        echo "Invalid message format\n";
    }
    
    //$buyer_id = $data['buyer_id'];
    $bidPrice = $data[$bidPrice];
    $this->broadcast($bidPrice);
    }

    public function onClose(ConnectionInterface $conn) {
        $this->clients->detach($conn);
        echo "Connection {$conn->resourceId} has disconnected\n";
    }

    public function onError(ConnectionInterface $conn, \Exception $e) {
        echo "An error has occurred: {$e->getMessage()}\n";
        $conn->close();
    }

    public function broadcast($msg) {
        foreach ($this->clients as $client) {
            $client->send($msg);
        }
    }
   
}

$server = new Ratchet\App('91.108.110.12', 8080);
$server->route('/', new WebSocketServer, ['*']);
echo "WebSocket server started\n";
$server->run();
