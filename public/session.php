<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false,
    'httponly' => true,
    'samesite' => 'Strict'
]);
session_start();

// Start the session with the above parameters
session_start();

// Sample session management (e.g., login or other actions)
$_SESSION['username'] = 'exampleUser';
echo "Session started for user: " . $_SESSION['username'];
