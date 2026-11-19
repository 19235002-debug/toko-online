<?php

namespace App\Services;

use Illuminate\Support\Facades\Cache;
use Illuminate\Support\Facades\Http;

class RajaOngkirService
{
    /**
     * Cari kecamatan / kota tujuan pengiriman melalui API RajaOngkir (Komerce V1),
     * dengan fallback daftar kota populer jika sedang offline / testing.
     */
    public static function searchDestination(string $keyword, int $limit = 15): array
    {
        $keyword = trim($keyword);
        if (mb_strlen($keyword) < 3) {
            return [];
        }

        $apiKey = config('rajaongkir.api_key');
        $baseUrl = rtrim(config('rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');

        if (!empty($apiKey)) {
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders(['key' => $apiKey])
                    ->timeout(6)
                    ->get($baseUrl . '/destination/domestic-destination', [
                        'search' => $keyword,
                        'limit' => $limit,
                    ]);

                if ($response->successful()) {
                    $data = $response->json('data');
                    if (is_array($data) && count($data) > 0) {
                        return array_map(function ($row) {
                            return [
                                'id' => (int) ($row['id'] ?? 0),
                                'label' => (string) ($row['label'] ?? ''),
                                'city_name' => (string) ($row['city_name'] ?? ''),
                                'province_name' => (string) ($row['province_name'] ?? ''),
                                'district_name' => (string) ($row['district_name'] ?? ''),
                            ];
                        }, array_slice($data, 0, $limit));
                    }
                }
            } catch (\Throwable $e) {
                // Fallback ke daftar lokal di bawah jika koneksi internet tidak tersedia
            }
        }

        return self::fallbackDestinations($keyword, $limit);
    }

    /**
     * Estimasi berat paket (gram) berdasarkan total qty item.
     */
    public static function weightForQty(int $qty): int
    {
        $perItem = max(50, (int) config('rajaongkir.item_weight', 350));

        return max(100, max(1, $qty) * $perItem);
    }

    /**
     * Hitung ongkir dengan cache 30 menit, sehingga tarif yang dilihat pembeli
     * sama dengan tarif yang divalidasi server saat checkout (dan hemat kuota API).
     */
    public static function calculateCost(int $destinationId, int $weightGrams = 1000, ?string $couriers = null): array
    {
        $weightGrams = max(100, $weightGrams);
        $couriers = $couriers ?: config('rajaongkir.couriers', 'jne:jnt:sicepat');
        $cacheKey = 'rajaongkir_cost_' . md5($destinationId . '|' . $weightGrams . '|' . $couriers);

        return Cache::remember($cacheKey, now()->addMinutes(30), function () use ($destinationId, $weightGrams, $couriers) {
            return self::fetchCost($destinationId, $weightGrams, $couriers);
        });
    }

    /**
     * Cari 1 layanan kurir spesifik (misal: jne + REG) dari hasil tarif resmi.
     * Mengembalikan null jika layanan tidak tersedia untuk tujuan tersebut.
     */
    public static function findOption(int $destinationId, int $weightGrams, string $courierCode, string $service): ?array
    {
        $courierCode = strtolower(trim($courierCode));
        $service = strtoupper(trim($service));

        foreach (self::calculateCost($destinationId, $weightGrams) as $opt) {
            if (strtolower($opt['code']) === $courierCode && strtoupper($opt['service']) === $service) {
                return $opt;
            }
        }

        return null;
    }

