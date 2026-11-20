<?php

use App\Helpers\ActionHelper;

if (!function_exists('btn_edit')) {
    function btn_edit(string $url, string $text = 'Edit', string $title = 'Edit Data', string $extraClass = '') {
        return ActionHelper::edit($url, $text, $title, $extraClass);
    }
}

if (!function_exists('btn_delete')) {
    function btn_delete(string $url, string $text = 'Hapus', string $title = 'Hapus Data', string $extraClass = '') {
        return ActionHelper::delete($url, $text, $title, $extraClass);
    }
}

if (!function_exists('btn_view')) {
    function btn_view(string $url, string $text = 'Detail', string $title = 'Lihat Detail', string $extraClass = '') {
        return ActionHelper::view($url, $text, $title, $extraClass);
    }
}

if (!function_exists('btn_actions')) {
    function btn_actions(string $editUrl, string $deleteUrl, string $extraButtons = '') {
        return ActionHelper::actions($editUrl, $deleteUrl, $extraButtons);
    }
}

if (!function_exists('btn_create')) {
    function btn_create(string $url, string $text = 'Tambah', string $icon = 'fa-solid fa-plus', string $extraClass = '') {
        return ActionHelper::create($url, $text, $icon, $extraClass);
    }
}

if (!function_exists('btn_print')) {
    function btn_print(string $url, string $text = 'Cetak Laporan', string $target = '_blank', string $extraClass = '') {
        return ActionHelper::print($url, $text, $target, $extraClass);
    }
}

if (!function_exists('btn_back')) {
    function btn_back(string $url, string $text = 'Kembali', string $extraClass = '') {
        return ActionHelper::back($url, $text, $extraClass);
    }
}

if (!function_exists('btn_save')) {
    function btn_save(string $text = 'Simpan', string $icon = 'fa-solid fa-floppy-disk', string $extraClass = '') {
        return ActionHelper::save($text, $icon, $extraClass);
    }
}

if (!function_exists('btn_reset')) {
    function btn_reset(string $text = 'Reset', string $icon = 'fa-solid fa-arrows-rotate', string $extraClass = '') {
        return ActionHelper::reset($text, $icon, $extraClass);
    }
}

if (!function_exists('store_setting')) {
    function store_setting() {
        return \App\Models\Setting::getSettings();
    }
}

if (!function_exists('store_favicon_url')) {
    function store_favicon_url(): string {
        $setting = store_setting();
        if (!empty($setting->favicon)) {
            return asset('storage/' . $setting->favicon);
        }
        return asset('frontend-assets/images/icons/favicon.png');
    }
}

if (!function_exists('store_logo_url')) {
    function store_logo_url(): ?string {
        $setting = store_setting();
        if (!empty($setting->logo_toko)) {
            return asset('storage/' . $setting->logo_toko);
        }
        return null;
    }
}

if (!function_exists('store_wa_url')) {
    function store_wa_url(string $message = '') {
        $setting = store_setting();
        $hp = preg_replace('/[^0-9]/', '', $setting->no_whatsapp ?? '081234567890');
        if (str_starts_with($hp, '0')) {
            $hp = '62' . substr($hp, 1);
        }
        $url = 'https://wa.me/' . $hp;
        if (!empty($message)) {
            $url .= '?text=' . urlencode($message);
        }
        return $url;
    }
}

