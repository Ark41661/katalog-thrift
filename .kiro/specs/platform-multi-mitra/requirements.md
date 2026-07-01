# Requirements — Platform Multi-Mitra Thrift & Fashion

## Overview
Transformasi dari katalog toko tunggal menjadi **platform promosi multi-mitra** untuk usaha thrift dan fashion. Mitra mendaftar, mengelola produk sendiri, dan transaksi tetap berlangsung di channel masing-masing (Shopee/WA). Platform berfungsi sebagai media promosi, discovery, dan mix & match lintas toko.

---

## Actors

| Actor | Deskripsi |
|---|---|
| **Super Admin** | Pemilik platform, verifikasi mitra, moderasi produk/review |
| **Mitra** | Pemilik usaha thrift/fashion, kelola produk sendiri |
| **Pengunjung** | Browse katalog tanpa login |
| **Member** | Pengunjung yang sudah login, bisa beri review |

---

## Requirements

### REQ-01 — Pendaftaran & Verifikasi Mitra

- **REQ-01.1** Calon mitra dapat mengisi form pendaftaran dengan: nama toko, nama pemilik, email, nomor WA, deskripsi toko, link Shopee/Tokopedia (opsional), foto logo toko.
- **REQ-01.2** Setelah submit, status mitra adalah `pending` dan menunggu verifikasi admin.
- **REQ-01.3** Admin menerima notifikasi (di dashboard) ada pendaftaran baru.
- **REQ-01.4** Admin dapat menyetujui (`approved`) atau menolak (`rejected`) pendaftaran dengan alasan penolakan.
- **REQ-01.5** Mitra yang disetujui mendapat email konfirmasi dan dapat login ke dashboard mitra.
- **REQ-01.6** Admin dapat menangguhkan (`suspended`) mitra yang aktif jika ada pelanggaran.

### REQ-02 — Autentikasi

- **REQ-02.1** Mitra login menggunakan email + password.
- **REQ-02.2** Member (pengunjung) dapat registrasi dengan nama, email, password.
- **REQ-02.3** Member login menggunakan email + password.
- **REQ-02.4** Super Admin login melalui halaman terpisah (`/admin/login`) seperti sekarang.
- **REQ-02.5** Mitra yang statusnya `pending` atau `suspended` tidak dapat login.

### REQ-03 — Dashboard Mitra

- **REQ-03.1** Mitra dapat menambah, mengedit, dan menghapus produk miliknya sendiri.
- **REQ-03.2** Produk mitra memiliki field: nama, kategori, warna, harga, ukuran, kondisi, deskripsi, cerita produk, foto (upload file atau URL), link Shopee, link Tokopedia, status aktif/nonaktif.
- **REQ-03.3** Mitra dapat melihat statistik sederhana: total produk, total produk aktif, total review diterima, rata-rata rating toko.
- **REQ-03.4** Mitra dapat mengedit profil toko: nama, deskripsi, logo, link sosial media.
- **REQ-03.5** Mitra dapat membuat **curated mix & match** — memilih 2–4 produk (dari tokonya sendiri atau toko lain) sebagai kombinasi outfit yang direkomendasikan.
- **REQ-03.6** Mitra tidak dapat melihat atau mengedit produk mitra lain.

### REQ-04 — Katalog Publik

- **REQ-04.1** Halaman utama menampilkan semua produk aktif dari semua mitra yang `approved`.
- **REQ-04.2** Pengunjung dapat filter produk berdasarkan: kategori, brand/mitra, ukuran, rentang harga, ketersediaan (tersedia/sold), new arrival.
- **REQ-04.3** Setiap produk menampilkan nama toko mitra sebagai label, dapat diklik ke halaman profil toko.
- **REQ-04.4** Halaman detail produk menampilkan: info produk lengkap, info toko mitra, mix & match rekomendasi, review produk, link beli (Shopee/WA).
- **REQ-04.5** Halaman profil toko mitra menampilkan: info toko, semua produk aktif toko tersebut, rating keseluruhan toko, review toko.

### REQ-05 — Mix & Match

- **REQ-05.1** Sistem otomatis merekomendasikan produk pelengkap berdasarkan kategori (hoodie → celana, sepatu, dll) dari semua mitra.
- **REQ-05.2** Admin dan mitra dapat membuat **curated outfit** — kombinasi produk yang dikurasi manual, ditampilkan di halaman Lookbook.
- **REQ-05.3** Setiap item dalam curated outfit dapat diklik untuk melihat detail produk dan link beli.
- **REQ-05.4** Curated outfit menampilkan nama toko masing-masing produk.
- **REQ-05.5** Halaman Lookbook/Outfit Generator menampilkan curated outfits + filter berdasarkan gaya (casual, streetwear, dll).

### REQ-06 — Review & Rating

- **REQ-06.1** Hanya Member (pengunjung yang login) yang dapat memberikan review.
- **REQ-06.2** Review produk berisi: rating bintang 1–5, komentar teks (opsional, maks 500 karakter).
- **REQ-06.3** Satu Member hanya dapat memberikan satu review per produk.
- **REQ-06.4** Review ditampilkan di halaman detail produk dengan nama member, rating, komentar, dan tanggal.
- **REQ-06.5** Rating toko dihitung otomatis dari rata-rata semua rating produk toko tersebut.
- **REQ-06.6** Admin dapat menghapus review yang melanggar (spam, tidak pantas).
- **REQ-06.7** Mitra tidak dapat menghapus review produknya sendiri.

### REQ-07 — Dashboard Super Admin

- **REQ-07.1** Admin dapat melihat daftar semua pendaftaran mitra (pending, approved, rejected, suspended).
- **REQ-07.2** Admin dapat menyetujui, menolak, atau menangguhkan mitra.
- **REQ-07.3** Admin dapat melihat semua produk dari semua mitra dan men-suspend produk yang bermasalah.
- **REQ-07.4** Admin dapat menghapus review yang melanggar.
- **REQ-07.5** Admin dapat melihat statistik platform: total mitra, total produk, total member, total review.
- **REQ-07.6** Admin dapat membuat curated outfit dari produk manapun.

### REQ-08 — Halaman Publik Tambahan

- **REQ-08.1** Halaman **Daftar Mitra** — menampilkan semua toko mitra yang aktif dengan logo, nama, jumlah produk, rating.
- **REQ-08.2** Halaman **Profil Toko** per mitra — produk, info toko, rating, review.
- **REQ-08.3** Halaman **Daftar Jadi Mitra** — form pendaftaran mitra baru.
- **REQ-08.4** Halaman **Login/Register Member** — untuk pengunjung yang ingin bisa review.

---

## Non-Functional Requirements

- Platform menggunakan Laravel (existing codebase) dengan autentikasi multi-guard (admin, mitra, member).
- Upload foto produk disimpan di `storage/app/public` dengan symlink ke `public/storage`.
- Semua halaman publik dapat diakses tanpa login.
- Responsive untuk mobile.

---

## Fitur yang TIDAK ada di scope ini (bisa ditambah nanti)

- Sistem pembayaran / transaksi di platform
- Chat antar pengguna di platform
- Notifikasi email otomatis (kecuali konfirmasi mitra)
- Sistem poin/reward member
