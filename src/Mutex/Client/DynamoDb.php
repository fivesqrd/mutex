<?php 
namespace Mutex\Client;

use Aws\DynamoDb as Aws;
use Mutex\Exception as Exception;

class DynamoDb
{
    protected $_dynamo;

    protected $_config = array();

    public function __construct($config) 
    {
        $this->_config = $config; 
    }

    public function getClient()
    {
        if ($this->_dynamo) {
            return $this->_dynamo;
        }

        $this->_dynamo = new Aws\DynamoDbClient($this->_config['aws']);
        
        return $this->_dynamo;
    }

    public function getTable()
    {
        if (!isset($this->_config['table'])) {
            throw new Exception('DynamoDb table config not provided');
        } 

        return $this->_config['table'];
    }
    
    public function set($key, $time = 0)
    {
        if (!$this->hasExpired($key)) {
            /* locked, no go */
            return false;
        }

        $expiry = time() + $time;

        $result = $this->getClient()->putItem([
            'Item' => [
                'Slot'      => ['S' => $key],
                'Host'      => ['S' => gethostname()],
                'Timestamp' => ['S' => date('Y-m-d H:i:s')],
                'Expires'   => ['N' => (string) $expiry],
            ],
            'TableName' => $this->getTable(),
        ]);

        $response = $result->get('@metadata');

        if ($response['statusCode'] != 200) {
            throw new Exception("DynamoDb returned unsuccessful response code: {$response['statusCode']}");
        }

        return true;
    }

    public function hasExpired($key)
    {
        $record = $this->get($key); 
    
        if ($record === false) {
            /* no record found, all clear */
            return true;
        }

        if ($record['Expires'] < time()) {
            /* record found but expired, all clear */
            return true;
        }

        return false;
    }

    public function get($key)
    {
        $result = $this->getClient()->getItem([
            'ConsistentRead' => true,
            'Key' => [
                'Slot' => ['S' => $key]
            ],
            'TableName' => $this->getTable(),
        ]);

        if (!$result->hasKey('Item')) {
            return false;
        }

        $item = $result->get('Item');
        
        return array(
            'Slot'      => $item['Slot']['S'],
            'Host'      => $item['Host']['S'],
            'Timestamp' => $item['Timestamp']['S'],
            'Expires'   => $item['Expires']['N'],
        );
    }

    public function delete($key)
    {
        $result = $this->getClient()->deleteItem([
            'Key' => [
                'Slot' => ['S' => $key]
            ],
            'TableName' => $this->getTable(),
        ]);
    }
}
