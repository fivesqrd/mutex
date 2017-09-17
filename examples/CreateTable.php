<?php
use Aws\Dynamodb;

require_once realpath(__DIR__ . '/../vendor/autoload.php');

$options = array(
    'aws' => array(
        'version'  => 'latest',
        'region'   => 'eu-west-1',
        'endpoint' => 'http://192.168.254.10:8000',
        'credentials' => array(
            'key'    => 'test',
            'secret' => 'test',
        )
    ),
    'table'     => 'Five-Nines-Locks' 
);


$client = new DynamoDb\DynamoDbClient($options['aws']);

/*
$result = $client->deleteTable([
    'TableName' => $options['table'],
]);
*/

$result = $client->createTable([
    'TableName' => $options['table'],
    'ProvisionedThroughput' => array(
        'ReadCapacityUnits'  => (int) 5,
        'WriteCapacityUnits' => (int) 5,
    ),
    'AttributeDefinitions' => array(
        array(
            'AttributeName' => 'Slot',
            'AttributeType' => 'S'
        )
    ),
    'KeySchema' => array(
        array(
            'AttributeName' => 'Slot',
            'KeyType'       => 'HASH'
        )
    )
]);
