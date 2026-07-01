<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tentang {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#ffffff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;color:var(--text);flex-shrink:0;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        .hero{background:var(--dark);padding:56px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:center;}
        .hero-eyebrow{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#6b7280;margin-bottom:12px;}
        .hero-title{font-size:clamp(36px,5vw,64px);font-weight:900;line-height:.92;color:#fff;margin-bottom:16px;}
        .hero-title span{color:var(--accent);}
        .hero-desc{font-size:16px;color:#9ca3af;line-height:1.65;}
        .hero-stats{display:flex;gap:24px;margin-top:28px;}
        .hero-stat-num{font-size:32px;font-weight:900;color:#fff;}
        .hero-stat-label{font-size:12px;color:#6b7280;margin-top:4px;}
        .hero-image img{width:100%;aspect-ratio:4/5;object-fit:cover;display:block;}
        .section{width:min(1280px,94%);margin:0 auto;padding:60px 0;}
        .section-eyebrow{font-size:11px;font-weight:900;letter-spacing:2.5px;text-transform:uppercase;color:var(--muted);margin-bottom:8px;}
        .section-title{font-size:clamp(28px,3.5vw,40px);font-weight:900;line-height:.95;margin-bottom:20px;}
        .section-title span{color:var(--accent);}
        .story-text{font-size:15px;color:var(--muted);line-height:1.7;margin-bottom:14px;}
        .values-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-top:24px;}
        .value-item{background:var(--card);border:1px solid var(--border);padding:20px;}
        .value-icon{font-size:28px;margin-bottom:10px;}
        .value-item h4{font-size:14px;font-weight:700;margin-bottom:6px;}
        .value-item p{font-size:13px;color:var(--muted);line-height:1.5;}
        .timeline{display:flex;flex-direction:column;gap:0;border-left:2px solid var(--border);padding-left:24px;margin-top:24px;}
        .timeline-item{padding-bottom:24px;position:relative;}
        .timeline-item::before{content:'';position:absolute;left:-30px;top:4px;width:10px;height:10px;background:var(--accent);border-radius:50%;}
        .timeline-year{font-size:12px;font-weight:900;color:var(--accent);}
        .timeline-title{font-size:15px;font-weight:700;margin:4px 0;}
        .timeline-desc{font-size:13px;color:var(--muted);line-height:1.5;}
        .lookbook-preview{position:relative;overflow:hidden;aspect-ratio:4/5;background:#111;display:block;}
        .lookbook-preview img{width:100%;height:100%;object-fit:cover;}
        .lookbook-overlay{position:absolute;inset:0;background:linear-gradient(transparent 50%,rgba(0,0,0,0.8));display:flex;flex-direction:column;justify-content:flex-end;padding:20px;}
        .lookbook-title{color:#fff;font-size:18px;font-weight:900;}
        .lookbook-link{color:var(--accent);font-size:13px;font-weight:700;margin-top:6px;}
        .contact-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(240px,1fr));gap:12px;margin-top:20px;}
        .contact-card{background:var(--card);border:1px solid var(--border);padding:18px;display:flex;align-items:center;gap:12px;}
        .contact-card:hover{border-color:#9ca3af;}
        .contact-label{font-size:10px;letter-spacing:1px;text-transform:uppercase;color:var(--muted);}
        .contact-value{font-size:14px;font-weight:700;}
        @media(max-width:960px){.hero-inner{grid-template-columns:1fr;} div[style*="grid-template-columns:1fr 1fr"]{grid-template-columns:1fr !important;}}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'tentang', 'storeName' => $storeName])

    <section class="hero">
        <div class="hero-inner">
            <div>
                <p class="hero-eyebrow">Slow Fashion · {{ $storeLocation }} · Sejak {{ $storeSince }}</p>
                <h1 class="hero-title">Cerita di Balik<br><span>{{ $storeName }}</span></h1>
                <p class="hero-desc">{{ $brandStory['tagline'] ?? $storeTagline }}</p>
                <div class="hero-stats">
                    <div><div class="hero-stat-num">{{ $totalPartners }}+</div><div class="hero-stat-label">Toko Mitra</div></div>
                    <div><div class="hero-stat-num">{{ $totalProducts }}+</div><div class="hero-stat-label">Produk Aktif</div></div>
                </div>
            </div>
            <div class="hero-image">
                <img src="{{ $heroImage }}" alt="{{ $storeName }}">
            </div>
        </div>
    </section>

    <div class="section">
        <p class="section-eyebrow">Misi Kami</p>
        <h2 class="section-title">Fashion dengan <span>Makna</span></h2>
        <p class="story-text">{{ $brandStory['mission'] ?? '' }}</p>
        <p class="story-text">{{ $brandStory['slow_fashion'] ?? '' }}</p>

        @if(!empty($brandStory['values']))
        <div class="values-grid">
            @foreach($brandStory['values'] as $v)
            <div class="value-item">
                <div class="value-icon">{{ $v['icon'] }}</div>
                <h4>{{ $v['title'] }}</h4>
                <p>{{ $v['desc'] }}</p>
            </div>
            @endforeach
        </div>
        @endif

        <div style="display:grid;grid-template-columns:1fr 1fr;gap:40px;margin-top:40px;align-items:start;">
            <div>
                @if(!empty($brandStory['timeline']))
                <p class="section-eyebrow">Perjalanan</p>
                <div class="timeline">
                    @foreach($brandStory['timeline'] as $t)
                    <div class="timeline-item">
                        <p class="timeline-year">{{ $t['year'] }}</p>
                        <p class="timeline-title">{{ $t['title'] }}</p>
                        <p class="timeline-desc">{{ $t['desc'] }}</p>
                    </div>
                    @endforeach
                </div>
                @endif
            </div>
            <div>
                @if($featuredOutfit)
                <a href="{{ route('catalog.lookbook') }}" class="lookbook-preview">
                    <img src="{{ Str::startsWith($featuredOutfit->cover_image, 'http') ? $featuredOutfit->cover_image : asset('storage/'.$featuredOutfit->cover_image) }}" alt="{{ $featuredOutfit->title }}">
                    <div class="lookbook-overlay">
                        <span class="lookbook-title">{{ $featuredOutfit->title }}</span>
                        <span class="lookbook-link">Lihat Lookbook Interaktif →</span>
                    </div>
                </a>
                @else
                <a href="{{ route('catalog.lookbook') }}" class="lookbook-preview">
                    <img src="{{ $heroImage }}" alt="Lookbook">
                    <div class="lookbook-overlay">
                        <span class="lookbook-title">Cinematic Lookbook</span>
                        <span class="lookbook-link">Sentuh hotspot, temukan produk →</span>
                    </div>
                </a>
                @endif
            </div>
        </div>
    </div>

    <div class="section" style="padding-top:0;">
        <p class="section-eyebrow">Hubungi Kami</p>
        <h2 class="section-title">Kontak</h2>
        <div class="contact-grid">
            <a class="contact-card" href="{{ route('community.index') }}">
                <span style="font-size:24px;">👥</span>
                <div><p class="contact-label">Komunitas</p><p class="contact-value">Lihat & kirim foto →</p></div>
            </a>
            <a class="contact-card" href="{{ route('partner.register') }}">
                <span style="font-size:24px;">🏪</span>
                <div><p class="contact-label">Mitra</p><p class="contact-value">Daftar jadi mitra →</p></div>
            </a>
        </div>
    </div>

</body>
</html>
