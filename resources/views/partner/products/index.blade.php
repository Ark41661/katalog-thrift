<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Produk Saya - {{ $partner->store_name }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#111827;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1f2937;}
        .sidebar-brand .name{font-size:14px;font-weight:900;letter-spacing:1px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#6b7280;margin-top:2px;}
        .sidebar-nav{padding:16px 0;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1f2937;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1f2937;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;flex-wrap:wrap;gap:10px;}
        .head h1{font-size:22px;font-weight:900;}
        .head-right{display:flex;gap:8px;align-items:center;flex-wrap:wrap;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:0;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:12px 14px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        th{font-weight:700;background:#f9fafb;}
        img.thumb{width:48px;height:48px;object-fit:cover;border:1px solid #e5e7eb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-active{background:#dcfce7;color:#166534;}
        .badge-sold{background:#fee2e2;color:#991b1b;}
        .badge-inactive{background:#f3f4f6;color:#6b7280;}
        .badge-variant{background:#fef3c7;color:#92400e;font-size:10px;margin-left:4px;}
        .btn{display:inline-block;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .btn-danger{background:#dc2626;color:#fff;}
        .btn-success{background:#16a34a;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
        .empty{text-align:center;padding:60px;color:#6b7280;}
        .bulk-bar{background:#f9fafb;border-bottom:1px solid #e5e7eb;padding:10px 14px;display:flex;align-items:center;gap:10px;flex-wrap:wrap;}
        .bulk-bar.hidden{display:none;}
        .bulk-bar select{padding:6px 8px;font-size:12px;border:1px solid #d1d5db;}
        .checkbox-col{width:36px;}
        .variants-popup{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;align-items:center;justify-content:center;}
        .variants-popup.open{display:flex;}
        .vars-box{background:#fff;padding:28px;width:min(520px,94%);max-height:80vh;overflow-y:auto;}
        .vars-box h3{font-size:18px;font-weight:900;margin-bottom:14px;}
        .vars-box table{width:100%;border-collapse:collapse;margin-bottom:12px;}
        .vars-box th,.vars-box td{padding:8px 10px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        .vars-box .close{float:right;font-size:20px;cursor:pointer;background:none;border:0;}
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
            <a href="{{ route('partner.products.index') }}" class="active">📦 Produk Saya</a>
            <a href="{{ route('partner.products.create') }}">➕ Tambah Produk</a>
            <a href="{{ route('partner.analytics') }}">📈 Analitik</a>
            <a href="{{ route('partner.questions.index') }}">❓ Pertanyaan</a>
            <a href="{{ route('partner.profile') }}">🏪 Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head">
            <h1>Produk Saya ({{ $products->count() }})</h1>
            <div class="head-right">
                <a href="{{ route('partner.products.create') }}" class="btn btn-dark">+ Tambah Produk</a>
            </div>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <form id="bulk-form" method="POST" action="{{ route('partner.products.bulk-update') }}">
                @csrf
                <input type="hidden" name="action" id="bulk-action" value="">
                <div class="bulk-bar hidden" id="bulk-bar">
                    <span id="bulk-count" style="font-size:13px;font-weight:700;">0 terpilih</span>
                    <select id="bulk-action-select">
                        <option value="">-- Aksi Massal --</option>
                        <option value="activate">Aktifkan</option>
                        <option value="deactivate">Nonaktifkan</option>
                        <option value="mark_sold">Tandai Terjual</option>
                        <option value="delete">Hapus</option>
                        <option value="export_csv">Export CSV</option>
                    </select>
                    <button type="button" class="btn btn-dark" id="bulk-execute" onclick="bulkAction()">Jalankan</button>
                    <button type="button" class="btn btn-outline" onclick="clearBulk()">Batal</button>
                </div>

                @if($products->isEmpty())
                    <div class="empty">Belum ada produk. <a href="{{ route('partner.products.create') }}" style="color:#dc2626;font-weight:700;">Tambah sekarang</a></div>
                @else
                    <table>
                        <thead>
                            <tr>
                                <th class="checkbox-col"><input type="checkbox" id="select-all" onchange="toggleAll(this)"></th>
                                <th>Foto</th>
                                <th>Nama</th>
                                <th>Kategori</th>
                                <th>Harga</th>
                                <th>Size</th>
                                <th>Varian</th>
                                <th>Status</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($products as $p)
                            <tr>
                                <td><input type="checkbox" name="product_ids[]" value="{{ $p->id }}" class="bulk-check" onchange="updateBulkBar()"></td>
                                <td><img class="thumb" src="{{ $p->image_url }}" alt="{{ $p->name }}"></td>
                                <td><strong>{{ $p->name }}</strong><br><small style="color:#6b7280;">{{ $p->brand }}</small></td>
                                <td>{{ $productTypes[$p->product_type]['emoji'] ?? '' }} {{ $productTypes[$p->product_type]['label'] ?? $p->product_type }}</td>
                                <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                                <td>{{ $p->size }}</td>
                                <td>
                                    @if($p->has_variants)
                                        <span class="badge badge-variant">{{ $p->variants->count() }} varian</span>
                                        <button type="button" class="btn btn-outline" style="padding:3px 6px;font-size:10px;" onclick="showVariants({{ $p->id }})">Lihat</button>
                                    @else
                                        <span style="color:#9ca3af;">—</span>
                                    @endif
                                </td>
                                <td>
                                    @if($p->is_sold) <span class="badge badge-sold">SOLD</span>
                                    @elseif($p->is_active) <span class="badge badge-active">Aktif</span>
                                    @else <span class="badge badge-inactive">Nonaktif</span>
                                    @endif
                                </td>
                                <td style="display:flex;gap:6px;flex-wrap:wrap;">
                                    <a href="{{ route('partner.products.edit', $p) }}" class="btn btn-outline">Edit</a>
                                    <form method="POST" action="{{ route('partner.products.destroy', $p) }}" onsubmit="return confirm('Hapus produk ini?')">
                                        @csrf @method('DELETE')
                                        <button type="submit" class="btn btn-danger">Hapus</button>
                                    </form>
                                </td>
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                @endif
            </form>
        </div>
    </main>

    {{-- Variants Popup --}}
    <div class="variants-popup" id="variants-popup">
        <div class="vars-box">
            <button class="close" onclick="document.getElementById('variants-popup').classList.remove('open')">✕</button>
            <h3 id="vars-title">Varian Produk</h3>
            <div id="vars-content"></div>
        </div>
    </div>

    <script>
        let variantsData = @json($products->pluck('variants', 'id'));
        function toggleAll(el) {
            document.querySelectorAll('.bulk-check').forEach(c => c.checked = el.checked);
            updateBulkBar();
        }
        function updateBulkBar() {
            const checked = document.querySelectorAll('.bulk-check:checked');
            const bar = document.getElementById('bulk-bar');
            if (checked.length > 0) {
                bar.classList.remove('hidden');
                document.getElementById('bulk-count').textContent = checked.length + ' terpilih';
            } else {
                bar.classList.add('hidden');
            }
        }
        function bulkAction() {
            const action = document.getElementById('bulk-action-select').value;
            if (!action) { alert('Pilih aksi terlebih dahulu.'); return; }
            if (action === 'delete') {
                if (!confirm('Hapus semua produk terpilih?')) return;
                const form = document.getElementById('bulk-form');
                form.action = '{{ route("partner.products.bulk-delete") }}';
                form.querySelector('#bulk-action').name = '_dummy';
                form.submit();
                return;
            }
            if (action === 'export_csv') {
                const form = document.getElementById('bulk-form');
                form.action = '{{ route("partner.products.export") }}';
                form.querySelector('#bulk-action').name = '_dummy';
                form.submit();
                return;
            }
            document.getElementById('bulk-form').action = '{{ route("partner.products.bulk-update") }}';
            document.getElementById('bulk-action').value = action;
            document.getElementById('bulk-form').submit();
        }
        function clearBulk() {
            document.querySelectorAll('.bulk-check').forEach(c => c.checked = false);
            document.querySelectorAll('#select-all').forEach(c => c.checked = false);
            updateBulkBar();
        }
        function showVariants(productId) {
            const vars = variantsData[productId] || [];
            const title = document.getElementById('vars-title');
            const content = document.getElementById('vars-content');
            title.textContent = 'Varian Produk';
            if (!vars.length) {
                content.innerHTML = '<p style="color:#6b7280;">Tidak ada varian.</p>';
            } else {
                let html = '<table><thead><tr><th>Size</th><th>Harga</th><th>Stok</th></tr></thead><tbody>';
                vars.forEach(v => {
                    html += '<tr><td>' + v.size + '</td><td>Rp ' + new Intl.NumberFormat('id-ID').format(v.price) + '</td><td>' + (v.stock ?? '—') + '</td></tr>';
                });
                html += '</tbody></table>';
                content.innerHTML = html;
            }
            document.getElementById('variants-popup').classList.add('open');
        }
    </script>
</body>
</html>
