<?php

require_once(__DIR__ . '/Bootstrap.php');

echo date('Y-m-d H:i:s') . " Starting job\n";

if (!Mutex\Lock::acquire(basename($argv[0]), 60)) {
    echo "- The work slot for this job has been locked, skipping...\n";
    exit;
}

echo  "- Lock acquired successfully...\n";
