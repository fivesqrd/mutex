# Mutex
Locking library for multi server implementations using distributed selection of execution.

## Install ##

```composer require fivesqrd/mutex:0.4.*```

## Basic usage ##
```
<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

use Fivesqrd\Mutex;

$mutex = new Mutex\Factory([
    'aws' => array(
        'version'  => '2012-08-10',
        'region'   => 'eu-west-1',
        'endpoint' => 'http://192.168.254.10:8000',
        'credentials' => array(
            'key'    => 'my-key',
            'secret' => 'my-secret',
        )
    ),
    'namespace' => 'My-Example-App',
    'table'     => 'My-DynamoDb-Table' 
]);

echo date('Y-m-d H:i:s') . " Starting job\n";

if (!$mutex->lock(basename($argv[0]))->acquire(10)) {
    echo "- The work slot for this job has been locked, skipping...\n";
    exit;
}

echo  "- Lock acquired successfully...\n";
```

## Laravel 5 ##

.env requirements
```
MUTEX_TABLE="My-Table"
MUTEX_NAMESPACE="My-App"

AWS_KEY="my-key"
AWS_SECRET="my-secret"
AWS_REGION="eu-west-1"
AWS_ENDPOINT=
```

Using it in a command class:
 ```
    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
		 if (!resolve('mutex')->lock(self::class)->acquire()) {
		    $this->info("Failed to acquire lock for this command");
		    return;
		}

		/* logic here */

		$this->info("Command completed successfully");
	}
```
