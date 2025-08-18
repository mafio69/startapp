<?php
// Set proper error reporting
error_reporting(E_ALL);
ini_set('display_errors', 1);

// Log start
$timestamp = date('Y-m-d H:i:s');
$logMessage = "[{$timestamp}] Cron job started" . PHP_EOL;

// Check if we can write to log directory
$logDir = '/var/log/app';
if (!is_writable($logDir)) {
    error_log("Cannot write to log directory: {$logDir}");
    exit(1);
}

// Write to custom log
file_put_contents('/var/log/app/custom-cron.log', $logMessage, FILE_APPEND | LOCK_EX);

try {
    // Your job logic here
    echo "Executing cron job at {$timestamp}\n";

    // Example: check file permissions
    $webDir = '/var/www/html';
    $owner = fileowner($webDir);
    $group = filegroup($webDir);

    $result = "Job completed successfully. Web dir owner: {$owner}, group: {$group}";
    error_log("[{$timestamp}] {$result}", 3, '/var/log/app/custom-cron.log');

} catch (Exception $e) {
    $error = "[{$timestamp}] Cron job error: " . $e->getMessage();
    error_log($error, 3, '/var/log/app/custom-cron.log');
    exit(1);
}
?>
