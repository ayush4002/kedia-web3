<?php

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Locate Laravel application root (supports standard & cPanel separated public_html structures)
$appPath = __DIR__ . '/..';
$candidates = [
    __DIR__ . '/..',
    __DIR__ . '/../kedia',
    __DIR__ . '/../kedia-web3',
    __DIR__ . '/../laravel',
    __DIR__ . '/../laravel_app',
    __DIR__ . '/../app_core',
];

foreach ($candidates as $dir) {
    if (file_exists($dir . '/vendor/autoload.php') && file_exists($dir . '/bootstrap/app.php')) {
        $appPath = $dir;
        break;
    }
}

// Maintenance mode check...
if (file_exists($maintenance = $appPath . '/storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register Composer autoloader...
require_once $appPath . '/vendor/autoload.php';

// Bootstrap Laravel and handle request...
(require_once $appPath . '/bootstrap/app.php')
    ->handleRequest(Request::capture());
