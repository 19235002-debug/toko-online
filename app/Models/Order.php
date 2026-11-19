<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Order extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_order';

    protected $table = 'orders';

    protected $guarded = ['id_order'];

    protected $casts = [
        'paid_at' => 'datetime',
        'shipped_at' => 'datetime',
        'completed_at' => 'datetime',
        'cancelled_at' => 'datetime',
    ];

    /**
     * Alur status pesanan yang diizinkan:
     * pending -> diproses -> dikirim -> selesai, dan pembatalan sebelum dikirim.
     */
    public const STATUS_FLOW = [
        'pending' => ['diproses', 'dibatalkan'],
        'diproses' => ['dikirim', 'dibatalkan'],
        'dikirim' => ['selesai'],
        'selesai' => [],
        'dibatalkan' => [],
    ];

    public const STATUS_LABELS = [
        'pending' => 'Menunggu Pembayaran / Konfirmasi',
        'diproses' => 'Diproses (Dikemas)',
        'dikirim' => 'Dikirim',
        'selesai' => 'Selesai',
        'dibatalkan' => 'Dibatalkan',
    ];

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
    }

    /**
     * Status berikutnya yang boleh dipilih admin dari status saat ini.
     */
    public function allowedNextStatuses(): array
    {
        return self::STATUS_FLOW[$this->status] ?? [];
    }

    public function canTransitionTo(string $newStatus): bool
    {
        return $newStatus === $this->status || in_array($newStatus, $this->allowedNextStatuses(), true);
    }

    /**
     * Pesanan Midtrans dianggap lunas jika sudah tercatat waktu bayarnya
     * atau status sudah melewati tahap pending.
     */
    public function isPaid(): bool
    {
        if ($this->metode_pembayaran === 'cod') {
            return $this->status === 'selesai';
        }

        return !empty($this->paid_at) || in_array($this->status, ['diproses', 'dikirim', 'selesai'], true);
    }

    public function getStatusLabelAttribute(): string
    {
        if ($this->status === 'pending' && $this->metode_pembayaran === 'cod') {
            return 'Menunggu Konfirmasi Penjual';
        }
        if ($this->status === 'pending') {
            return 'Menunggu Pembayaran';
        }

        return self::STATUS_LABELS[$this->status] ?? ucfirst((string) $this->status);
    }

    public static function generateKodeOrder()
    {
        $date = date('Ymd');
        $lastOrder = self::whereDate('created_at', today())->latest('id_order')->first();
        $number = 1;
        if ($lastOrder && preg_match('/-(\d+)$/', $lastOrder->kode_order, $matches)) {
            $number = intval($matches[1]) + 1;
        }
        return 'ORD-' . $date . '-' . str_pad($number, 4, '0', STR_PAD_LEFT);
    }
}
