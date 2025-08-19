<?php

require_once '../config/environment.php';
require_once '../config/logger.php';

// Auto-detect environment i ustaw odpowiednie zachowanie
if (Environment::isDevelopment()) {
    Logger::debug('Application started in DEVELOPMENT mode');
    echo "<h1>Development Mode</h1>";
    echo "<p>Xdebug: " . (extension_loaded('xdebug') ? 'Loaded' : 'Not loaded') . "</p>";
    echo "<p>Error display: " . (ini_get('display_errors') ? 'ON' : 'OFF') . "</p>";
    echo "<p>Log level: " . Environment::getLogLevel() . "</p>";
} else {
    Logger::warning('Application started in PRODUCTION mode');
    echo "<h1>Production Mode</h1>";
}

// Przykład logowania
Logger::debug('This debug message will only show in DEV');
Logger::warning('This warning shows in both DEV and PROD');
Logger::error('This error always shows');

phpinfo();
?>
