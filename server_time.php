<?php
// server_time.php
echo json_encode(['server_time' => round(microtime(true) * 1000)]);
?>