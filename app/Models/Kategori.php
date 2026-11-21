<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Support\Str;

/**
 * @property int $id_kategori
 * @property int|null $parent_id
 * @property string $nama_kategori
 * @property string $slug
 * @property string|null $gambar
 * @property int $urutan
 * @property string|null $keterangan
 */
class Kategori extends Model
{
    use HasFactory;

    protected $table = 'kategoris';
    protected $primaryKey = 'id_kategori';
    protected $guarded = ['id_kategori'];

    protected static function boot()
    {
        parent::boot();

        static::saving(function ($model) {
            if (empty($model->slug)) {
                $prefix = $model->parent ? ($model->parent->nama_kategori . '-') : '';
                $model->slug = Str::slug($prefix . $model->nama_kategori);
            }
        });

        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_main_kategoris');
        });

        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_main_kategoris');
        });
    }

    public function parent()
    {
        return $this->belongsTo(Kategori::class, 'parent_id', 'id_kategori');
    }

    public function children()
    {
        return $this->hasMany(Kategori::class, 'parent_id', 'id_kategori')
            ->orderBy('urutan', 'asc')
            ->orderBy('nama_kategori', 'asc');
    }

    public function scopeMain($query)
    {
        return $query->whereNull('parent_id')
            ->orderBy('urutan', 'asc')
            ->orderBy('id_kategori', 'asc');
    }

    public function barangs()
    {
        return $this->hasMany(Barang::class, 'kategori', 'nama_kategori');
    }

    public function subBarangs()
    {
        return $this->hasMany(Barang::class, 'sub_kategori', 'nama_kategori');
    }
}
