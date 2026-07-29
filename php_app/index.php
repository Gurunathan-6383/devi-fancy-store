<?php
require_once __DIR__ . '/src/Core/helpers.php';

error_reporting(E_ALL);
ini_set('display_errors', env('APP_DEBUG', true) ? '1' : '0');

session_start();
require_once __DIR__ . '/src/Core/Database.php';
require_once __DIR__ . '/src/Core/Router.php';
require_once __DIR__ . '/src/Core/Request.php';
require_once __DIR__ . '/src/Core/Response.php';
require_once __DIR__ . '/src/Core/Middleware.php';
require_once __DIR__ . '/src/Core/JWT.php';
require_once __DIR__ . '/src/Middleware/AuthMiddleware.php';
require_once __DIR__ . '/src/Middleware/CustomerAuthMiddleware.php';

spl_autoload_register(function ($class) {
    $prefix = 'App\\';
    $base_dir = __DIR__ . '/src/';
    $len = strlen($prefix);
    if (strncmp($prefix, $class, $len) !== 0) return;
    $relative_class = substr($class, $len);
    $file = $base_dir . str_replace('\\', '/', $relative_class) . '.php';
    if (file_exists($file)) require $file;
});

$router = new \App\Core\Router();

require_once __DIR__ . '/routes.php';

$router->dispatch();
