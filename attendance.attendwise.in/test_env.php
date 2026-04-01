<?php
echo "PHP version: " . PHP_VERSION . "\n";
echo "Dir: " . __DIR__ . "\n";
if (file_exists('vendor/autoload.php')) {
    echo "Autoload found\n";
} else {
    echo "Autoload MISSING\n";
    $files = scandir('vendor');
    print_r($files);
}
