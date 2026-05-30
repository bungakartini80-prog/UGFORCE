<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    echo "Attempting to require vendor/autoload.php...\n";
    require __DIR__ . '/../vendor/autoload.php';
    echo "Loaded autoload successfully!\n";

    echo "Attempting to require bootstrap/app.php...\n";
    $app = require __DIR__ . '/../bootstrap/app.php';
    echo "Loaded bootstrap successfully!\n";
    
    echo "App base path: " . $app->basePath() . "\n";
} catch (\Throwable $e) {
    echo "CAUGHT EXCEPTION:\n";
    echo $e->getMessage() . "\n";
    echo $e->getTraceAsString() . "\n";
}
