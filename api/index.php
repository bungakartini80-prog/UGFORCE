<?php

ini_set('display_errors', 1);
ini_set('display_startup_errors', 1);
error_reporting(E_ALL);

try {
    require __DIR__ . '/../public/index.php';
    if (file_exists('/tmp/laravel-error.txt')) {
        http_response_code(200);
        echo "<h1>Laravel Captured Exception:</h1>";
        echo "<pre>" . htmlspecialchars(file_get_contents('/tmp/laravel-error.txt')) . "</pre>";
        @unlink('/tmp/laravel-error.txt');
    }
} catch (\Throwable $e) {
    http_response_code(200);
    echo "<h1>Vercel Serverless Exception</h1>";
    if (file_exists('/tmp/laravel-error.txt')) {
        echo "<h2>Primary Exception (Logged):</h2>";
        echo "<pre>" . htmlspecialchars(file_get_contents('/tmp/laravel-error.txt')) . "</pre>";
        @unlink('/tmp/laravel-error.txt'); // Clean up
    }
    echo "<p><strong>Message:</strong> " . htmlspecialchars($e->getMessage()) . "</p>";
    echo "<p><strong>File:</strong> " . htmlspecialchars($e->getFile()) . " (Line " . $e->getLine() . ")</p>";
    echo "<h3>Stack Trace:</h3>";
    echo "<pre>" . htmlspecialchars($e->getTraceAsString()) . "</pre>";
}
