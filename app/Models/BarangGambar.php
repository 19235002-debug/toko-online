<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangGambar extends Model
{
    use HasFactory;

    protected $table = 'barang_gambars';
    protected $primaryKey = 'id_gambar';
    protected $guarded = ['id_gambar'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }
}

