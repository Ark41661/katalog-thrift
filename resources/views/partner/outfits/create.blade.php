<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Buat Outfit - {{ $partner->store_name }}</title>
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
        .wrap{max-width:900px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=text],textarea,select{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{resize:vertical;min-height:70px;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .btn{display:inline-block;padding:10px 18px;font-size:14px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .form-actions{display:flex;gap:10px;margin-top:4px;}
        .product-search{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;margin-bottom:12px;}
        .product-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(150px,1fr));gap:10px;max-height:380px;overflow-y:auto;border:1px solid #e5e7eb;padding:12px;}
        .product-item{border:2px solid #e5e7eb;cursor:pointer;transition:border-color .15s;position:relative;}
        .product-item.selected{border-color:#dc2626;background:#fff5f5;}
        .product-item img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .product-item-body{padding:7px;}
        .product-item-name{font-size:11px;font-weight:700;line-height:1.3;margin-bottom:2px;}
        .product-item-meta{font-size:10px;color:#6b7280;}
        .product-item-store{font-size:10px;color:#1d4ed8;}
        .product-item-badge{position:absolute;top:5px;right:5px;background:#dc2626;color:#fff;font-size:9px;font-weight:900;width:18px;height:18px;border-radius:50%;display:none;align-items:center;justify-content:center;}
        .product-item.selected .product-item-badge{display:flex;}
        .selected-list{display:flex;flex-direction:column;gap:8px;min-height:50px;}
        .selected-item{display:flex;align-items:center;gap:10px;padding:10px;background:#f9fafb;border:1px solid #e5e7eb;}
        .selected-item img{width:40px;height:40px;object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;}
        .selected-item-info{flex:1;}
        .selected-item-name{font-size:13px;font-weight:700;}
        .selected-item-meta{font-size:11px;color:#6b7280;}
        .selected-item-remove{background:#fee2e2;color:#dc2626;border:0;padding:5px 10px;font-size:11px;font-weight:700;cursor:pointer;flex-shrink:0;}
        .empty-selected{color:#9ca3af;font-size:13px;text-align:center;padding:16px;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
        .my-products-label{font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#6b7280;margin-bottom:8px;padding:6px 0;border-bottom:1px solid #f1f5f9;}
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
            <a href="{{ route('partner.outfits.index') }}">✦ Outfit Saya</a>
            <a href="{{ route('partner.outfits.create') }}" class="active">➕ Buat Outfit</a>
            <a href="{{ route('partner.profile') }}">🏪 Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="wrap">
            <div class="head">
                <h1>Buat Outfit Baru</h1>
                <a href="{{ route('partner.outfits.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
            <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">Pilih 2–6 produk untuk dikombinasikan. Kamu bisa pilih produk dari tokomu sendiri <strong>atau produk mitra lain</strong> untuk mix & match lintas toko.</p>

            <form method="POST" action="{{ route('partner.outfits.store') }}" id="outfit-form">
                @csrf
                <div class="section">
                    <p class="section-title">Info Outfit</p>
                    <label>Judul Outfit</label>
                    <input type="text" name="title" value="{{ old('title') }}" placeholder="Contoh: Casual Street Look" required>
                    <label>Deskripsi <span style="font-weight:400;color:#9ca3af">(opsional)</span></label>
                    <textarea name="description" placeholder="Ceritakan konsep outfit ini...">{{ old('description') }}</textarea>
                    <label>Gaya</label>
                    <select name="style_type">
                        <option value="">-- Pilih Gaya --</option>
                        @foreach($styleTypes as $val => $label)
                            <option value="{{ $val }}">{{ $label }}</option>
                        @endforeach
                    </select>
                </div>

                <div class="section">
                    <p class="section-title">Pilih Produk (2–6 item)</p>
                    <input type="text" class="product-search" placeholder="Cari nama produk, brand, atau toko..." oninput="filterProducts(this.value)">
                    <div class="product-list">
                        @php $myPartnerIds = [$partner->id]; @endphp
                        @foreach($products as $p)
                        <div class="product-item {{ in_array($p->partner_id, $myPartnerIds) ? 'my-product' : '' }}"
                             data-id="{{ $p->id }}"
                             data-name="{{ strtolower($p->name) }}"
                             data-brand="{{ strtolower($p->brand) }}"
                             data-store="{{ strtolower($p->partner?->store_name ?? '') }}"
                             onclick="toggleProduct({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->brand) }}', '{{ $p->size }}', '{{ $p->image_url }}', '{{ $p->partner?->store_name }}', {{ $p->partner_id === $partner->id ? 'true' : 'false' }})">
                            <span class="product-item-badge" id="badge-{{ $p->id }}">✓</span>
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
                            <div class="product-item-body">
                                <div class="product-item-name">{{ $p->name }}</div>
                                <div class="product-item-meta">{{ $p->brand }} · {{ $p->size }}</div>
                                <div class="product-item-store {{ $p->partner_id === $partner->id ? '' : '' }}" style="{{ $p->partner_id === $partner->id ? 'color:#16a34a;' : 'color:#1d4ed8;' }}">
                                    {{ $p->partner_id === $partner->id ? '⭐ Toko Saya' : ($p->partner?->store_name ?? '') }}
                                </div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                    <p class="hint" style="margin-top:8px;">⭐ = produk dari toko kamu sendiri · Biru = produk mitra lain</p>
                </div>

                <div class="section">
                    <p class="section-title">Produk Terpilih</p>
                    <div class="selected-list" id="selected-list">
                        <div class="empty-selected">Belum ada produk dipilih.</div>
                    </div>
                    <div id="hidden-inputs"></div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark" id="submit-btn" disabled style="opacity:.5;cursor:not-allowed;">Pilih min. 2 produk</button>
                    <a href="{{ route('partner.outfits.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <script>
    const selected = [];
    function toggleProduct(id, name, brand, size, image, store, isMine) {
        const idx = selected.findIndex(p => p.id === id);
        if (idx > -1) {
            selected.splice(idx, 1);
            document.getElementById('badge-' + id).parentElement.classList.remove('selected');
        } else {
            if (selected.length >= 6) { alert('Maksimal 6 produk.'); return; }
            selected.push({ id, name, brand, size, image, store, isMine });
            document.getElementById('badge-' + id).parentElement.classList.add('selected');
        }
        renderSelected();
    }
    function renderSelected() {
        const list = document.getElementById('selected-list');
        const hidden = document.getElementById('hidden-inputs');
        const btn = document.getElementById('submit-btn');
        hidden.innerHTML = '';
        if (!selected.length) {
            list.innerHTML = '<div class="empty-selected">Belum ada produk dipilih.</div>';
            btn.disabled = true; btn.style.opacity = '.5'; btn.textContent = 'Pilih min. 2 produk'; return;
        }
        list.innerHTML = selected.map(p => `
            <div class="selected-item">
                <img src="${p.image}" alt="${p.name}">
                <div class="selected-item-info">
                    <div class="selected-item-name">${p.name}</div>
                    <div class="selected-item-meta">${p.brand} · Size ${p.size} · <span style="color:${p.isMine?'#16a34a':'#1d4ed8'}">${p.isMine?'⭐ Toko Saya':p.store}</span></div>
                </div>
                <button type="button" class="selected-item-remove" onclick="removeProduct(${p.id})">✕</button>
            </div>`).join('');
        selected.forEach(p => { hidden.innerHTML += `<input type="hidden" name="products[]" value="${p.id}">`; });
        btn.disabled = selected.length < 2;
        btn.style.opacity = selected.length < 2 ? '.5' : '1';
        btn.style.cursor = selected.length < 2 ? 'not-allowed' : 'pointer';
        btn.textContent = selected.length < 2 ? `Pilih min. 2 produk (${selected.length}/2)` : `Simpan Outfit (${selected.length} produk)`;
    }
    function removeProduct(id) {
        const idx = selected.findIndex(p => p.id === id);
        if (idx > -1) { selected.splice(idx, 1); document.getElementById('badge-'+id).parentElement.classList.remove('selected'); }
        renderSelected();
    }
    function filterProducts(q) {
        q = q.toLowerCase();
        document.querySelectorAll('.product-item').forEach(item => {
            const match = item.dataset.name.includes(q) || item.dataset.brand.includes(q) || item.dataset.store.includes(q);
            item.style.display = match ? 'block' : 'none';
        });
    }
    </script>
</body>
</html>
