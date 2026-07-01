<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Produk - {{ $partner->store_name }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#111827;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1f2937;}
        .sidebar-brand .name{font-size:14px;font-weight:900;letter-spacing:1px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#6b7280;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1f2937;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1f2937;}
        .main{margin-left:220px;padding:28px 32px;}
        .form-wrap{max-width:720px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=text],input[type=number],input[type=url],textarea,select{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        input[type=file]{width:100%;padding:8px;border:1px solid #d1d5db;font-size:13px;}
        textarea{resize:vertical;min-height:90px;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .error-msg{color:#dc2626;font-size:12px;margin-top:4px;}
        .checkbox-row{display:flex;align-items:center;gap:8px;margin-top:12px;}
        .checkbox-row input{width:auto;}
        .checkbox-row label{margin:0;font-weight:600;}
        .checkboxes{display:grid;grid-template-columns:1fr 1fr 1fr;gap:8px;margin-top:12px;}
        .btn{display:inline-block;padding:11px 20px;font-size:14px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .form-actions{display:flex;gap:10px;margin-top:4px;}
        .preview-img{margin-top:8px;max-width:180px;max-height:140px;object-fit:cover;border:1px solid #e5e7eb;display:none;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
        .tab-group{display:flex;gap:0;margin-bottom:12px;border:1px solid #e5e7eb;}
        .tab-btn{flex:1;padding:9px;font-size:13px;font-weight:600;border:0;background:#f9fafb;cursor:pointer;color:#6b7280;}
        .tab-btn.active{background:#111827;color:#fff;}
        .tab-content{display:none;}
        .tab-content.active{display:block;}
        .size-chart-table{width:100%;border-collapse:collapse;margin-top:12px;font-size:12px;}
        .size-chart-table th,.size-chart-table td{border:1px solid #e5e7eb;padding:6px 8px;text-align:center;}
        .size-chart-table th{background:#f9fafb;font-weight:700;}
        .size-chart-table input{width:100%;padding:6px;border:1px solid #d1d5db;font-size:12px;text-align:center;}
        .btn-add-row{margin-top:8px;padding:6px 12px;font-size:12px;font-weight:700;border:1px solid #e5e7eb;background:#f9fafb;cursor:pointer;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $partner->store_name }}</div>
            <div class="role">Portal Mitra</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('partner.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('partner.products.index') }}">📦 Produk Saya</a>
            <a href="{{ route('partner.products.create') }}" class="active">➕ Tambah Produk</a>
            <a href="{{ route('partner.analytics') }}">📈 Analitik</a>
            <a href="{{ route('partner.questions.index') }}">❓ Pertanyaan</a>
            <a href="{{ route('partner.notifications') }}">🔔 Notifikasi</a>
            <a href="{{ route('partner.profile') }}">🏪 Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="form-wrap">
            <div class="head">
                <h1>Tambah Produk</h1>
                <a href="{{ route('partner.products.index') }}" class="btn btn-outline">← Kembali</a>
            </div>

            <form method="POST" action="{{ route('partner.products.store') }}" enctype="multipart/form-data">
                @csrf

                <div class="section">
                    <p class="section-title">Info Dasar</p>
                    <label>Nama Produk</label>
                    <input type="text" name="name" value="{{ old('name') }}" required>
                    @error('name')<p class="error-msg">{{ $message }}</p>@enderror

                    <label>Brand</label>
                    <input type="text" name="brand" value="{{ old('brand') }}" required>

                    <label>Tipe Produk</label>
                    <select name="product_type" required>
                        @foreach($productTypes as $val => $type)
                            <option value="{{ $val }}" {{ old('product_type','hoodie') === $val ? 'selected' : '' }}>{{ $type['emoji'] }} {{ $type['label'] }}</option>
                        @endforeach
                    </select>

                    <label>Gaya</label>
                    <select name="style_type">
                        <option value="">-- Pilih Gaya --</option>
                        @foreach($styleTypes as $val => $label)
                            <option value="{{ $val }}" {{ old('style_type') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label>Harga (Rp)</label>
                    <input type="number" name="price" value="{{ old('price') }}" min="0" required>

                    <label>Ukuran</label>
                    <input type="text" name="size" value="{{ old('size') }}" placeholder="S / M / L / XL" required>

                    <label>Kondisi</label>
                    <input type="text" name="condition" value="{{ old('condition') }}" placeholder="9/10 atau Sangat Baik" required>
                </div>

                <div class="section">
                    <p class="section-title">Deskripsi & Cerita</p>
                    <label>Deskripsi Produk</label>
                    <textarea name="description" required>{{ old('description') }}</textarea>

                    <label>Cerita di Balik Produk <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <textarea name="story" style="min-height:70px;">{{ old('story') }}</textarea>
                    <p class="hint">Cerita unik produk ini — ditemukan di mana, kondisi spesial, dll.</p>
                </div>

                <div class="section">
                    <p class="section-title">Panduan Ukuran (Size Chart)</p>
                    <div class="checkbox-row">
                        <input type="checkbox" id="has_size_chart" name="has_size_chart" value="1" onchange="toggleSizeChart(this)">
                        <label for="has_size_chart">Tambahkan panduan ukuran untuk produk ini</label>
                    </div>
                    <div id="size-chart-wrap" style="display:none;margin-top:12px;">
                        <p class="hint">Isi ukuran dalam cm. Pelanggan akan melihat tabel ini di halaman detail produk.</p>
                        <table class="size-chart-table" id="size-chart-table">
                            <thead>
                                <tr>
                                    @foreach($sizeChartColumns as $key => $label)
                                        <th>{{ $label }}</th>
                                    @endforeach
                                    <th></th>
                                </tr>
                            </thead>
                            <tbody id="size-chart-body">
                                @foreach($sizeChartDefaults as $i => $row)
                                <tr>
                                    @foreach($sizeChartColumns as $key => $label)
                                        <td><input type="text" name="size_chart[{{ $i }}][{{ $key }}]" value="{{ $row[$key] ?? '' }}"></td>
                                    @endforeach
                                    <td><button type="button" onclick="this.closest('tr').remove()" style="border:0;background:none;color:#dc2626;cursor:pointer;font-weight:700;">✕</button></td>
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                        <button type="button" class="btn-add-row" onclick="addSizeRow()">+ Tambah Baris</button>
                    </div>
                </div>

                <div class="section">
                    <p class="section-title">Foto Produk</p>
                    <div class="tab-group">
                        <button type="button" class="tab-btn active" onclick="switchTab('upload')">Upload File</button>
                        <button type="button" class="tab-btn" onclick="switchTab('url')">Pakai URL</button>
                    </div>
                    <div id="tab-upload" class="tab-content active">
                        <label>Upload Foto (maks 2MB)</label>
                        <input type="file" name="image_file" accept="image/*" onchange="previewFile(this)">
                        <img id="file-preview" class="preview-img" src="" alt="Preview">
                    </div>
                    <div id="tab-url" class="tab-content">
                        <label>URL Foto</label>
                        <input type="url" name="image" value="{{ old('image') }}" placeholder="https://..." oninput="previewUrl(this.value)">
                        <img id="url-preview" class="preview-img" src="" alt="Preview">
                    </div>
                    @error('image_file')<p class="error-msg">{{ $message }}</p>@enderror
                    @error('image')<p class="error-msg">{{ $message }}</p>@enderror
                </div>

                <div class="section">
                    <p class="section-title">Link Toko</p>
                    <label>Link Produk Shopee <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <input type="url" name="shopee_url" value="{{ old('shopee_url') }}" placeholder="https://shopee.co.id/...">

                    <label>Link Produk Tokopedia <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <input type="url" name="tokopedia_url" value="{{ old('tokopedia_url') }}" placeholder="https://tokopedia.com/...">
                </div>

                <div class="section">
                    <p class="section-title">Varian Produk</p>
                    <div class="checkbox-row">
                        <input type="checkbox" id="has_variants" name="has_variants" value="1" onchange="toggleVariants(this)" {{ old('has_variants') ? 'checked' : '' }}>
                        <label for="has_variants">Produk ini memiliki varian (size/harga berbeda)</label>
                    </div>
                    <div id="variants-wrap" style="display:none;margin-top:12px;">
                        <p class="hint">Setiap varian memiliki ukuran dan harga sendiri. Biarkan harga utama sebagai default.</p>
                        <table class="size-chart-table" style="margin-top:8px;">
                            <thead><tr><th>Ukuran</th><th>Harga (Rp)</th><th>Stok</th><th></th></tr></thead>
                            <tbody id="variants-body">
                                <tr>
                                    <td><input type="text" name="variants[0][size]" placeholder="M"></td>
                                    <td><input type="number" name="variants[0][price]" placeholder="150000" min="0"></td>
                                    <td><input type="number" name="variants[0][stock]" placeholder="1" min="0"></td>
                                    <td><button type="button" onclick="this.closest('tr').remove()" style="border:0;background:none;color:#dc2626;cursor:pointer;">✕</button></td>
                                </tr>
                            </tbody>
                        </table>
                        <button type="button" class="btn-add-row" onclick="addVariantRow()">+ Tambah Varian</button>
                    </div>
                </div>

                <div class="section">
                    <p class="section-title">SEO</p>
                    <label>Meta Title <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <input type="text" name="meta_title" value="{{ old('meta_title') }}" placeholder="{{ old('name', 'Nama Produk') }}">
                    <label>Meta Description <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <textarea name="meta_description" placeholder="Deskripsi singkat untuk SEO..." style="min-height:50px;">{{ old('meta_description') }}</textarea>
                    <label>Meta Keywords <span style="font-weight:400;color:#9ca3af">(pisahkan dengan koma)</span></label>
                    <input type="text" name="meta_keywords" value="{{ old('meta_keywords') }}" placeholder="thrift, hoodie, preloved">
                </div>

                <div class="section">
                    <p class="section-title">Status</p>
                    <div class="checkboxes">
                        <div class="checkbox-row">
                            <input type="checkbox" id="is_active" name="is_active" value="1" {{ old('is_active','1') ? 'checked' : '' }}>
                            <label for="is_active">Aktif di Katalog</label>
                        </div>
                        <div class="checkbox-row">
                            <input type="checkbox" id="is_new_arrival" name="is_new_arrival" value="1" {{ old('is_new_arrival') ? 'checked' : '' }}>
                            <label for="is_new_arrival">🆕 New Arrival</label>
                        </div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark">Simpan Produk</button>
                    <a href="{{ route('partner.products.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </main>
    <script>
        const sizeColumns = @json(array_keys($sizeChartColumns));
        let sizeRowIndex = {{ count($sizeChartDefaults) }};

        function toggleSizeChart(cb) {
            document.getElementById('size-chart-wrap').style.display = cb.checked ? 'block' : 'none';
        }

        function addSizeRow() {
            const tbody = document.getElementById('size-chart-body');
            const tr = document.createElement('tr');
            sizeColumns.forEach(key => {
                const td = document.createElement('td');
                td.innerHTML = `<input type="text" name="size_chart[${sizeRowIndex}][${key}]" value="">`;
                tr.appendChild(td);
            });
            const tdDel = document.createElement('td');
            tdDel.innerHTML = '<button type="button" onclick="this.closest(\'tr\').remove()" style="border:0;background:none;color:#dc2626;cursor:pointer;font-weight:700;">✕</button>';
            tr.appendChild(tdDel);
            tbody.appendChild(tr);
            sizeRowIndex++;
        }

        function toggleVariants(cb) { document.getElementById('variants-wrap').style.display = cb.checked ? 'block' : 'none'; }
        let varRowIndex = 1;
        function addVariantRow() {
            const tbody = document.getElementById('variants-body');
            const tr = document.createElement('tr');
            tr.innerHTML = `
                <td><input type="text" name="variants[${varRowIndex}][size]" placeholder="L"></td>
                <td><input type="number" name="variants[${varRowIndex}][price]" placeholder="150000" min="0"></td>
                <td><input type="number" name="variants[${varRowIndex}][stock]" placeholder="1" min="0"></td>
                <td><button type="button" onclick="this.closest('tr').remove()" style="border:0;background:none;color:#dc2626;cursor:pointer;">✕</button></td>`;
            tbody.appendChild(tr);
            varRowIndex++;
        }

        function switchTab(tab) {
            document.querySelectorAll('.tab-btn').forEach(b => b.classList.remove('active'));
            document.querySelectorAll('.tab-content').forEach(c => c.classList.remove('active'));
            document.getElementById('tab-' + tab).classList.add('active');
            event.target.classList.add('active');
        }
        function previewFile(input) {
            const img = document.getElementById('file-preview');
            if (input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
                img.style.display = 'block';
            }
        }
        function previewUrl(url) {
            const img = document.getElementById('url-preview');
            if (url) { img.src = url; img.style.display = 'block'; img.onerror = () => img.style.display = 'none'; }
            else img.style.display = 'none';
        }
    </script>
</body>
</html>
