<?php
// Przykład REST API
require_once __DIR__ . '/../config/environment.php';
require_once __DIR__ . '/../config/logger.php';

header('Content-Type: application/json');

$method = $_SERVER['REQUEST_METHOD'];
$path = $_SERVER['REQUEST_URI'];

Logger::info("API call: $method $path");

switch($method) {
    case 'GET':
        echo json_encode(['status' => 'success', 'env' => Environment::isDevelopment() ? 'dev' : 'prod']);
        break;
    case 'POST':
        $input = json_decode(file_get_contents('php://input'), true);
        Logger::debug('POST data received', $input);
        echo json_encode(['received' => $input]);
        break;
    default:
        http_response_code(405);
        echo json_encode(['error' => 'Method not allowed']);
}
