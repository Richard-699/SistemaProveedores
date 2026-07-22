<?php
session_start();
ob_start();

header('Content-Type: application/json; charset=utf-8');
header("Cache-Control: no-cache, must-revalidate");
header("Pragma: no-cache");
header("Expires: Sat, 26 Jul 1997 05:00:00 GMT");

try {
    session_unset();
    session_destroy();

    echo json_encode(['status' => 'success', 'redirect' => '../../View/Auth/index.php']);
    exit;
} catch (Exception $e) {
    echo json_encode(['status' => 'error', 'message' => $e->getMessage()]);
    exit;
}
