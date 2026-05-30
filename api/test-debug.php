<?php
header('Content-Type: text/plain');
ini_set('display_errors', 1);
error_reporting(E_ALL);

echo "Listing bootstrap/cache folder:\n";
if (is_dir(__DIR__ . '/../bootstrap/cache')) {
    print_r(scandir(__DIR__ . '/../bootstrap/cache'));
} else {
    echo "bootstrap/cache not found!\n";
}
