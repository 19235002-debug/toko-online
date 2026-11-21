<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Facades\Cache;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'id_setting';
    protected $guarded = ['id_setting'];

    protected static ?Setting $staticCache = null;

    protected static function booted()
    {
        static::saved(function () {
            self::clearStaticCache();
        });
        static::deleted(function () {
            self::clearStaticCache();
        });
    }

    public static function clearStaticCache(): void
    {
        static::$staticCache = null;
        Cache::forget('store_settings_singleton');
    }

    protected $casts = [
        'active_couriers' => 'array',
        'active_payment_methods' => 'array',
    ];

    /**
     * Master daftar kurir yang didukung sistem.
     */
    public const ALL_COURIERS = [
        'jne' => [
            'name' => 'JNE',
            'full_name' => 'Jalur Nugraha Ekakurir (JNE)',
            'services' => 'REG, OKE, YES',
            'color' => '#dc2626',
            'icon' => 'fa-solid fa-truck-fast',
        ],
        'jnt' => [
            'name' => 'J&T Express',
            'full_name' => 'J&T Express',
            'services' => 'EZ, Jemari, Super',
            'color' => '#ef4444',
            'icon' => 'fa-solid fa-truck-ramp-box',
        ],
        'sicepat' => [
            'name' => 'SiCepat Ekspres',
            'full_name' => 'SiCepat Ekspres',
            'services' => 'REG, SIUNT, BEST',
            'color' => '#e11d48',
            'icon' => 'fa-solid fa-bolt',
        ],
        'anteraja' => [
            'name' => 'AnterAja',
            'full_name' => 'AnterAja',
            'services' => 'Standard, Next Day, Same Day',
            'color' => '#8b5cf6',
            'icon' => 'fa-solid fa-truck',
        ],
        'pos' => [
            'name' => 'POS Indonesia',
            'full_name' => 'POS Indonesia (Kilat Khusus)',
            'services' => 'Pos Reguler, Pos Nextday',
            'color' => '#ea580c',
            'icon' => 'fa-solid fa-envelope',
        ],
        'ninja' => [
            'name' => 'Ninja Xpress',
            'full_name' => 'Ninja Xpress',
            'services' => 'Standard Delivery',
            'color' => '#b91c1c',
            'icon' => 'fa-solid fa-user-ninja',
        ],
        'lion' => [
            'name' => 'Lion Parcel',
            'full_name' => 'Lion Parcel',
            'services' => 'REGPACK, ONEPACK',
            'color' => '#c2410c',
            'icon' => 'fa-solid fa-plane-departure',
        ],
        'tiki' => [
            'name' => 'TIKI',
            'full_name' => 'Titipan Kilat (TIKI)',
            'services' => 'REG, ONS, SDS',
            'color' => '#0284c7',
            'icon' => 'fa-solid fa-boxes-packing',
        ],
    ];

    /**
     * Master metode pembayaran yang didukung sistem.
     */
    public const ALL_PAYMENTS = [
        'midtrans' => [
            'name' => 'Pembayaran Online (Instan)',
            'short' => 'Online Payment',
            'badge' => 'QRIS, E-Wallet & VA',
            'desc' => 'Mendukung QRIS, GoPay, OVO, ShopeePay, Virtual Account (BCA, Mandiri, BNI, BRI), dan Kartu Kredit/Debit secara instan.',
            'icon' => 'fa-solid fa-credit-card',
            'color' => '#4f46e5',
        ],
        'cod' => [
            'name' => 'COD (Cash on Delivery)',
            'short' => 'Bayar di Tempat',
            'badge' => 'Bayar Tunai ke Kurir',
            'desc' => 'Pelanggan membayar secara tunai langsung kepada kurir saat paket pesanan tiba di alamat tujuan.',
            'icon' => 'fa-solid fa-hand-holding-dollar',
            'color' => '#059669',
        ],
    ];

    /**
     * Dapatkan satu baris setting aktif (dengan cache in-memory & Laravel Cache agar query tidak berulang).
     */
    public static function getSettings()
    {
        if (static::$staticCache !== null) {
            return static::$staticCache;
        }

        static::$staticCache = Cache::remember('store_settings_singleton', 3600, function () {
            return self::firstOrCreate(
                ['id_setting' => 1],
                [
                    'nama_toko' => 'Coza Store',
                    'no_whatsapp' => '081234567890',
                    'email_toko' => 'admin@cozastore.com',
                    'alamat_toko' => 'Jl. Jendral Sudirman No. 123, Jakarta Pusat',
                    'rekening_bank' => "BCA: 1234567890 a.n Toko Coza\nMandiri: 9876543210 a.n Toko Coza",
                    'deskripsi_toko' => 'Toko online busana dan aksesoris modern dengan produk berkualitas tinggi.',
                    'ongkir_reguler' => 15000,
                    'ongkir_express' => 30000,
                    'min_gratis_ongkir' => 300000,
                    'active_couriers' => ['jne', 'jnt', 'sicepat', 'anteraja', 'pos'],
                    'active_payment_methods' => ['midtrans', 'cod'],
                ]
            );
        });

        return static::$staticCache;
    }

    /**
     * Dapatkan daftar kurir aktif (lowercase array).
     */
    public function getActiveCouriers(): array
    {
        if (is_array($this->active_couriers) && count($this->active_couriers) > 0) {
            return array_map('strtolower', $this->active_couriers);
        }
        return ['jne', 'jnt', 'sicepat', 'anteraja', 'pos'];
    }

    /**
     * Cek apakah kurir tertentu aktif.
     */
    public function isCourierActive(string $code): bool
    {
        return in_array(strtolower($code), $this->getActiveCouriers(), true);
    }

    /**
     * Dapatkan daftar metode pembayaran aktif.
     */
    public function getActivePaymentMethods(): array
    {
        if (is_array($this->active_payment_methods) && count($this->active_payment_methods) > 0) {
            return array_map('strtolower', $this->active_payment_methods);
        }
        return ['midtrans', 'cod'];
    }

    /**
     * Cek apakah metode pembayaran tertentu aktif.
     */
    public function isPaymentActive(string $method): bool
    {
        return in_array(strtolower($method), $this->getActivePaymentMethods(), true);
    }

    /**
     * Toggle status aktif kurir.
     */
    public function toggleCourier(string $code): bool
    {
        $code = strtolower($code);
        $current = $this->getActiveCouriers();

        if (in_array($code, $current, true)) {
            // Minimal harus ada 1 kurir aktif agar pembeli tetap bisa checkout
            if (count($current) <= 1) {
                return false;
            }
            $current = array_values(array_diff($current, [$code]));
            $nowActive = false;
        } else {
            $current[] = $code;
            $nowActive = true;
        }

        $this->active_couriers = array_values(array_unique($current));
        $this->save();

        return $nowActive;
    }

    /**
     * Toggle status aktif metode pembayaran.
     */
    public function togglePaymentMethod(string $method): bool
    {
        $method = strtolower($method);
        $current = $this->getActivePaymentMethods();

        if (in_array($method, $current, true)) {
            // Minimal harus ada 1 metode bayar aktif
            if (count($current) <= 1) {
                return false;
            }
            $current = array_values(array_diff($current, [$method]));
            $nowActive = false;
        } else {
            $current[] = $method;
            $nowActive = true;
        }

        $this->active_payment_methods = array_values(array_unique($current));
        $this->save();

        return $nowActive;
    }
}
