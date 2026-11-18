<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Barang extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_barang';

    protected $table = 'barang';

    protected $guarded = ['id_barang'];

    protected $casts = [
        'promo_aktif' => 'boolean',
        'promo_mulai' => 'datetime',
        'promo_selesai' => 'datetime',
    ];

    public function gambars()
    {
        return $this->hasMany(BarangGambar::class, 'barang_id', 'id_barang')->orderBy('urutan', 'asc');
    }

    public function variasis()
    {
        return $this->hasMany(BarangVariasi::class, 'barang_id', 'id_barang');
    }

    public function reviews()
    {
        return $this->hasMany(Review::class, 'barang_id', 'id_barang');
    }

    public function approvedReviews()
    {
        return $this->hasMany(Review::class, 'barang_id', 'id_barang')->where('status', 'approved');
    }

    public function getAverageRatingAttribute()
    {
        return round($this->approvedReviews()->avg('rating') ?: 0, 1);
    }

    public function getTotalReviewsAttribute()
    {
        return $this->approvedReviews()->count();
    }

    /**
     * Mengambil seluruh daftar gambar produk (foto utama + galeri).
     */
    public function getAllImagesListAttribute()
    {
        $list = [];
        if ($this->gambar) {
            $list[] = asset('storage/' . $this->gambar);
        }
        foreach ($this->gambars as $g) {
            $list[] = asset('storage/' . $g->gambar);
        }
        return $list;
    }

    /**
     * Daftar variasi ukuran yang tersedia.
     */
    public function getUkuranListAttribute()
    {
        return $this->variasis->pluck('ukuran')->filter()->unique()->values();
    }

    /**
     * Daftar variasi warna yang tersedia.
     */
    public function getWarnaListAttribute()
    {
        return $this->variasis->pluck('warna')->filter()->unique()->values();
    }

    /**
     * Stok aktual produk: jika memiliki variasi, hitung jumlah stok seluruh variasi.
     */
    public function getStokAktualAttribute()
    {
        if ($this->relationLoaded('variasis')) {
            if ($this->variasis->count() > 0) {
                return intval($this->variasis->sum('stok'));
            }
        } elseif ($this->variasis()->exists()) {
            return intval($this->variasis()->sum('stok'));
        }

        return intval($this->stok);
    }

    /**
     * Cek apakah promo produk sedang aktif dan berada dalam rentang durasi promo.
     */
    public function getIsPromoActiveAttribute()
    {
        if (!$this->promo_aktif) {
            return false;
        }

        $hasValidDiscount = ($this->promo_persen && $this->promo_persen > 0 && $this->promo_persen < 100)
            || ($this->harga_promo && $this->harga_promo > 0 && $this->harga_promo < $this->harga);

        if (!$hasValidDiscount) {
            return false;
        }

        $now = now();
        if ($this->promo_mulai && $now->lt($this->promo_mulai)) {
            return false;
        }
        if ($this->promo_selesai && $now->gt($this->promo_selesai)) {
            return false;
        }

        return true;
    }

    /**
     * Status teks promo untuk admin (Aktif, Terjadwal, Berakhir, Nonaktif).
     */
    public function getPromoStatusLabelAttribute()
    {
        if (!$this->promo_aktif) {
            return 'Nonaktif';
        }
        $now = now();
        if ($this->promo_mulai && $now->lt($this->promo_mulai)) {
            return 'Terjadwal';
        }
        if ($this->promo_selesai && $now->gt($this->promo_selesai)) {
            return 'Berakhir';
        }
        return 'Aktif';
    }

    /**
     * Persentase diskon aktual saat promo aktif.
     */
    public function getDiskonPersenAktualAttribute()
    {
        if (!$this->is_promo_active) {
            return 0;
        }
        if ($this->promo_persen && $this->promo_persen > 0 && $this->promo_persen < 100) {
            return intval($this->promo_persen);
        }
        if ($this->harga > 0 && $this->harga_promo > 0 && $this->harga_promo < $this->harga) {
            return max(1, (int) round((($this->harga - $this->harga_promo) / $this->harga) * 100));
        }
        return 0;
    }

    /**
     * Hitung harga akhir setelah promo (mendukung harga dasar maupun harga variasi).
     */
    public function hitungHargaPromo($basePrice = null)
    {
        $price = intval($basePrice ?? $this->harga);
        if (!$this->is_promo_active) {
            return $price;
        }

        if ($this->harga_promo > 0 && $this->harga_promo < $this->harga && ($basePrice === null || intval($basePrice) === intval($this->harga))) {
            return intval($this->harga_promo);
        }

        $persen = $this->diskon_persen_aktual;
        if ($persen > 0) {
            return max(0, (int) round($price - ($price * ($persen / 100))));
        }

        return $price;
    }

    /**
     * Harga final produk utama setelah promo (jika aktif).
     */
    public function getHargaFinalAttribute()
    {
        return $this->hitungHargaPromo($this->harga);
    }

    /**
     * Scope query untuk produk yang promonya sedang aktif dan berada dalam rentang durasi.
     */
    public function scopePromoActive($query)
    {
        $now = now();
        return $query->where('promo_aktif', true)
            ->where(function ($q) {
                $q->where(function ($sub) {
                    $sub->whereNotNull('promo_persen')->where('promo_persen', '>', 0)->where('promo_persen', '<', 100);
                })->orWhere(function ($sub) {
                    $sub->whereNotNull('harga_promo')->where('harga_promo', '>', 0)->whereColumn('harga_promo', '<', 'harga');
                });
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('promo_mulai')->orWhere('promo_mulai', '<=', $now);
            })
            ->where(function ($q) use ($now) {
                $q->whereNull('promo_selesai')->orWhere('promo_selesai', '>=', $now);
            });
    }
}
