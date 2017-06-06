<?php
require_once('src/Mutex/Lock.php');
require_once('src/Mutex/Exception.php');
require_once('src/Mutex/Client/Redis.php');

Mutex\Lock::$options = array(
    'host'      => '127.0.0.1',
    'port'      => 6379,
    'namespace' => 'My-App',
);
