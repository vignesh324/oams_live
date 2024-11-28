<?php
$file = $_GET['file'];
$isManualSave = isset($_GET['isManualSave']) && $_GET['isManualSave'] == 'true' ? 1 : 0;
$startTime = time();

if (!file_exists($file)) {
	file_put_contents($file, $startTime);
	$response = ['startTime' => date('c', $startTime)];
} else {
	$content = file_get_contents($file);
	if ($content) {
		if ($file == 'session1_timer.txt' && $isManualSave == 1) {
			$content = $startTime;
			file_put_contents($file, $startTime);
			$response = ['startTime' => date('c', $content)];
		}else{
			$response = ['startTime' => date('c', $content)];
		}
	} else {
		file_put_contents($file, $startTime);
		$response = ['startTime' => date('c', $startTime)];
	}
}

header('Content-Type: application/json');
echo json_encode($response);
