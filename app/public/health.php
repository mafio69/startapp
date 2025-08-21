<?php
/**
 * Production Health Check Endpoint
 * Sprawdza wszystkie krytyczne komponenty systemu
 */

header('Content-Type: application/json');

$startTime = microtime(true);
$checks = [];
$overallStatus = 'healthy';

// 1. PHP Runtime Check
$checks['php'] = [
    'status' => 'healthy',
    'version' => PHP_VERSION,
    'memory_usage' => memory_get_usage(true),
    'memory_limit' => ini_get('memory_limit')
];

// 2. Environment Check
$environment = $_ENV['APP_ENV'] ?? $_SERVER['APP_ENV'] ?? 'unknown';
$checks['environment'] = [
    'status' => $environment !== 'unknown' ? 'healthy' : 'warning',
    'name' => $environment,
    'debug_mode' => ini_get('display_errors') ? 'on' : 'off'
];

// 3. File System Check
$logDir = '/var/log/app';
$appDir = '/var/www/html';

$checks['filesystem'] = [
    'status' => 'healthy',
    'log_directory' => [
        'path' => $logDir,
        'writable' => is_writable($logDir),
        'exists' => is_dir($logDir)
    ],
    'app_directory' => [
        'path' => $appDir,
        'readable' => is_readable($appDir),
        'exists' => is_dir($appDir)
    ]
];

if (!$checks['filesystem']['log_directory']['writable'] ||
    !$checks['filesystem']['app_directory']['readable']) {
    $checks['filesystem']['status'] = 'unhealthy';
    $overallStatus = 'unhealthy';
}

// 4. Extensions Check
$requiredExtensions = ['json', 'curl', 'mbstring', 'openssl'];
$loadedExtensions = get_loaded_extensions();

$checks['extensions'] = [
    'status' => 'healthy',
    'required' => [],
    'loaded_count' => count($loadedExtensions)
];

foreach ($requiredExtensions as $ext) {
    $isLoaded = in_array($ext, $loadedExtensions);
    $checks['extensions']['required'][$ext] = $isLoaded;

    if (!$isLoaded) {
        $checks['extensions']['status'] = 'unhealthy';
        $overallStatus = 'unhealthy';
    }
}

// 5. Database Check (jeśli używasz)
/*
try {
    $pdo = new PDO($dsn, $user, $pass);
    $checks['database'] = [
        'status' => 'healthy',
        'connection' => 'ok'
    ];
} catch (PDOException $e) {
    $checks['database'] = [
        'status' => 'unhealthy',
        'error' => 'Connection failed'
    ];
    $overallStatus = 'unhealthy';
}
*/

// 6. Services Check (Nginx, PHP-FPM)
$checks['services'] = [
    'status' => 'healthy',
    'php_fpm' => function_exists('fastcgi_finish_request') ? 'running' : 'unknown',
    'web_server' => $_SERVER['SERVER_SOFTWARE'] ?? 'unknown'
];

// Response Time
$responseTime = round((microtime(true) - $startTime) * 1000, 2);

// Final Response
$response = [
    'status' => $overallStatus,
    'timestamp' => date('c'),
    'environment' => $environment,
    'response_time_ms' => $responseTime,
    'checks' => $checks
];

// Set appropriate HTTP status
http_response_code($overallStatus === 'healthy' ? 200 : 503);

echo json_encode($response, JSON_PRETTY_PRINT | JSON_UNESCAPED_SLASHES);
?>
