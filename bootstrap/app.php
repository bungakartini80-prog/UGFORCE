<?php

use Illuminate\Foundation\Application;
use Illuminate\Foundation\Configuration\Exceptions;
use Illuminate\Foundation\Configuration\Middleware;

$app = Application::configure(basePath: dirname(__DIR__))
    ->withRouting(
        web: __DIR__.'/../routes/web.php',
        commands: __DIR__.'/../routes/console.php',
        health: '/up',
    )
    ->withMiddleware(function (Middleware $middleware): void {
        $middleware->trustProxies(at: '*');

        $middleware->validateCsrfTokens(except: [
            'api/face/*',
        ]);

        // Daftarkan alias middleware di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        //
    })
    ->create();

if (isset($_SERVER['VERCEL']) || isset($_ENV['VERCEL']) || isset($_SERVER['VERCEL_REGION'])) {
    $storagePath = '/tmp/storage';
    $folders = [
        $storagePath,
        $storagePath . '/app',
        $storagePath . '/app/public',
        $storagePath . '/framework',
        $storagePath . '/framework/cache',
        $storagePath . '/framework/cache/data',
        $storagePath . '/framework/sessions',
        $storagePath . '/framework/views',
        $storagePath . '/logs',
        $storagePath . '/bootstrap',
        $storagePath . '/bootstrap/cache',
    ];
    foreach ($folders as $folder) {
        if (!is_dir($folder)) {
            mkdir($folder, 0777, true);
        }
    }
    $app->useStoragePath($storagePath);

    // Redirect bootstrap cache files to writable /tmp
    $_ENV['APP_SERVICES_CACHE'] = $storagePath . '/bootstrap/cache/services.php';
    $_ENV['APP_PACKAGES_CACHE'] = $storagePath . '/bootstrap/cache/packages.php';
    $_ENV['APP_CONFIG_CACHE'] = $storagePath . '/bootstrap/cache/config.php';
    $_ENV['APP_ROUTES_CACHE'] = $storagePath . '/bootstrap/cache/routes-v7.php';
    $_ENV['APP_EVENTS_CACHE'] = $storagePath . '/bootstrap/cache/events.php';

    putenv("APP_SERVICES_CACHE={$storagePath}/bootstrap/cache/services.php");
    putenv("APP_PACKAGES_CACHE={$storagePath}/bootstrap/cache/packages.php");
    putenv("APP_CONFIG_CACHE={$storagePath}/bootstrap/cache/config.php");
    putenv("APP_ROUTES_CACHE={$storagePath}/bootstrap/cache/routes-v7.php");
    putenv("APP_EVENTS_CACHE={$storagePath}/bootstrap/cache/events.php");
}

return $app;