    /**
     * Lacak nomor resi (waybill) via RajaOngkir Komerce. Null jika gagal / tidak tersedia.
     */
    public static function trackWaybill(string $awb, string $courierCode): ?array
    {
        $awb = trim($awb);
        $courierCode = strtolower(trim($courierCode));
        $apiKey = config('rajaongkir.api_key');

        if ($awb === '' || $courierCode === '' || empty($apiKey)) {
            return null;
        }

        $cacheKey = 'rajaongkir_waybill_' . md5($awb . '|' . $courierCode);

        return Cache::remember($cacheKey, now()->addMinutes(15), function () use ($awb, $courierCode, $apiKey) {
            $baseUrl = rtrim(config('rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
            try {
                $response = Http::withoutVerifying()
                    ->withHeaders(['key' => $apiKey])
                    ->timeout(7)
                    ->post($baseUrl . '/track/waybill?' . http_build_query(['awb' => $awb, 'courier' => $courierCode]));

                if (!$response->successful()) {
                    return null;
                }

                $data = $response->json('data');
                if (!is_array($data)) {
                    return null;
                }

                $manifest = array_map(function ($m) {
                    return [
                        'description' => (string) ($m['manifest_description'] ?? ''),
                        'date' => trim(($m['manifest_date'] ?? '') . ' ' . ($m['manifest_time'] ?? '')),
                        'city' => (string) ($m['city_name'] ?? ''),
                    ];
                }, (array) ($data['manifest'] ?? []));

                return [
                    'delivered' => (bool) ($data['delivered'] ?? false),
                    'status' => (string) ($data['summary']['status'] ?? ($data['delivery_status']['status'] ?? '')),
                    'receiver' => (string) ($data['delivery_status']['pod_receiver'] ?? ''),
                    'manifest' => $manifest,
                ];
            } catch (\Throwable $e) {
                return null;
            }
        });
    }

    private static function fetchCost(int $destinationId, int $weightGrams, string $couriers): array
    {
        $apiKey = config('rajaongkir.api_key');
        $baseUrl = rtrim(config('rajaongkir.base_url', 'https://rajaongkir.komerce.id/api/v1'), '/');
        $originId = (int) config('rajaongkir.origin_id', 17473);

        if (!empty($apiKey) && $destinationId > 0) {
            try {
                $response = Http::withoutVerifying()
                    ->asForm()
                    ->withHeaders(['key' => $apiKey])
                    ->timeout(7)
                    ->post($baseUrl . '/calculate/domestic-cost', [
                        'origin' => $originId,
                        'destination' => $destinationId,
                        'weight' => $weightGrams,
                        'courier' => $couriers,
                        'price' => 'lowest',
                    ]);

                if ($response->successful()) {
                    $data = $response->json('data');
                    if (is_array($data) && count($data) > 0) {
                        $options = [];
                        foreach ($data as $item) {
                            $cost = (int) ($item['cost'] ?? 0);
                            if ($cost <= 0) {
                                continue;
                            }
                            $code = strtoupper((string) ($item['code'] ?? 'KURIR'));
                            $service = (string) ($item['service'] ?? 'REG');
                            $etd = trim((string) ($item['etd'] ?? ''));
                            $etdFormatted = $etd !== '' ? str_ireplace('day', 'Hari', $etd) : '1-3 Hari';

                            $options[] = [
                                'code' => strtolower($code),
                                'courier_name' => (string) ($item['name'] ?? $code),
                                'service' => $service,
                                'description' => (string) ($item['description'] ?? ''),
                                'cost' => $cost,
                                'etd' => $etdFormatted,
                                'label' => "{$code} {$service}",
                                'source' => 'rajaongkir',
                            ];
                        }

                        if (!empty($options)) {
                            usort($options, fn($a, $b) => $a['cost'] <=> $b['cost']);
                            return array_slice($options, 0, 15);
                        }
                    }
                }
            } catch (\Throwable $e) {
                // Fallback ke simulasi sandbox
            }
        }

        return self::fallbackCosts($destinationId, $weightGrams);
    }

    private static function fallbackDestinations(string $keyword, int $limit): array
    {
        $presets = [
            ['id' => 17473, 'label' => 'GROGOL PETAMBURAN, JAKARTA BARAT, DKI JAKARTA', 'city_name' => 'JAKARTA BARAT', 'province_name' => 'DKI JAKARTA', 'district_name' => 'GROGOL PETAMBURAN'],
            ['id' => 17480, 'label' => 'GAMBIR, JAKARTA PUSAT, DKI JAKARTA', 'city_name' => 'JAKARTA PUSAT', 'province_name' => 'DKI JAKARTA', 'district_name' => 'GAMBIR'],
            ['id' => 17510, 'label' => 'KEBAYORAN BARU, JAKARTA SELATAN, DKI JAKARTA', 'city_name' => 'JAKARTA SELATAN', 'province_name' => 'DKI JAKARTA', 'district_name' => 'KEBAYORAN BARU'],
            ['id' => 18101, 'label' => 'COBLONG, BANDUNG, JAWA BARAT', 'city_name' => 'BANDUNG', 'province_name' => 'JAWA BARAT', 'district_name' => 'COBLONG'],
            ['id' => 18205, 'label' => 'BEKASI SELATAN, BEKASI, JAWA BARAT', 'city_name' => 'BEKASI', 'province_name' => 'JAWA BARAT', 'district_name' => 'BEKASI SELATAN'],
            ['id' => 18310, 'label' => 'BEJI, DEPOK, JAWA BARAT', 'city_name' => 'DEPOK', 'province_name' => 'JAWA BARAT', 'district_name' => 'BEJI'],
            ['id' => 19102, 'label' => 'SEMARANG TENGAH, SEMARANG, JAWA TENGAH', 'city_name' => 'SEMARANG', 'province_name' => 'JAWA TENGAH', 'district_name' => 'SEMARANG TENGAH'],
            ['id' => 20105, 'label' => 'DEPOK, SLEMAN, DI YOGYAKARTA', 'city_name' => 'SLEMAN', 'province_name' => 'DI YOGYAKARTA', 'district_name' => 'DEPOK'],
            ['id' => 21108, 'label' => 'GUBENG, SURABAYA, JAWA TIMUR', 'city_name' => 'SURABAYA', 'province_name' => 'JAWA TIMUR', 'district_name' => 'GUBENG'],
        ];

        $q = mb_strtolower($keyword);
        $filtered = array_values(array_filter($presets, fn($p) => str_contains(mb_strtolower($p['label']), $q)));

        return array_slice($filtered ?: $presets, 0, $limit);
    }

    private static function fallbackCosts(int $destinationId, int $weightGrams): array
    {
        $kg = max(1, (int) ceil($weightGrams / 1000));
        $base = ($destinationId >= 19000) ? 18000 : 10000;

        return [
            [
                'code' => 'sicepat',
                'courier_name' => 'SiCepat Express',
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => $base * $kg,
                'etd' => '1-2 Hari',
                'label' => 'SICEPAT REG',
                'source' => 'sandbox',
            ],
            [
                'code' => 'jnt',
                'courier_name' => 'J&T Express',
                'service' => 'EZ',
                'description' => 'Reguler Ekonomis',
                'cost' => ($base + 1000) * $kg,
                'etd' => '2-3 Hari',
                'label' => 'JNT EZ',
                'source' => 'sandbox',
            ],
            [
                'code' => 'jne',
                'courier_name' => 'JNE Express',
                'service' => 'REG',
                'description' => 'Layanan Reguler',
                'cost' => ($base + 2000) * $kg,
                'etd' => '1-2 Hari',
                'label' => 'JNE REG',
                'source' => 'sandbox',
            ],
            [
                'code' => 'jne',
                'courier_name' => 'JNE Express',
                'service' => 'YES',
                'description' => 'Yakin Esok Sampai',
                'cost' => ($base + 10000) * $kg,
                'etd' => '1 Hari',
                'label' => 'JNE YES',
                'source' => 'sandbox',
            ],
        ];
    }
}

