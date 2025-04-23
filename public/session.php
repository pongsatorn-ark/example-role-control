<?php
session_set_cookie_params([
    'lifetime' => 0,
    'path' => '/',
    'secure' => false, // set true if HTTPS
    'httponly' => true,
    'samesite' => 'Strict',
]);
session_start();
