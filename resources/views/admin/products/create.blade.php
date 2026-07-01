<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - Admin {{ $storeName }}</title>
    <style>
        body { margin: 0; font-family: Arial, Helvetica, sans-serif; background: #f3f4f6; color: #111827; }
        .container { width: min(760px, 94%); margin: 24px auto; padding-bottom: 40px; }
        .head { display: flex; justify-content: space-between; align-items: center; gap: 12px; margin-bottom: 16px; }
        .card { border: 1px solid #e5e7eb; background: #fff; padding: 24px; margin-bottom: 16px; }
        .section-title { font-size: 11px; font-weight: 900; letter-spacing: 2px; text-transform: uppercase; color: #6b7280; margin: 0 0 16px; padding-bottom: 8px; border-bottom: 1px solid #f1f5f9; }
        label { display: block; font-size: 13px; font-weight: 700; margin-bottom: 6px; margin-top: 14px; }
        label:first-of-type { margin-top: 0; }
        input[type=text], input[type=number], input[type=url], textarea {
            width: 100%; padding: 10px 12px; border: 1px solid #d1d5db;
            font-size: 14px; font-family: inherit; box-sizing: border-box;
        }
        textarea { resize: vertical; min-height: 100px; }
        .checkbox-row { display: flex; align-items: center; gap: 8px; margin-top: 12px; }
        .checkbox-row input { width: auto; }
        .checkbox-row label { margin: 0; font-weight: 600; }
        .checkboxes { display: grid; grid-template-columns: 1fr 1fr 1fr; gap: 8px; margin-top: 14px; }
        .error-msg { color: #dc2626; font-size: 13px; margin-top: 4px; }
        .hint { font-size: 12px; color: #9ca3af; margin-top: 4px; }
        .btn { display: inline-block; border: 0; padding: 10px 18px; font-size: 14px; font-weight: 700; cursor: pointer; text-decoration: none; }
        .btn-dark { background: #111827; color: #fff; }
        .btn-dark:hover { background: #374151; }
        .btn-outline { background: #fff; color: #111827; border: 1px solid #d1d5db; }
        .btn-outline:hover { background: #f9fafb; }
        .form-actions { display: flex; gap: 10px; margin-top: 4px; }
        .preview-img { margin-top: 8px; max-width: 200px; max-height: 160px; object-fit: cover; border: 1px solid #e5e7eb; display: none; }
    </style>
</head>
<body>
    <main class="container">
        <div class="head">
            <h1 style="margin:0;">Tambah Produk</h1>
            <a href="{{ route('admin.products.index') }}" class="btn btn-outline">← Kembali</a>
        </div>

        <form method="POST" action="{{ route('admin.products.store') }}">
            @csrf

            {{-- INFO DASAR --}}
            <section class="card">
                <p class="section-title">Info Dasar</p>

                <label for="name">Nama Produk</label>
                <input id="name" name="name" type="text" value="{{ old('name') }}" required>
                @error('name') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="brand">Brand</label>
                <input id="brand" name="brand" type="text" value="{{ old('brand') }}" required>
                @error('brand') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="color">Warna <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <div style="display:flex;gap:8px;align-items:center;">
                    <input id="color" name="color" type="text" value="{{ old('color') }}" placeholder="Contoh: Orange, Black, Maroon" style="flex:1;">
                    <input id="color_hex" name="color_hex" type="color" value="{{ old('color_hex', '#000000') }}" style="width:44px;height:42px;padding:2px;border:1px solid #d1d5db;cursor:pointer;">
                </div>
                <p class="hint">Nama warna untuk filter outfit generator. Klik kotak warna untuk pilih kode warna.</p>
                @error('color') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="style_type">Tipe Gaya</label>
                <select id="style_type" name="style_type">
                    <option value="">-- Pilih Gaya --</option>
                    <option value="casual" {{ old('style_type') === 'casual' ? 'selected' : '' }}>Casual</option>
                    <option value="streetwear" {{ old('style_type') === 'streetwear' ? 'selected' : '' }}>Streetwear</option>
                    <option value="sporty" {{ old('style_type') === 'sporty' ? 'selected' : '' }}>Sporty</option>
                    <option value="vintage" {{ old('style_type') === 'vintage' ? 'selected' : '' }}>Vintage</option>
                </select>
                @error('style_type') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="product_type">Tipe Produk</label>
                <select id="product_type" name="product_type">
                    @foreach(config('catalog.product_types') as $val => $type)
                        <option value="{{ $val }}" {{ old('product_type', 'hoodie') === $val ? 'selected' : '' }}>
                            {{ $type['emoji'] }} {{ $type['label'] }}
                        </option>
                    @endforeach
                </select>
                @error('product_type') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="price">Harga (Rp)</label>
                <input id="price" name="price" type="number" min="0" value="{{ old('price') }}" required>
                @error('price') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="size">Size</label>
                <input id="size" name="size" type="text" value="{{ old('size') }}" placeholder="S / M / L / XL / XXL" required>
                @error('size') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="condition">Kondisi</label>
                <input id="condition" name="condition" type="text" value="{{ old('condition') }}" placeholder="9/10 atau 8.5/10" required>
                @error('condition') <p class="error-msg">{{ $message }}</p> @enderror
            </section>

            {{-- DESKRIPSI & CERITA --}}
            <section class="card">
                <p class="section-title">Deskripsi & Cerita Produk</p>

                <label for="description">Deskripsi Produk</label>
                <textarea id="description" name="description" required>{{ old('description') }}</textarea>
                <p class="hint">Detail produk: bahan, warna, fitur, dll.</p>
                @error('description') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="story">Cerita di Balik Produk <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <textarea id="story" name="story" style="min-height:80px;">{{ old('story') }}</textarea>
                <p class="hint">Contoh: "Hoodie ini ditemukan di pasar vintage Tokyo, kondisi masih sangat mulus." Cerita ini tampil di halaman detail produk.</p>
                @error('story') <p class="error-msg">{{ $message }}</p> @enderror
            </section>

            {{-- FOTO --}}
            <section class="card">
                <p class="section-title">Foto</p>

                <label for="image">URL Foto Produk Utama</label>
                <input id="image" name="image" type="url" value="{{ old('image') }}" placeholder="https://..." required oninput="previewImg('img-preview', this.value)">
                @error('image') <p class="error-msg">{{ $message }}</p> @enderror
                <img id="img-preview" class="preview-img" src="" alt="Preview">

                <label for="lookbook_image">URL Foto Lookbook <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <input id="lookbook_image" name="lookbook_image" type="url" value="{{ old('lookbook_image') }}" placeholder="https://..." oninput="previewImg('lb-preview', this.value)">
                <p class="hint">Foto gaya/outfit lengkap untuk halaman Lookbook. Bisa berbeda dari foto produk utama.</p>
                @error('lookbook_image') <p class="error-msg">{{ $message }}</p> @enderror
                <img id="lb-preview" class="preview-img" src="" alt="Preview Lookbook">

                <label for="lookbook_style_tip">Style Tip <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <input id="lookbook_style_tip" name="lookbook_style_tip" type="text" value="{{ old('lookbook_style_tip') }}" placeholder="Contoh: Cocok dipadukan celana cargo dan sneakers putih">
                <p class="hint">Saran mix & match yang tampil di hover foto lookbook.</p>
                @error('lookbook_style_tip') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="lookbook_pairing">Padukan Dengan <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <textarea id="lookbook_pairing" name="lookbook_pairing" style="min-height:80px;" placeholder="Celana: Cargo Pants Hitam&#10;Sepatu: Sneakers Putih&#10;Aksesori: Topi Bucket">{{ old('lookbook_pairing') }}</textarea>
                <p class="hint">Satu item per baris, format: <strong>Label: Nama Item</strong>. Contoh: <code>Celana: Cargo Pants Hitam</code></p>
                @error('lookbook_pairing') <p class="error-msg">{{ $message }}</p> @enderror
            </section>

            {{-- LINK TOKO --}}
            <section class="card">
                <p class="section-title">Link Toko</p>

                <label for="shopee_url">Link Produk Shopee <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <input id="shopee_url" name="shopee_url" type="url" value="{{ old('shopee_url') }}" placeholder="https://shopee.co.id/product/...">
                @error('shopee_url') <p class="error-msg">{{ $message }}</p> @enderror

                <label for="tokopedia_url">Link Produk Tokopedia <span style="font-weight:400;color:#6b7280">(opsional)</span></label>
                <input id="tokopedia_url" name="tokopedia_url" type="url" value="{{ old('tokopedia_url') }}" placeholder="https://www.tokopedia.com/...">
                @error('tokopedia_url') <p class="error-msg">{{ $message }}</p> @enderror
            </section>

            {{-- STATUS --}}
            <section class="card">
                <p class="section-title">Status Produk</p>
                <div class="checkboxes">
                    <div class="checkbox-row">
                        <input id="is_active" name="is_active" type="checkbox" value="1" {{ old('is_active', '1') ? 'checked' : '' }}>
                        <label for="is_active">Aktif di Katalog</label>
                    </div>
                    <div class="checkbox-row">
                        <input id="is_new_arrival" name="is_new_arrival" type="checkbox" value="1" {{ old('is_new_arrival') ? 'checked' : '' }}>
                        <label for="is_new_arrival">🆕 New Arrival</label>
                    </div>
                    <div class="checkbox-row">
                        <input id="is_sold" name="is_sold" type="checkbox" value="1" {{ old('is_sold') ? 'checked' : '' }}>
                        <label for="is_sold">🔴 Tandai SOLD</label>
                    </div>
                </div>
            </section>

            <div class="form-actions">
                <button type="submit" class="btn btn-dark">Simpan Produk</button>
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline">Batal</a>
            </div>
        </form>
    </main>
    <script>
        function previewImg(id, url) {
            const img = document.getElementById(id);
            if (url) {
                img.src = url;
                img.style.display = 'block';
                img.onerror = () => { img.style.display = 'none'; };
            } else {
                img.style.display = 'none';
            }
        }
    </script>
</body>
</html>
