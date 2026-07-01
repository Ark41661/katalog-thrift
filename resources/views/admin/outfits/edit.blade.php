<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Edit Outfit Kurasi - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
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
        .checkbox-row{display:flex;align-items:center;gap:8px;margin-top:12px;}
        .checkbox-row input{width:auto;}
        .checkbox-row label{margin:0;font-weight:600;}
        .product-search{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;margin-bottom:12px;}
        .product-list{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:10px;max-height:400px;overflow-y:auto;border:1px solid #e5e7eb;padding:12px;}
        .product-item{border:2px solid #e5e7eb;cursor:pointer;transition:border-color .15s;position:relative;}
        .product-item.selected{border-color:#dc2626;background:#fff5f5;}
        .product-item img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .product-item-body{padding:8px;}
        .product-item-name{font-size:12px;font-weight:700;line-height:1.3;margin-bottom:2px;}
        .product-item-meta{font-size:11px;color:#6b7280;}
        .product-item-badge{position:absolute;top:6px;right:6px;background:#dc2626;color:#fff;font-size:10px;font-weight:900;width:20px;height:20px;border-radius:50%;display:none;align-items:center;justify-content:center;}
        .product-item.selected .product-item-badge{display:flex;}
        .selected-list{display:flex;flex-direction:column;gap:8px;min-height:60px;}
        .selected-item{display:flex;align-items:center;gap:10px;padding:10px;background:#f9fafb;border:1px solid #e5e7eb;}
        .selected-item img{width:44px;height:44px;object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;}
        .selected-item-info{flex:1;}
        .selected-item-name{font-size:13px;font-weight:700;}
        .selected-item-meta{font-size:11px;color:#6b7280;}
        .selected-item-note{flex:1;}
        .selected-item-note input{padding:6px 10px;border:1px solid #d1d5db;font-size:12px;width:100%;}
        .selected-item-remove{background:#fee2e2;color:#dc2626;border:0;padding:6px 10px;font-size:12px;font-weight:700;cursor:pointer;flex-shrink:0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .hotspot-canvas-wrap{position:relative;max-width:400px;margin-top:12px;border:2px solid #e5e7eb;background:#111;cursor:crosshair;}
        .hotspot-canvas-wrap img{width:100%;display:block;aspect-ratio:4/5;object-fit:cover;}
        .hotspot-dot{position:absolute;width:18px;height:18px;background:#fff;border-radius:50%;transform:translate(-50%,-50%);box-shadow:0 0 0 3px rgba(255,255,255,0.4);pointer-events:none;font-size:9px;font-weight:900;color:#111;display:flex;align-items:center;justify-content:center;}
        .hotspot-product-list{display:flex;flex-wrap:wrap;gap:8px;margin-top:12px;}
        .hotspot-product-btn{padding:6px 12px;font-size:12px;font-weight:700;border:1px solid #e5e7eb;background:#fff;cursor:pointer;}
        .hotspot-product-btn.active{background:#dc2626;color:#fff;border-color:#dc2626;}
        .hotspot-product-btn.placed{border-color:#16a34a;color:#16a34a;}
        input[type=file]{padding:8px;border:1px solid #d1d5db;font-size:13px;width:100%;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $storeName }}</div>
            <div class="role">Super Admin</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.partners.index') }}">🏪 Kelola Mitra</a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.outfits.index') }}" class="active">✦ Outfit Kurasi</a>
            <a href="{{ route('admin.reviews.index') }}">⭐ Review</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="wrap">
            <div class="head">
                <h1>Edit Outfit: {{ $outfit->title }}</h1>
                <a href="{{ route('admin.outfits.index') }}" class="btn btn-outline">← Kembali</a>
            </div>

            <form method="POST" action="{{ route('admin.outfits.update', $outfit) }}" id="outfit-form" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="section">
                    <p class="section-title">Info Outfit</p>
                    <label>Judul Outfit</label>
                    <input type="text" name="title" value="{{ old('title', $outfit->title) }}" required>

                    <label>Deskripsi</label>
                    <textarea name="description">{{ old('description', $outfit->description) }}</textarea>

                    <label>Gaya</label>
                    <select name="style_type">
                        <option value="">-- Pilih Gaya --</option>
                        @foreach($styleTypes as $val => $label)
                            <option value="{{ $val }}" {{ old('style_type', $outfit->style_type) === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <div class="checkbox-row" style="margin-top:14px;">
                        <input type="checkbox" id="is_active" name="is_active" value="1" {{ $outfit->is_active ? 'checked' : '' }}>
                        <label for="is_active">Tampilkan di Lookbook</label>
                    </div>
                </div>

                <div class="section">
                    <p class="section-title">Pilih Produk (2–6 item)</p>
                    <input type="text" class="product-search" placeholder="Cari nama produk atau brand..." oninput="filterProducts(this.value)">
                    <div class="product-list">
                        @foreach($products as $p)
                        <div class="product-item {{ $outfit->products->contains($p->id) ? 'selected' : '' }}"
                             id="item-{{ $p->id }}"
                             data-id="{{ $p->id }}" data-name="{{ strtolower($p->name) }}" data-brand="{{ strtolower($p->brand) }}"
                             onclick="toggleProduct({{ $p->id }}, '{{ addslashes($p->name) }}', '{{ addslashes($p->brand) }}', '{{ $p->size }}', '{{ $p->image_url }}', '{{ $p->partner?->store_name }}')">
                            <span class="product-item-badge" id="badge-{{ $p->id }}" style="{{ $outfit->products->contains($p->id) ? 'display:flex' : '' }}">✓</span>
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
                            <div class="product-item-body">
                                <div class="product-item-name">{{ $p->name }}</div>
                                <div class="product-item-meta">{{ $p->brand }} · {{ $p->size }}</div>
                                <div class="product-item-meta" style="color:#1d4ed8;">{{ $p->partner?->store_name }}</div>
                            </div>
                        </div>
                        @endforeach
                    </div>
                </div>

                <div class="section">
                    <p class="section-title">Produk Terpilih</p>
                    <div class="selected-list" id="selected-list"></div>
                    <div id="hidden-inputs"></div>
                </div>

                <div class="section">
                    <p class="section-title">Foto / Video Sinematik</p>
                    @if($outfit->cover_image)
                        <p class="hint">Cover saat ini:</p>
                        <img src="{{ Str::startsWith($outfit->cover_image, 'http') ? $outfit->cover_image : asset('storage/'.$outfit->cover_image) }}" style="max-width:200px;margin-bottom:12px;display:block;" id="existing-cover">
                    @endif
                    <label>Ganti Cover Image</label>
                    <input type="file" name="cover_image" accept="image/*" id="cover-input" onchange="previewCover(this)">
                    <label style="margin-top:14px;">URL Video</label>
                    <input type="url" name="cover_video" value="{{ old('cover_video', $outfit->cover_video) }}" placeholder="https://...">
                    <div id="hotspot-section" style="{{ $outfit->cover_image ? '' : 'display:none;' }}margin-top:16px;">
                        <label>Hotspot Interaktif</label>
                        <div class="hotspot-product-list" id="hotspot-product-list"></div>
                        <div class="hotspot-canvas-wrap" id="hotspot-canvas" onclick="placeHotspot(event)">
                            <img id="cover-preview" src="{{ $outfit->cover_image ? (Str::startsWith($outfit->cover_image, 'http') ? $outfit->cover_image : asset('storage/'.$outfit->cover_image)) : '' }}" alt="Cover">
                            <div id="hotspot-dots"></div>
                        </div>
                        <div id="hotspot-hidden"></div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark" id="submit-btn">Simpan Perubahan</button>
                    <a href="{{ route('admin.outfits.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </main>

    <script>
    const existingItems = @json($outfit->items->map(fn($i) => [
        'id'    => $i->product->id,
        'name'  => $i->product->name,
        'brand' => $i->product->brand,
        'size'  => $i->product->size,
        'image' => $i->product->image_url,
        'store' => $i->product->partner?->store_name,
        'note'  => $i->note,
    ]));
    const existingHotspots = @json($outfit->items->filter(fn($i) => $i->hotspot_x !== null)->mapWithKeys(fn($i) => [$i->product_id => ['x' => $i->hotspot_x, 'y' => $i->hotspot_y]]));

    const selected = [...existingItems];
    const hotspots = { ...existingHotspots };
    let activeHotspotProduct = null;
    renderSelected();
    renderHotspotProducts();

    function toggleProduct(id, name, brand, size, image, store) {
        const idx = selected.findIndex(p => p.id === id);
        if (idx > -1) {
            selected.splice(idx, 1);
            document.getElementById('badge-' + id).parentElement.classList.remove('selected');
            document.getElementById('badge-' + id).style.display = 'none';
        } else {
            if (selected.length >= 6) { alert('Maksimal 6 produk per outfit.'); return; }
            selected.push({ id, name, brand, size, image, store, note: '' });
            document.getElementById('badge-' + id).parentElement.classList.add('selected');
            document.getElementById('badge-' + id).style.display = 'flex';
        }
        renderSelected();
        renderHotspotProducts();
    }

    function previewCover(input) {
        const section = document.getElementById('hotspot-section');
        const img = document.getElementById('cover-preview');
        if (input.files && input.files[0]) {
            img.src = URL.createObjectURL(input.files[0]);
            section.style.display = 'block';
            renderHotspotProducts();
        }
    }

    function renderHotspotProducts() {
        const list = document.getElementById('hotspot-product-list');
        if (!list || selected.length === 0) return;
        list.innerHTML = selected.map((p, i) => `
            <button type="button" class="hotspot-product-btn ${hotspots[p.id] ? 'placed' : ''} ${activeHotspotProduct === p.id ? 'active' : ''}"
                onclick="setActiveHotspot(${p.id})">${i+1}. ${p.name.substring(0,20)}${hotspots[p.id] ? ' ✓' : ''}</button>
        `).join('');
        renderHotspotDots();
    }

    function setActiveHotspot(id) { activeHotspotProduct = id; renderHotspotProducts(); }

    function placeHotspot(e) {
        if (!activeHotspotProduct) { alert('Pilih produk dulu, lalu klik pada foto.'); return; }
        const rect = e.currentTarget.getBoundingClientRect();
        hotspots[activeHotspotProduct] = {
            x: ((e.clientX - rect.left) / rect.width * 100).toFixed(1),
            y: ((e.clientY - rect.top) / rect.height * 100).toFixed(1)
        };
        renderHotspotProducts();
        syncHotspotInputs();
    }

    function renderHotspotDots() {
        const dots = document.getElementById('hotspot-dots');
        if (!dots) return;
        dots.innerHTML = '';
        selected.forEach((p, i) => {
            if (hotspots[p.id]) {
                const dot = document.createElement('div');
                dot.className = 'hotspot-dot';
                dot.style.left = hotspots[p.id].x + '%';
                dot.style.top = hotspots[p.id].y + '%';
                dot.textContent = i + 1;
                dots.appendChild(dot);
            }
        });
    }

    function syncHotspotInputs() {
        const hidden = document.getElementById('hotspot-hidden');
        hidden.innerHTML = '';
        Object.entries(hotspots).forEach(([id, pos]) => {
            hidden.innerHTML += `<input type="hidden" name="hotspots[${id}][x]" value="${pos.x}">`;
            hidden.innerHTML += `<input type="hidden" name="hotspots[${id}][y]" value="${pos.y}">`;
        });
    }

    function renderSelected() {
        const list   = document.getElementById('selected-list');
        const hidden = document.getElementById('hidden-inputs');
        hidden.innerHTML = '';

        if (selected.length === 0) {
            list.innerHTML = '<div style="color:#9ca3af;font-size:13px;text-align:center;padding:20px;">Belum ada produk dipilih.</div>';
            renderHotspotProducts();
            return;
        }

        list.innerHTML = selected.map(p => `
            <div class="selected-item">
                <img src="${p.image}" alt="${p.name}">
                <div class="selected-item-info">
                    <div class="selected-item-name">${p.name}</div>
                    <div class="selected-item-meta">${p.brand} · Size ${p.size} · ${p.store || ''}</div>
                </div>
                <div class="selected-item-note">
                    <input type="text" placeholder="Catatan (opsional)" id="note-${p.id}" value="${p.note || ''}">
                </div>
                <button type="button" class="selected-item-remove" onclick="removeProduct(${p.id})">✕</button>
            </div>
        `).join('');

        selected.forEach(p => {
            hidden.innerHTML += `<input type="hidden" name="products[]" value="${p.id}">`;
        });
        renderHotspotProducts();
    }

    function removeProduct(id) {
        const idx = selected.findIndex(p => p.id === id);
        if (idx > -1) selected.splice(idx, 1);
        delete hotspots[id];
        const badge = document.getElementById('badge-' + id);
        if (badge) { badge.parentElement.classList.remove('selected'); badge.style.display = 'none'; }
        renderSelected();
        syncHotspotInputs();
    }

    function filterProducts(q) {
        document.querySelectorAll('.product-item').forEach(item => {
            item.style.display = (item.dataset.name.includes(q.toLowerCase()) || item.dataset.brand.includes(q.toLowerCase())) ? 'block' : 'none';
        });
    }

    document.getElementById('outfit-form').addEventListener('submit', function() {
        const hidden = document.getElementById('hidden-inputs');
        selected.forEach(p => {
            const noteEl = document.getElementById('note-' + p.id);
            if (noteEl && noteEl.value) {
                hidden.innerHTML += `<input type="hidden" name="notes[${p.id}]" value="${noteEl.value}">`;
            }
        });
        syncHotspotInputs();
    });
    </script>
</body>
</html>
