<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Carbon;

/**
 * @property int $id_voucher
 * @property string $kode_voucher
 * @property string $nama_voucher
 * @property string $tipe
 * @property int $nilai
 * @property int|null $maksimal_diskon
 * @property int $minimal_belanja
 * @property int|null $kuota
 * @property int $digunakan
 * @property Carbon|null $tanggal_mulai
 * @property Carbon|null $tanggal_selesai
 * @property bool $status
 */
class Voucher extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_voucher';

    protected $table = 'vouchers';

    protected $guarded = ['id_voucher'];

    protected $casts = [
        'status' => 'boolean',
        'tanggal_mulai' => 'date',
        'tanggal_selesai' => 'date',
    ];

    /**
     * Hitung nominal potongan diskon untuk subtotal belanja tertentu.
     */
    public function hitungDiskon($subtotal)
    {
        $subtotal = max(0, intval($subtotal));
        if ($this->tipe === 'persen') {
            $diskon = (int) floor($subtotal * (intval($this->nilai) / 100));
            if ($this->maksimal_diskon && intval($this->maksimal_diskon) > 0) {
                $diskon = min($diskon, intval($this->maksimal_diskon));
            }
        } else {
            $diskon = intval($this->nilai);
        }

        return min($diskon, $subtotal);
    }

    /**
     * Cek apakah voucher valid digunakan untuk subtotal belanja tertentu.
     */
    public function isValidForTotal($subtotal)
    {
        if (!$this->status) {
            return ['valid' => false, 'message' => 'Voucher ini sedang tidak aktif.', 'diskon' => 0];
        }

        $today = now()->startOfDay();
        if ($this->tanggal_mulai) {
            $mulai = Carbon::parse($this->tanggal_mulai);
            if ($today->lt($mulai->copy()->startOfDay())) {
                return ['valid' => false, 'message' => 'Promo voucher belum dimulai (mulai ' . $mulai->format('d M Y') . ').', 'diskon' => 0];
            }
        }

        if ($this->tanggal_selesai) {
            $selesai = Carbon::parse($this->tanggal_selesai);
            if ($today->gt($selesai->copy()->endOfDay())) {
                return ['valid' => false, 'message' => 'Masa berlaku voucher sudah berakhir.', 'diskon' => 0];
            }
        }

        if ($this->kuota !== null && intval($this->digunakan) >= intval($this->kuota)) {
            return ['valid' => false, 'message' => 'Kuota pemakaian voucher sudah habis.', 'diskon' => 0];
        }

        if (intval($subtotal) < intval($this->minimal_belanja)) {
            return [
                'valid' => false,
                'message' => 'Minimal belanja untuk voucher ini adalah Rp ' . number_format($this->minimal_belanja, 0, ',', '.') . '.',
                'diskon' => 0
            ];
        }

        $diskon = $this->hitungDiskon($subtotal);
        return [
            'valid' => true,
            'message' => 'Voucher berhasil diterapkan! Hemat Rp ' . number_format($diskon, 0, ',', '.') . '.',
            'diskon' => $diskon
        ];
    }

    /**
     * Scope voucher yang sedang aktif dan valid hari ini (untuk ditampilkan ke pembeli).
     */
    public function scopeAktifTersedia($query)
    {
        $today = now()->toDateString();
        return $query->where('status', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_mulai')->orWhere('tanggal_mulai', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_selesai')->orWhere('tanggal_selesai', '>=', $today);
            })
            ->where(function ($q) {
                $q->whereNull('kuota')->orWhereColumn('digunakan', '<', 'kuota');
            });
    }
}

