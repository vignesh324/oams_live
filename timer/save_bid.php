<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "auction_db";

$conn = new mysqli($servername, $username, $password, $dbname);

if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

$data = json_decode(file_get_contents('php://input'), true);
$id = $data['id'];
$minBid = $data['minBid'];
$maxBid = $data['maxBid'];

$sql = "UPDATE products SET min_bid = '$minBid', max_bid = '$maxBid' WHERE id = $id";

$response = [];
if ($conn->query($sql) === TRUE) {
    $response['success'] = true;
} else {
    $response['success'] = false;
    $response['error'] = $conn->error;
}

$conn->close();

header('Content-Type: application/json');
echo json_encode($response);
?>
