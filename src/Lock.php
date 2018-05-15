<?php
namespace Fivesqrd\Mutex;

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
            /* wait for a random number of seconds */
            sleep(rand(0, 5));

            if ($this->set($time)) {
                return $this->getKey();
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

    public function set($time = null)
    {
        return $this->_client->set($this->getKey(), $time);
    }

    /**
     * Extend the lock expiry by X seconds
     * @param int $time
     */
    public function extend($time)
    {
        return $this->_client->update($this->getKey(), $time);
    }

    public function release()
    {
        return $this->_client->delete($this->getKey());
    }
}
