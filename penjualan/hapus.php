<?php
/**
 * @var $connection PDO
 */
include 'koneksi.php';
$reply = [
    'status' => false,
    'error' => '',
    'data' => []
];

/*
 * Validate http method
 */
if($_SERVER['REQUEST_METHOD'] !== 'POST'){
    header('Content-Type: application/json');
    http_response_code(400);
    $reply['error'] = 'POST method required';
    echo json_encode($reply);
    exit();
}
$reply['error'] = 'Sorry....';
header('Content-Type: application/json');
echo json_encode($reply);