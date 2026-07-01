<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Dashboard - {{ $partner->store_name }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#111827;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1f2937;}
        .sidebar-brand .name{font-size:14px;font-weight:900;letter-spacing:1px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#6b7280;margin-top:2px;}
        .sidebar-nav{padding:16px 0;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;text-decoration:none;transition:background .15s,color .15s;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1f2937;color:#fff;}
        .sidebar-nav .nav-icon{font-size:16px;width:20px;text-align:center;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1f2937;}
        .main{margin-left:220px;padding:28px 32px;}
        .page-header{margin-bottom:24px;}
        .page-header h1{font-size:24px;font-weight:900;}
        .page-header p{color:#6b7280;font-size:14px;margin-top:4px;}
        .stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:28px;}
        .stat-card{background:#fff;border:1px solid #e5e7eb;padding:20px;}
        .stat-num{font-size:36px;font-weight:900;line-height:1;}
        .stat-label{font-size:12px;color:#6b7280;margin-top:6px;letter-spacing:.5px;}
        .stat-card.accent .stat-num{color:#dc2626;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:20px;margin-bottom:20px;}
        .section-title{font-size:13px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:10px 8px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        th{font-weight:700;color:#374151;background:#f9fafb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-active{background:#dcfce7;color:#166534;}
        .badge-sold{background:#fee2e2;color:#991b1b;}
        .badge-inactive{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:20px;border:1px solid #bbf7d0;}
        .stars{color:#f59e0b;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;padding:0;}
        form.logout-form button:hover{color:#fff;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $partner->store_name }}</div>
            <div class="role">Portal Mitra · {{ $partner->tier_badge ?? '🏪' }} {{ $partner->tier_name ?? 'Bronze' }}</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('partner.dashboard') }}" class="active"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('partner.products.index') }}"><span class="nav-icon">📦</span> Produk Saya</a>
            <a href="{{ route('partner.products.create') }}"><span class="nav-icon">➕</span> Tambah Produk</a>
            <a href="{{ route('partner.analytics') }}"><span class="nav-icon">📈</span> Analitik</a>
            <a href="{{ route('partner.questions.index') }}"><span class="nav-icon">❓</span> Pertanyaan</a>
            <a href="{{ route('partner.notifications') }}"><span class="nav-icon">🔔</span> Notifikasi</a>
            <a href="{{ route('partner.profile') }}"><span class="nav-icon">🏪</span> Profil Toko</a>
            <a href="{{ route('partners.show', $partner->store_slug) }}" target="_blank"><span class="nav-icon">👁</span> Lihat Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf
                <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Selamat datang, {{ auth('partner')->user()->name }}!</h1>
            <p>{{ now()->format('l, d F Y') }}</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="stat-num">{{ $totalProducts }}</div>
                <div class="stat-label">Total Produk</div>
            </div>
            <div class="stat-card accent">
                <div class="stat-num">{{ $activeProducts }}</div>
                <div class="stat-label">Produk Aktif</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $soldProducts }}</div>
                <div class="stat-label">Terjual</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ $reviewCount }}</div>
                <div class="stat-label">Total Review</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ number_format($avgRating, 1) }}</div>
                <div class="stat-label">⭐ Rating Toko</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">Produk Terbaru</div>
            @if($recentProducts->isEmpty())
                <p style="color:#6b7280;font-size:14px;">Belum ada produk. <a href="{{ route('partner.products.create') }}" style="color:#dc2626;font-weight:700;">Tambah produk pertama</a></p>
            @else
                <table>
                    <thead><tr><th>Nama</th><th>Harga</th><th>Size</th><th>Status</th><th>Aksi</th></tr></thead>
                    <tbody>
                        @foreach($recentProducts as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                            <td>{{ $p->size }}</td>
                            <td>
                                @if($p->is_sold) <span class="badge badge-sold">SOLD</span>
                                @elseif($p->is_active) <span class="badge badge-active">Aktif</span>
                                @else <span class="badge badge-inactive">Nonaktif</span>
                                @endif
                            </td>
                            <td><a href="{{ route('partner.products.edit', $p) }}" class="btn btn-outline">Edit</a></td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
                <div style="margin-top:12px;">
                    <a href="{{ route('partner.products.index') }}" class="btn btn-dark">Lihat Semua Produk</a>
                </div>
            @endif
        </div>
    </main>
</body>
</html>
