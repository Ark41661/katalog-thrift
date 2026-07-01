<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $storeName }} — {{ $heroTitle }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;flex-shrink:0;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        /* HERO */
        .hero{background:var(--dark);min-height:90vh;display:grid;grid-template-columns:1fr 1fr;overflow:hidden;}
        .hero-copy{padding:80px 60px;display:flex;flex-direction:column;justify-content:center;}
        .hero-eyebrow{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#6b7280;margin-bottom:14px;}
        .hero-title{font-size:clamp(40px,6vw,88px);font-weight:900;line-height:.88;color:#fff;letter-spacing:-2px;margin-bottom:20px;}
        .hero-title span{color:var(--accent);}
        .hero-sub{font-size:16px;color:#9ca3af;line-height:1.65;max-width:420px;margin-bottom:32px;}
        .hero-actions{display:flex;gap:12px;flex-wrap:wrap;}
        .btn-hero-primary{padding:14px 28px;background:var(--accent);color:#fff;font-size:15px;font-weight:700;}
        .btn-hero-primary:hover{background:var(--accent-dark);}
        .btn-hero-secondary{padding:14px 28px;background:transparent;color:#9ca3af;font-size:15px;font-weight:700;border:1px solid #374151;}
        .btn-hero-secondary:hover{border-color:#6b7280;color:#fff;}
        .hero-stats{display:flex;gap:32px;margin-top:40px;padding-top:32px;border-top:1px solid #1f2937;}
        .hero-stat-num{font-size:32px;font-weight:900;color:#fff;}
        .hero-stat-label{font-size:12px;color:#6b7280;margin-top:3px;}
        .hero-image{position:relative;overflow:hidden;}
        .hero-image img{width:100%;height:100%;object-fit:cover;object-position:center;}
        .hero-image-overlay{position:absolute;inset:0;background:linear-gradient(to right,var(--dark) 0%,transparent 30%);}
        /* SECTION */
        .section{width:min(1280px,94%);margin:0 auto;padding:60px 0;}
        .section-eyebrow{font-size:11px;font-weight:900;letter-spacing:2.5px;text-transform:uppercase;color:var(--muted);margin-bottom:8px;}
        .section-title{font-size:clamp(28px,3.5vw,44px);font-weight:900;line-height:.95;color:var(--text);margin-bottom:24px;}
        .section-title span{color:var(--accent);}
        /* FEATURES */
        .features{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:20px;margin-bottom:40px;}
        .feature{background:var(--card);border:1px solid var(--border);padding:24px;}
        .feature-icon{font-size:32px;margin-bottom:12px;}
        .feature h3{font-size:16px;font-weight:700;margin-bottom:8px;}
        .feature p{font-size:14px;color:var(--muted);line-height:1.6;}
        /* PRODUCT GRID */
        .product-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:16px;}
        .product-card{background:var(--card);border:1px solid var(--border);overflow:hidden;display:block;color:var(--text);}
        .product-card:hover{box-shadow:0 4px 16px rgba(0,0,0,0.08);}
        .product-card img{width:100%;aspect-ratio:3/4;object-fit:cover;display:block;}
        .product-card-body{padding:12px;}
        .product-card-brand{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--muted);margin-bottom:3px;}
        .product-card-name{font-size:13px;font-weight:700;margin-bottom:4px;}
        .product-card-price{font-size:15px;font-weight:900;color:var(--accent);}
        .product-card-store{font-size:11px;color:#1d4ed8;margin-top:3px;}
        /* ARTICLE GRID */
        .article-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(280px,1fr));gap:20px;}
        .article-card{background:var(--card);border:1px solid var(--border);overflow:hidden;display:block;color:var(--text);}
        .article-card:hover{box-shadow:0 4px 16px rgba(0,0,0,0.08);}
        .article-card img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block;}
        .article-card-body{padding:16px;}
        .article-cat{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--accent);font-weight:700;margin-bottom:6px;}
        .article-title{font-size:15px;font-weight:700;line-height:1.3;margin-bottom:6px;}
        .article-excerpt{font-size:13px;color:var(--muted);line-height:1.5;}
        /* UGC GRID */
        .ugc-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(180px,1fr));gap:8px;}
        .ugc-item{position:relative;overflow:hidden;aspect-ratio:1;}
        .ugc-item img{width:100%;height:100%;object-fit:cover;display:block;transition:transform .3s;}
        .ugc-item:hover img{transform:scale(1.05);}
        .ugc-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.4);opacity:0;transition:opacity .2s;display:flex;align-items:flex-end;padding:12px;}
        .ugc-item:hover .ugc-overlay{opacity:1;}
        .ugc-name{color:#fff;font-size:12px;font-weight:700;}
        @media(max-width:960px){.hero{grid-template-columns:1fr;min-height:auto;}.hero-image{height:300px;}.hero-copy{padding:48px 24px;}}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'home', 'storeName' => $storeName])

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-copy">
            <p class="hero-eyebrow">{{ config('catalog.catalog_season') }}</p>
            <h1 class="hero-title">{!! nl2br(e($heroTitle)) !!}</h1>
            <p class="hero-sub">{{ $heroSubtitle }}</p>
            <div class="hero-actions">
                <a href="{{ route('catalog.index') }}" class="btn-hero-primary">{{ $heroCtaText }} →</a>
                <a href="{{ route('catalog.about') }}" class="btn-hero-secondary">Cerita Brand →</a>
            </div>
            <div class="hero-stats">
                <div>
                    <div class="hero-stat-num">{{ $totalPartners }}+</div>
                    <div class="hero-stat-label">Toko Mitra</div>
                </div>
                <div>
                    <div class="hero-stat-num">{{ $totalProducts }}+</div>
                    <div class="hero-stat-label">Produk Aktif</div>
                </div>
            </div>
        </div>
        <div class="hero-image">
            <img src="{{ $heroImage }}" alt="{{ $storeName }}">
            <div class="hero-image-overlay"></div>
        </div>
    </section>

    {{-- FITUR PLATFORM --}}
    <div class="section">
        <p class="section-eyebrow">Kenapa ThriftHub</p>
        <h2 class="section-title">Platform <span>Thrift & Fashion</span><br>Terlengkap</h2>
        <div class="features">
            <div class="feature"><div class="feature-icon">🏪</div><h3>Multi-Mitra</h3><p>Produk dari berbagai toko thrift terpercaya dalam satu platform.</p></div>
            <div class="feature"><div class="feature-icon">✦</div><h3>Lookbook Interaktif</h3><p>Foto sinematik dengan hotspot — sentuh pakaian, langsung lihat harga & detail.</p></div>
            <div class="feature"><div class="feature-icon">📖</div><h3>Editorial Fashion</h3><p>Artikel mix & match, tips perawatan, dan panduan gaya hidup.</p></div>
            <div class="feature"><div class="feature-icon">👥</div><h3>Komunitas</h3><p>Foto pelanggan nyata yang memakai produk dari platform kami.</p></div>
            <div class="feature"><div class="feature-icon">⭐</div><h3>Review Transparan</h3><p>Rating dan ulasan jujur dari pembeli yang sudah bertransaksi.</p></div>
            <div class="feature"><div class="feature-icon">🔗</div><h3>Langsung ke Toko</h3><p>Klik produk → langsung ke WhatsApp atau Shopee mitra.</p></div>
        </div>
    </div>

    {{-- NEW ARRIVALS --}}
    @if($featuredProducts->isNotEmpty())
    <div style="background:var(--card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:60px 0;">
        <div class="section" style="padding:0;">
            <p class="section-eyebrow">Baru Masuk</p>
            <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;">
                <h2 class="section-title" style="margin-bottom:0;">New <span>Arrivals</span></h2>
                <a href="{{ route('catalog.index', ['new_arrival' => 1]) }}" style="font-size:13px;font-weight:700;color:var(--accent);">Lihat Semua →</a>
            </div>
            <div class="product-grid">
                @foreach($featuredProducts as $p)
                <a href="{{ route('catalog.show', $p->slug) }}" class="product-card">
                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
                    <div class="product-card-body">
                        <p class="product-card-brand">{{ $p->brand }}</p>
                        <p class="product-card-name">{{ $p->name }}</p>
                        <p class="product-card-price">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                        @if($p->partner)<p class="product-card-store">🏪 {{ $p->partner->store_name }}</p>@endif
                    </div>
                </a>
                @endforeach
            </div>
        </div>
    </div>
    @endif

    {{-- EDITORIAL --}}
    @if($latestArticles->isNotEmpty())
    <div class="section">
        <p class="section-eyebrow">Editorial</p>
        <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;">
            <h2 class="section-title" style="margin-bottom:0;">Fashion <span>Stories</span></h2>
            <a href="{{ route('articles.index') }}" style="font-size:13px;font-weight:700;color:var(--accent);">Semua Artikel →</a>
        </div>
        <div class="article-grid">
            @foreach($latestArticles as $a)
            <a href="{{ route('articles.show', $a->slug) }}" class="article-card">
                @if($a->cover_image_url)<img src="{{ $a->cover_image_url }}" alt="{{ $a->title }}" loading="lazy">@endif
                <div class="article-card-body">
                    <p class="article-cat">{{ ['mix-match'=>'Mix & Match','tips-perawatan'=>'Tips Perawatan','tren'=>'Tren Fashion','panduan'=>'Panduan'][$a->category] ?? $a->category }}</p>
                    <p class="article-title">{{ $a->title }}</p>
                    @if($a->excerpt)<p class="article-excerpt">{{ Str::limit($a->excerpt, 80) }}</p>@endif
                </div>
            </a>
            @endforeach
        </div>
    </div>
    @endif

    {{-- UGC KOMUNITAS --}}
    @if($featuredUgc->isNotEmpty())
    <div style="background:var(--card);border-top:1px solid var(--border);border-bottom:1px solid var(--border);padding:60px 0;">
        <div class="section" style="padding:0;">
            <p class="section-eyebrow">Komunitas</p>
            <div style="display:flex;justify-content:space-between;align-items:flex-end;margin-bottom:20px;">
                <h2 class="section-title" style="margin-bottom:0;">Mereka <span>Pakai</span> Ini</h2>
                <a href="{{ route('community.index') }}" style="font-size:13px;font-weight:700;color:var(--accent);">Lihat Semua →</a>
            </div>
            <div class="ugc-grid">
                @foreach($featuredUgc as $ugc)
                <div class="ugc-item">
                    <img src="{{ $ugc->photo_url }}" alt="{{ $ugc->submitter_name }}" loading="lazy">
                    <div class="ugc-overlay">
                        <span class="ugc-name">{{ $ugc->submitter_instagram ? '@' . ltrim($ugc->submitter_instagram, '@') : $ugc->submitter_name }}</span>
                    </div>
                </div>
                @endforeach
            </div>
        </div>
    </div>
    @endif


</body>
</html>
