<?php

// Path to the vendor directory
$vendorPath = realpath(FCPATH . '../vendor/autoload.php');

if (! file_exists($vendorPath)) {
    exit('Composer autoload not found. Run "composer install".');
}

// Load Composer autoloader
require $vendorPath;

// Load Paths config
$paths = new Config\Paths();

// Load system bootstrap
require $paths->systemDirectory . 'bootstrap.php';
