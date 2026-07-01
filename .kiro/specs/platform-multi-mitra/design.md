# Design — Platform Multi-Mitra Thrift & Fashion

## Arsitektur Sistem

```
┌─────────────────────────────────────────────────────┐
│                   PUBLIC ROUTES                      │
│  / katalog  /produk/:slug  /toko/:slug  /lookbook   │
│  /mitra  /daftar-mitra  /login  /register           │
└─────────────────────────────────────────────────────┘
         │                    │                    │
┌────────▼──────┐   ┌─────────▼──────┐   ┌────────▼──────┐
│  MEMBER AREA  │   │  MITRA AREA    │   │  ADMIN AREA   │
│  /member/*    │   │  /mitra/*      │   │  /admin/*     │
│  - Login      │   │  - Login       │   │  - Login      │
│  - Review     │   │  - Dashboard   │   │  - Dashboard  │
└───────────────┘   │  - Produk CRUD │   │  - Verifikasi │
                    │  - Profil Toko │   │  - Moderasi   │
                    │  - Mix & Match │   │  - Statistik  │
                    └────────────────┘   └───────────────┘
```

---

## Database Schema

### Tabel Baru

#### `partners` (Mitra)
```
id, user_id (FK → users), store_name, store_slug (unique),
description, logo, location, whatsapp, shopee_url, tokopedia_url,
instagram_url, tiktok_url, status (enum: pending|approved|rejected|suspended),
rejection_reason (nullable), approved_at (nullable),
created_at, updated_at
```

#### `users` (update existing)
```
+ role (enum: admin|partner|member) default: member
+ partner_id (FK → partners, nullable)
```

#### `products` (update existing — tambah kolom)
```
+ partner_id (FK → partners) — produk milik mitra mana
+ image_path (nullable) — path upload file lokal
(image tetap ada untuk URL eksternal, salah satu dipakai)
```

#### `reviews`
```
id, user_id (FK → users), product_id (FK → products),
rating (tinyint 1-5), comment (text nullable, max 500),
created_at, updated_at
UNIQUE(user_id, product_id)
```

#### `outfits` (Curated Mix & Match)
```
id, title, description (nullable), style_type,
created_by_type (enum: admin|partner),
created_by_id, cover_image (nullable),
is_active (boolean),
created_at, updated_at
```

#### `outfit_items` (Produk dalam outfit)
```
id, outfit_id (FK → outfits), product_id (FK → products),
sort_order (int), note (nullable)
```

---

## Multi-Guard Authentication

Laravel mendukung multiple guards. Kita pakai:

```php
// config/auth.php
guards: [
    'web'    => member (users table, role=member)
    'partner' => mitra (users table, role=partner)  
    'admin'  => super admin (session-based, config)
]
```

Sebenarnya semua user ada di tabel `users` yang sama, dibedakan oleh `role`. Guard terpisah untuk middleware protection.

---

## Struktur Routes

```
// PUBLIC
GET  /                          → CatalogController@index
GET  /produk/{slug}             → CatalogController@show
GET  /toko                      → PartnerController@index (daftar mitra)
GET  /toko/{slug}               → PartnerController@show (profil toko)
GET  /lookbook                  → LookbookController@index
GET  /daftar-mitra              → PartnerController@registerForm
POST /daftar-mitra              → PartnerController@register

// MEMBER AUTH
GET  /login                     → MemberAuthController@showLogin
POST /login                     → MemberAuthController@login
GET  /register                  → MemberAuthController@showRegister
POST /register                  → MemberAuthController@register
POST /logout                    → MemberAuthController@logout

// MEMBER (authenticated)
POST /produk/{slug}/review      → ReviewController@store
PUT  /produk/{slug}/review      → ReviewController@update
DELETE /produk/{slug}/review    → ReviewController@destroy

// MITRA AREA
GET  /mitra/login               → PartnerAuthController@showLogin
POST /mitra/login               → PartnerAuthController@login
POST /mitra/logout              → PartnerAuthController@logout
GET  /mitra/dashboard           → PartnerDashboardController@index
GET  /mitra/produk              → PartnerProductController@index
GET  /mitra/produk/create       → PartnerProductController@create
POST /mitra/produk              → PartnerProductController@store
GET  /mitra/produk/{id}/edit    → PartnerProductController@edit
PUT  /mitra/produk/{id}         → PartnerProductController@update
DELETE /mitra/produk/{id}       → PartnerProductController@destroy
GET  /mitra/profil              → PartnerProfileController@edit
PUT  /mitra/profil              → PartnerProfileController@update
GET  /mitra/outfit              → PartnerOutfitController@index
POST /mitra/outfit              → PartnerOutfitController@store
...

// ADMIN AREA
GET  /admin                     → AdminDashboardController@index
GET  /admin/mitra               → AdminPartnerController@index
PUT  /admin/mitra/{id}/approve  → AdminPartnerController@approve
PUT  /admin/mitra/{id}/reject   → AdminPartnerController@reject
PUT  /admin/mitra/{id}/suspend  → AdminPartnerController@suspend
GET  /admin/produk              → AdminProductController@index (semua produk)
PUT  /admin/produk/{id}/suspend → AdminProductController@suspend
GET  /admin/review              → AdminReviewController@index
DELETE /admin/review/{id}       → AdminReviewController@destroy
GET  /admin/outfit              → AdminOutfitController@index
...
```

