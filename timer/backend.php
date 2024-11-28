<?php
$servername = "localhost";
$username = "root";
$password = "";
$dbname = "oams_staging";

// Create connection
$conn = new mysqli($servername, $username, $password, $dbname);

// Check connection
if ($conn->connect_error) {
    die("Connection failed: " . $conn->connect_error);
}

// Fetch products
$sql = "SELECT id, name, price, status FROM products";
$result = $conn->query($sql);

$products = [];
if ($result->num_rows > 0) {
    while ($row = $result->fetch_assoc()) {
        $products[] = $row;
    }
}

// Get current server time
$serverTime = time();

$response = [
    "products" => $products,
    "serverTime" => $serverTime
];

header('Content-Type: application/json');
echo json_encode($response);

$conn->close();
?>
