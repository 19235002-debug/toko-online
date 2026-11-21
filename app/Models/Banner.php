<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Banner extends Model
{
    use HasFactory;

    protected $primaryKey = 'id_banner';

    protected $table = 'banners';

    protected $guarded = ['id_banner'];

    protected static function booted()
    {
        static::saved(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_active_banners');
        });
        static::deleted(function () {
            \Illuminate\Support\Facades\Cache::forget('frontend_active_banners');
        });
    }
}

