<?php

namespace App\Http\Controllers;

use App\Models\Banner;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use RealRashid\SweetAlert\Facades\Alert;
use Illuminate\Support\Facades\Storage;
use Exception;

class BannerController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $banners = Banner::orderBy('urutan', 'asc')->orderBy('id_banner', 'desc')->get();

        return view('banner.banner', [
            'banners' => $banners
        ]);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('banner.banner-add');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'judul' => 'required|max:150',
            'subjudul' => 'nullable|max:150',
            'link' => 'nullable|max:255',
            'urutan' => 'nullable|integer',
            'gambar' => 'required|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        if ($request->hasFile('gambar')) {
            $validated['gambar'] = ImageCompressor::compressAndStore($request->file('gambar'), 'banners', 1600, 82);
        }

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['urutan'] = $request->filled('urutan') ? $request->urutan : ((Banner::max('urutan') ?? 0) + 1);
        $validated['link'] = $request->filled('link') ? $request->link : '/frontend#product-grid';

        Banner::create($validated);

        Alert::success('Sukses', 'Banner baru berhasil ditambahkan!');
        return redirect('/banner');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit($id_banner)
    {
        $banner = Banner::findOrFail($id_banner);

        return view('banner.banner-edit', [
            'banner' => $banner,
        ]);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, $id_banner)
    {
        $validated = $request->validate([
            'judul' => 'required|max:150',
            'subjudul' => 'nullable|max:150',
            'link' => 'nullable|max:255',
            'urutan' => 'nullable|integer',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,gif,webp|max:4096',
        ]);

        $banner = Banner::findOrFail($id_banner);

        if ($request->hasFile('gambar')) {
            // Hapus gambar lama jika ada di storage/banners
            if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }

            $validated['gambar'] = ImageCompressor::compressAndStore($request->file('gambar'), 'banners', 1600, 82);
        }

        $validated['status'] = $request->has('status') ? 1 : 0;
        $validated['link'] = $request->filled('link') ? $request->link : '/frontend#product-grid';

        $banner->update($validated);

        Alert::info('Sukses', 'Banner berhasil diperbarui!');
        return redirect('/banner');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy($id_banner)
    {
        try {
            $banner = Banner::findOrFail($id_banner);

            if ($banner->gambar && Storage::disk('public')->exists($banner->gambar)) {
                Storage::disk('public')->delete($banner->gambar);
            }

            $banner->delete();

            Alert::success('Sukses', 'Banner berhasil dihapus!');
            return redirect('/banner');
        } catch (Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan saat menghapus banner.');
            return redirect('/banner');
        }
    }
}

