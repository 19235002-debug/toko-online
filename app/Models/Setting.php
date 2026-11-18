<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Setting extends Model
{
    use HasFactory;

    protected $table = 'settings';
    protected $primaryKey = 'id_setting';
    protected $guarded = ['id_setting'];

    /**
     * Dapatkan satu baris setting aktif.
     */
    public static function getSettings()
    {
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
            ]
        );
    }
}
