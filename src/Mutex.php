<?php

class Mutex
{
    protected $_options = array(
        'namespace' => null,
        'table'     => null,
        'aws'       => [],
        'version'   => '2012-08-10'
    );

    public function __construct($options = array())
    {
        $this->_options = array_merge($this->_options, $options);
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
