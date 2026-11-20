<?php

namespace App\Http\Controllers;

use App\Models\Setting;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
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
            'ongkir_reguler' => 'nullable|integer|min:0',
            'ongkir_express' => 'nullable|integer|min:0',
            'min_gratis_ongkir' => 'nullable|integer|min:0',
            'logo_toko' => 'nullable|file|mimes:png,jpg,jpeg,webp,svg|max:2048',
            'favicon' => 'nullable|file|mimes:png,jpg,jpeg,webp,ico,svg|max:1024',
        ], [
            'nama_toko.required' => 'Nama toko wajib diisi.',
            'no_whatsapp.required' => 'Nomor WhatsApp wajib diisi.',
            'no_whatsapp.regex' => 'Nomor WhatsApp harus berupa angka 10-15 digit (contoh: 081234567890).',
            'email_toko.required' => 'Email toko wajib diisi.',
            'email_toko.email' => 'Format email toko tidak valid.',
            'logo_toko.mimes' => 'Logo toko harus berformat PNG, JPG, WEBP, atau SVG.',
            'logo_toko.max' => 'Ukuran logo toko maksimal 2 MB.',
            'favicon.mimes' => 'Favicon harus berformat PNG, ICO, JPG, WEBP, atau SVG.',
            'favicon.max' => 'Ukuran favicon maksimal 1 MB.',
        ]);

        $setting = Setting::getSettings();

        if ($request->boolean('hapus_logo') && $setting->logo_toko) {
            Storage::disk('public')->delete($setting->logo_toko);
            $validated['logo_toko'] = null;
        }
        if ($request->hasFile('logo_toko')) {
            if ($setting->logo_toko) {
                Storage::disk('public')->delete($setting->logo_toko);
            }
            $validated['logo_toko'] = $request->file('logo_toko')->store('store_branding', 'public');
        }

        if ($request->boolean('hapus_favicon') && $setting->favicon) {
            Storage::disk('public')->delete($setting->favicon);
            $validated['favicon'] = null;
        }
        if ($request->hasFile('favicon')) {
            if ($setting->favicon) {
                Storage::disk('public')->delete($setting->favicon);
            }
            $validated['favicon'] = $request->file('favicon')->store('store_branding', 'public');
        }

        $setting->update($validated);

        Alert::success('Berhasil', 'Pengaturan informasi toko berhasil diperbarui!');
        return redirect()->route('setting.index');
    }
}
