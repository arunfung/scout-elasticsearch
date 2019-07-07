<?php
/**
 * Created by PhpStorm.
 * User: arun
 * Date: 2019-07-07
 * Time: 14:29
 */

return [
    'index' => env('ELASTIC_SEARCH_INDEX'),
    'hosts' => [
        [
            'host' => env('ELASTIC_SEARCH_HOST', '127.0.0.1'),
            "port" => env("ELASTIC_SEARCH_PORT", 9200),
            'user' => env('ELASTIC_SEARCH_USER', ''),
            'pass' => env('ELASTIC_SEARCH_PASS', ''),
            'scheme' => env('ELASTIC_SEARCH_SCHEME', 'http'),
        ],
    ],
];
