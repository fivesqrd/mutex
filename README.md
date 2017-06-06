# Mutex
Shared locking library for multi server implementations

## Basic usage ##
```
<?php

if (!Mutex\Lock::acquire($argv[0])) {
    echo date('Y-m-d H:i:s') . " This work slot has been locked, skipping...\n";
    exit;
}
```
