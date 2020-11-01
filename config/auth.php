<?php
    /**
     * Created by PhpStorm.
     * User: valentindaitkhe
     * Date: 29/04/2020
     * Time: 20:53
     */
    return [
        'defaults' => [
            'guard' => 'api',
            'passwords' => 'users',
        ],

        'guards' => [
            'api' => [
                'driver' => 'jwt',
                'provider' => 'users',
            ],
        ],

        'providers' => [
            'users' => [
                'driver' => 'eloquent',
                'model' => \App\User::class
            ]
        ]
    ];
