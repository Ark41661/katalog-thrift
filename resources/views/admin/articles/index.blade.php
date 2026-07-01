<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Artikel - Admin</title>
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
        .badge-published{background:#dcfce7;color:#166534;}
        .badge-draft{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .btn-danger{background:#dc2626;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="name">{{ $storeName }}</div><div class="role">Super Admin</div></div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.partners.index') }}">🏪 Kelola Mitra</a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.outfits.index') }}">✦ Outfit Kurasi</a>
            <a href="{{ route('admin.articles.index') }}" class="active">📖 Editorial</a>
            <a href="{{ route('admin.ugc.index') }}">📸 Komunitas UGC</a>
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
            <h1>Editorial / Artikel ({{ $articles->total() }})</h1>
            <a href="{{ route('admin.articles.create') }}" class="btn btn-dark">+ Tulis Artikel</a>
        </div>
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        <div class="card">
            <table>
                <thead><tr><th>Judul</th><th>Kategori</th><th>Penulis</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($articles as $a)
                    <tr>
                        <td><strong>{{ $a->title }}</strong></td>
                        <td>{{ ['mix-match'=>'Mix & Match','tips-perawatan'=>'Tips Perawatan','tren'=>'Tren Fashion','panduan'=>'Panduan'][$a->category] ?? $a->category }}</td>
                        <td>{{ $a->author }}</td>
                        <td><span class="badge {{ $a->is_published ? 'badge-published' : 'badge-draft' }}">{{ $a->is_published ? 'Terbit' : 'Draft' }}</span></td>
                        <td>{{ $a->published_at?->format('d M Y') ?? '—' }}</td>
                        <td style="display:flex;gap:6px;">
                            <a href="{{ route('admin.articles.edit', $a) }}" class="btn btn-outline">Edit</a>
                            @if($a->is_published)<a href="{{ route('articles.show', $a->slug) }}" target="_blank" class="btn btn-outline">Lihat</a>@endif
                            <form method="POST" action="{{ route('admin.articles.destroy', $a) }}" onsubmit="return confirm('Hapus artikel ini?')">
                                @csrf @method('DELETE')
                                <button type="submit" class="btn btn-danger">Hapus</button>
                            </form>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#6b7280;">Belum ada artikel. <a href="{{ route('admin.articles.create') }}" style="color:#dc2626;font-weight:700;">Tulis sekarang</a></td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $articles->links() }}</div>
    </main>
</body>
</html>
