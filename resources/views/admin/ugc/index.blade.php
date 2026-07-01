<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola UGC - Admin</title>
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
        .head{margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .head p{font-size:13px;color:#6b7280;margin-top:4px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
        .ugc-card{background:#fff;border:1px solid #e5e7eb;overflow:hidden;}
        .ugc-card img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .ugc-card-body{padding:12px;}
        .ugc-user{font-size:13px;font-weight:700;margin-bottom:2px;}
        .ugc-instagram{font-size:11px;color:#1d4ed8;margin-bottom:4px;}
        .ugc-caption{font-size:12px;color:#6b7280;margin-bottom:8px;line-height:1.4;}
        .ugc-product{font-size:11px;color:#dc2626;font-weight:600;margin-bottom:8px;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;margin-bottom:8px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-approved{background:#dcfce7;color:#166534;}
        .badge-rejected{background:#fee2e2;color:#991b1b;}
        .badge-featured{background:#dbeafe;color:#1d4ed8;}
        .actions{display:flex;gap:4px;flex-wrap:wrap;}
        .btn{display:inline-block;padding:6px 10px;font-size:11px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-approve{background:#16a34a;color:#fff;}
        .btn-reject{background:#dc2626;color:#fff;}
        .btn-feature{background:#1d4ed8;color:#fff;}
        .btn-danger{background:#fee2e2;color:#dc2626;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid #e5e7eb;}
        .tab{padding:10px 16px;font-size:13px;font-weight:700;color:#6b7280;text-decoration:none;border-bottom:2px solid transparent;margin-bottom:-2px;}
        .tab.active{color:#dc2626;border-bottom-color:#dc2626;}
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
            <a href="{{ route('admin.articles.index') }}">📖 Editorial</a>
            <a href="{{ route('admin.ugc.index') }}" class="active">📸 Komunitas UGC</a>
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
            <h1>Komunitas UGC ({{ $photos->total() }})</h1>
            <p>Foto dari pelanggan. Setujui untuk tampil di halaman Komunitas, tandai Featured untuk tampil di Landing Page.</p>
        </div>
        @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif
        <div class="grid">
            @forelse($photos as $photo)
            <div class="ugc-card">
                <img src="{{ $photo->photo_url }}" alt="{{ $photo->submitter_name }}" loading="lazy">
                <div class="ugc-card-body">
                    <p class="ugc-user">{{ $photo->submitter_name }}</p>
                    @if($photo->submitter_instagram)<p class="ugc-instagram">{{ '@' . ltrim($photo->submitter_instagram, '@') }}</p>@endif
                    @if($photo->caption)<p class="ugc-caption">{{ Str::limit($photo->caption, 60) }}</p>@endif
                    @if($photo->product)<p class="ugc-product">🏷 {{ $photo->product->name }}</p>@endif
                    <div style="display:flex;gap:6px;flex-wrap:wrap;margin-bottom:8px;">
                        <span class="badge badge-{{ $photo->status }}">{{ ucfirst($photo->status) }}</span>
                        @if($photo->is_featured)<span class="badge badge-featured">⭐ Featured</span>@endif
                    </div>
                    <div class="actions">
                        @if($photo->status === 'pending')
                            <form method="POST" action="{{ route('admin.ugc.approve', $photo) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-approve">✓ Setujui</button>
                            </form>
                            <form method="POST" action="{{ route('admin.ugc.reject', $photo) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-reject">✗ Tolak</button>
                            </form>
                        @endif
                        @if($photo->status === 'approved')
                            <form method="POST" action="{{ route('admin.ugc.featured', $photo) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-feature">{{ $photo->is_featured ? '✗ Unfeature' : '⭐ Feature' }}</button>
                            </form>
                        @endif
                        <form method="POST" action="{{ route('admin.ugc.destroy', $photo) }}" onsubmit="return confirm('Hapus foto ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn btn-danger">Hapus</button>
                        </form>
                    </div>
                </div>
            </div>
            @empty
            <div style="grid-column:1/-1;text-align:center;padding:60px;color:#6b7280;">Belum ada foto yang dikirim.</div>
            @endforelse
        </div>
        <div style="margin-top:20px;">{{ $photos->links() }}</div>
    </main>
</body>
</html>
