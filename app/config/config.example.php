<?php

return [
    'db'      => [
        'host'      => '',
        'port'      => '',
        'username'  => '',
        'password'  => '',
        'dbAdapter' => '',
    ],
    'routing' => [
        'defaultController' => '\\App\\Controllers\\DefaultController',
        'defaultAction'     => 'index',
    ],
    'baseUrl' => '',
    'emails'  => [
        'from'       => '',
        'connection' => [
            'smtp' => [
                'host'     => '',
                'user'     => '',
                'password' => '',
                'port'     => '',
            ]
        ]
    ]
];