<?php

declare(strict_types=1);

use Illuminate\Http\Request;

define('LARAVEL_START', microtime(true));

$root = dirname(__DIR__);
$tmp = '/tmp/laravel';

foreach (['views', 'sessions', 'cache', 'cache/data', 'app'] as $directory) {
    $path = $tmp.'/'.$directory;

    if (! is_dir($path)) {
        mkdir($path, 0777, true);
    }
}

$serverlessEnvironment = [
    'APP_CONFIG_CACHE' => $tmp.'/config.php',
    'APP_EVENTS_CACHE' => $tmp.'/events.php',
    'APP_PACKAGES_CACHE' => $tmp.'/packages.php',
    'APP_ROUTES_CACHE' => $tmp.'/routes.php',
    'APP_SERVICES_CACHE' => $tmp.'/services.php',
    'VIEW_COMPILED_PATH' => $tmp.'/views',
    'LOG_CHANNEL' => 'stderr',
    'LOG_STACK' => 'stderr',
    'CACHE_STORE' => 'array',
    'SESSION_DRIVER' => 'cookie',
    'APP_VERSION_STATE_PATH' => $tmp.'/app/system-version.json',
    'APP_VERSION_AUTO_INCREMENT' => 'false',
];

foreach ($serverlessEnvironment as $key => $value) {
    putenv("{$key}={$value}");
    $_ENV[$key] = $value;
    $_SERVER[$key] = $value;
}

if (file_exists($maintenance = $root.'/storage/framework/maintenance.php')) {
    require $maintenance;
}

require $root.'/vendor/autoload.php';

/** @var Illuminate\Foundation\Application $app */
$app = require_once $root.'/bootstrap/app.php';

$app->handleRequest(Request::capture());
