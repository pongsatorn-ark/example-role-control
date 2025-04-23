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
        session_write_close(); // write current session

        session_id($existingSession);
        session_start();
        session_destroy(); // kill old session

        session_id(null); // reset to generate new
    }

    session_start();
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $users[$username]['role'];

    $redis->set("user:session:$username", session_id());

    echo json_encode(['message' => 'Login successful', 'role' => $_SESSION['role']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
