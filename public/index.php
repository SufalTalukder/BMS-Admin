<?php

define('FCPATH', __DIR__ . DIRECTORY_SEPARATOR);

chdir(__DIR__);

require FCPATH . '../app/Config/Paths.php';

$paths = new Config\Paths();

require FCPATH . '../app/Config/Boot/boot.php';
