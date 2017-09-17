# Mutex
Locking library for multi server implementations using distributed selection of execution.

## Install ##

```composer require fivesqrd/mutex:0.3.*```

## Basic usage ##
```
<?php
    public function handle()
    {
        if (!Mutex::lock(self::class)->acquire()) {
    	    $this->info(date('Y-m-d H:i:s') . " This work slot has been locked, skipping...");
            return;
        }
    }
```
