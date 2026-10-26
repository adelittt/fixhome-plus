<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Default Shipping Driver
    |--------------------------------------------------------------------------
    |
    | Menentukan adapter penyedia kurir instan default yang aktif (AB-013, PK-01).
    | Pilihan: 'mock' (simulasi lokal terintegrasi), 'biteship' (sandbox Biteship API).
    |
    */
    'default' => env('SHIPPING_DRIVER', 'mock'),

    /*
    |--------------------------------------------------------------------------
    | Shipping Drivers Configuration
    |--------------------------------------------------------------------------
    */
    'drivers' => [
        'mock' => [
            'name' => 'FixHome Express (Simulasi Instan)',
            'driver_name' => 'Budi Santoso (Kurir Motor Instan)',
            'driver_plate' => 'B 4123 SXZ',
            'driver_phone' => '081288887777',
            'default_shipping_fee' => 15000,
            'estimated_hours' => 2.0,
        ],

        'biteship' => [
            'name' => 'Biteship Kurir Instan (Sandbox)',
            'api_key' => env('BITESHIP_API_KEY', 'biteship_test_mock_sandbox_key'),
            'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com/v1'),
            'courier' => env('BITESHIP_COURIER', 'grab'),
            'courier_service_type' => env('BITESHIP_SERVICE_TYPE', 'instant'),
            'sandbox' => env('BITESHIP_SANDBOX', true),
            'timeout' => env('BITESHIP_TIMEOUT', 10),
        ],
    ],
];
