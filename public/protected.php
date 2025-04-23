<?php
require 'session.php';

// Check if user is logged in and has valid session
if (!isset($_SESSION['username'])) {
    http_response_code(401);
    echo json_encode(['error' => 'Unauthorized']);
    exit;
}

// A simple "database" (you can replace this with actual database queries)
$items = [
    1 => ['name' => 'Item 1', 'description' => 'This is the first item.'],
    2 => ['name' => 'Item 2', 'description' => 'This is the second item.']
];

// Handle CRUD based on the request method
$method = $_SERVER['REQUEST_METHOD'];
switch ($method) {
    case 'GET':
        // Role-based access
        switch ($_SESSION['role']) {
            case 'admin':
            case 'user':
            case 'viewer':
                echo json_encode($items);
                break;
            default:
                http_response_code(403);
                echo json_encode(['error' => 'Access denied. Unknown role.']);
                break;
        }
        break;

    case 'POST':
        // Create new item (only admin and user can create)
        if ($_SESSION['role'] == 'admin' || $_SESSION['role'] == 'user') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['name']) && isset($data['description'])) {
                $newId = count($items) + 1;
                $items[$newId] = ['name' => $data['name'], 'description' => $data['description']];
                echo json_encode(['message' => 'Item created', 'id' => $newId]);
            } else {
                http_response_code(400);
                echo json_encode(['error' => 'Bad Request: Missing parameters']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden: You do not have permission to create']);
        }
        break;

    case 'PUT':
        // Update existing item (only admin can update)
        if ($_SESSION['role'] == 'admin') {
            $data = json_decode(file_get_contents("php://input"), true);
            if (isset($data['id']) && isset($items[$data['id']])) {
                if (isset($data['name']) && isset($data['description'])) {
                    $items[$data['id']] = ['name' => $data['name'], 'description' => $data['description']];
                    echo json_encode(['message' => 'Item updated']);
                } else {
                    http_response_code(400);
                    echo json_encode(['error' => 'Bad Request: Missing parameters']);
                }
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found: Item does not exist']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden: You do not have permission to update']);
        }
        break;

    case 'DELETE':
        // Delete item (only admin can delete)
        if ($_SESSION['role'] == 'admin') {
            if (isset($_GET['id']) && isset($items[$_GET['id']])) {
                unset($items[$_GET['id']]);
                echo json_encode(['message' => 'Item deleted']);
            } else {
                http_response_code(404);
                echo json_encode(['error' => 'Not Found: Item does not exist']);
            }
        } else {
            http_response_code(403);
            echo json_encode(['error' => 'Forbidden: You do not have permission to delete']);
        }
        break;

    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method Not Allowed']);
        break;
}
