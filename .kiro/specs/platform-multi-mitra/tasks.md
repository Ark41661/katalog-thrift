# Tasks — Platform Multi-Mitra Thrift & Fashion

## Task 1 — Database Migrations & Models

- [ ] 1.1 Migration: tambah kolom `role` ke tabel `users`
- [ ] 1.2 Migration: buat tabel `partners`
- [ ] 1.3 Migration: tambah kolom `partner_id` dan `image_path` ke tabel `products`
- [ ] 1.4 Migration: buat tabel `reviews`
- [ ] 1.5 Migration: buat tabel `outfits` dan `outfit_items`
- [ ] 1.6 Update Model `User` — tambah role, relasi ke Partner
- [ ] 1.7 Buat Model `Partner` — relasi ke User, Products, Reviews
- [ ] 1.8 Update Model `Product` — tambah relasi ke Partner, Reviews
- [ ] 1.9 Buat Model `Review` — relasi ke User, Product
- [ ] 1.10 Buat Model `Outfit` + `OutfitItem` — relasi ke Products

## Task 2 — Autentikasi Multi-Guard

- [ ] 2.1 Update `config/auth.php` — tambah guard `partner` dan `member`
- [ ] 2.2 Buat middleware `EnsurePartnerAuthenticated`
- [ ] 2.3 Buat middleware `EnsureMemberAuthenticated`
- [ ] 2.4 Buat `MemberAuthController` — login, register, logout member
- [ ] 2.5 Buat `PartnerAuthController` — login, logout mitra
- [ ] 2.6 Buat views: `member/login.blade.php`, `member/register.blade.php`
- [ ] 2.7 Buat view: `partner/login.blade.php`
- [ ] 2.8 Daftarkan routes auth di `routes/web.php`

## Task 3 — Pendaftaran & Verifikasi Mitra

- [ ] 3.1 Buat `PartnerController@registerForm` + `@register` (public)
- [ ] 3.2 Buat view `public/partners/register.blade.php` — form pendaftaran
- [ ] 3.3 Buat `AdminPartnerController` — index, approve, reject, suspend
- [ ] 3.4 Buat view `admin/partners/index.blade.php` — daftar mitra + aksi
- [ ] 3.5 Update `AdminDashboardController` — tampilkan jumlah pending mitra
- [ ] 3.6 Update `admin/dashboard.blade.php` — badge notifikasi mitra pending

## Task 4 — Dashboard Mitra

- [ ] 4.1 Buat `PartnerDashboardController@index` — statistik toko
- [ ] 4.2 Buat view `partner/dashboard.blade.php`
- [ ] 4.3 Buat `PartnerProductController` — CRUD produk milik mitra sendiri
- [ ] 4.4 Buat views `partner/products/` — index, create, edit
- [ ] 4.5 Implementasi upload foto produk ke `storage/app/public/products/`
- [ ] 4.6 Buat `PartnerProfileController` — edit profil toko
- [ ] 4.7 Buat view `partner/profile.blade.php`
- [ ] 4.8 Daftarkan semua routes mitra di `routes/web.php`

## Task 5 — Katalog Publik (Update)

- [ ] 5.1 Update `CatalogController@index` — query produk dari semua mitra approved
- [ ] 5.2 Update `catalog/index.blade.php` — tampilkan badge nama toko per produk
- [ ] 5.3 Tambah filter "per toko" di halaman katalog
- [ ] 5.4 Update `CatalogController@show` — sertakan info mitra + review
- [ ] 5.5 Update `catalog/show.blade.php` — tampilkan info toko, section review

## Task 6 — Halaman Profil Toko

- [ ] 6.1 Buat `PartnerController@index` — daftar semua mitra approved
- [ ] 6.2 Buat view `public/partners/index.blade.php` — grid toko dengan rating
- [ ] 6.3 Buat `PartnerController@show` — profil toko + produk + review
- [ ] 6.4 Buat view `public/partners/show.blade.php`
- [ ] 6.5 Daftarkan routes `/toko` dan `/toko/{slug}`

