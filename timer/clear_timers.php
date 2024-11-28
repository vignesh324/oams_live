<?php
$min_hour_time = $_GET['flag'] ?? null;

if (isset($min_hour_time) && $min_hour_time == 1) {
    $files = ['session1_timer.txt', 'session2_timer.txt'];
} else {
    $files = ['min_bid_timer.txt', 'session1_timer.txt', 'session2_timer.txt'];
}
$success = true;
$errors = [];

foreach ($files as $file) {
    if (file_exists($file)) {
        if (file_put_contents($file, '') === false) {
            $success = false;
            $errors[] = "Failed to clear $file";
        }
    } else {
        $success = false;
        $errors[] = "$file does not exist";
    }
}

$response = ['success' => $success, 'errors' => $errors];

header('Content-Type: application/json');
echo json_encode($response);
