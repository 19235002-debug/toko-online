<?php

namespace App\Http\Controllers;

use App\Models\Voucher;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;

class VoucherController extends Controller
{
    public function index()
    {
        $vouchers = Voucher::latest('id_voucher')->get();
        return view('voucher.voucher', compact('vouchers'));
    }

    public function create()
    {
        return view('voucher.voucher-add');
    }

    public function store(Request $request)
    {
        $request->merge([
            'kode_voucher' => strtoupper(preg_replace('/\s+/', '', trim($request->kode_voucher)))
        ]);

        $validated = $request->validate([
            'kode_voucher' => 'required|string|max:50|unique:vouchers,kode_voucher',
            'nama_voucher' => 'required|string|max:100',
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|integer|min:1',
            'maksimal_diskon' => 'nullable|integer|min:0',
            'minimal_belanja' => 'nullable|integer|min:0',
            'kuota' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|boolean',
        ], [
            'kode_voucher.unique' => 'Kode voucher ini sudah digunakan. Gunakan kode lain.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        if ($validated['tipe'] === 'persen' && intval($validated['nilai']) > 100) {
            return back()->withInput()->withErrors(['nilai' => 'Persentase diskon maksimal 100%.']);
        }

        $validated['minimal_belanja'] = $validated['minimal_belanja'] ?? 0;
        $validated['status'] = $request->has('status');

        Voucher::create($validated);

        Alert::success('Berhasil!', 'Voucher promo ' . $validated['kode_voucher'] . ' berhasil ditambahkan.');
        return redirect()->route('voucher.index');
    }

    public function edit($id)
    {
        $voucher = Voucher::findOrFail($id);
        return view('voucher.voucher-edit', compact('voucher'));
    }

    public function update(Request $request, $id)
    {
        $voucher = Voucher::findOrFail($id);

        $request->merge([
            'kode_voucher' => strtoupper(preg_replace('/\s+/', '', trim($request->kode_voucher)))
        ]);

        $validated = $request->validate([
            'kode_voucher' => 'required|string|max:50|unique:vouchers,kode_voucher,' . $voucher->id_voucher . ',id_voucher',
            'nama_voucher' => 'required|string|max:100',
            'tipe' => 'required|in:persen,nominal',
            'nilai' => 'required|integer|min:1',
            'maksimal_diskon' => 'nullable|integer|min:0',
            'minimal_belanja' => 'nullable|integer|min:0',
            'kuota' => 'nullable|integer|min:1',
            'tanggal_mulai' => 'nullable|date',
            'tanggal_selesai' => 'nullable|date|after_or_equal:tanggal_mulai',
            'status' => 'nullable|boolean',
        ], [
            'kode_voucher.unique' => 'Kode voucher ini sudah digunakan. Gunakan kode lain.',
            'tanggal_selesai.after_or_equal' => 'Tanggal selesai tidak boleh lebih awal dari tanggal mulai.',
        ]);

        if ($validated['tipe'] === 'persen' && intval($validated['nilai']) > 100) {
            return back()->withInput()->withErrors(['nilai' => 'Persentase diskon maksimal 100%.']);
        }

        $validated['minimal_belanja'] = $validated['minimal_belanja'] ?? 0;
        $validated['status'] = $request->has('status');

        $voucher->update($validated);

        Alert::success('Berhasil!', 'Voucher promo ' . $voucher->kode_voucher . ' berhasil diperbarui.');
        return redirect()->route('voucher.index');
    }

    public function toggleStatus($id)
    {
        $voucher = Voucher::findOrFail($id);
        $voucher->status = !$voucher->status;
        $voucher->save();

        $statusText = $voucher->status ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Berhasil!', 'Voucher ' . $voucher->kode_voucher . ' telah ' . $statusText . '.');
        return redirect()->route('voucher.index');
    }

    public function destroy($id)
    {
        $voucher = Voucher::findOrFail($id);
        $kode = $voucher->kode_voucher;
        $voucher->delete();

        Alert::success('Berhasil!', 'Voucher ' . $kode . ' berhasil dihapus.');
        return redirect()->route('voucher.index');
    }
}

