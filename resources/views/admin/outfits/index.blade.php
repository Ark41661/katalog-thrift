<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfit Kurasi - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .card{background:#fff;border:1px solid #e5e7eb;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:12px 14px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        th{font-weight:700;background:#f9fafb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-active{background:#dcfce7;color:#166534;}
        .badge-inactive{background:#fee2e2;color:#991b1b;}
        .outfit-photos{display:flex;gap:4px;}
        .outfit-photos img{width:36px;height:36px;object-fit:cover;border:1px solid #e5e7eb;border-radius:2px;}
        .btn{display:inline-block;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .btn-danger{background:#dc2626;color:#fff;}
        .btn-toggle{background:#f59e0b;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .empty{text-align:center;padding:60px;color:#6b7280;}
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
        <div class="head">
            <h1>Outfit Kurasi ({{ $outfits->count() }})</h1>
            <a href="{{ route('admin.outfits.create') }}" class="btn btn-dark">+ Buat Outfit Baru</a>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            @if($outfits->isEmpty())
                <div class="empty">
                    Belum ada outfit kurasi. <a href="{{ route('admin.outfits.create') }}" style="color:#dc2626;font-weight:700;">Buat sekarang</a>
                </div>
            @else
            <table>
                <thead><tr><th>Foto Produk</th><th>Judul</th><th>Gaya</th><th>Produk</th><th>Status</th><th>Dibuat</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($outfits as $outfit)
                    <tr>
                        <td>
                            <div class="outfit-photos">
                                @foreach($outfit->products->take(4) as $p)
                                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
                                @endforeach
                            </div>
                        </td>
                        <td>
                            <strong>{{ $outfit->title }}</strong>
                            @if($outfit->description)
                                <br><small style="color:#6b7280;">{{ Str::limit($outfit->description, 50) }}</small>
                            @endif
                        </td>
                        <td>{{ $outfit->style_type ? ucfirst($outfit->style_type) : '—' }}</td>
                        <td>{{ $outfit->products->count() }} item</td>
                        <td>
                            <span class="badge {{ $outfit->is_active ? 'badge-active' : 'badge-inactive' }}">
                                {{ $outfit->is_active ? 'Aktif' : 'Nonaktif' }}
                            </span>
                        </td>
                        <td>{{ $outfit->created_at->format('d M Y') }}</td>
                        <td style="display:flex;gap:6px;flex-wrap:wrap;">
                            <a href="{{ route('admin.outfits.edit', $outfit) }}" class="btn btn-outline">Edit</a>
                            <form method="POST" action="{{ route('admin.outfits.toggle', $outfit) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-toggle">{{ $outfit->is_active ? 'Nonaktifkan' : 'Aktifkan' }}</button>
                            </form>
                            <form method="POST" action="{{ route('admin.outfits.destroy', $outfit) }}" onsubmit="return confirm('Hapus outfit ini?')">
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
</body>
</html>
