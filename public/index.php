<?php
require_once __DIR__ . '/../vendor/autoload.php';

$dotenvPath = dirname(__DIR__);
if (file_exists($dotenvPath . '/.env')) {
    $dotenv = Dotenv\Dotenv::createImmutable($dotenvPath);
    $dotenv->load();
}

$routes = require __DIR__ . '/../src/RoutesAPI.php';

$method = $_SERVER['REQUEST_METHOD'];
$uri = strtok($_SERVER['REQUEST_URI'], '?');

$basePath = '/projeto_unicamp/public';
if (str_starts_with($uri, $basePath)) {
    $uri = substr($uri, strlen($basePath));
}

foreach ($routes as $route) {
    [$routeMethod, $routeUri, $handler] = $route;
    if ($method === $routeMethod && $uri === $routeUri) {
        [$controllerClass, $action] = $handler;
        $controller = new $controllerClass();
        $controller->$action();
        exit;
    }
}

http_response_code(404);
echo json_encode(['error' => 'Route not found']);
