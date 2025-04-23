<?php

require 'session.php';

$users = require 'users.php';

$redis = new Redis();
$redis->connect('redis', 6379); // use "redis" as hostname from Docker Compose

// Get posted email & password
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {

    $existingSession = $redis->get("user:session:$username");

    if ($existingSession && $existingSession !== session_id()) {
        // Destroy previous session
        session_write_close(); // Save current (empty) session

        session_id($existingSession);
        session_start();
        session_destroy();

        session_write_close();

        // Start a fresh session
        session_id('');
        session_start();
    }

    $_SESSION['username'] = $username;
    $_SESSION['role'] = $users[$username]['role'];

    $redis->setex("user:session:$username", 3600, session_id()); // expire in 1 hour

    echo json_encode(['message' => 'Login successful', 'role' => $_SESSION['role']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
