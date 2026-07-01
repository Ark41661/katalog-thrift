<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Analitik - {{ $partner->store_name }}</title>
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
        .stat-card.gold .stat-num{color:#f59e0b;}
        .stat-card.green .stat-num{color:#059669;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:20px;margin-bottom:20px;}
        .section-title{font-size:13px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;}
        .tier-badge{display:inline-block;padding:4px 12px;border-radius:999px;font-size:12px;font-weight:700;}
        .tier-platinum{background:#e0e7ff;color:#3730a3;}
        .tier-gold{background:#fef3c7;color:#92400e;}
        .tier-silver{background:#f1f5f9;color:#475569;}
        .tier-bronze{background:#fed7aa;color:#9a3412;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:10px 8px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        th{font-weight:700;background:#f9fafb;}
        .stars{color:#f59e0b;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:20px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
        form.logout-form button:hover{color:#fff;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $partner->store_name }}</div>
            <div class="role">Portal Mitra · {{ $tierBadge }} {{ $tierName }}</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('partner.dashboard') }}"><span class="nav-icon">📊</span> Dashboard</a>
            <a href="{{ route('partner.products.index') }}"><span class="nav-icon">📦</span> Produk Saya</a>
            <a href="{{ route('partner.analytics') }}" class="active"><span class="nav-icon">📈</span> Analitik</a>
            <a href="{{ route('partner.questions.index') }}"><span class="nav-icon">❓</span> Pertanyaan</a>
            <a href="{{ route('partner.profile') }}"><span class="nav-icon">🏪</span> Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="page-header">
            <h1>📈 Analitik Toko</h1>
            <p>Data kinerja toko dan produk kamu.</p>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="stats">
            <div class="stat-card">
                <div class="stat-num">{{ number_format($totalViews) }}</div>
                <div class="stat-label">Total Dilihat</div>
            </div>
            <div class="stat-card green">
                <div class="stat-num">{{ number_format($totalWaClicks) }}</div>
                <div class="stat-label">Klik WhatsApp</div>
            </div>
            <div class="stat-card accent">
                <div class="stat-num">{{ $wishlistCount }}</div>
                <div class="stat-label">Wishlist</div>
            </div>
            <div class="stat-card gold">
                <div class="stat-num">{{ $followerCount }}</div>
                <div class="stat-label">Pengikut</div>
            </div>
            <div class="stat-card">
                <div class="stat-num">{{ number_format($avgRating, 1) }} ⭐</div>
                <div class="stat-label">Rating Toko</div>
            </div>
        </div>

        <div class="section">
            <div class="section-title">🏆 Tier Toko</div>
            <p style="font-size:18px;font-weight:900;">
                <span class="tier-badge tier-{{ strtolower($tierName) }}">{{ $tierBadge }} {{ $tierName }}</span>
            </p>
            <p style="font-size:13px;color:#6b7280;margin-top:8px;">
                Tier dihitung dari jumlah produk aktif, rating, pengikut, dan total views.
            </p>
        </div>

        <div class="section">
            <div class="section-title">Produp Terpopuler</div>
            @if($topProducts->isEmpty())
                <p style="color:#6b7280;">Belum ada data.</p>
            @else
                <table>
                    <thead><tr><th>Nama</th><th>Dilihat</th><th>Klik WA</th><th>Harga</th></tr></thead>
                    <tbody>
                        @foreach($topProducts as $p)
                        <tr>
                            <td><strong>{{ $p->name }}</strong></td>
                            <td>{{ $p->total_views }}</td>
                            <td>{{ $p->total_wa_clicks }}</td>
                            <td>Rp {{ number_format($p->price, 0, ',', '.') }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>

        <div class="section">
            <div class="section-title">Distribusi Rating</div>
            @php $total = array_sum($reviewStats->toArray()); @endphp
            @for($i=5; $i>=1; $i--)
                @php
                    $count = $reviewStats[$i] ?? 0;
                    $pct = $total > 0 ? ($count / $total) * 100 : 0;
                @endphp
                <div style="display:flex;align-items:center;gap:10px;margin-bottom:6px;font-size:13px;">
                    <span style="width:30px;">{{ $i }}★</span>
                    <div style="flex:1;height:8px;background:#f1f5f9;border-radius:4px;">
                        <div style="height:100%;width:{{ $pct }}%;background:#f59e0b;border-radius:4px;"></div>
                    </div>
                    <span style="width:40px;text-align:right;color:#6b7280;">{{ $count }}</span>
                </div>
            @endfor
        </div>

        <div class="section">
            <div class="section-title">Tren 30 Hari Terakhir</div>
            @if($dailyData->isEmpty())
                <p style="color:#6b7280;">Belum ada data harian.</p>
            @else
                <table>
                    <thead><tr><th>Tanggal</th><th>Dilihat</th><th>Klik WA</th></tr></thead>
                    <tbody>
                        @foreach($dailyData as $d)
                        <tr>
                            <td>{{ \Carbon\Carbon::parse($d->date)->format('d M Y') }}</td>
                            <td>{{ number_format($d->views) }}</td>
                            <td>{{ number_format($d->wa_clicks) }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            @endif
        </div>
    </main>
</body>
</html>
