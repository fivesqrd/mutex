<?php

namespace Fivesqrd\Mutex\Laravel;

use Illuminate\Console\Command;
use Aws\DynamoDb;

class MutexCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'make:mutex';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Create a table for the DynamoDB session driver';

    /**
     * Create a new command instance.
     *
     * @return void
     */
    public function __construct()
    {
        parent::__construct();
    }

    /**
     * Execute the console command.
     *
     * @return mixed
     */
    public function handle()
    {
        $config = config('mutex');

        try {

            if (env('APP_ENV') != 'local') {
                return $this->error(
                    "Please create production tables using the AWS Management Console."
                );
            }

            $client = new DynamoDb\DynamoDbClient($config['aws']);

            $client->createTable([
                'TableName' => $config['table'],
                'AttributeDefinitions' => [
                    [
                        'AttributeName' => 'Slot',
                        'AttributeType' => 'S',
                    ],
                    [
                        'AttributeName' => 'Host',
                        'AttributeType' => 'S',
                    ],
                    [
                        'AttributeName' => 'Timestamp',
                        'AttributeType' => 'S',
                    ],
                    [
                        'AttributeName' => 'Expires',
                        'AttributeType' => 'N',
                    ],
                ],
                'KeySchema' => [
                    [
                        'AttributeName' => 'Slot',
                        'KeyType' => 'HASH',
                    ],
                ],
                'ProvisionedThroughput' => [
                    'ReadCapacityUnits'  => 1,
                    'WriteCapacityUnits' => 1,
                ],
            ]);

            $this->info("Table {$config['table']} created successfully. Please update DynamoDB billing and throughput settings on your AWS Management Console.");

        } catch (\Exception $e) {

            $this->error('Something went wrong!: ' . $e->getMessage());
        
        }
    }
}
