<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BarangVariasi extends Model
{
    use HasFactory;

    protected $table = 'barang_variasis';
    protected $primaryKey = 'id_variasi';
    protected $guarded = ['id_variasi'];

    public function barang()
    {
        return $this->belongsTo(Barang::class, 'barang_id', 'id_barang');
    }

    public function getLabelAttribute()
    {
        $parts = [];
        if ($this->warna) $parts[] = 'Warna: ' . $this->warna;
        if ($this->ukuran) $parts[] = 'Ukuran: ' . $this->ukuran;
        return implode(', ', $parts) ?: 'Default';
    }
}