---

## Controllers Baru

```
app/Http/Controllers/
├── Public/
│   ├── CatalogController.php      (update existing)
│   ├── PartnerController.php      (NEW - daftar toko, profil toko)
│   └── LookbookController.php     (NEW - outfit/lookbook)
├── Member/
│   ├── MemberAuthController.php   (NEW)
│   └── ReviewController.php       (NEW)
├── Partner/
│   ├── PartnerAuthController.php  (NEW)
│   ├── PartnerDashboardController.php (NEW)
│   ├── PartnerProductController.php   (NEW)
│   ├── PartnerProfileController.php   (NEW)
│   └── PartnerOutfitController.php    (NEW)
└── Admin/
    ├── AdminDashboardController.php   (update existing)
    ├── AdminPartnerController.php     (NEW)
    ├── AdminProductController.php     (update existing)
    ├── AdminReviewController.php      (NEW)
    └── AdminOutfitController.php      (NEW)
```

---

## Models Baru/Update

```
app/Models/
├── User.php          (update — tambah role, relasi partner)
├── Partner.php       (NEW)
├── Product.php       (update — tambah partner_id, relasi)
├── Review.php        (NEW)
├── Outfit.php        (NEW)
└── OutfitItem.php    (NEW)
```

---

## Halaman Views

```
resources/views/
├── public/
│   ├── catalog/
│   │   ├── index.blade.php    (update — tampilkan badge mitra)
│   │   └── show.blade.php     (update — tambah review section, info mitra)
│   ├── partners/
│   │   ├── index.blade.php    (NEW — daftar semua mitra)
│   │   ├── show.blade.php     (NEW — profil toko mitra)
│   │   └── register.blade.php (NEW — form daftar mitra)
│   └── lookbook/
│       └── index.blade.php    (update — curated outfits)
├── member/
│   ├── login.blade.php        (NEW)
│   └── register.blade.php     (NEW)
├── partner/
│   ├── login.blade.php        (NEW)
│   ├── dashboard.blade.php    (NEW)
│   ├── products/
│   │   ├── index.blade.php    (NEW)
│   │   ├── create.blade.php   (NEW)
│   │   └── edit.blade.php     (NEW)
│   ├── profile.blade.php      (NEW)
│   └── outfits/
│       └── index.blade.php    (NEW)
└── admin/
    ├── dashboard.blade.php    (update)
    ├── partners/
    │   └── index.blade.php    (NEW)
    ├── products/
    │   └── index.blade.php    (update)
    └── reviews/
        └── index.blade.php    (NEW)
```

---

## Rating Calculation

Rating toko dihitung via accessor di model `Partner`:

```php
// Partner.php
public function getRatingAttribute(): float
{
    return $this->products()
        ->withAvg('reviews', 'rating')
        ->get()
        ->avg('reviews_avg_rating') ?? 0;
}

public function getReviewCountAttribute(): int
{
    return Review::whereIn('product_id', $this->products()->pluck('id'))->count();
}
```

---

## Upload Foto Produk

Mitra upload foto → disimpan di `storage/app/public/products/{partner_id}/`
Diakses via `Storage::url($path)` → `/storage/products/{partner_id}/filename.jpg`

Jalankan `php artisan storage:link` untuk symlink.

---

## Urutan Implementasi (Tasks)

1. Database migrations (partners, update users, update products, reviews, outfits, outfit_items)
2. Models + relasi
3. Auth multi-guard (member + partner)
4. Pendaftaran mitra (public form + admin verifikasi)
5. Dashboard mitra + CRUD produk
6. Update katalog publik (badge mitra, filter per toko)
7. Halaman profil toko
8. Sistem review
9. Curated outfit / lookbook update
10. Dashboard admin update (statistik, moderasi)
