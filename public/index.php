<?php

use Illuminate\Foundation\Application;
use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

// Keep local/dev output clean while waiting for upstream vendor updates.
// Set APP_SHOW_DEPRECATIONS=1 to re-enable deprecated notices.
$showDeprecations = filter_var(
    $_SERVER['APP_SHOW_DEPRECATIONS'] ?? getenv('APP_SHOW_DEPRECATIONS') ?: '0',
    FILTER_VALIDATE_BOOLEAN
);

if (! $showDeprecations) {
    error_reporting(E_ALL & ~E_DEPRECATED & ~E_USER_DEPRECATED);
}

// Determine if the application is in maintenance mode...
if (file_exists($maintenance = __DIR__ . '/../storage/framework/maintenance.php')) {
    require $maintenance;
}

// Register the Composer autoloader...
require __DIR__ . '/../vendor/autoload.php';

// Bootstrap Laravel and handle the request...
/** @var Application $app */
$app = require_once __DIR__ . '/../bootstrap/app.php';

$app->handleRequest(Request::capture());
