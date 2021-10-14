<?php

return [
    'namespace' => env('MUTEX_NAMESPACE'),
    'table'     => env('MUTEX_TABLE'),
    'aws'       => [
        'version' => env('AWS_VERSION', '2012-08-10'),
        'region'  => env('AWS_DEFAULT_REGION', 'eu-west-1'),
        'credentials' => [
            'key'    => env('AWS_ACCESS_KEY_ID'),
            'secret' => env('AWS_SECRET_ACCESS_KEY'),
        ],
        'endpoint' => env('AWS_ENDPOINT') ?: null
    ],
];
