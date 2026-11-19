<?php

return [
    'api_key' => env('RAJAONGKIR_API_KEY', ''),
    'base_url' => env('RAJAONGKIR_BASE_URL', 'https://rajaongkir.komerce.id/api/v1'),
    'origin_id' => (int) env('RAJAONGKIR_ORIGIN_ID', 17473),
    'origin_label' => env('RAJAONGKIR_ORIGIN_LABEL', 'GROGOL PETAMBURAN, JAKARTA BARAT, DKI JAKARTA'),
    'couriers' => env('RAJAONGKIR_COURIERS', 'jne:jnt:sicepat'),
    // Estimasi berat per item pakaian (gram) untuk menghitung ongkir
    'item_weight' => (int) env('RAJAONGKIR_ITEM_WEIGHT', 350),
    'payment_api_key' => env('KOMERCE_PAYMENT_API_KEY', ''),
    'qrisly_api_key' => env('KOMERCE_QRISLY_API_KEY', ''),
];

