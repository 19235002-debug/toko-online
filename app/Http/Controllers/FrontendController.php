<?php

namespace App\Http\Controllers;

use App\Models\Barang;
use App\Models\Banner;
use App\Models\Order;
use App\Models\OrderItem;
use App\Models\Kategori;
use App\Models\Review;
use App\Models\BarangVariasi;
use App\Models\BarangGambar;
use App\Models\Voucher;
use App\Services\ImageCompressor;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Validator;
use Midtrans\Config;
use Midtrans\Snap;
use Midtrans\Notification;
use Exception;

class FrontendController extends Controller
{
    /**
     * Display the frontend storefront with live products from the database.
     */
    public function index(Request $request)
    {
        $kategori = $request->query('kategori');
        $search = $request->query('search');

        $query = Barang::query();

        if ($kategori && $kategori !== 'all') {
            $query->where('kategori', $kategori);
        }

        if ($search) {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('supplier', 'like', '%' . $search . '%');
            });
        }

        $totalProduk = (clone $query)->count();
        $barang = $query->with(['approvedReviews', 'gambars', 'variasis'])->orderBy('id_barang', 'desc')->take(8)->get();
        $promoProducts = Barang::with(['approvedReviews', 'gambars', 'variasis'])->promoActive()->orderBy('id_barang', 'desc')->take(8)->get();
        $mainKategoris = Kategori::main()->with('children')->get();
        $categories = $mainKategoris->pluck('nama_kategori');
        if ($categories->isEmpty()) {
            $categories = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');
        }
        $banners = Banner::where('status', true)->orderBy('urutan', 'asc')->orderBy('id_banner', 'desc')->get();
        $vouchers = Voucher::aktifTersedia()->orderBy('id_voucher', 'desc')->get();

        return view('frontend.index', compact('barang', 'promoProducts', 'categories', 'mainKategoris', 'banners', 'vouchers', 'totalProduk'));
    }

    /**
     * Halaman Katalog Produk Lengkap (/shop) dengan filter, pencarian, pengurutan, dan pagination.
     */
    public function shop(Request $request)
    {
        $kategori = $request->query('kategori');
        $subKategori = $request->query('sub_kategori');
        $search = trim($request->query('search', ''));
        $sort = $request->query('sort', 'terbaru');
        $minHarga = $request->query('min_harga');
        $maxHarga = $request->query('max_harga');
        $readyStock = $request->query('ready_stock');
        $onlyPromo = $request->query('promo');

        $query = Barang::query();

        if ($kategori && $kategori !== 'all') {
            $query->where('kategori', $kategori);
        }

        if ($subKategori && $subKategori !== 'all') {
            $query->where('sub_kategori', $subKategori);
        }

        if ($search !== '') {
            $query->where(function ($q) use ($search) {
                $q->where('nama', 'like', '%' . $search . '%')
                  ->orWhere('kategori', 'like', '%' . $search . '%')
                  ->orWhere('sub_kategori', 'like', '%' . $search . '%')
                  ->orWhere('supplier', 'like', '%' . $search . '%');
            });
        }

        if ($minHarga !== null && $minHarga !== '') {
            $query->whereRaw('CAST(harga AS UNSIGNED) >= ?', [intval($minHarga)]);
        }

        if ($maxHarga !== null && $maxHarga !== '') {
            $query->whereRaw('CAST(harga AS UNSIGNED) <= ?', [intval($maxHarga)]);
        }

        if ($readyStock == '1') {
            $query->whereRaw('CAST(stok AS SIGNED) > 0');
        }

        if ($onlyPromo == '1') {
            $query->promoActive();
        }

        switch ($sort) {
            case 'termurah':
                $query->orderByRaw('CAST(harga AS UNSIGNED) ASC');
                break;
            case 'termahal':
                $query->orderByRaw('CAST(harga AS UNSIGNED) DESC');
                break;
            case 'nama':
                $query->orderBy('nama', 'asc');
                break;
            default:
                $query->orderBy('id_barang', 'desc');
                break;
        }

        /** @var \Illuminate\Pagination\LengthAwarePaginator $barang */
        $barang = $query->with(['approvedReviews', 'gambars', 'variasis'])
            ->paginate(12);
        $barang->withQueryString();

        $mainKategoris = Kategori::main()->with('children')->get();
        $categories = $mainKategoris->pluck('nama_kategori');
        if ($categories->isEmpty()) {
            $categories = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');
        }
        $categoryCounts = Barang::select('kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');
        $allProductsCount = Barang::count();
        $vouchers = Voucher::aktifTersedia()->orderBy('id_voucher', 'desc')->get();

        return view('frontend.shop', compact(
            'barang',
            'categories',
            'mainKategoris',
            'categoryCounts',
            'allProductsCount',
            'vouchers',
            'kategori',
            'subKategori',
            'search',
            'sort',
            'minHarga',
            'maxHarga',
            'readyStock',
            'onlyPromo'
        ));
    }

    /**
     * Return single product details for AJAX Quick View modal.
     */
    public function show($id)
    {
        $item = Barang::with(['approvedReviews', 'gambars', 'variasis'])->findOrFail($id);

        $reviews = $item->approvedReviews()->latest('id_review')->get()->map(function ($r) {
            return [
                'id' => $r->id_review,
                'nama' => $r->nama_reviewer,
                'rating' => $r->rating,
                'komentar' => $r->komentar,
                'foto' => $r->foto ? asset('storage/' . $r->foto) : null,
                'kode_order' => $r->kode_order,
                'is_verified' => !empty($r->order_id) || !empty($r->kode_order),
                'tanggal' => $r->created_at->format('d M Y'),
            ];
        });

        // Kumpulkan semua foto (foto utama + galeri banyak foto)
        $images = [];
        if ($item->gambar) {
            $images[] = asset('storage/' . $item->gambar);
        }
        foreach ($item->gambars as $g) {
            $images[] = asset('storage/' . $g->gambar);
        }
        if (empty($images)) {
            $images[] = asset('frontend-assets/images/product-detail-01.jpg');
        }

        // Kumpulkan variasi (warna & ukuran)
        $variasis = $item->variasis->map(function ($v) use ($item) {
            return [
                'id_variasi' => $v->id_variasi,
                'warna' => $v->warna,
                'ukuran' => $v->ukuran,
                'stok' => $v->stok,
                'harga' => $v->harga ? $item->hitungHargaPromo($v->harga) : null,
                'harga_asli' => $v->harga ? intval($v->harga) : null,
                'label' => $v->label,
            ];
        });

        $canReview = false;
        $reviewMessage = 'Silakan login dengan akun yang telah membeli produk ini untuk menulis ulasan.';
        if (auth()->check()) {
            $eligibleOrder = $this->findEligibleReviewOrderForUser(auth()->user(), $item->id_barang);
            if ($eligibleOrder['order']) {
                $canReview = true;
                $reviewMessage = '';
            } else {
                $reviewMessage = $eligibleOrder['message'];
            }
        }

        return response()->json([
            'id_barang' => $item->id_barang,
            'nama' => $item->nama,
            'kategori' => $item->kategori,
            'supplier' => $item->supplier,
            'stok' => $item->stok_aktual,
            'harga' => $item->harga_final,
            'harga_asli' => intval($item->harga),
            'harga_formatted' => 'Rp ' . number_format($item->harga_final, 0, ',', '.'),
            'is_promo_active' => $item->is_promo_active,
            'diskon_persen' => $item->diskon_persen_aktual,
            'promo_selesai' => $item->promo_selesai ? $item->promo_selesai->format('d M Y H:i') : null,
            'gambar' => $images[0],
            'images' => $images,
            'has_variants' => $item->variasis->count() > 0,
            'variasis' => $variasis,
            'ukuran_list' => $item->ukuran_list,
            'warna_list' => $item->warna_list,
            'avg_rating' => $item->average_rating,
            'total_reviews' => $item->total_reviews,
            'reviews' => $reviews,
            'can_review' => $canReview,
            'review_message' => $reviewMessage,
        ]);
    }

    /**
     * Simpan ulasan dan rating baru untuk produk.
     * HANYA jika pembeli sudah pernah membeli produk ini dan pesanan sudah berstatus 'selesai' (sampai ke penerima).
     */
    public function storeReview(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'barang_id' => 'required|exists:barang,id_barang',
            'order_ref' => 'nullable|string|max:100',
            'nama_reviewer' => 'required|string|max:100',
            'email_reviewer' => 'nullable|email|max:150',
            'rating' => 'required|integer|min:1|max:5',
            'komentar' => 'required|string|min:5|max:1000',
            'foto' => 'nullable|image|mimes:jpeg,png,jpg,webp|max:5120',
        ], [
            'nama_reviewer.required' => 'Nama lengkap wajib diisi.',
            'rating.required' => 'Silakan pilih rating bintang (1-5).',
            'komentar.required' => 'Tuliskan ulasan atau pengalaman Anda tentang produk ini.',
            'komentar.min' => 'Ulasan minimal 5 karakter.',
            'foto.image' => 'File foto ulasan harus berupa gambar (JPG, PNG, atau WEBP).',
            'foto.max' => 'Ukuran foto maksimal 5 MB.',
        ]);

        if ($validator->fails()) {
            return response()->json([
                'status' => 'error',
                'message' => $validator->errors()->first(),
            ], 422);
        }

        $order = null;

        if ($request->filled('order_ref')) {
            $orderRef = trim($request->order_ref);
            $order = Order::with('items')
                ->where(function ($q) use ($orderRef) {
                    $q->where('kode_order', $orderRef)
                      ->orWhere('no_hp', $orderRef);
                })
                ->latest('id_order')
                ->first();

            if (!$order) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Pesanan "' . $orderRef . '" tidak ditemukan.',
                ], 422);
            }

            $itemPurchased = $order->items->firstWhere('barang_id', $request->barang_id);
            if (!$itemPurchased) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Produk ini tidak ada di dalam riwayat pesanan ' . $order->kode_order . '.',
                ], 422);
            }

            if ($order->status !== 'selesai') {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Ulasan hanya dapat ditulis jika pesanan sudah sampai ke penerima dan berstatus "Selesai".',
                ], 422);
            }

            $existingReview = Review::where('order_id', $order->id_order)
                ->where('barang_id', $request->barang_id)
                ->first();

            if ($existingReview) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Anda sudah pernah memberikan ulasan untuk produk ini pada pesanan ' . $order->kode_order . '.',
                ], 422);
            }
        } else {
            if (!auth()->check()) {
                return response()->json([
                    'status' => 'error',
                    'message' => 'Silakan login terlebih dahulu dengan akun yang sudah membeli produk ini.',
                ], 401);
            }

            $eligible = $this->findEligibleReviewOrderForUser(auth()->user(), $request->barang_id);
            if (!$eligible['order']) {
                return response()->json([
                    'status' => 'error',
                    'message' => $eligible['message'],
                ], 422);
            }
            $order = $eligible['order'];
        }

        $fotoPath = null;
        if ($request->hasFile('foto')) {
            $fotoPath = $this->compressAndStoreReviewPhoto($request->file('foto'));
        }

        $review = Review::create([
            'barang_id' => $request->barang_id,
            'order_id' => $order->id_order,
            'kode_order' => $order->kode_order,
            'nama_reviewer' => strip_tags($request->nama_reviewer ?: $order->nama_customer),
            'email_reviewer' => $request->email_reviewer ? strip_tags($request->email_reviewer) : null,
            'rating' => $request->rating,
            'komentar' => strip_tags($request->komentar),
            'foto' => $fotoPath,
            'status' => 'approved',
        ]);

        return response()->json([
            'status' => 'success',
            'message' => 'Terima kasih! Ulasan Anda berhasil disimpan.',
            'review' => [
                'nama' => $review->nama_reviewer,
                'rating' => $review->rating,
                'komentar' => $review->komentar,
                'foto' => $review->foto ? asset('storage/' . $review->foto) : null,
                'kode_order' => $order->kode_order,
                'is_verified' => true,
                'tanggal' => $review->created_at->format('d M Y'),
            ],
        ]);
    }

    /**
     * Cek otomatis apakah user yang login memiliki pesanan berstatus 'selesai' untuk produk ini yang belum diulas.
     */
    private function findEligibleReviewOrderForUser($user, $barangId): array
    {
        $userOrders = Order::where(function ($q) use ($user) {
                $q->where('user_id', $user->id_user);
                if (!empty($user->no_hp)) {
                    $q->orWhere('no_hp', $user->no_hp);
                }
            })
            ->whereHas('items', function ($q) use ($barangId) {
                $q->where('barang_id', $barangId);
            })
            ->latest('id_order')
            ->get();

        if ($userOrders->isEmpty()) {
            return [
                'order' => null,
                'message' => 'Anda belum memiliki riwayat pembelian untuk produk ini. Ulasan hanya dapat ditulis setelah membeli dan pesanan selesai.',
            ];
        }

        $completedOrders = $userOrders->where('status', 'selesai');
        if ($completedOrders->isEmpty()) {
            return [
                'order' => null,
                'message' => 'Pesanan Anda untuk produk ini belum berstatus "Selesai". Ulasan dapat ditulis setelah paket diterima.',
            ];
        }

        foreach ($completedOrders as $compOrder) {
            $alreadyReviewed = Review::where('order_id', $compOrder->id_order)
                ->where('barang_id', $barangId)
                ->exists();
            if (!$alreadyReviewed) {
                return ['order' => $compOrder, 'message' => ''];
            }
        }

        return [
            'order' => null,
            'message' => 'Anda sudah memberikan ulasan untuk seluruh pembelian produk ini.',
        ];
    }

    /**
     * Kompres foto ulasan (resize maks 800x800 & JPEG quality 72) agar ukuran file kecil.
     */
    private function compressAndStoreReviewPhoto($file): string
    {
        return ImageCompressor::compressAndStore($file, 'review_images', 800, 72);
    }

    /**
     * Konfirmasi pesanan telah sampai dan diterima oleh pembeli.
     */
    public function confirmReceived(Request $request, $kode_order)
    {
        $order = Order::where('kode_order', $kode_order)->firstOrFail();

        if ($order->status !== 'dikirim') {
            return redirect()->back()->with('error', 'Pesanan tidak dalam status pengiriman.');
        }

        $order->status = 'selesai';
        $order->save();

        return redirect()->route('frontend.track', ['order' => $kode_order])
            ->with('success', 'Pesanan telah dikonfirmasi sampai dan selesai! Silakan berikan ulasan Anda di bawah.');
    }

    /**
     * Halaman lacak pesanan (Tracking Order) untuk pembeli.
     */
    public function trackOrder(Request $request)
    {
        $keyword = trim($request->query('order') ?? $request->query('q') ?? '');
        $order = null;
        $searched = false;

        if ($keyword !== '') {
            $searched = true;
            $order = Order::with('items.barang')
                ->where('kode_order', $keyword)
                ->orWhere('no_hp', $keyword)
                ->latest('id_order')
                ->first();

            // Jika pesanan Midtrans masih pending, sinkronkan otomatis sebelum halaman tampil
            if ($order && $order->status === 'pending' && $order->metode_pembayaran === 'midtrans') {
                OrderController::syncOrderStatusWithMidtrans($order);
                $order->refresh();
            }
        }

        return view('frontend.track-order', compact('order', 'keyword', 'searched'));
    }

    /**
     * Halaman "Pesanan Saya" (Riwayat Belanja) untuk pelanggan yang sudah login.
     */
    public function myOrders(Request $request)
    {
        $user = auth()->user();
        if (!$user) {
            return redirect()->route('login');
        }

        // Sinkronkan otomatis transaksi Midtrans yang masih pending milik user ini
        $pendingOrders = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id_user);
            if ($user->no_hp) {
                $q->orWhere('no_hp', $user->no_hp);
            }
        })
        ->where('status', 'pending')
        ->where('metode_pembayaran', 'midtrans')
        ->get();

        foreach ($pendingOrders as $pOrder) {
            OrderController::syncOrderStatusWithMidtrans($pOrder);
        }

        $orders = Order::with('items.barang')
            ->where(function ($q) use ($user) {
                $q->where('user_id', $user->id_user);
                if ($user->no_hp) {
                    $q->orWhere('no_hp', $user->no_hp);
                }
            })
            ->latest('id_order')
            ->paginate(10);

        return view('frontend.pesanan-saya', compact('orders', 'user'));
    }

    /**
     * Cek dan validasi kode voucher dari keranjang / checkout modal via AJAX.
     */
    public function checkVoucher(Request $request)
    {
        $request->validate([
            'kode_voucher' => 'required|string|max:50',
            'subtotal' => 'required|numeric|min:0',
        ]);

        $kode = strtoupper(trim($request->kode_voucher));
        $subtotal = intval($request->subtotal);

        $voucher = Voucher::where('kode_voucher', $kode)->first();
        if (!$voucher) {
            return response()->json([
                'success' => false,
                'message' => 'Kode voucher "' . $kode . '" tidak ditemukan.'
            ], 422);
        }

        $check = $voucher->isValidForTotal($subtotal);
        if (!$check['valid']) {
            return response()->json([
                'success' => false,
                'message' => $check['message']
            ], 422);
        }

        $diskon = $check['diskon'];
        $totalAkhir = max(0, $subtotal - $diskon);

        return response()->json([
            'success' => true,
            'kode_voucher' => $voucher->kode_voucher,
            'nama_voucher' => $voucher->nama_voucher,
            'diskon' => $diskon,
            'diskon_formatted' => 'Rp ' . number_format($diskon, 0, ',', '.'),
            'total_akhir' => $totalAkhir,
            'total_akhir_formatted' => 'Rp ' . number_format($totalAkhir, 0, ',', '.'),
            'message' => $check['message']
        ]);
    }

    /**
     * Validate cart stock before opening checkout modal.
     */
    public function validateCartStock(Request $request)
    {
        $cart = is_string($request->cart_items) ? json_decode($request->cart_items, true) : $request->cart_items;

        if (empty($cart) || !is_array($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja Anda kosong!',
            ], 422);
        }

        $stockMap = [];
        $errors = [];

        foreach ($cart as $item) {
            $id = $item['id'] ?? null;
            $qty = intval($item['qty'] ?? 1);
            $variasiId = !empty($item['variasi_id']) ? intval($item['variasi_id']) : null;
            $cartKey = $item['cart_key'] ?? ($id . ($variasiId ? '_v' . $variasiId : ''));

            if (!$id || $qty <= 0) continue;

            $barang = Barang::find($id);
            if (!$barang) {
                $errors[] = 'Produk "' . ($item['nama'] ?? 'Pilihan') . '" tidak ditemukan.';
                continue;
            }

            if ($variasiId) {
                $variant = BarangVariasi::where('id_variasi', $variasiId)
                    ->where('barang_id', $barang->id_barang)
                    ->first();

                if (!$variant) {
                    $errors[] = 'Varian produk "' . $barang->nama . '" sudah tidak tersedia.';
                    continue;
                }

                $available = intval($variant->stok);
                $stockMap[$cartKey] = $available;

                if ($available <= 0) {
                    $errors[] = 'Stok varian "' . $variant->label . '" untuk "' . $barang->nama . '" sedang habis.';
                } elseif ($qty > $available) {
                    $errors[] = 'Stok varian "' . $variant->label . '" untuk "' . $barang->nama . '" tidak mencukupi (tersedia: ' . $available . ' unit, di keranjang: ' . $qty . ' unit).';
                }
            } else {
                $available = intval($barang->stok);
                $stockMap[$cartKey] = $available;

                if ($available <= 0) {
                    $errors[] = 'Stok produk "' . $barang->nama . '" sedang habis.';
                } elseif ($qty > $available) {
                    $errors[] = 'Stok produk "' . $barang->nama . '" tidak mencukupi (tersedia: ' . $available . ' unit, di keranjang: ' . $qty . ' unit).';
                }
            }
        }

        if (!empty($errors)) {
            return response()->json([
                'success' => false,
                'message' => $errors[0],
                'errors' => $errors,
                'stock_map' => $stockMap,
            ], 422);
        }

        return response()->json([
            'success' => true,
            'stock_map' => $stockMap,
        ]);
    }

    /**
     * Ambil data keranjang & wishlist user yang tersimpan di database.
     */
    public function getUserSyncState()
    {
        $userId = auth()->id();

        $cartRows = \App\Models\CartItem::with(['barang', 'variation'])
            ->where('user_id', $userId)
            ->get();

        $cart = [];
        foreach ($cartRows as $row) {
            if (!$row->barang) continue;
            $basePrice = ($row->variation && !empty($row->variation->harga))
                ? intval($row->variation->harga)
                : intval($row->barang->harga);
            $finalPrice = $row->barang->hitungHargaPromo($basePrice);
            $maxStok = $row->variation ? intval($row->variation->stok) : $row->barang->stok_aktual;
            if ($maxStok <= 0) continue;

            $cartKey = $row->barang_id . ($row->variasi_id ? '_v' . $row->variasi_id : '');
            $cart[] = [
                'id' => (string) $row->barang_id,
                'cart_key' => $cartKey,
                'variasi_id' => $row->variasi_id,
                'variasi' => $row->variation ? $row->variation->label : $row->variasi,
                'nama' => $row->barang->nama,
                'harga' => $finalPrice,
                'gambar' => $row->barang->gambar
                    ? asset('storage/' . $row->barang->gambar)
                    : asset('frontend-assets/images/product-01.jpg'),
                'qty' => min(intval($row->qty), $maxStok),
                'max_stok' => $maxStok,
            ];
        }

        $wishlistRows = \App\Models\WishlistItem::with('barang')
            ->where('user_id', $userId)
            ->get();

        $wishlist = [];
        foreach ($wishlistRows as $w) {
            if (!$w->barang) continue;
            $wishlist[] = [
                'id' => (string) $w->barang_id,
                'nama' => $w->barang->nama,
                'kategori' => $w->barang->kategori,
                'harga' => $w->barang->harga_final,
                'gambar' => $w->barang->gambar
                    ? asset('storage/' . $w->barang->gambar)
                    : asset('frontend-assets/images/product-01.jpg'),
            ];
        }

        return response()->json([
            'success' => true,
            'cart' => $cart,
            'wishlist' => $wishlist,
        ]);
    }

    /**
     * Sinkronisasi keranjang belanja dari frontend ke database user.
     */
    public function syncUserCart(Request $request)
    {
        $userId = auth()->id();
        $items = is_string($request->cart_items) ? json_decode($request->cart_items, true) : $request->cart_items;

        \App\Models\CartItem::where('user_id', $userId)->delete();

        if (is_array($items)) {
            foreach ($items as $item) {
                $barangId = intval($item['id'] ?? 0);
                $qty = max(1, intval($item['qty'] ?? 1));
                $variasiId = !empty($item['variasi_id']) ? intval($item['variasi_id']) : null;
                if ($barangId <= 0) continue;

                \App\Models\CartItem::create([
                    'user_id' => $userId,
                    'barang_id' => $barangId,
                    'variasi_id' => $variasiId,
                    'variasi' => $item['variasi'] ?? null,
                    'qty' => $qty,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Sinkronisasi wishlist dari frontend ke database user.
     */
    public function syncUserWishlist(Request $request)
    {
        $userId = auth()->id();
        $items = is_string($request->wishlist_items) ? json_decode($request->wishlist_items, true) : $request->wishlist_items;

        \App\Models\WishlistItem::where('user_id', $userId)->delete();

        if (is_array($items)) {
            foreach ($items as $item) {
                $barangId = intval($item['id'] ?? 0);
                if ($barangId <= 0) continue;

                \App\Models\WishlistItem::firstOrCreate([
                    'user_id' => $userId,
                    'barang_id' => $barangId,
                ]);
            }
        }

        return response()->json(['success' => true]);
    }

    /**
     * Process checkout from cart.
     */
    public function checkout(Request $request)
    {
        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'no_hp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string',
            'metode_pembayaran' => 'required|in:midtrans,cod',
            'kurir' => 'nullable|in:reguler,express',
            'catatan' => 'nullable|string',
            'kode_voucher' => 'nullable|string|max:50',
            'cart_items' => 'required',
        ], [
            'nama_customer.required' => 'Nama lengkap wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'no_hp.regex' => 'Nomor WhatsApp / HP hanya boleh berupa angka (10 - 15 digit, contoh: 081234567890).',
            'alamat.required' => 'Alamat pengiriman wajib diisi.',
        ]);

        $cart = is_string($request->cart_items) ? json_decode($request->cart_items, true) : $request->cart_items;

        if (empty($cart) || !is_array($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja Anda kosong!',
            ], 422);
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart) {
                $totalHarga = 0;
                $validatedItems = [];

                // 1. Validasi ketersediaan stok
                foreach ($cart as $item) {
                    $id = $item['id'] ?? null;
                    $qty = intval($item['qty'] ?? 1);
                    $variasiId = !empty($item['variasi_id']) ? intval($item['variasi_id']) : null;
                    $variasiText = $item['variasi'] ?? null;

                    if (!$id || $qty <= 0) continue;

                    $barang = Barang::where('id_barang', $id)->lockForUpdate()->first();

                    if (!$barang) {
                        throw new Exception('Produk "' . ($item['nama'] ?? $item['name'] ?? 'Pilihan') . '" tidak ditemukan.');
                    }

                    $itemPrice = intval($barang->harga);

                    // Jika item memilih varian tertentu
                    if ($variasiId) {
                        $variant = BarangVariasi::where('id_variasi', $variasiId)->where('barang_id', $barang->id_barang)->lockForUpdate()->first();
                        if ($variant) {
                            if (intval($variant->stok) < $qty) {
                                throw new Exception('Stok varian "' . $variant->label . '" untuk produk "' . $barang->nama . '" tidak mencukupi (sisa: ' . $variant->stok . ', dipesan: ' . $qty . ').');
                            }
                            if (!empty($variant->harga)) {
                                $itemPrice = intval($variant->harga);
                            }
                            $variasiText = $variant->label;
                        }
                    } else {
                        if (intval($barang->stok) < $qty) {
                            throw new Exception('Stok produk "' . $barang->nama . '" tidak mencukupi (sisa: ' . $barang->stok . ', dipesan: ' . $qty . ').');
                        }
                    }

                    // Terapkan harga promo jika promo produk sedang aktif & dalam durasi
                    $itemPrice = $barang->hitungHargaPromo($itemPrice);

                    $subtotal = $itemPrice * $qty;
                    $totalHarga += $subtotal;

                    $validatedItems[] = [
                        'barang' => $barang,
                        'variasi_id' => $variasiId,
                        'variasi' => $variasiText,
                        'harga' => $itemPrice,
                        'qty' => $qty,
                        'subtotal' => $subtotal,
                    ];
                }

                if (empty($validatedItems)) {
                    throw new Exception('Tidak ada item valid di dalam keranjang.');
                }

                // Cek & terapkan voucher jika diisi
                $kodeVoucher = null;
                $diskon = 0;
                if (!empty($validated['kode_voucher'])) {
                    $kode = strtoupper(trim($validated['kode_voucher']));
                    $voucher = Voucher::where('kode_voucher', $kode)->lockForUpdate()->first();
                    if (!$voucher) {
                        throw new Exception('Kode voucher "' . $kode . '" tidak ditemukan.');
                    }
                    $check = $voucher->isValidForTotal($totalHarga);
                    if (!$check['valid']) {
                        throw new Exception($check['message']);
                    }
                    $diskon = $check['diskon'];
                    $kodeVoucher = $voucher->kode_voucher;
                    $voucher->digunakan = intval($voucher->digunakan) + 1;
                    $voucher->save();
                }

                $storeSetting = \App\Models\Setting::getSettings();
                $regulerRate = intval($storeSetting->ongkir_reguler ?? 15000);
                $expressRate = intval($storeSetting->ongkir_express ?? 30000);
                $minFreeRate = intval($storeSetting->min_gratis_ongkir ?? 300000);

                $kurir = $validated['kurir'] ?? null;
                $ongkir = 0;
                $kurirLabel = 'Gratis Ongkir';
                if ($kurir === 'express') {
                    $ongkir = $expressRate;
                    $kurirLabel = 'Express (1 Hari)';
                } elseif ($kurir === 'reguler') {
                    $ongkir = ($totalHarga >= $minFreeRate) ? 0 : $regulerRate;
                    $kurirLabel = $ongkir === 0 ? 'Reguler (Gratis Ongkir)' : 'Reguler (2-3 Hari)';
                }

                $finalTotal = max(0, $totalHarga - $diskon) + $ongkir;

                // 2. Buat record Order
                $order = Order::create([
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'kode_order' => Order::generateKodeOrder(),
                    'nama_customer' => $validated['nama_customer'],
                    'no_hp' => $validated['no_hp'],
                    'alamat' => $validated['alamat'],
                    'catatan' => $validated['catatan'] ?? null,
                    'kode_voucher' => $kodeVoucher,
                    'diskon' => $diskon,
                    'kurir' => $kurirLabel,
                    'ekspedisi' => $kurirLabel,
                    'ongkir' => $ongkir,
                    'metode_pembayaran' => $validated['metode_pembayaran'],
                    'total_harga' => $finalTotal,
                    'status' => 'pending',
                ]);

                // Auto-lengkapi profil akun lama jika no_hp atau alamat sebelumnya kosong
                if (auth()->check()) {
                    $authUser = \App\Models\User::find(auth()->id());
                    if ($authUser) {
                        $changed = false;
                        if (empty($authUser->no_hp)) {
                            $authUser->no_hp = $validated['no_hp'];
                            $changed = true;
                        }
                        if (empty($authUser->alamat)) {
                            $authUser->alamat = $validated['alamat'];
                            $changed = true;
                        }
                        if ($changed) {
                            $authUser->save();
                        }
                    }
                }

                // 3. Simpan item pesanan & kurangi stok produk & variasi
                foreach ($validatedItems as $vItem) {
                    OrderItem::create([
                        'order_id' => $order->id_order,
                        'barang_id' => $vItem['barang']->id_barang,
                        'variasi_id' => $vItem['variasi_id'] ?? null,
                        'nama_barang' => $vItem['barang']->nama,
                        'variasi' => $vItem['variasi'] ?? null,
                        'harga' => $vItem['harga'],
                        'qty' => $vItem['qty'],
                        'subtotal' => $vItem['subtotal'],
                    ]);

                    if (!empty($vItem['variasi_id'])) {
                        $vObj = BarangVariasi::find($vItem['variasi_id']);
                        if ($vObj) {
                            $vObj->stok = max(0, intval($vObj->stok) - $vItem['qty']);
                            $vObj->save();
                        }
                        $vItem['barang']->stok = intval($vItem['barang']->variasis()->sum('stok'));
                        $vItem['barang']->save();
                    } else {
                        $vItem['barang']->stok = max(0, intval($vItem['barang']->stok) - $vItem['qty']);
                        $vItem['barang']->save();
                    }
                }

                return $order;
            });

            if (auth()->check()) {
                \App\Models\CartItem::where('user_id', auth()->id())->delete();
            }

            // 4. Jika metode pembayaran adalah Midtrans, buat Snap Token
            $snapToken = null;
            if ($order->metode_pembayaran === 'midtrans') {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = (bool) config('midtrans.is_production', false);
                Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
                Config::$is3ds = (bool) config('midtrans.is_3ds', true);

                $itemDetails = [];
                foreach ($order->items as $item) {
                    $itemDetails[] = [
                        'id' => (string) ($item->barang_id ?? $item->id_order_item),
                        'price' => intval($item->harga),
                        'quantity' => intval($item->qty),
                        'name' => substr($item->nama_barang, 0, 50),
                    ];
                }

                if ($order->diskon > 0) {
                    $itemDetails[] = [
                        'id' => 'VOUCHER-' . ($order->kode_voucher ?? 'PROMO'),
                        'price' => -intval($order->diskon),
                        'quantity' => 1,
                        'name' => substr('Diskon Voucher (' . ($order->kode_voucher ?? 'PROMO') . ')', 0, 50),
                    ];
                }

                if ($order->ongkir > 0) {
                    $itemDetails[] = [
                        'id' => 'ONGKIR',
                        'price' => intval($order->ongkir),
                        'quantity' => 1,
                        'name' => substr('Ongkos Kirim (' . ($order->kurir ?? 'Kurir') . ')', 0, 50),
                    ];
                }

                $params = [
                    'transaction_details' => [
                        'order_id' => $order->kode_order,
                        'gross_amount' => intval($order->total_harga),
                    ],
                    'item_details' => $itemDetails,
                    'customer_details' => [
                        'first_name' => $order->nama_customer,
                        'phone' => $order->no_hp,
                        'billing_address' => [
                            'first_name' => $order->nama_customer,
                            'phone' => $order->no_hp,
                            'address' => $order->alamat,
                        ],
                        'shipping_address' => [
                            'first_name' => $order->nama_customer,
                            'phone' => $order->no_hp,
                            'address' => $order->alamat,
                        ],
                    ],
                ];

                try {
                    $snapToken = Snap::getSnapToken($params);
                    $order->snap_token = $snapToken;
                    $order->save();
                } catch (Exception $midtransErr) {
                    throw new Exception('Koneksi Midtrans Sandbox: ' . $midtransErr->getMessage() . '. Pastikan MIDTRANS_SERVER_KEY di file .env sudah diisi dengan Server Key akun Sandbox Midtrans Anda.');
                }
            }

            // 5. Siapkan pesan WhatsApp untuk konfirmasi pesanan instan
            $waMessage = "Halo Admin Toko, saya telah membuat pesanan baru:\n\n";
            $waMessage .= "Nomor Pesanan: *" . $order->kode_order . "*\n";
            $waMessage .= "Nama: *" . $order->nama_customer . "*\n";
            $waMessage .= "No HP: " . $order->no_hp . "\n";
            $waMessage .= "Alamat: " . $order->alamat . "\n";
            $waMessage .= "Metode Bayar: *" . strtoupper($order->metode_pembayaran) . "*\n";
            $waMessage .= "Total Bayar: *Rp " . number_format($order->total_harga, 0, ',', '.') . "*\n\n";
            $waMessage .= "Mohon segera diproses ya. Terima kasih!";

            $waUrl = store_wa_url($waMessage);

            return response()->json([
                'success' => true,
                'message' => 'Pesanan berhasil dibuat dengan nomor ' . $order->kode_order . '!',
                'kode_order' => $order->kode_order,
                'total_harga' => $order->total_harga,
                'total_formatted' => 'Rp ' . number_format($order->total_harga, 0, ',', '.'),
                'metode_pembayaran' => $order->metode_pembayaran,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
                'wa_url' => $waUrl,
            ]);

        } catch (Exception $e) {
            return response()->json([
                'success' => false,
                'message' => $e->getMessage(),
            ], 400);
        }
    }

    /**
     * Handle Midtrans Webhook / HTTP Notification callback.
     */
    public function midtransCallback(Request $request)
    {
        Config::$serverKey = config('midtrans.server_key');
        Config::$isProduction = (bool) config('midtrans.is_production', false);

        try {
            $notification = new Notification();

            $transactionStatus = $notification->transaction_status;
            $orderId = $notification->order_id;
            $fraudStatus = $notification->fraud_status;

            $order = Order::with('items')->where('kode_order', $orderId)->first();
            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            $oldStatus = $order->status;

            if ($transactionStatus == 'capture') {
                if ($fraudStatus == 'challenge') {
                    $order->status = 'pending';
                } else if ($fraudStatus == 'accept') {
                    $order->status = 'diproses';
                }
            } else if ($transactionStatus == 'settlement') {
                $order->status = 'diproses';
            } else if ($transactionStatus == 'pending') {
                $order->status = 'pending';
            } else if (in_array($transactionStatus, ['deny', 'expire', 'cancel'])) {
                $order->status = 'dibatalkan';
                if ($oldStatus !== 'dibatalkan') {
                    OrderController::restoreOrderStockAndVoucher($order);
                }
            }

            $order->save();

            return response()->json(['message' => 'Status pesanan ' . $orderId . ' berhasil diselaraskan dengan Midtrans.']);

        } catch (Exception $e) {
            return response()->json(['error' => $e->getMessage()], 500);
        }
    }

    /**
     * Endpoint untuk sinkronisasi instan dari browser saat pop-up Snap selesai/ditutup.
     */
    public function syncOrder($kodeOrder)
    {
        $order = Order::with('items')->where('kode_order', $kodeOrder)->first();
        if (!$order) {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak ditemukan'], 404);
        }

        $updated = OrderController::syncOrderStatusWithMidtrans($order);
        $order->refresh();

        return response()->json([
            'success' => true,
            'kode_order' => $order->kode_order,
            'status' => $order->status,
            'updated' => $updated
        ]);
    }

    /**
     * Pembeli membatalkan pesanan sendiri (khusus status pending).
     */
    public function cancelOrder($kode_order)
    {
        $user = auth()->user();
        $order = Order::with('items')->where('kode_order', $kode_order)->firstOrFail();

        $isOwner = ($order->user_id && $order->user_id == $user->id_user)
            || ($user->no_hp && $order->no_hp === $user->no_hp);

        if (!$isOwner && !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak berhak membatalkan pesanan ini.');
        }

        if ($order->status !== 'pending') {
            \RealRashid\SweetAlert\Facades\Alert::warning('Tidak Dapat Dibatalkan', 'Pesanan yang sudah diproses atau dikirim tidak dapat dibatalkan.');
            return back();
        }

        DB::transaction(function () use ($order) {
            $order->status = 'dibatalkan';
            $order->save();
            OrderController::restoreOrderStockAndVoucher($order);
        });

        \RealRashid\SweetAlert\Facades\Alert::success('Pesanan Dibatalkan', 'Pesanan ' . $order->kode_order . ' telah dibatalkan dan stok produk telah dikembalikan.');
        return redirect()->route('frontend.my_orders');
    }

    /**
     * Cetak / lihat invoice oleh pembeli di halaman Pesanan Saya.
     */
    public function printCustomerInvoice($kode_order)
    {
        $user = auth()->user();
        $order = Order::with('items.barang')->where('kode_order', $kode_order)->firstOrFail();

        $isOwner = ($order->user_id && $order->user_id == $user->id_user)
            || ($user->no_hp && $order->no_hp === $user->no_hp);

        if (!$isOwner && !in_array($user->role, ['admin', 'superadmin'])) {
            abort(403, 'Anda tidak berhak mengakses invoice ini.');
        }

        return view('order.print-invoice', compact('order'));
    }
}
