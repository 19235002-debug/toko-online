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
use App\Models\Setting;
use App\Services\ImageCompressor;
use App\Services\BiteshipService;
use App\Services\RajaOngkirService;
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
        $promoProducts = Barang::with(['approvedReviews', 'gambars', 'variasis'])->promoActive()->orderBy('id_barang', 'desc')->take(4)->get();
        $mainKategoris = \Illuminate\Support\Facades\Cache::remember('frontend_main_kategoris', 600, function () {
            return Kategori::main()->with('children')->get();
        });
        $categories = $mainKategoris->pluck('nama_kategori');
        if ($categories->isEmpty()) {
            $categories = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');
        }
        $banners = \Illuminate\Support\Facades\Cache::remember('frontend_active_banners', 600, function () {
            return Banner::where('status', true)->orderBy('urutan', 'asc')->orderBy('id_banner', 'desc')->get();
        });
        $vouchers = \Illuminate\Support\Facades\Cache::remember('frontend_active_vouchers', 600, function () {
            return Voucher::aktifTersedia()->orderBy('id_voucher', 'desc')->get();
        });

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

        $mainKategoris = \Illuminate\Support\Facades\Cache::remember('frontend_main_kategoris', 600, function () {
            return Kategori::main()->with('children')->get();
        });
        $categories = $mainKategoris->pluck('nama_kategori');
        if ($categories->isEmpty()) {
            $categories = Barang::select('kategori')->whereNotNull('kategori')->distinct()->pluck('kategori');
        }
        $categoryCounts = Barang::select('kategori', DB::raw('COUNT(*) as total'))
            ->groupBy('kategori')
            ->pluck('total', 'kategori');
        $allProductsCount = Barang::count();
        $vouchers = \Illuminate\Support\Facades\Cache::remember('frontend_active_vouchers', 600, function () {
            return Voucher::aktifTersedia()->orderBy('id_voucher', 'desc')->get();
        });

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
            'promo_selesai_iso' => $item->promo_selesai ? $item->promo_selesai->toIso8601String() : null,
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
        $order->completed_at = now();
        if ($order->metode_pembayaran === 'cod' && !$order->paid_at) {
            $order->paid_at = now();
        }
        $order->save();

        if ($request->has('from_my_orders')) {
            return redirect()->route('frontend.my_orders')
                ->with('success', 'Pesanan ' . $kode_order . ' berhasil dikonfirmasi selesai! Terima kasih sudah berbelanja.');
        }

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
                ->where(function ($q) use ($keyword) {
                    $q->where('kode_order', $keyword)
                        ->orWhere('no_resi', $keyword)
                        ->orWhere('no_hp', $keyword);
                })
                ->latest('id_order')
                ->first();

            // Jika pesanan Midtrans masih pending, sinkronkan otomatis sebelum halaman tampil
            if ($order && $order->status === 'pending' && $order->metode_pembayaran === 'midtrans') {
                OrderController::syncOrderStatusWithMidtrans($order);
                $order->refresh();
            }

            // Jika pesanan belum dibayar dan sudah melewati batas 3 hari, otomatis batalkan
            if ($order && $order->status === 'pending' && !$order->paid_at && $order->metode_pembayaran !== 'cod' && $order->created_at <= now()->subDays(3)) {
                OrderController::cancelUnpaidOrders(3);
                $order->refresh();
            }
        }

        // Riwayat perjalanan paket dari Biteship / kurir (jika resi sudah diinput admin / otomatis terbit)
        $tracking = null;
        if ($order && $order->no_resi && in_array($order->status, ['dikirim', 'selesai'], true)) {
            $tracking = BiteshipService::trackWaybill($order->no_resi, $order->kurir_kode ?: (string) $order->ekspedisi);
            if (!$tracking) {
                $tracking = RajaOngkirService::trackWaybill($order->no_resi, $order->kurir_kode ?: (string) $order->ekspedisi);
            }
            if ($order->status === 'dikirim' && !empty($tracking)) {
                if (OrderController::syncOrderWithBiteship($order, $tracking)) {
                    $order->refresh();
                }
            }
        }

        return view('frontend.track-order', compact('order', 'keyword', 'searched', 'tracking'));
    }

    /**
     * Nama ekspedisi yang rapi dari kode kurir RajaOngkir.
     */
    public static function courierDisplayName(string $code, ?string $fallback = null): string
    {
        $map = [
            'jne' => 'JNE',
            'jnt' => 'J&T',
            'sicepat' => 'SiCepat',
            'anteraja' => 'AnterAja',
            'pos' => 'POS Indonesia',
            'tiki' => 'TIKI',
            'ninja' => 'Ninja Xpress',
            'lion' => 'Lion Parcel',
            'ide' => 'ID Express',
            'sap' => 'SAP Express',
            'wahana' => 'Wahana',
        ];

        return $map[strtolower($code)] ?? ($fallback ?: strtoupper($code));
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

        // Batalkan pesanan belum dibayar yang melewati batas 3 hari
        OrderController::cancelUnpaidOrders(3);

        $baseQuery = Order::where(function ($q) use ($user) {
            $q->where('user_id', $user->id_user);
            if ($user->no_hp) {
                $q->orWhere('no_hp', $user->no_hp);
            }
        });

        // Hitung count riil per status untuk user ini
        $rawCounts = (clone $baseQuery)
            ->selectRaw('status, count(*) as count')
            ->groupBy('status')
            ->pluck('count', 'status')
            ->toArray();

        $counts = [
            'all' => array_sum($rawCounts),
            'pending' => $rawCounts['pending'] ?? 0,
            'diproses' => $rawCounts['diproses'] ?? 0,
            'dikirim' => $rawCounts['dikirim'] ?? 0,
            'selesai' => $rawCounts['selesai'] ?? 0,
            'dibatalkan' => $rawCounts['dibatalkan'] ?? 0,
        ];

        $activeStatus = $request->query('status', 'all');
        $query = (clone $baseQuery)->with('items.barang');

        if ($activeStatus && $activeStatus !== 'all' && in_array($activeStatus, ['pending', 'diproses', 'dikirim', 'selesai', 'dibatalkan'])) {
            $query->where('status', $activeStatus);
        }

        $orders = $query->latest('id_order')
            ->paginate(10)
            ->appends($request->query());

        return view('frontend.pesanan-saya', compact('orders', 'user', 'counts', 'activeStatus'));
    }

    /**
     * Cari kecamatan / kelurahan / kota tujuan pengiriman via Biteship Maps API (fallback RajaOngkir).
     */
    public function shippingDestinations(Request $request)
    {
        $q = trim((string) $request->query('q', ''));
        $results = BiteshipService::searchArea($q, 15);

        if (empty($results)) {
            $results = RajaOngkirService::searchDestination($q, 15);
        }

        return response()->json([
            'success' => true,
            'destinations' => $results,
        ]);
    }

    /**
     * Hitung daftar tarif ongkir kurir (JNE, J&T, SiCepat, Anteraja) via Biteship Rates API.
     */
    public function shippingCost(Request $request)
    {
        $validated = $request->validate([
            'destination_id' => 'required',
            'total_qty' => 'nullable|integer|min:1',
            'destination_postal_code' => 'nullable|string|max:10',
        ]);

        $qty = max(1, intval($validated['total_qty'] ?? 1));
        $weightGrams = BiteshipService::weightForQty($qty);
        $postal = $request->input('destination_postal_code');

        $options = BiteshipService::calculateCost($validated['destination_id'], $weightGrams, $postal);

        if (empty($options)) {
            $options = RajaOngkirService::calculateCost($validated['destination_id'], $weightGrams);
        }

        // Filter hanya kurir yang aktif di pengaturan admin toko
        $activeCouriers = Setting::getSettings()->getActiveCouriers();
        $options = array_values(array_filter($options, function ($opt) use ($activeCouriers) {
            $code = strtolower($opt['code'] ?? '');
            return in_array($code, $activeCouriers, true);
        }));

        return response()->json([
            'success' => true,
            'weight_grams' => $weightGrams,
            'options' => $options,
        ]);
    }

    /**
     * Ambil daftar voucher aktif yang tersedia untuk ditampilkan di modal checkout.
     */
    public function availableVouchers(Request $request)
    {
        $subtotal = max(0, intval($request->query('subtotal', 0)));
        $today = now()->toDateString();

        $vouchers = Voucher::where('status', true)
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_mulai')->orWhereDate('tanggal_mulai', '<=', $today);
            })
            ->where(function ($q) use ($today) {
                $q->whereNull('tanggal_selesai')->orWhereDate('tanggal_selesai', '>=', $today);
            })
            ->where(function ($q) {
                $q->whereNull('kuota')->orWhereColumn('digunakan', '<', 'kuota');
            })
            ->orderBy('minimal_belanja', 'asc')
            ->get()
            ->map(function ($v) use ($subtotal) {
                $minBelanja = intval($v->minimal_belanja ?? 0);
                $eligible = $subtotal >= $minBelanja;
                $labelDiskon = $v->tipe === 'persen'
                    ? 'Diskon ' . intval($v->nilai) . '%'
                    : 'Potongan Rp ' . number_format(intval($v->nilai), 0, ',', '.');

                return [
                    'kode_voucher' => $v->kode_voucher,
                    'nama_voucher' => $v->nama_voucher,
                    'label_diskon' => $labelDiskon,
                    'minimal_belanja' => $minBelanja,
                    'maksimal_diskon' => $v->maksimal_diskon ? intval($v->maksimal_diskon) : null,
                    'tanggal_selesai' => $v->tanggal_selesai ? $v->tanggal_selesai->format('d M Y') : null,
                    'eligible' => $eligible,
                    'kurang' => max(0, $minBelanja - $subtotal),
                ];
            })
            ->values();

        return response()->json([
            'success' => true,
            'vouchers' => $vouchers,
        ]);
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
        $activePayments = Setting::getSettings()->getActivePaymentMethods();
        $activeCouriers = Setting::getSettings()->getActiveCouriers();

        $validated = $request->validate([
            'nama_customer' => 'required|string|max:100',
            'no_hp' => ['required', 'regex:/^[0-9]{10,15}$/'],
            'alamat' => 'required|string',
            'metode_pembayaran' => ['required', 'in:' . implode(',', $activePayments)],
            'destination_id' => 'required',
            'destination_label' => 'required|string|max:255',
            'destination_postal_code' => 'nullable|string|max:10',
            'courier_code' => 'required|string|max:20',
            'courier_service' => 'required|string|max:50',
            'catatan' => 'nullable|string',
            'kode_voucher' => 'nullable|string|max:50',
            'cart_items' => 'required',
        ], [
            'nama_customer.required' => 'Nama lengkap wajib diisi.',
            'no_hp.required' => 'Nomor WhatsApp / HP wajib diisi.',
            'no_hp.regex' => 'Nomor WhatsApp / HP hanya boleh berupa angka (10 - 15 digit, contoh: 081234567890).',
            'alamat.required' => 'Alamat pengiriman wajib diisi.',
            'metode_pembayaran.in' => 'Metode pembayaran yang dipilih sedang tidak aktif atau dinonaktifkan oleh toko.',
            'destination_id.required' => 'Pilih kecamatan / kelurahan tujuan pengiriman terlebih dahulu.',
            'destination_label.required' => 'Pilih kecamatan / kelurahan tujuan pengiriman terlebih dahulu.',
            'courier_code.required' => 'Pilih layanan kurir pengiriman terlebih dahulu.',
            'courier_service.required' => 'Pilih layanan kurir pengiriman terlebih dahulu.',
        ]);

        if (!in_array(strtolower($validated['courier_code']), $activeCouriers, true)) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan kurir ' . strtoupper($validated['courier_code']) . ' sedang dinonaktifkan oleh toko. Silakan pilih kurir lain.',
            ], 422);
        }

        $cart = is_string($request->cart_items) ? json_decode($request->cart_items, true) : $request->cart_items;

        if (empty($cart) || !is_array($cart)) {
            return response()->json([
                'success' => false,
                'message' => 'Keranjang belanja Anda kosong!',
            ], 422);
        }

        // Hitung ulang ongkir di server berdasarkan tarif resmi Biteship / kurir
        $totalQty = 0;
        foreach ($cart as $c) {
            $totalQty += max(0, intval($c['qty'] ?? 1));
        }
        $beratGram = BiteshipService::weightForQty($totalQty);
        $postal = $request->input('destination_postal_code');
        $shipping = BiteshipService::findOption(
            $validated['destination_id'],
            $beratGram,
            $validated['courier_code'],
            $validated['courier_service'],
            $postal
        );

        if (!$shipping) {
            $shipping = RajaOngkirService::findOption(
                $validated['destination_id'],
                $beratGram,
                $validated['courier_code'],
                $validated['courier_service']
            );
        }

        if (!$shipping) {
            return response()->json([
                'success' => false,
                'message' => 'Layanan kurir yang dipilih tidak tersedia untuk tujuan ini. Silakan pilih ulang kurir pengiriman.',
            ], 422);
        }

        try {
            $order = DB::transaction(function () use ($validated, $cart, $shipping, $beratGram) {
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

                $ongkir = intval($shipping['cost']);

                $kurirKode = strtolower($shipping['code']);
                $layanan = strtoupper($shipping['service']);
                $ekspedisi = self::courierDisplayName($kurirKode, $shipping['courier_name'] ?? null);
                $kurirLabel = $ekspedisi . ' ' . $layanan;

                $finalTotal = max(0, $totalHarga - $diskon) + $ongkir;

                // 2. Buat record Order
                $order = Order::create([
                    'user_id' => auth()->check() ? auth()->id() : null,
                    'kode_order' => Order::generateKodeOrder(),
                    'nama_customer' => $validated['nama_customer'],
                    'no_hp' => $validated['no_hp'],
                    'alamat' => trim($validated['alamat']),
                    'destination_id' => is_numeric($validated['destination_id']) ? (int) $validated['destination_id'] : null,
                    'destination_area_id' => (string) $validated['destination_id'],
                    'destination_postal_code' => $validated['destination_postal_code'] ?? null,
                    'destination_label' => trim($validated['destination_label']),
                    'catatan' => $validated['catatan'] ?? null,
                    'kode_voucher' => $kodeVoucher,
                    'diskon' => $diskon,
                    'kurir' => $kurirLabel,
                    'kurir_kode' => $kurirKode,
                    'layanan_kurir' => $layanan,
                    'estimasi' => $shipping['etd'] ?? null,
                    'berat' => $beratGram,
                    'ekspedisi' => $ekspedisi,
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

                $fullAddress = trim($order->alamat . ', ' . $order->destination_label, ', ');

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
                            'address' => substr($fullAddress, 0, 200),
                        ],
                        'shipping_address' => [
                            'first_name' => $order->nama_customer,
                            'phone' => $order->no_hp,
                            'address' => substr($fullAddress, 0, 200),
                        ],
                    ],
                    // Batas waktu pembayaran: lewat 3 hari otomatis expire & pesanan dibatalkan
                    'expiry' => [
                        'unit' => 'days',
                        'duration' => 3,
                    ],
                ];

                try {
                    $snapToken = Snap::getSnapToken($params);
                    $order->snap_token = $snapToken;
                    $order->midtrans_order_id = $order->kode_order;
                    $order->save();
                } catch (Exception $midtransErr) {
                    // Batalkan pesanan & kembalikan stok agar tidak ada pesanan "menggantung" tanpa token bayar
                    DB::transaction(function () use ($order) {
                        $order->status = 'dibatalkan';
                        $order->cancelled_at = now();
                        $order->catatan = trim(($order->catatan ?? '') . ' [Otomatis dibatalkan: gagal membuat transaksi Midtrans]');
                        $order->save();
                        OrderController::restoreOrderStockAndVoucher($order);
                    });
                    throw new Exception('Koneksi Midtrans Sandbox: ' . $midtransErr->getMessage() . '. Pesanan dibatalkan otomatis dan stok dikembalikan. Pastikan MIDTRANS_SERVER_KEY di file .env sudah benar, lalu coba lagi.');
                }
            }

            // 5. Siapkan pesan WhatsApp untuk konfirmasi pesanan instan
            $waMessage = "Halo Admin Toko, saya telah membuat pesanan baru:\n\n";
            $waMessage .= "Nomor Pesanan: *" . $order->kode_order . "*\n";
            $waMessage .= "Nama: *" . $order->nama_customer . "*\n";
            $waMessage .= "No HP: " . $order->no_hp . "\n";
            $waMessage .= "Alamat: " . $order->alamat . ", " . $order->destination_label . "\n";
            $waMessage .= "Kurir: *" . $order->kurir . "* (" . ($order->estimasi ?? '-') . ")\n";
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
                'ongkir' => $order->ongkir,
                'kurir' => $order->kurir,
                'metode_pembayaran' => $order->metode_pembayaran,
                'snap_token' => $snapToken,
                'client_key' => config('midtrans.client_key'),
                'track_url' => route('frontend.track', ['order' => $order->kode_order]),
                'invoice_url' => route('frontend.order.invoice', ['kode_order' => $order->kode_order]),
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

            $baseOrderId = preg_replace('/-R\d+$/', '', $orderId);
            $order = Order::with('items')
                ->where('midtrans_order_id', $orderId)
                ->orWhere('kode_order', $orderId)
                ->orWhere('kode_order', $baseOrderId)
                ->first();
            if (!$order) {
                return response()->json(['message' => 'Order tidak ditemukan'], 404);
            }

            OrderController::applyMidtransStatus($order, $transactionStatus, $fraudStatus, $notification->payment_type ?? null);

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
            $order->cancelled_at = now();
            $order->save();
            OrderController::restoreOrderStockAndVoucher($order);
        });

        // Batalkan juga transaksi di Midtrans agar kode bayar (VA/QRIS) tidak bisa dibayar lagi
        if ($order->metode_pembayaran === 'midtrans' && $order->snap_token) {
            try {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = (bool) config('midtrans.is_production', false);
                \Midtrans\Transaction::cancel($order->kode_order);
            } catch (\Throwable $e) {
                // Transaksi belum dipilih metode bayarnya di Snap -> tidak perlu dibatalkan
            }
        }

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

    /**
     * Pembeli mengganti metode pembayaran pesanan (misal dari Online ke COD, atau reset token Snap).
     */
    public function switchPaymentMethod(Request $request, $kode_order)
    {
        $request->validate([
            'target' => 'required|in:cod,reset_online',
        ]);

        $order = Order::with('items')->where('kode_order', $kode_order)->firstOrFail();

        if (auth()->check()) {
            $user = auth()->user();
            $isOwner = ($order->user_id && $order->user_id == $user->id_user)
                || ($user->no_hp && $order->no_hp === $user->no_hp)
                || in_array($user->role, ['admin', 'superadmin']);
            if (!$isOwner) {
                return response()->json(['success' => false, 'message' => 'Akses ditolak.'], 403);
            }
        }

        if ($order->status !== 'pending') {
            return response()->json(['success' => false, 'message' => 'Pesanan tidak dalam status menunggu pembayaran.'], 400);
        }

        if ($request->target === 'cod') {
            if ($order->metode_pembayaran === 'midtrans' && $order->snap_token) {
                try {
                    Config::$serverKey = config('midtrans.server_key');
                    Config::$isProduction = (bool) config('midtrans.is_production', false);
                    \Midtrans\Transaction::cancel($order->midtrans_order_id ?: $order->kode_order);
                } catch (\Throwable $e) {}
            }

            $order->metode_pembayaran = 'cod';
            $order->save();

            return response()->json([
                'success' => true,
                'mode' => 'cod',
                'message' => 'Metode pembayaran berhasil diubah ke COD (Bayar di Tempat)!',
                'metode_pembayaran' => 'cod',
                'total_formatted' => 'Rp ' . number_format($order->total_harga, 0, ',', '.'),
            ]);
        }

        if ($request->target === 'reset_online') {
            $prevOrderId = $order->midtrans_order_id ?: $order->kode_order;
            try {
                Config::$serverKey = config('midtrans.server_key');
                Config::$isProduction = (bool) config('midtrans.is_production', false);
                \Midtrans\Transaction::cancel($prevOrderId);
            } catch (\Throwable $e) {}

            $attempt = ($order->payment_attempts ?? 0) + 1;
            $order->payment_attempts = $attempt;
            $newMidtransOrderId = $order->kode_order . '-R' . $attempt;
            $order->midtrans_order_id = $newMidtransOrderId;

            Config::$serverKey = config('midtrans.server_key');
            Config::$isProduction = (bool) config('midtrans.is_production', false);
            Config::$isSanitized = (bool) config('midtrans.is_sanitized', true);
            Config::$is3ds = (bool) config('midtrans.is_3ds', true);

            $custName = trim($order->nama_customer ?: 'Pelanggan');
            $nameParts = explode(' ', $custName, 2);
            $firstName = $nameParts[0];
            $lastName = $nameParts[1] ?? '';

            $params = [
                'transaction_details' => [
                    'order_id' => $newMidtransOrderId,
                    'gross_amount' => (int) $order->total_harga,
                ],
                'customer_details' => [
                    'first_name' => $firstName,
                    'last_name' => $lastName,
                    'phone' => $order->no_hp,
                ],
                'expiry' => [
                    'unit' => 'days',
                    'duration' => 3,
                ],
            ];

            try {
                $newSnapToken = Snap::getSnapToken($params);
                $order->metode_pembayaran = 'midtrans';
                $order->snap_token = $newSnapToken;
                $order->save();

                return response()->json([
                    'success' => true,
                    'mode' => 'online',
                    'message' => 'Sesi pembayaran berhasil di-reset. Silakan pilih channel pembayaran baru.',
                    'snap_token' => $newSnapToken,
                    'kode_order' => $order->kode_order,
                    'total_formatted' => 'Rp ' . number_format($order->total_harga, 0, ',', '.'),
                ]);
            } catch (\Throwable $e) {
                return response()->json(['success' => false, 'message' => 'Gagal membuat sesi pembayaran baru: ' . $e->getMessage()], 500);
            }
        }

        return response()->json(['success' => false, 'message' => 'Target tidak valid.'], 400);
    }
}
