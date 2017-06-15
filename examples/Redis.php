<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

Mutex\Lock::$options = array(
    'redis' => array(
        'host'      => '127.0.0.1',
        'port'      => 6379,
    ),
    'namespace' => 'My-Example-App'
);

echo date('Y-m-d H:i:s') . " Starting job\n";

if (!Mutex\Lock::acquire(basename($argv[0]), 60)) {
    echo "- The work slot for this job has been locked, skipping...\n";
    exit;
}

echo  "- Lock acquired successfully...\n";
