<?php
require 'session.php';

$redis = new Redis();
$redis->connect('redis', 6379);

if (isset($_SESSION['username'])) {
    $redis->del("user:session:{$_SESSION['username']}");
}

session_destroy();

// Respond to the client
echo json_encode(['message' => 'Logged out successfully']);
