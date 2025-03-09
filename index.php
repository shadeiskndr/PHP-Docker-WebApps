<?php
declare(strict_types=1);
session_start();

// Define base path for the application
define('BASE_PATH', __DIR__);

// Define routes and their corresponding files
$routes = [
    '/' => '/home.php',
    '/aircond' => 'calculator/aircond.php',
    '/bmi' => 'calculator/bmi.php',
    '/discount' => 'calculator/discount.php',
    '/num' => 'calculator/num.php',
    '/puteri' => 'calculator/puteri.php',
    '/speed' => 'calculator/speed.php',
    '/tax' => 'calculator/tax.php',
    '/integers' => 'calculator/integers.php',
    '/boutique' => 'crud/BoutiqueInventory/boutiqueInventory.php',
    '/movies/list' => 'crud/admin_listMovie.php',
    '/movies/add' => 'crud/admin_addMovie.php',
    '/cars/list' => 'crud/view_carList.php',
    '/cars/add' => 'crud/add_carManager.php',
    '/media/upload' => 'calculator/upload.php',
    '/media/gallery' => 'calculator/gallery.php',
];

// Get the requested route
$requestUri = parse_url($_SERVER['REQUEST_URI'], PHP_URL_PATH);
$requestUri = preg_replace('#/+#', '/', $requestUri);
// Handle trailing slashes while preserving root path
$requestUri = $requestUri === '/' ? '/' : rtrim($requestUri, '/');

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
