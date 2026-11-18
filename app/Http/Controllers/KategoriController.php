<?php

namespace App\Http\Controllers;

use App\Models\Kategori;
use App\Models\Barang;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Support\Str;
use RealRashid\SweetAlert\Facades\Alert;
use Exception;

class KategoriController extends Controller
{
    /**
     * Display a hierarchical listing of categories and sub-categories.
     */
    public function index()
    {
        $mainKategoris = Kategori::main()
            ->with(['children' => function ($q) {
                $q->withCount('subBarangs');
            }])
            ->withCount('barangs')
            ->get();

        return view('kategori.kategori', compact('mainKategoris'));
    }

    /**
     * Show the form for creating a new category or sub-category.
     */
    public function create(Request $request)
    {
        $parentKategoris = Kategori::main()->get();
        $selectedParentId = $request->query('parent_id');
        return view('kategori.kategori-add', compact('parentKategoris', 'selectedParentId'));
    }

    /**
     * Store a newly created category / sub-category in storage.
     */
    public function store(Request $request)
    {
        $validated = $request->validate([
            'parent_id' => 'nullable|exists:kategoris,id_kategori',
            'nama_kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'keterangan' => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
            'gambar.image' => 'File harus berupa gambar.',
            'gambar.max' => 'Ukuran gambar maksimal 2MB.',
        ]);

        try {
            $parent = !empty($validated['parent_id']) ? Kategori::find($validated['parent_id']) : null;
            $baseSlug = $parent
                ? Str::slug($parent->nama_kategori . '-' . $validated['nama_kategori'])
                : Str::slug($validated['nama_kategori']);

            // Pastikan slug unik
            $slug = $baseSlug;
            $counter = 1;
            while (Kategori::where('slug', $slug)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $gambarPath = null;
            if ($request->hasFile('gambar')) {
                $gambarPath = $request->file('gambar')->store('kategori_images', 'public');
            }

            Kategori::create([
                'parent_id' => $validated['parent_id'] ?? null,
                'nama_kategori' => $validated['nama_kategori'],
                'slug' => $slug,
                'gambar' => $gambarPath,
                'urutan' => intval($validated['urutan'] ?? 0),
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            Alert::success('Sukses', ($parent ? 'Sub-kategori' : 'Kategori utama') . ' baru berhasil ditambahkan!');
            return redirect()->route('kategori.index');
        } catch (Exception $e) {
            Alert::error('Gagal', 'Terjadi kesalahan: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Show the form for editing the specified category.
     */
    public function edit($id)
    {
        $kategori = Kategori::where('id_kategori', $id)->firstOrFail();
        $parentKategoris = Kategori::main()->where('id_kategori', '!=', $id)->get();
        return view('kategori.kategori-edit', compact('kategori', 'parentKategoris'));
    }

    /**
     * Update the specified category in storage.
     */
    public function update(Request $request, $id)
    {
        $kategori = Kategori::where('id_kategori', $id)->firstOrFail();

        $validated = $request->validate([
            'parent_id' => 'nullable|exists:kategoris,id_kategori',
            'nama_kategori' => 'required|string|max:100',
            'urutan' => 'nullable|integer|min:0',
            'gambar' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:2048',
            'keterangan' => 'nullable|string',
        ], [
            'nama_kategori.required' => 'Nama kategori wajib diisi.',
        ]);

        try {
            $oldName = $kategori->nama_kategori;
            $parentId = $validated['parent_id'] ?? null;
            if ($parentId == $kategori->id_kategori) {
                $parentId = null;
            }

            $parent = $parentId ? Kategori::find($parentId) : null;
            $baseSlug = $parent
                ? Str::slug($parent->nama_kategori . '-' . $validated['nama_kategori'])
                : Str::slug($validated['nama_kategori']);

            $slug = $baseSlug;
            $counter = 1;
            while (Kategori::where('slug', $slug)->where('id_kategori', '!=', $id)->exists()) {
                $slug = $baseSlug . '-' . $counter++;
            }

            $gambarPath = $kategori->gambar;
            if ($request->hasFile('gambar')) {
                if ($gambarPath && Storage::disk('public')->exists($gambarPath)) {
                    Storage::disk('public')->delete($gambarPath);
                }
                $gambarPath = $request->file('gambar')->store('kategori_images', 'public');
            }

            $kategori->update([
                'parent_id' => $parentId,
                'nama_kategori' => $validated['nama_kategori'],
                'slug' => $slug,
                'gambar' => $gambarPath,
                'urutan' => intval($validated['urutan'] ?? 0),
                'keterangan' => $validated['keterangan'] ?? null,
            ]);

            // Sinkronkan nama kategori / sub-kategori pada tabel barang jika berubah
            if ($oldName !== $validated['nama_kategori']) {
                if (is_null($parentId)) {
                    Barang::where('kategori', $oldName)->update(['kategori' => $validated['nama_kategori']]);
                } else {
                    Barang::where('sub_kategori', $oldName)->update(['sub_kategori' => $validated['nama_kategori']]);
                }
            }

            Alert::success('Sukses', 'Data kategori berhasil diperbarui!');
            return redirect()->route('kategori.index');
        } catch (Exception $e) {
            Alert::error('Gagal', 'Gagal memperbarui kategori: ' . $e->getMessage());
            return redirect()->back()->withInput();
        }
    }

    /**
     * Remove the specified category from storage.
     */
    public function destroy($id)
    {
        try {
            $kategori = Kategori::with('children')->where('id_kategori', $id)->firstOrFail();
            $nama = $kategori->nama_kategori;

            // Hapus gambar sub-kategori jika parent dihapus
            foreach ($kategori->children as $child) {
                if ($child->gambar && Storage::disk('public')->exists($child->gambar)) {
                    Storage::disk('public')->delete($child->gambar);
                }
            }
            if ($kategori->gambar && Storage::disk('public')->exists($kategori->gambar)) {
                Storage::disk('public')->delete($kategori->gambar);
            }

            $kategori->delete();

            Alert::success('Sukses', 'Kategori ' . $nama . ' berhasil dihapus!');
            return redirect()->route('kategori.index');
        } catch (Exception $e) {
            Alert::error('Gagal', 'Gagal menghapus kategori: ' . $e->getMessage());
            return redirect()->route('kategori.index');
        }
    }
}
