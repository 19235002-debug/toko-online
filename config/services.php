<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Third Party Services
    |--------------------------------------------------------------------------
    |
    | This file is for storing the credentials for third party services such
    | as Mailgun, Postmark, AWS and more. This file provides the de facto
    | location for this type of information, allowing packages to have
    | a conventional file to locate the various service credentials.
    |
    */

    'mailgun' => [
        'domain' => env('MAILGUN_DOMAIN'),
        'secret' => env('MAILGUN_SECRET'),
        'endpoint' => env('MAILGUN_ENDPOINT', 'api.mailgun.net'),
        'scheme' => 'https',
    ],

    'postmark' => [
        'token' => env('POSTMARK_TOKEN'),
    ],

    'ses' => [
        'key' => env('AWS_ACCESS_KEY_ID'),
        'secret' => env('AWS_SECRET_ACCESS_KEY'),
        'region' => env('AWS_DEFAULT_REGION', 'us-east-1'),
    ],

    'biteship' => [
        'api_key' => env('BITESHIP_API_KEY', ''),
        'base_url' => env('BITESHIP_BASE_URL', 'https://api.biteship.com'),
        'origin_area_id' => env('BITESHIP_ORIGIN_AREA_ID', 'IDNP6IDNC148IDND843IDZ12250'),
        'origin_postal_code' => env('BITESHIP_ORIGIN_POSTAL_CODE', '12250'),
        'origin_address' => env('BITESHIP_ORIGIN_ADDRESS', 'Jl. Jendral Sudirman No. 123, Senayan, Jakarta Pusat'),
        'origin_contact_name' => env('BITESHIP_ORIGIN_NAME', 'Kelompok 3 Fashion Store'),
        'origin_contact_phone' => env('BITESHIP_ORIGIN_PHONE', '081234567890'),
    ],

];
