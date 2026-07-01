<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav{padding:16px 0;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .page-header{margin-bottom:24px;}
        .page-header h1{font-size:24px;font-weight:900;}
        .page-header p{color:#6b7280;font-size:14px;}
        .stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:16px;margin-bottom:28px;}
        .stat-card{background:#fff;border:1px solid #e5e7eb;padding:20px;}
        .stat-num{font-size:36px;font-weight:900;line-height:1;}
        .stat-label{font-size:12px;color:#6b7280;margin-top:6px;}
        .stat-card.warn .stat-num{color:#dc2626;}
        .stat-card.gold .stat-num{color:#f59e0b;}
        .stat-card.green .stat-num{color:#059669;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:20px;margin-bottom:20px;}
        .section-title{font-size:12px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:10px 8px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        th{font-weight:700;background:#f9fafb;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        form.logout-form button:hover{color:#fff;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ config('catalog.store_name') }}</div>
            <div class="role">Super Admin</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.partners.index') }}">🏪 Kelola Mitra</a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.reviews.index') }}">⭐ Review</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
            <a href="{{ route('admin.badges') }}">🏆 Badges</a>
            <a href="{{ route('admin.analytics') }}" class="active">📈 Analitik</a>
            <a href="{{ route('admin.notifications') }}">🔔 Notifikasi</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="page-header">
            <h1>📈 Analitik Platform</h1>
            <p>Statistik menyeluruh platform {{ config('catalog.store_name') }}.</p>
        </div>
        <div class="stats">
            <div class="stat-card"><div class="stat-num">{{ number_format($totalViews) }}</div><div class="stat-label">Total Views Produk</div></div>
            <div class="stat-card green"><div class="stat-num">{{ number_format($totalWaClicks) }}</div><div class="stat-label">Total Klik WhatsApp</div></div>
            <div class="stat-card gold"><div class="stat-num">{{ $totalWishlist }}</div><div class="stat-label">Total Wishlist</div></div>
            <div class="stat-card"><div class="stat-num">{{ $totalActiveProducts }}</div><div class="stat-label">Produk Aktif</div></div>
            <div class="stat-card warn"><div class="stat-num">{{ $totalSoldProducts }}</div><div class="stat-label">Terjual</div></div>
        </div>
        <div class="section">
            <div class="section-title">Mitra Teratas (by Produk Aktif)</div>
            @if($topPartners->isEmpty())
                <p style="color:#6b7280;">Belum ada data.</p>
            @else
                <table>
                    <thead><tr><th>#</th><th>Toko</th><th>Produk Aktif</th><th>Rating</th><th>Total Views</th><th>Followers</th></tr></thead>
                    <tbody>
                        @foreach($topPartners as $i=>$p)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td><strong>{{ $p->store_name }}</strong></td>
                            <td>{{ $p->products_count }}</td>
                            <td>{{ number_format($p->average_rating, 1) }} ⭐</td>
                            <td>{{ number_format($p->total_views) }}</td>
                            <td>{{ $p->follower_count }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
        <div class="section">
            <div class="section-title">Distribusi Kategori Produk</div>
            @forelse($categoryStats as $cat)
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;font-size:13px;">
                    <span style="width:120px;">{{ $cat['label'] }}</span>
                    <div style="flex:1;height:8px;background:#f1f5f9;border-radius:4px;">
                        <div style="height:100%;width:{{ $cat['pct'] }}%;background:#111827;border-radius:4px;"></div>
                    </div>
                    <span style="width:60px;text-align:right;color:#6b7280;">{{ $cat['count'] }}</span>
                </div>
            @empty
                <p style="color:#6b7280;">Belum ada produk.</p>
            @endforelse
        </div>
    </main>
</body>
</html>
