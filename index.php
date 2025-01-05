<?php
declare(strict_types=1);
session_start();

// Define base path for the application
define('BASE_PATH', __DIR__);

// Define routes and their corresponding files
$routes = [
    '/' => '/home.php',
    '/calculator/aircond' => 'calculator/aircond.php',
    '/calculator/bmi' => 'calculator/bmi.php',
    '/calculator/discount' => 'calculator/discount.php',
    '/calculator/num' => 'calculator/num.php',
    '/calculator/puteri' => 'calculator/puteri.php',
    '/calculator/speed' => 'calculator/speed.php',
    '/calculator/tax' => 'calculator/tax.php'
];

// Get the requested route
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);

// Security headers
header("Content-Security-Policy: default-src 'self'; style-src 'self' 'unsafe-inline';");
header("X-Frame-Options: DENY");
header("X-XSS-Protection: 1; mode=block");
header("X-Content-Type-Options: nosniff");

// Include database connection
require_once BASE_PATH . '/includes/db.php';

// Route the request
if (isset($routes[$requestUri])) {
    include BASE_PATH . '/' . $routes[$requestUri];
} else {
    http_response_code(404);
    include BASE_PATH . '/404.php';
}
