<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class SettingController extends Controller
{
    /**
     * Tampilkan halaman pengaturan toko.
     */
    public function index()
    {
        $setting = Setting::getSettings();
        return view('setting.setting', compact('setting'));
    }

    /**
     * Simpan perubahan pengaturan toko.
     */
    public function update(Request $request)
    {
        $validated = $request->validate([
            'nama_toko' => 'required|string|max:100',
            'no_whatsapp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'email_toko' => 'required|email|max:100',
            'alamat_toko' => 'nullable|string',
            'rekening_bank' => 'nullable|string',
            'deskripsi_toko' => 'nullable|string',
            'ongkir_reguler' => 'required|integer|min:0',
            'ongkir_express' => 'required|integer|min:0',
            'min_gratis_ongkir' => 'required|integer|min:0',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_whatsapp.regex' => 'Nomor WhatsApp harus berupa angka 10-15 digit (contoh: 081234567890).',
            'email_toko.required' => 'Email toko wajib diisi.',
            'email_toko.email' => 'Format email toko tidak valid.',
        ]);

        $setting = Setting::getSettings();
        $setting->update($validated);

        Alert::success('Berhasil', 'Pengaturan informasi toko berhasil diperbarui!');
        return redirect()->route('setting.index');
    }
}
