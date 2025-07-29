<?php

header('Content-Type: application/json');

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
