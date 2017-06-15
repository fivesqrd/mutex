<?php
namespace Mutex;

use Aws\Dynamodb;

class Lock
{
    protected $_client;

    public static $options = array();

    public static function acquire($job, $time = 30)
    {
        $lock = new self(new Client\DynamoDb(self::$options));
        
        try {
            $key = $lock->getKey($job);

            /* wait for a random number of seconds */
            sleep(rand(0, 5));

            if ($lock->set($key, $time)) {
                return $key;
            }
            
        } catch (\Exception $e) {
            echo "Mutex lock failed: {$e->getMessage()}\n";
        }

        return false;
    }

    public function __construct($client)
    {
        $this->_client = $client;
    }

    public function getKey($job)
    {
        if (!array_key_exists('namespace', self::$options) || empty(self::$options['namespace'])) {
            throw new Exception('Mutex namespace not configured');
        }

        if (empty($job)) {
            throw new Exception('Mutex key is required to acquire lock');
        }

        return self::$options['namespace'] . ':' . $job;
    }

    public function set($key, $time = null)
    {
        return $this->_client->set($key, $time);
    }
}
