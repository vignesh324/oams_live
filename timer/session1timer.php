<?php
$file = $_GET['file'];
$startTime = time();

if (!file_exists($file)) {
    file_put_contents($file, $startTime);
} else {
    $content = file_get_contents($file);
	if ($content) {
		$startTime = $content;
	} else {
		file_put_contents($file, $startTime);
	}
}

$response = ['startTime' => date('c', $startTime)];

header('Content-Type: application/json');
echo json_encode($response);
?>
