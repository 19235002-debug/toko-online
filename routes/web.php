<?php

use App\Http\Controllers\AuthController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\BarangController;
use App\Http\Controllers\UserController;
use App\Http\Controllers\BannerController;
use App\Http\Controllers\FrontendController;
use App\Http\Controllers\OrderController;
use App\Http\Controllers\LaporanController;
use App\Http\Controllers\SettingController;
use App\Http\Controllers\KategoriController;
use App\Http\Controllers\ReviewController;
use App\Http\Controllers\VoucherController;
use Illuminate\Support\Facades\Route;

/*
|--------------------------------------------------------------------------
| Web Routes
|--------------------------------------------------------------------------
*/

// ==========================================
// 1. FRONTEND TOKO (Publik & Pengunjung)
// ==========================================
Route::get('/', [FrontendController::class, 'index'])->name('frontend.home');
Route::get('/frontend', [FrontendController::class, 'index'])->name('frontend.index');
Route::get('/shop', [FrontendController::class, 'shop'])->name('frontend.shop');
Route::get('/frontend/product/{id}', [FrontendController::class, 'show'])->name('frontend.product');
Route::get('/lacak-pesanan', [FrontendController::class, 'trackOrder'])->name('frontend.track');
Route::post('/lacak-pesanan/{kode_order}/confirm', [FrontendController::class, 'confirmReceived'])->name('frontend.track.confirm');
Route::post('/review/store', [FrontendController::class, 'storeReview'])->name('frontend.review.store');
Route::get('/voucher/available', [FrontendController::class, 'availableVouchers'])->name('frontend.voucher.available')->middleware('auth');
Route::post('/voucher/check', [FrontendController::class, 'checkVoucher'])->name('frontend.voucher.check')->middleware('auth');
Route::get('/shipping/destinations', [FrontendController::class, 'shippingDestinations'])->name('frontend.shipping.destinations')->middleware('auth');
Route::post('/shipping/cost', [FrontendController::class, 'shippingCost'])->name('frontend.shipping.cost')->middleware('auth');
Route::post('/checkout/validate-stock', [FrontendController::class, 'validateCartStock'])->name('frontend.checkout.validateStock')->middleware('auth');
Route::post('/checkout', [FrontendController::class, 'checkout'])->name('frontend.checkout')->middleware('auth');

// Sinkronisasi Keranjang & Wishlist ke Database
Route::middleware('auth')->group(function () {
    Route::get('/user/sync-state', [FrontendController::class, 'getUserSyncState'])->name('frontend.sync.state');
    Route::post('/user/cart/sync', [FrontendController::class, 'syncUserCart'])->name('frontend.cart.sync');
    Route::post('/user/wishlist/sync', [FrontendController::class, 'syncUserWishlist'])->name('frontend.wishlist.sync');
});

// Midtrans Webhook / Callback
Route::post('/midtrans/callback', [FrontendController::class, 'midtransCallback'])->name('midtrans.callback');
Route::post('/midtrans/sync/{kode_order}', [FrontendController::class, 'syncOrder'])->name('midtrans.sync');
Route::post('/pesanan/switch-payment/{kode_order}', [FrontendController::class, 'switchPaymentMethod'])->name('frontend.order.switch_payment');

// ==========================================
// 2. AUTHENTICATION (Login, Register, Logout)
// ==========================================
Route::get('/login', [AuthController::class, 'index'])->name('login')->middleware('guest');
Route::post('/login', [AuthController::class, 'authenticate']);
Route::get('/register', [AuthController::class, 'register'])->name('register')->middleware('guest');
Route::post('/register', [AuthController::class, 'process']);
Route::get('/forgot-password', [AuthController::class, 'showForgotPasswordForm'])->name('password.request')->middleware('guest');
Route::post('/forgot-password', [AuthController::class, 'sendResetLink'])->name('password.email')->middleware('guest');
Route::get('/reset-password/{token}', [AuthController::class, 'showResetPasswordForm'])->name('password.reset')->middleware('guest');
Route::post('/reset-password', [AuthController::class, 'resetPassword'])->name('password.update')->middleware('guest');
Route::post('/logout', [AuthController::class, 'logout'])->name('logout')->middleware('auth');

