<?php

require 'session.php';

$users = require 'users.php';

// Get posted email & password
$username = $_POST['username'] ?? '';
$password = $_POST['password'] ?? '';

if (isset($users[$username]) && password_verify($password, $users[$username]['password'])) {

    // Use username as a custom session name
    session_name("session_$username"); // Unique session name for each user
    session_start(); // Start the session
    
    $_SESSION['username'] = $username;
    $_SESSION['role'] = $users[$username]['role'];

    echo json_encode(['message' => 'Login successful', 'role' => $_SESSION['role']]);
} else {
    http_response_code(401);
    echo json_encode(['error' => 'Invalid credentials']);
}
