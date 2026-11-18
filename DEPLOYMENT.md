# Panduan Persiapan Publish / Hosting (Production Checklist)

Dokumen ini berisi daftar periksa sebelum memublikasikan aplikasi **Fashion Store (Project Kelompok 3)** ke server hosting/VPS production.

## 1. Konfigurasi `.env` Production
Pastikan nilai berikut diperbarui di file `.env` server production:
- `APP_ENV=production`
- `APP_DEBUG=false`
- `APP_URL=https://domain-anda.com`
- `DB_HOST`, `DB_DATABASE`, `DB_USERNAME`, `DB_PASSWORD` sesuai database hosting
- **Midtrans Production Keys**:
  - `MIDTRANS_IS_PRODUCTION=true`
  - `MIDTRANS_SERVER_KEY=...` (Server Key Production dari Dashboard Midtrans)
  - `MIDTRANS_CLIENT_KEY=...` (Client Key Production dari Dashboard Midtrans)
- **SMTP Email** (untuk fitur Lupa Password):
  - `MAIL_MAILER=smtp`, `MAIL_HOST`, `MAIL_PORT`, `MAIL_USERNAME`, `MAIL_PASSWORD`, `MAIL_ENCRYPTION`, `MAIL_FROM_ADDRESS`

## 2. Database & Storage Symlink
Jalankan perintah berikut di server:
```bash
composer install --no-dev --optimize-autoloader
php artisan key:generate --force
php artisan migrate --force
php artisan storage:link
```

## 3. Optimasi Cache Laravel
Setelah konfigurasi `.env` selesai:
```bash
php artisan config:cache
php artisan route:cache
php artisan view:cache
```

## 4. Permission Folder
Pastikan web server memiliki izin tulis pada folder berikut:
- `storage/` (dan seluruh subfoldernya)
- `bootstrap/cache/`

## 5. Verifikasi Sebelum Live
- Uji halaman utama (`/`), halaman toko (`/shop`), lacak pesanan (`/lacak-pesanan`), dan deep-link produk (`/?produk=1`).
- Uji login/register/lupa password (`/forgot-password`).
- Uji upload produk di Admin (`/barang/create`) dan pastikan gambar tampil lewat `public/storage/...`.
- Uji webhook Midtrans (`/midtrans/callback`) pada URL production di dashboard Midtrans.

