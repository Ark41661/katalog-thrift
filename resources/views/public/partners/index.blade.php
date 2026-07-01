<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Mitra - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f7f7f5;color:#111827;}
        :root{--border:#e5e7eb;--muted:#6b7280;--accent:#dc2626;--dark:#111827;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;flex-shrink:0;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        .hero{background:#111827;padding:48px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;}
        .hero h1{font-size:clamp(36px,5vw,64px);font-weight:900;color:#fff;line-height:.92;margin-bottom:12px;}
        .hero p{color:#9ca3af;font-size:16px;max-width:480px;}
        .hero-cta{display:inline-block;margin-top:20px;padding:13px 28px;background:#dc2626;color:#fff;font-size:14px;font-weight:700;text-decoration:none;}
        .hero-cta:hover{background:#b91c1c;}
        .main{width:min(1280px,94%);margin:32px auto 60px;}
        .section-title{font-size:13px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:20px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(260px,1fr));gap:20px;}
        .partner-card{background:#fff;border:1px solid #e5e7eb;overflow:hidden;text-decoration:none;color:#111827;display:block;transition:box-shadow .2s;}
        .partner-card:hover{box-shadow:0 4px 20px rgba(0,0,0,0.08);}
        .partner-card-header{background:#111827;padding:24px;display:flex;align-items:center;gap:14px;}
        .partner-logo{width:56px;height:56px;border-radius:50%;object-fit:cover;border:2px solid #374151;}
        .partner-name{font-size:16px;font-weight:900;color:#fff;}
        .partner-location{font-size:12px;color:#9ca3af;margin-top:2px;}
        .partner-verified{display:inline-block;background:#1d4ed8;color:#fff;font-size:10px;font-weight:700;padding:2px 6px;border-radius:3px;margin-top:4px;}
        .partner-body{padding:16px;}
        .partner-desc{font-size:13px;color:#6b7280;line-height:1.5;margin-bottom:12px;display:-webkit-box;-webkit-line-clamp:2;-webkit-box-orient:vertical;overflow:hidden;}
        .partner-stats{display:flex;gap:16px;}
        .partner-stat{text-align:center;}
        .partner-stat-num{font-size:20px;font-weight:900;color:#111827;}
        .partner-stat-label{font-size:11px;color:#9ca3af;margin-top:2px;}
        .stars{color:#f59e0b;font-size:13px;}
        .empty{text-align:center;padding:80px 20px;color:#6b7280;}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'mitra', 'storeName' => $storeName])

    <section class="hero">
        <div class="hero-inner">
            <h1>TOKO<br>MITRA</h1>
            <p>Temukan koleksi thrift dan fashion dari berbagai toko mitra terpercaya kami.</p>
            <a href="{{ route('partner.register') }}" class="hero-cta">+ Daftarkan Toko Kamu</a>
        </div>
    </section>

    <div class="main">
        <p class="section-title">{{ $partners->count() }} Toko Mitra Aktif</p>

        @if($partners->isEmpty())
            <div class="empty">
                <h2 style="font-size:24px;color:#111827;margin-bottom:8px;">Belum ada mitra</h2>
                <p>Jadilah yang pertama bergabung!</p>
                <a href="{{ route('partner.register') }}" style="color:#dc2626;font-weight:700;display:inline-block;margin-top:16px;">Daftar Jadi Mitra →</a>
            </div>
        @else
            <div class="grid">
                @foreach($partners as $p)
                <a href="{{ route('partners.show', $p->store_slug) }}" class="partner-card">
                    <div class="partner-card-header">
                        <img src="{{ $p->logo_url }}" alt="{{ $p->store_name }}" class="partner-logo">
                        <div>
                            <div class="partner-name">{{ $p->store_name }}</div>
                            @if($p->location)<div class="partner-location">📍 {{ $p->location }}</div>@endif
                            @if($p->is_verified)<span class="partner-verified">✓ Verified</span>@endif
                        </div>
                    </div>
                    <div class="partner-body">
                        @if($p->description)
                            <p class="partner-desc">{{ $p->description }}</p>
                        @endif
                        <div class="partner-stats">
                            <div class="partner-stat">
                                <div class="partner-stat-num">{{ $p->active_products_count }}</div>
                                <div class="partner-stat-label">Produk</div>
                            </div>
                            <div class="partner-stat">
                                <div class="partner-stat-num stars">{{ number_format($p->average_rating, 1) }}</div>
                                <div class="partner-stat-label">⭐ Rating</div>
                            </div>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
