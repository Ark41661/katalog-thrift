<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfit Saya - {{ $partner->store_name }}</title>
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
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .card{background:#fff;border:1px solid #e5e7eb;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:12px 14px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        th{font-weight:700;background:#f9fafb;}
        .outfit-photos{display:flex;gap:4px;}
        .outfit-photos img{width:36px;height:36px;object-fit:cover;border:1px solid #e5e7eb;border-radius:2px;}
        .btn{display:inline-block;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-danger{background:#dc2626;color:#fff;}
        .btn-share{background:#1d4ed8;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
        .empty{text-align:center;padding:60px;color:#6b7280;}
        .toast{position:fixed;bottom:24px;right:24px;background:#111827;color:#fff;padding:12px 20px;font-size:13px;font-weight:600;z-index:999;display:none;}
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
            <a href="{{ route('partner.products.create') }}">➕ Tambah Produk</a>
            <a href="{{ route('partner.outfits.index') }}" class="active">✦ Outfit Saya</a>
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
            <h1>Outfit Saya ({{ $outfits->count() }})</h1>
            <a href="{{ route('partner.outfits.create') }}" class="btn btn-dark">+ Buat Outfit</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <p style="font-size:13px;color:#6b7280;margin-bottom:16px;">Buat kombinasi outfit dari produk kamu + produk mitra lain. Outfit akan tampil di halaman Lookbook publik.</p>

        <div class="card">
            @if($outfits->isEmpty())
                <div class="empty">Belum ada outfit. <a href="{{ route('partner.outfits.create') }}" style="color:#dc2626;font-weight:700;">Buat sekarang</a></div>
            @else
            <table>
                <thead><tr><th>Foto</th><th>Judul</th><th>Gaya</th><th>Item</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($outfits as $outfit)
                    <tr>
                        <td>
                            <div class="outfit-photos">
                                @foreach($outfit->products->take(3) as $p)
                                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
                                @endforeach
                            </div>
                        </td>
                        <td><strong>{{ $outfit->title }}</strong></td>
                        <td>{{ $outfit->style_type ? ucfirst($outfit->style_type) : '—' }}</td>
                        <td>{{ $outfit->products->count() }} item</td>
                        <td>{{ $outfit->created_at->format('d M Y') }}</td>
                        <td style="display:flex;gap:6px;">
                            <button class="btn btn-share" onclick="copyShare('{{ route('outfit.share', $outfit->share_token) }}')">🔗 Share</button>
                            <form method="POST" action="{{ route('partner.outfits.destroy', $outfit) }}" onsubmit="return confirm('Hapus outfit ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            @endif
        </div>
    </main>

    <div class="toast" id="toast"></div>
    <script>
        function copyShare(url) {
            navigator.clipboard.writeText(url).then(() => {
                const t = document.getElementById('toast');
                t.textContent = 'Link outfit disalin! 🔗'; t.style.display = 'block';
                setTimeout(() => t.style.display = 'none', 2000);
            });
        }
    </script>
</body>
</html>
