<?php
// config/fedapay.php

return [
    /*
    |--------------------------------------------------------------------------
    | FedaPay Configuration
    |--------------------------------------------------------------------------
    |
    | Configuration pour l'intégration avec FedaPay.
    |
    */
    
    'api_key' => env('FEDAPAY_SECRET_KEY'),
    'public_key' => env('FEDAPAY_PUBLIC_KEY'),
    'environment' => env('FEDAPAY_ENVIRONMENT', 'sandbox'),
    
    'webhook_secret' => env('FEDAPAY_WEBHOOK_SECRET'),
    
    'currency' => [
        'iso' => env('FEDAPAY_CURRENCY', 'XOF'),
    ],
    
    'modes' => [
        'mtn' => 'mtn',
        'moov' => 'moov',
        'mtn_ci' => 'mtn_ci',
        'moov_tg' => 'moov_tg',
        'card' => 'card',
    ],
    
    'urls' => [
        'sandbox' => 'https://sandbox-api.fedapay.com',
        'live' => 'https://api.fedapay.com',
    ],
    
    'defaults' => [
        'timeout' => 30,
        'retries' => 3,
    ],
];