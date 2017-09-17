<?php

class Mutex
{
    protected $_options = array();

    public function __construct($options = array())
    {
        $this->_options = $options;
    }

    public function lock($job)
    {
        if (!isset($this->_options['namespace'])) {
            throw new Exception('Mutex namespace not configured');
        }

        if (!isset($this->_options['aws'])) {
            throw new Exception('Mutex AWS settings not provided');
        }

        return new Mutex\Lock(
            new Mutex\Client\DynamoDb($this->_options), 
            $this->_options['namespace'], 
            $job
        );
    }
}
