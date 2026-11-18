<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\BarangGambar;
use App\Models\BarangVariasi;
use App\Models\Kategori;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Facades\DB;

class BarangController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $barang = Barang::with(['gambars', 'variasis'])->orderBy('id_barang', 'desc')->get();

        return view('barang.barang', [
            'barang' => $barang
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $mainKategoris = Kategori::main()->with('children')->get();
        $kategoris = $mainKategoris;
        return view('barang.barang-add', compact('kategoris', 'mainKategoris'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100|unique:barang',
            'kategori' => 'required',
            'sub_kategori' => 'nullable|string|max:100',
            'supplier' => 'required',
            'stok' => 'nullable|integer',
            'harga' => 'required|numeric',
            'promo_aktif' => 'nullable|boolean',
            'promo_persen' => 'nullable|integer|min:1|max:99',
            'harga_promo' => 'nullable|numeric|min:0',
            'promo_mulai' => 'nullable|date',
            'promo_selesai' => 'nullable|date|after_or_equal:promo_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'galeri_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'variasi_warna.*' => 'nullable|string|max:50',
            'variasi_ukuran.*' => 'nullable|string|max:50',
            'variasi_stok.*' => 'nullable|integer|min:0',
            'variasi_harga.*' => 'nullable|numeric|min:0',
        ]);

        DB::transaction(function () use ($request, $validated) {
            // 1. Simpan foto utama
            if ($request->hasFile('gambar')) {
                $validated['gambar'] = ImageCompressor::compressAndStore($request->file('gambar'), 'barang_images', 1200, 80);
            }

            // Hitung total stok dari variasi jika ada
            $totalStok = intval($request->input('stok', 0));
            $variasiStokList = $request->input('variasi_stok', []);
            if (!empty($variasiStokList) && is_array($variasiStokList)) {
                $sumVariantStock = array_sum(array_map('intval', $variasiStokList));
                if ($sumVariantStock > 0) {
                    $totalStok = $sumVariantStock;
                }
            }
            $validated['stok'] = $totalStok;
            $validated = array_merge($validated, $this->extractPromoPayload($request, intval($validated['harga'])));

            $barang = Barang::create($validated);

            // 2. Simpan galeri gambar (banyak foto)
            if ($request->hasFile('galeri_gambar')) {
                $urutan = 1;
                foreach ($request->file('galeri_gambar') as $file) {
                    $path = ImageCompressor::compressAndStore($file, 'barang_images', 1200, 80);
                    BarangGambar::create([
                        'barang_id' => $barang->id_barang,
                        'gambar' => $path,
                        'urutan' => $urutan++,
                    ]);
                }
            }

            // 3. Simpan variasi ukuran & warna
            $variantTotal = $this->syncVariasis($request, $barang);
            if ($variantTotal !== null) {
                $barang->stok = $variantTotal;
                $barang->save();
            }
        });

        Alert::success('Sukses', 'Produk beserta foto galeri dan variasi berhasil disimpan!');
        return redirect('/barang');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_barang)
    {
        $barang = Barang::with(['gambars', 'variasis'])->findOrFail($id_barang);
        $mainKategoris = Kategori::main()->with('children')->get();
        $kategoris = $mainKategoris;

        return view('barang.barang-edit', compact('barang', 'kategoris', 'mainKategoris'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_barang)
    {
        $validated = $request->validate([
            'nama' => 'required|max:100|unique:barang,nama,' . $id_barang . ',id_barang',
            'kategori' => 'required',
            'sub_kategori' => 'nullable|string|max:100',
            'supplier' => 'required',
            'stok' => 'nullable|integer',
            'harga' => 'required|numeric',
            'promo_aktif' => 'nullable|boolean',
            'promo_persen' => 'nullable|integer|min:1|max:99',
            'harga_promo' => 'nullable|numeric|min:0',
            'promo_mulai' => 'nullable|date',
            'promo_selesai' => 'nullable|date|after_or_equal:promo_mulai',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'galeri_gambar.*' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:3072',
            'variasi_warna.*' => 'nullable|string|max:50',
            'variasi_ukuran.*' => 'nullable|string|max:50',
            'variasi_stok.*' => 'nullable|integer|min:0',
            'variasi_harga.*' => 'nullable|numeric|min:0',
        ]);

        $barang = Barang::findOrFail($id_barang);

        DB::transaction(function () use ($request, $barang, $validated) {
            // 1. Update foto utama jika ada
            if ($request->hasFile('gambar')) {
                if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                    Storage::disk('public')->delete($barang->gambar);
                }
                $validated['gambar'] = ImageCompressor::compressAndStore($request->file('gambar'), 'barang_images', 1200, 80);
            }

            // 2. Simpan foto galeri baru
            if ($request->hasFile('galeri_gambar')) {
                $lastUrutan = $barang->gambars()->max('urutan') ?: 0;
                foreach ($request->file('galeri_gambar') as $file) {
                    $path = ImageCompressor::compressAndStore($file, 'barang_images', 1200, 80);
                    BarangGambar::create([
                        'barang_id' => $barang->id_barang,
                        'gambar' => $path,
                        'urutan' => ++$lastUrutan,
                    ]);
                }
            }

            // 3. Update variasi (hapus lama, buat baru yang diinput)
            $barang->variasis()->delete();
            $variantTotal = $this->syncVariasis($request, $barang);

            // Jika ada variasi, sinkronkan total stok produk
            $validated['stok'] = $variantTotal !== null
                ? $variantTotal
                : intval($request->input('stok', $barang->stok));

            $validated = array_merge($validated, $this->extractPromoPayload($request, intval($validated['harga'])));

            $barang->update($validated);
        });

        Alert::info('Sukses', 'Produk, galeri gambar, dan variasi berhasil diperbarui!');
        return redirect('/barang');
    }

    /**
     * Simpan variasi produk dari request dan kembalikan total stok variasi (atau null jika tidak ada variasi).
     */
    private function syncVariasis(Request $request, Barang $barang): ?int
    {
        $warnas = $request->input('variasi_warna', []);
        $ukurans = $request->input('variasi_ukuran', []);
        $stoks = $request->input('variasi_stok', []);
        $hargas = $request->input('variasi_harga', []);

        if (!is_array($warnas)) {
            return null;
        }

        $hasVariants = false;
        $totalVariantStock = 0;

        foreach ($warnas as $index => $warna) {
            $ukuran = $ukurans[$index] ?? null;
            $stok = isset($stoks[$index]) ? intval($stoks[$index]) : 0;
            $harga = !empty($hargas[$index]) ? intval($hargas[$index]) : null;

            if (!empty($warna) || !empty($ukuran)) {
                $hasVariants = true;
                $totalVariantStock += $stok;
                BarangVariasi::create([
                    'barang_id' => $barang->id_barang,
                    'warna' => trim((string) $warna),
                    'ukuran' => trim((string) $ukuran),
                    'stok' => $stok,
                    'harga' => $harga,
                ]);
            }
        }

        return $hasVariants ? $totalVariantStock : null;
    }

    /**
     * Toggle cepat status aktif/nonaktif promo produk dari daftar barang admin.
     */
    public function togglePromo($id_barang)
    {
        $barang = Barang::findOrFail($id_barang);
        $barang->promo_aktif = !$barang->promo_aktif;
        $barang->save();

        $statusText = $barang->promo_aktif ? 'diaktifkan' : 'dinonaktifkan';
        Alert::success('Promo Diperbarui', 'Promo untuk "' . $barang->nama . '" telah ' . $statusText . '.');
        return redirect('/barang');
    }

    /**
     * Normalisasi payload promo & durasi promo dari form admin.
     */
    private function extractPromoPayload(Request $request, int $harga): array
    {
        $promoAktif = $request->boolean('promo_aktif');
        $persen = $request->filled('promo_persen') ? intval($request->input('promo_persen')) : null;
        $hargaPromo = $request->filled('harga_promo') ? intval($request->input('harga_promo')) : null;

        if ($persen && !$hargaPromo && $harga > 0) {
            $hargaPromo = max(0, (int) round($harga - ($harga * ($persen / 100))));
        } elseif ($hargaPromo && $hargaPromo < $harga && !$persen && $harga > 0) {
            $persen = max(1, min(99, (int) round((($harga - $hargaPromo) / $harga) * 100)));
        }

        return [
            'promo_aktif' => $promoAktif,
            'promo_persen' => $persen,
            'harga_promo' => $hargaPromo,
            'promo_mulai' => $request->filled('promo_mulai') ? $request->input('promo_mulai') : null,
            'promo_selesai' => $request->filled('promo_selesai') ? $request->input('promo_selesai') : null,
        ];
    }

    /**
     * Hapus satu gambar galeri.
     */
    public function deleteGambar($id_gambar)
    {
        $gambar = BarangGambar::findOrFail($id_gambar);
        if ($gambar->gambar && Storage::disk('public')->exists($gambar->gambar)) {
            Storage::disk('public')->delete($gambar->gambar);
        }
        $gambar->delete();

        Alert::success('Sukses', 'Foto galeri berhasil dihapus!');
        return redirect()->back();
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_barang)
    {
        try {
            $barang = Barang::with('gambars')->findOrFail($id_barang);

            // Hapus file gambar utama
            if ($barang->gambar && Storage::disk('public')->exists($barang->gambar)) {
                Storage::disk('public')->delete($barang->gambar);
            }

            // Hapus file gambar galeri
            foreach ($barang->gambars as $g) {
                if ($g->gambar && Storage::disk('public')->exists($g->gambar)) {
                    Storage::disk('public')->delete($g->gambar);
                }
            }

            $barang->delete();

            Alert::success('Sukses', 'Produk berhasil dihapus!');
            return redirect('/barang');
        } catch (Exception $ex) {
            Alert::warning('Peringatan', 'Tidak dapat menghapus, produk sedang digunakan dalam transaksi!');
            return redirect('/barang');
        }
    }

    public function print()
    {
        $barang = Barang::with(['gambars', 'variasis'])->get();
        return view('barang.print-barang', compact('barang'));
    }
}
