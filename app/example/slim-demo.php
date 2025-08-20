<?php
// Przykład integracji ze Slim Framework
use Slim\Factory\AppFactory;

require_once __DIR__ . '/../config/environment.php';
require_once __DIR__ . '/../config/logger.php';

$app = AppFactory::create();

$app->get('/', function ($request, $response) {
    Logger::info('Slim app started');
    $response->getBody()->write("Hello Slim + Docker!");
    return $response;
});

$app->run();

