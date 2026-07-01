<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Dashboard - {{ $storeName }}</title>
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
        .sidebar-nav .badge-red{background:#dc2626;color:#fff;font-size:10px;padding:2px 6px;border-radius:999px;margin-left:auto;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .page-header{margin-bottom:24px;}
        .page-header h1{font-size:24px;font-weight:900;}
        .page-header p{color:#6b7280;font-size:14px;margin-top:4px;}
        .stats{display:grid;grid-template-columns:repeat(auto-fill,minmax(160px,1fr));gap:16px;margin-bottom:28px;}
        .stat-card{background:#fff;border:1px solid #e5e7eb;padding:20px;}
        .stat-num{font-size:36px;font-weight:900;line-height:1;}
        .stat-label{font-size:12px;color:#6b7280;margin-top:6px;}
        .stat-card.warn .stat-num{color:#dc2626;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:20px;margin-bottom:20px;}
        .section-title{font-size:12px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:10px 8px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        th{font-weight:700;background:#f9fafb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-approved{background:#dcfce7;color:#166534;}
        .badge-rejected{background:#fee2e2;color:#991b1b;}
        .badge-suspended{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:7px 12px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-approve{background:#16a34a;color:#fff;}
        .btn-reject{background:#dc2626;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:20px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        form.logout-form button:hover{color:#fff;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $storeName }}</div>
            <div class="role">Super Admin</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}" class="active">📊 Dashboard</a>
            <a href="{{ route('admin.partners.index') }}">🏪 Kelola Mitra
                @if($pendingPartners > 0)<span class="badge-red">{{ $pendingPartners }}</span>@endif
            </a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.outfits.index') }}">✦ Outfit Kurasi</a>
            <a href="{{ route('admin.articles.index') }}">📖 Editorial</a>
            <a href="{{ route('admin.ugc.index') }}">📸 Komunitas
                @if(isset($pendingUgc) && $pendingUgc > 0)<span class="badge-red">{{ $pendingUgc }}</span>@endif
            </a>
            <a href="{{ route('admin.reviews.index') }}">⭐ Review</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan Produk
                @if($pendingReports > 0)<span class="badge-red">{{ $pendingReports }}</span>@endif
            </a>
            <a href="{{ route('admin.web-reports.index') }}">🌐 Laporan Web</a>
            <a href="{{ route('admin.badges') }}">🏆 Badges</a>
            <a href="{{ route('admin.analytics') }}">📈 Analitik</a>
            <a href="{{ route('admin.notifications') }}">🔔 Notifikasi</a>
            <a href="{{ route('catalog.index') }}" target="_blank">🌐 Lihat Platform</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>Dashboard Admin</h1>
            <p>{{ now()->format('l, d F Y') }}</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card"><div class="stat-num">{{ $totalPartners }}</div><div class="stat-label">Mitra Aktif</div></div>
            <div class="stat-card warn"><div class="stat-num">{{ $pendingPartners }}</div><div class="stat-label">Mitra Pending</div></div>
            <div class="stat-card"><div class="stat-num">{{ $totalProducts }}</div><div class="stat-label">Total Produk</div></div>
            <div class="stat-card"><div class="stat-num">{{ $totalMembers }}</div><div class="stat-label">Member</div></div>
            <div class="stat-card"><div class="stat-num">{{ $totalReviews }}</div><div class="stat-label">Review</div></div>
            <div class="stat-card warn"><div class="stat-num">{{ $pendingReports }}</div><div class="stat-label">Laporan Pending</div></div>
            <div class="stat-card warn"><div class="stat-num">{{ $pendingUgc }}</div><div class="stat-label">UGC Pending</div></div>
        </div>

        @if($recentPartners->isNotEmpty())
        <div class="section">
            <div class="section-title">Pendaftaran Mitra Baru</div>
            <table>
                <thead><tr><th>Nama Toko</th><th>Pemilik</th><th>WA</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @foreach($recentPartners as $p)
                    <tr>
                        <td><strong>{{ $p->store_name }}</strong></td>
                        <td>{{ $p->user->name }}</td>
                        <td>{{ $p->whatsapp }}</td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td style="display:flex;gap:6px;">
                            <form method="POST" action="{{ route('admin.partners.approve', $p) }}">
                                @csrf @method('PUT')
                                <button type="submit" class="btn btn-approve">✓ Setujui</button>
                            </form>
                            <a href="{{ route('admin.partners.index') }}" class="btn btn-outline">Detail</a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
            <div style="margin-top:12px;">
                <a href="{{ route('admin.partners.index') }}" class="btn btn-outline">Lihat Semua Mitra →</a>
            </div>
        </div>
        @endif
    </main>
</body>
</html>
