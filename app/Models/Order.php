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

    public function items()
    {
        return $this->hasMany(OrderItem::class, 'order_id', 'id_order');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'user_id', 'id_user');
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

