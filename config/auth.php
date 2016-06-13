<?php

return [

    'multi-auth' => [
        'admin' => [
            'driver' => 'eloquent',
            'model'  => App\Admin::class
        ],
        'merchant' => [
            'driver' => 'eloquent',
            'model'  => App\Merchant::class
        ],
        'store' => [
            'driver' => 'eloquent',
            'model'  => App\Store::class
        ]
    ],

    'password' => [
        'email' => 'emails.password',
        'table' => 'password_resets',
        'expire' => 60,
    ],

];
