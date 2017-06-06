<?php 
namespace Mutex\Client;

class Redis
{
    protected $_redis;

    protected $_config = array();

    const MAX_POLLS = 30;

    public function __construct($config) 
    {
        $this->_config = $config; 
    }

    public function getClient()
    {
        if ($this->_redis) {
            return $this->_redis;
        }

        $this->_redis = new \Redis();

        $attempts = 0;

        while (!$this->_redis->connect($this->_config['host'], $this->_config['port'])) {

            if ($attempts > self::MAX_POLLS) {
                throw new \Mutex\Exception(
                    'Time out connecting to cache server'
                );
            }

            $attempts++;

            sleep(10);
        }

        return $this->_redis;
    }
    
    public function set($key, $value)
    {
        return $this->getClient()->setNx($key, $value);
    }

    public function expire($key, $time)
    {
        return $this->getClient()->setTimeout($key, $time);
    }
}
