<?php
namespace Mutex;

use Aws\Dynamodb;

class Lock
{
    protected $_client;

    protected $_namespace;

    protected $_job;

    public function __construct($client, $namespace, $job)
    {
        $this->_client = $client;
        $this->_namespace = $namespace;
        $this->_job = $job;
    }

    public function acquire($time = 30)
    {
        try {
            $key = $this->getKey($this->_job);

            /* wait for a random number of seconds */
            sleep(rand(0, 5));

            if ($this->set($key, $time)) {
                return $key;
            }
            
        } catch (Exception $e) {
            echo "Mutex lock failed: {$e->getMessage()}\n";
        }

        return false;
    }

    public function getKey()
    {
        if (empty($this->_job)) {
            throw new Exception('Mutex key is required to acquire lock');
        }

        return $this->_namespace . ':' . $this->_job;
    }

    public function set($key, $time = null)
    {
        return $this->_client->set($key, $time);
    }

    public function release()
    {
        return $this->_client->delete($this->getKey());
    }
}
