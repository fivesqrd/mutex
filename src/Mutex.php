<?php

class Mutex
{
    public static $options = array();

    public static function lock($job)
    {
        if (!isset(self::$options['namespace'])) {
            throw new Exception('Mutex namespace not configured');
        }

        if (!isset(self::$options['aws'])) {
            throw new Exception('Mutex AWS configured not provided');
        }

        return new Mutex\Lock(
            new Mutex\Client\DynamoDb(self::$options), 
            self::$options['namespace'], 
            $job
        );
    }
}
