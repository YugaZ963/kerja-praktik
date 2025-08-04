<?php

return [
    /*
    |--------------------------------------------------------------------------
    | Google Maps API Configuration
    |--------------------------------------------------------------------------
    |
    | This file contains the configuration for Google Maps integration.
    | You can set your API key and store location details here.
    |
    */

    'api_key' => env('GOOGLE_MAPS_API_KEY', 'AIzaSyBFw0Qbyq9zTFTd-tUY6dOWTgHz-EGE7KQABC'),

    'store_location' => [
        'lat' => env('STORE_LATITUDE', -6.9174201),
        'lng' => env('STORE_LONGITUDE', 107.604071),
        'name' => env('STORE_NAME', 'RAVAZKA - Toko Seragam Sekolah'),
        'address' => env('STORE_ADDRESS', 'Pasar Baru, Bandung, Jawa Barat'),
        'phone' => env('STORE_PHONE', '+62 896-7775-4918'),
    ],

    'map_settings' => [
        'zoom' => 16,
        'map_type' => 'roadmap', // roadmap, satellite, hybrid, terrain
        'marker_icon' => [
            'width' => 40,
            'height' => 40,
            'color' => '#0d6efd',
        ],
    ],
];