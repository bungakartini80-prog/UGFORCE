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
        // Daftarkan alias middleware di sini
        $middleware->alias([
            'role' => \App\Http\Middleware\RoleMiddleware::class,
        ]);
    })
    ->withExceptions(function (Exceptions $exceptions): void {
        $exceptions->report(function (\Throwable $e) {
            header('Content-Type: text/plain', true, 500);
            echo "ACTUAL ORIGINAL EXCEPTION:\n";
            echo get_class($e) . " - " . $e->getMessage() . "\n";
            echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
            echo $e->getTraceAsString() . "\n";
            if ($prev = $e->getPrevious()) {
                echo "Previous Original Exception:\n";
                echo "  Class: " . get_class($prev) . "\n";
                echo "  Message: " . $prev->getMessage() . "\n";
                echo "  File: " . $prev->getFile() . ":" . $prev->getLine() . "\n";
                echo "  Trace:\n" . $prev->getTraceAsString() . "\n";
            }
            exit(1);
        });
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