## Task 7 — Sistem Review

- [ ] 7.1 Buat `ReviewController` — store, update, destroy (member only)
- [ ] 7.2 Update `catalog/show.blade.php` — form review + daftar review
- [ ] 7.3 Buat `AdminReviewController@index` + `@destroy`
- [ ] 7.4 Buat view `admin/reviews/index.blade.php`
- [ ] 7.5 Implementasi rating rata-rata di model Partner dan Product

## Task 8 — Curated Outfit / Lookbook

- [ ] 8.1 Buat `PartnerOutfitController` — CRUD outfit oleh mitra
- [ ] 8.2 Buat views `partner/outfits/` — index, create, edit
- [ ] 8.3 Buat `AdminOutfitController` — CRUD outfit oleh admin
- [ ] 8.4 Update `LookbookController@index` — tampilkan curated outfits
- [ ] 8.5 Update `lookbook/index.blade.php` — tampilkan outfit dengan item breakdown + nama toko

## Task 9 — Admin Dashboard Update

- [ ] 9.1 Update `AdminDashboardController` — statistik platform lengkap
- [ ] 9.2 Update `admin/dashboard.blade.php` — statistik + quick actions
- [ ] 9.3 Update `AdminProductController@index` — tampilkan semua produk semua mitra
- [ ] 9.4 Tambah aksi suspend produk di admin

## Task 10 — Storage & Konfigurasi

- [ ] 10.1 Jalankan `php artisan storage:link`
- [ ] 10.2 Update `.env` dan `.env.example` — ganti `STORE_NAME=ThriftHub`, tambah `PLATFORM_NAME`
- [ ] 10.3 Update `config/catalog.php` — ganti nama platform ke ThriftHub
- [ ] 10.4 Update halaman publik — ganti branding ke ThriftHub
- [ ] 10.5 Hapus semua produk existing (`php artisan db:seed --class=FreshStartSeeder`)

## Task 11 — Badge Toko Terverifikasi

- [ ] 11.1 Tambah kolom `is_verified` (boolean) ke tabel `partners`
- [ ] 11.2 Admin dapat toggle badge verified di halaman kelola mitra
- [ ] 11.3 Tampilkan badge ✓ di kartu toko, profil toko, dan label produk

## Task 12 — Laporan Produk

- [ ] 12.1 Migration: buat tabel `product_reports` (user_id, product_id, reason, status)
- [ ] 12.2 Buat Model `ProductReport`
- [ ] 12.3 Tambah tombol "Laporkan Produk" di halaman detail produk (member only)
- [ ] 12.4 Buat `ReportController@store` — simpan laporan
- [ ] 12.5 Buat `AdminReportController@index` — daftar laporan masuk
- [ ] 12.6 Admin dapat tandai laporan sebagai ditangani atau abaikan

## Task 13 — Wishlist Member

- [ ] 13.1 Migration: buat tabel `wishlists` (user_id, product_id, created_at)
- [ ] 13.2 Buat Model `Wishlist`
- [ ] 13.3 Buat `WishlistController` — toggle (add/remove), index
- [ ] 13.4 Tambah tombol ♡ di kartu produk dan halaman detail (member only)
- [ ] 13.5 Buat halaman `member/wishlist.blade.php` — daftar produk yang disimpan

## Task 14 — Notifikasi WA (Fonnte/WA API)

- [ ] 14.1 Tambah config `FONNTE_TOKEN` di `.env`
- [ ] 14.2 Buat `WhatsappService` — wrapper kirim pesan via Fonnte API
- [ ] 14.3 Kirim WA ke mitra saat pendaftaran disetujui admin
- [ ] 14.4 Kirim WA ke mitra saat produknya di-suspend admin
- [ ] 14.5 (Opsional) Kirim WA ke member saat ada reply review mereka
