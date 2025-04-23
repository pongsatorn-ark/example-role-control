<?php

$users = require 'users.php';

// Get posted email & password
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {

    // Use the username to generate a unique session ID for each user
    session_id();  // You can also use other unique identifiers, like user email or a random ID
    session_start();        // Start the session with the custom ID

    $_SESSION['username'] = $username;
    $_SESSION['role'] = $users[$username]['role'];

    echo json_encode(['message' => 'Login successful', 'role' => $_SESSION['role']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
