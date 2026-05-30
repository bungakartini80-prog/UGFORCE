<?php
header('Content-Type: text/plain');
echo "Vercel PHP runtime is active!\n";
echo "Current directory: " . getcwd() . "\n";
echo "Root directory contents:\n";
print_r(scandir(__DIR__ . '/..'));
echo "\nVendor directory contents:\n";
if (is_dir(__DIR__ . '/../vendor')) {
    print_r(scandir(__DIR__ . '/../vendor'));
} else {
    echo "vendor directory not found!\n";
}
