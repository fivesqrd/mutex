<?php
namespace Mutex;

class Lock
{
    public static $options = array(
        'host'      => null,
        'port'      => null,
        'namespace' => null
    );

    public static function acquire($job, $time = 30)
    {
        if (!array_key_exists('namespace', self::$options) || empty(self::$options['namespace'])) {
            throw new Exception('Mutex namespace not configured');
        }

        if (empty($job)) {
            throw new Exception('Mutex key is required to acquire lock');
        }
        
        try {
            $key = self::$options['namespace'] . ':' . $job;

            /* wait for a random number of seconds */
            sleep(rand(0, 5));

            $client = new Client\Redis(self::$options);

            $lock = $client->set($key, gethostname() . ':' . time());

            if ($lock && $time) {
                /* if lock was successfully acquired, set an expiration */
                $client->expire($key, $time);
            }

            return $lock;
            
        } catch (\Exception $e) {
            echo "Mutex lock failed: {$e->getMessage()}\n";
        }

        return false;
    }
}
