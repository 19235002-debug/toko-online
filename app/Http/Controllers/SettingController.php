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
        $allCouriers = Setting::ALL_COURIERS;
        $allPayments = Setting::ALL_PAYMENTS;
        $activeCouriers = $setting->getActiveCouriers();
        $activePayments = $setting->getActivePaymentMethods();

        return view('setting.setting', compact('setting', 'allCouriers', 'allPayments', 'activeCouriers', 'activePayments'));
    }

    /**
     * Tampilkan halaman khusus opsi pengiriman & pembayaran (bisa diakses langsung).
     */
    public function methodsIndex()
    {
        $setting = Setting::getSettings();
        $allCouriers = Setting::ALL_COURIERS;
        $allPayments = Setting::ALL_PAYMENTS;
        $activeCouriers = $setting->getActiveCouriers();
        $activePayments = $setting->getActivePaymentMethods();

        return view('setting.methods', compact('setting', 'allCouriers', 'allPayments', 'activeCouriers', 'activePayments'));
    }

    /**
     * Simpan perubahan pengaturan toko (profil & identitas).
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
            'active_couriers' => 'nullable|array',
            'active_payment_methods' => 'nullable|array',
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

        if ($request->has('active_couriers')) {
            $validated['active_couriers'] = array_values(array_filter($request->input('active_couriers', [])));
        }
        if ($request->has('active_payment_methods')) {
            $validated['active_payment_methods'] = array_values(array_filter($request->input('active_payment_methods', [])));
        }

        $setting->update($validated);

        Alert::success('Berhasil', 'Pengaturan toko berhasil diperbarui!');
        return redirect()->back();
    }

    /**
     * Simpan perubahan khusus opsi kurir dan metode pembayaran.
     */
    public function updateMethods(Request $request)
    {
        $couriers = $request->input('active_couriers', []);
        $payments = $request->input('active_payment_methods', []);

        if (empty($couriers) || !is_array($couriers)) {
            Alert::error('Gagal', 'Minimal harus ada 1 ekspedisi kurir yang aktif.');
            return redirect()->back();
        }

        if (empty($payments) || !is_array($payments)) {
            Alert::error('Gagal', 'Minimal harus ada 1 metode pembayaran yang aktif.');
            return redirect()->back();
        }

        $setting = Setting::getSettings();
        $setting->active_couriers = array_values(array_map('strtolower', $couriers));
        $setting->active_payment_methods = array_values(array_map('strtolower', $payments));
        $setting->save();

        Alert::success('Berhasil', 'Opsi pengiriman kurir dan metode pembayaran berhasil diperbarui!');
        return redirect()->back();
    }

    /**
     * AJAX Toggle Aktif/Nonaktif Kurir Pengiriman.
     */
    public function toggleCourier(Request $request)
    {
        $code = strtolower(trim((string) $request->input('code', $request->input('courier', ''))));
        if (!array_key_exists($code, Setting::ALL_COURIERS)) {
            return response()->json(['success' => false, 'message' => 'Kode kurir tidak dikenali.'], 400);
        }

        $setting = Setting::getSettings();
        $current = $setting->getActiveCouriers();

        // Jika mencoba mematikan dan hanya tersisa 1 kurir
        if (in_array($code, $current, true) && count($current) <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal harus ada 1 layanan kurir yang aktif agar pelanggan dapat checkout.',
                'is_active' => true,
            ], 422);
        }

        $nowActive = $setting->toggleCourier($code);
        $courierName = Setting::ALL_COURIERS[$code]['name'] ?? strtoupper($code);

        return response()->json([
            'success' => true,
            'code' => $code,
            'courier' => $code,
            'is_active' => $nowActive,
            'active_count' => count($setting->getActiveCouriers()),
            'message' => 'Layanan kurir ' . $courierName . ($nowActive ? ' berhasil diaktifkan!' : ' dinonaktifkan.'),
        ]);
    }

    /**
     * AJAX Toggle Aktif/Nonaktif Metode Pembayaran.
     */
    public function togglePayment(Request $request)
    {
        $method = strtolower(trim((string) $request->input('method', $request->input('payment', ''))));
        if (!array_key_exists($method, Setting::ALL_PAYMENTS)) {
            return response()->json(['success' => false, 'message' => 'Metode pembayaran tidak dikenali.'], 400);
        }

        $setting = Setting::getSettings();
        $current = $setting->getActivePaymentMethods();

        // Jika mencoba mematikan dan hanya tersisa 1 metode pembayaran
        if (in_array($method, $current, true) && count($current) <= 1) {
            return response()->json([
                'success' => false,
                'message' => 'Minimal harus ada 1 metode pembayaran yang aktif agar transaksi dapat diproses.',
                'is_active' => true,
            ], 422);
        }

        $nowActive = $setting->togglePaymentMethod($method);
        $payName = Setting::ALL_PAYMENTS[$method]['name'] ?? strtoupper($method);

        return response()->json([
            'success' => true,
            'method' => $method,
            'payment' => $method,
            'is_active' => $nowActive,
            'active_count' => count($setting->getActivePaymentMethods()),
            'message' => 'Metode ' . $payName . ($nowActive ? ' berhasil diaktifkan!' : ' dinonaktifkan.'),
        ]);
    }
}