// ==========================================
// 3. USER / CUSTOMER AUTHENTICATED AREA
// ==========================================
Route::middleware('auth')->group(function () {
    // Riwayat Belanja / Pesanan Saya
    Route::get('/pesanan-saya', [FrontendController::class, 'myOrders'])->name('frontend.my_orders');
    Route::get('/orders', fn() => redirect()->route('frontend.my_orders'))->name('frontend.orders');
    Route::post('/pesanan-saya/{kode_order}/cancel', [FrontendController::class, 'cancelOrder'])->name('frontend.order.cancel');
    Route::get('/pesanan-saya/{kode_order}/invoice', [FrontendController::class, 'printCustomerInvoice'])->name('frontend.order.invoice');

    // Profil Pengguna (Mendeteksi role Admin atau Customer)
    Route::get('/profile', function () {
        $user = auth()->user();
        if (in_array($user->role, ['admin', 'superadmin'])) {
            return view('profile.profile-edit', compact('user'));
        }
        return view('frontend.profil-saya', compact('user'));
    })->name('profile.edit');

    Route::put('/profile', [UserController::class, 'updateProfile'])->name('profile.update');
});

// ==========================================
// 4. ADMIN PANEL (HANYA UNTUK ROLE ADMIN)
// ==========================================
Route::middleware(['auth', 'admin'])->group(function () {
    // Dashboard Admin
    Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');

    // Manajemen Kategori
    Route::resource('kategori', KategoriController::class);

    // Manajemen Produk / Barang
    Route::get('/barang/print', [BarangController::class, 'print'])->name('barang.print');
    Route::patch('/barang/{id}/toggle-promo', [BarangController::class, 'togglePromo'])->name('barang.togglePromo');
    Route::delete('/barang/gambar/{id}', [BarangController::class, 'deleteGambar'])->name('barang.gambar.delete');
    Route::resource('barang', BarangController::class);
    Route::get('/produk', [BarangController::class, 'index']);
    Route::get('/produk/create', [BarangController::class, 'create']);

    // Manajemen Banner
    Route::resource('banner', BannerController::class);

    // Manajemen Voucher & Promo
    Route::post('/voucher/{id}/toggle', [VoucherController::class, 'toggleStatus'])->name('voucher.toggle');
    Route::resource('voucher', VoucherController::class);

    // Manajemen Pesanan
    Route::get('/order/print/{id}', [OrderController::class, 'printInvoice'])->name('order.print');
    Route::get('/order/shipping-label/{id}', [OrderController::class, 'printShippingLabel'])->name('order.shippingLabel');
    Route::post('/order/{id}/request-pickup', [OrderController::class, 'requestBiteshipPickup'])->name('order.requestPickup');
    Route::post('/order/sync-shipping', [OrderController::class, 'syncAllShippedOrders'])->name('order.syncShipping');
    Route::get('/order/{id}/check-midtrans', [OrderController::class, 'checkMidtransStatus'])->name('order.checkMidtrans');
    Route::put('/order/{id}/status', [OrderController::class, 'updateStatus'])->name('order.updateStatus');
    Route::resource('order', OrderController::class);
    Route::get('/pesanan', [OrderController::class, 'index']);

    // Laporan Penjualan
    Route::get('/laporan/export-csv', [LaporanController::class, 'exportCsv'])->name('laporan.exportCsv');
    Route::get('/laporan/print', [LaporanController::class, 'print'])->name('laporan.print');
    Route::get('/laporan', [LaporanController::class, 'index'])->name('laporan.index');

    // Pengaturan Toko & Opsi Pengiriman / Pembayaran
    Route::get('/setting', [SettingController::class, 'index'])->name('setting.index');
    Route::put('/setting', [SettingController::class, 'update'])->name('setting.update');
    Route::get('/setting/metode', [SettingController::class, 'methodsIndex'])->name('setting.methods');
    Route::put('/setting/metode', [SettingController::class, 'updateMethods'])->name('setting.methods.update');
    Route::post('/setting/toggle-courier', [SettingController::class, 'toggleCourier'])->name('setting.toggleCourier');
    Route::post('/setting/toggle-payment', [SettingController::class, 'togglePayment'])->name('setting.togglePayment');

    // Moderasi Ulasan & Rating
    Route::get('/review', [ReviewController::class, 'index'])->name('review.index');
    Route::post('/review/{id}/toggle', [ReviewController::class, 'toggleStatus'])->name('review.toggle');
    Route::delete('/review/{id}', [ReviewController::class, 'destroy'])->name('review.destroy');

    // Manajemen Data User
    Route::resource('user', UserController::class);
});
