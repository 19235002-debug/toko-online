# Coza Store — Toko Fashion Online (Kelompok 3)

Aplikasi e-commerce toko fashion berbasis **Laravel 10** dengan integrasi pembayaran online **Midtrans Snap** (serta COD), perhitungan ongkos kirim & pelacakan resi **RajaOngkir**, manajemen katalog & varian produk, promo/voucher, ulasan terverifikasi, dan dashboard admin modern.

---

## Fitur Utama

### Storefront (Pelanggan)
- **Katalog & Pencarian**: Halaman beranda modern (showcase kategori **Wanita, Pria, Anak, Bayi** & promo terbatas dengan countdown) serta katalog `/shop` dengan filter kategori/subkategori, rentang harga, stok, promo, pengurutan, dan pagination modern.
- **Varian Produk & Quick View**: Pilihan warna dan ukuran dengan sinkronisasi dua arah; varian yang stoknya habis otomatis dinonaktifkan.
- **Keranjang & Wishlist**: Tersinkronisasi ke database untuk pengguna yang login.
- **Checkout RajaOngkir & Midtrans**:
  - Pencarian alamat tujuan hingga tingkat **kelurahan/desa & kode pos** melalui API RajaOngkir.
  - Perhitungan ongkir resmi (JNE, J&T, SiCepat) divalidasi ulang di server saat checkout.
  - Pembayaran **Midtrans Snap** (VA Bank, QRIS, E-Wallet) dengan batas waktu 24 jam, sinkronisasi status otomatis, modal struk modern, serta **COD**.
  - Dukungan kode voucher diskon & harga promo produk aktif.
- **Lacak Pesanan & Riwayat**:
  - Pencarian pesanan berdasarkan **Kode Invoice**, **Nomor Resi**, atau **Nomor WhatsApp**.
  - Timeline progres pesanan, riwayat perjalanan paket RajaOngkir, konfirmasi barang diterima, pembatalan pesanan `pending` (mengembalikan stok & kuota voucher), cetak invoice, dan ulasan produk terverifikasi.

### Dashboard Admin
- **Manajemen Produk & Kategori**: CRUD produk, multi-foto, varian warna/ukuran beserta stok masing-masing, promo terjadwal, dan kategori bertingkat.
- **Manajemen Pesanan**: Kontrol alur status (`Pending` → `Diproses` → `Dikirim` → `Selesai` / `Dibatalkan`), validasi pelunasan Midtrans sebelum diproses, input ekspedisi & nomor resi wajib saat dikirim, cetak faktur, dan kirim resi ke WhatsApp pembeli.
- **Voucher, Banner, Ulasan, User & Laporan**: CRUD voucher & banner, moderasi ulasan, manajemen pengguna/role, pengaturan toko, serta ekspor laporan penjualan CSV.

---

## Teknologi

- **Backend**: Laravel 10, PHP 8.1+, MySQL
- **Payment Gateway**: Midtrans Snap (`midtrans/midtrans-php`)
- **Ongkos Kirim & Resi**: RajaOngkir API (Komerce V1)
- **Frontend & Admin UI**: Blade, Bootstrap 4, TailAdmin CSS, jQuery, SweetAlert2, DataTables, FontAwesome 6

---

## Instalasi Lokal

1. Clone repository dan masuk ke folder proyek:
   ```bash
   git clone https://github.com/19235002-debug/toko-online.git
   cd toko-online
   ```
2. Install dependensi PHP:
   ```bash
   composer install
   ```
3. Salin file environment dan generate application key:
   ```bash
   cp .env.example .env
   php artisan key:generate
   ```
4. Atur koneksi database serta kredensial **Midtrans** dan **RajaOngkir** di file `.env`:
   ```env
   DB_DATABASE=project
   DB_USERNAME=root
   DB_PASSWORD=

   MIDTRANS_SERVER_KEY=your_midtrans_server_key
   MIDTRANS_CLIENT_KEY=your_midtrans_client_key
   MIDTRANS_IS_PRODUCTION=false

   RAJAONGKIR_API_KEY=your_rajaongkir_api_key
   ```
5. Jalankan migrasi, seeder, dan buat symlink storage:
   ```bash
   php artisan migrate --seed
   php artisan storage:link
   ```
6. Jalankan server pengembangan:
   ```bash
   php artisan serve
   ```
   Buka `http://127.0.0.1:8000` di browser.

---

## Pengujian

```bash
php artisan test
```

---

## Lisensi

Proyek ini dibuat untuk keperluan pengembangan dan pembelajaran **Kelompok 3**.
