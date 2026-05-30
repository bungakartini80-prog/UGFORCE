<?php
require __DIR__ . '/../vendor/autoload.php';
$app = require_once __DIR__ . '/../bootstrap/app.php';

use Illuminate\Http\Request;
use Illuminate\Contracts\Http\Kernel;

try {
    $request = Request::capture();
    $kernel = $app->make(Kernel::class);
    // Call handle directly, wrapping in try-catch
    $response = $kernel->handle($request);
    $response->send();
    $kernel->terminate($request, $response);
} catch (\Throwable $e) {
    header('Content-Type: text/plain', true, 500);
    echo "ORIGINAL CRASH DETECTED ON VERCEL:\n";
    echo "Class: " . get_class($e) . "\n";
    echo "Message: " . $e->getMessage() . "\n";
    echo "File: " . $e->getFile() . ":" . $e->getLine() . "\n";
    echo "Trace:\n" . $e->getTraceAsString() . "\n";
    if ($prev = $e->getPrevious()) {
        echo "Previous:\n";
        echo "  Class: " . get_class($prev) . "\n";
        echo "  Message: " . $prev->getMessage() . "\n";
        echo "  File: " . $prev->getFile() . ":" . $prev->getLine() . "\n";
        echo "  Trace:\n" . $prev->getTraceAsString() . "\n";
    }
    exit(1);
}
