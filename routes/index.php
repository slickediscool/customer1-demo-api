<?php

header('Content-Type: application/json');

// Database connection configuration
$db_host = 'eflow-demo-db-instance-1.cmx22ymccmu0.us-east-1.rds.amazonaws.com';
$db_port = '5432';
$db_name = 'postgres';
$db_user = 'postgres';
$db_password = 'Slickediscool34?'; //

// Create database connection
try {
    $dsn = "pgsql:host=$db_host;port=$db_port;dbname=$db_name";
    $pdo = new PDO($dsn, $db_user, $db_password);
    $pdo->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);
} catch(PDOException $e) {
    // Log error but don't expose database details to API users
    error_log("Database connection failed: " . $e->getMessage());
    // Continue without database for now
    $pdo = null;
}

$request_uri = $_SERVER['REQUEST_URI'];
$request_method = $_SERVER['REQUEST_METHOD'];

switch ($request_uri) {
    case '/health':
        if ($request_method === 'GET') {
            echo json_encode(['status' => 'OK']);
        }
        break;

    case '/api/messages':
        if ($request_method === 'GET') {
            echo json_encode(['messages' => ['Sample message 1', 'Sample message 2']]);
        } elseif ($request_method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            echo json_encode(['message' => 'Message received', 'data' => $data]);
        }
        break;

    case '/api/chat':
        if ($request_method === 'POST') {
            echo json_encode(['response' => 'This is a simple chat response']);
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}
