<?php
try {
    // Forward Vercel requests to normal index.php
    require __DIR__ . '/../public/index.php';
} catch (\Throwable $e) {
    header('Content-Type: text/plain', true, 500);
    echo "CRASH DETECTED ON VERCEL:\n";
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
