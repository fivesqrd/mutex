<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

Mutex::$options = array(
    'aws' => array(
        'version'  => 'latest',
        'region'   => 'eu-west-1',
        'endpoint' => 'http://192.168.254.10:8000',
        'credentials' => array(
            'key'    => 'my-key',
            'secret' => 'my-secret',
        )
    ),
    'namespace' => 'My-Example-App',
    'table'     => 'Five-Nines-Locks' 
);

echo date('Y-m-d H:i:s') . " Starting job\n";

if (!Mutex::lock(basename($argv[0]))->acquire(10)) {
    echo "- The work slot for this job has been locked, skipping...\n";
    exit;
}

echo  "- Lock acquired successfully...\n";
