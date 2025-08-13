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
            $db_status = $pdo ? 'connected' : 'disconnected';
            echo json_encode(['status' => 'OK', 'database' => $db_status]);
        }
        break;

    case '/api/messages':
        if ($request_method === 'GET') {
            if ($pdo) {
                try {
                    // Create table if it doesn't exist
                    $pdo->exec("CREATE TABLE IF NOT EXISTS messages (
                        id SERIAL PRIMARY KEY,
                        content TEXT,
                        created_at TIMESTAMP DEFAULT CURRENT_TIMESTAMP
                    )");
                    
                    // Fetch messages from database
                    $stmt = $pdo->query("SELECT content FROM messages ORDER BY created_at DESC LIMIT 10");
                    $messages = $stmt->fetchAll(PDO::FETCH_COLUMN);
                    echo json_encode(['messages' => $messages ?: ['No messages in database yet']]);
                } catch(PDOException $e) {
                    error_log("Database query failed: " . $e->getMessage());
                    echo json_encode(['messages' => ['Database error occurred']]);
                }
            } else {
                echo json_encode(['messages' => ['Sample message 1', 'Sample message 2']]);
            }
        } elseif ($request_method === 'POST') {
            $data = json_decode(file_get_contents('php://input'), true);
            if ($pdo && isset($data['message'])) {
                try {
                    $stmt = $pdo->prepare("INSERT INTO messages (content) VALUES (?)");
                    $stmt->execute([$data['message']]);
                    echo json_encode(['message' => 'Message saved to Aurora PostgreSQL', 'data' => $data]);
                } catch(PDOException $e) {
                    error_log("Insert failed: " . $e->getMessage());
                    echo json_encode(['message' => 'Failed to save message', 'error' => 'Database error']);
                }
            } else {
                echo json_encode(['message' => 'Message received', 'data' => $data]);
            }
        }
        break;

    case '/api/chat':
        if ($request_method === 'POST') {
            echo json_encode(['response' => 'This is a simple chat response']);
        }
        break;
        
    case '/api/db-test':
        if ($request_method === 'GET') {
            if ($pdo) {
                try {
                    $stmt = $pdo->query("SELECT NOW() as current_time, version() as pg_version");
                    $result = $stmt->fetch(PDO::FETCH_ASSOC);
                    echo json_encode([
                        'status' => 'connected',
                        'database_time' => $result['current_time'],
                        'postgresql_version' => $result['pg_version']
                    ]);
                } catch(PDOException $e) {
                    echo json_encode(['status' => 'error', 'message' => 'Database query failed']);
                }
            } else {
                echo json_encode(['status' => 'disconnected', 'message' => 'No database connection']);
            }
        }
        break;

    default:
        http_response_code(404);
        echo json_encode(['error' => 'Not Found']);
        break;
}
