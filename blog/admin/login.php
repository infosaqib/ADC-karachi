<?php
error_reporting(E_ALL);
ini_set('display_errors', 1);
header('Content-Type: application/json');

// Log incoming request
file_put_contents('debug.log', print_r($_POST, true));


include '../../config/config.php';

$data = json_decode(file_get_contents("php://input"), true);
$user = $data['username'];
$pass = $data['password'];

$stmt = $pdo->prepare("SELECT * FROM admin_users WHERE username = ? AND password = ?");
$stmt->execute([$user, $pass]);

if ($stmt->rowCount() > 0) {
    session_start();
    $_SESSION['loggedin'] = true;
    echo json_encode(['status' => 'success']);
} else {
    echo json_encode(['status' => 'fail']);
}