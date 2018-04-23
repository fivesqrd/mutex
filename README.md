# Mutex
Locking library for multi server implementations using distributed selection of execution.

## Install ##

```composer require fivesqrd/mutex:0.4.*```

## Basic usage ##
```
<?php
require_once realpath(__DIR__ . '/../vendor/autoload.php');

$mutex = new Mutex([
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
    'table'     => 'My-DynamoDb-Table' 
]);

echo date('Y-m-d H:i:s') . " Starting job\n";

if (!$mutex->lock(basename($argv[0]))->acquire(10)) {
    echo "- The work slot for this job has been locked, skipping...\n";
    exit;
}

echo  "- Lock acquired successfully...\n";
```

## Laravel ##

In config/services.php add the config mutex and aws keys:
```
 	'mutex' => [
        'namespace' => 'My-App-Name',
        'table'     => 'My-Dynamo-Table'
    ],

    'aws' => [
        'version' => 'latest',
        'region'  => 'eu-west-1',
        'credentials' => [
            'key'    => env('AWS_KEY'),
            'secret' => env('AWS_SECRET'),
        ],
        'endpoint' => env('AWS_ENDPOINT') ?: null
    ],
```

In app/Providers/AppServiceProvider.php register a singleton:
```
  	/**
     * Register any application services.
     *
     * @return void
     */
    public function register()
    {
        $this->app->singleton('mutex', function ($app) {
            $config = $app->make('config')->get('services.mutex');
            $config['aws'] = $app->make('config')->get('services.aws');
            return new Mutex($config);
        });
    }
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
