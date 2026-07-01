<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Editorial - {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--border:#e5e7eb;--dark:#111827;}
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
        .hero{background:var(--dark);padding:48px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;}
        .hero h1{font-size:clamp(36px,5vw,64px);font-weight:900;color:#fff;line-height:.92;margin-bottom:12px;}
        .hero h1 span{color:var(--accent);}
        .hero p{color:#9ca3af;font-size:15px;max-width:480px;}
        .main{width:min(1280px,94%);margin:32px auto 60px;}
        .cat-tabs{display:flex;gap:0;overflow-x:auto;scrollbar-width:none;border-bottom:2px solid var(--border);margin-bottom:28px;}
        .cat-tabs::-webkit-scrollbar{display:none;}
        .cat-tab{padding:10px 18px;font-size:13px;font-weight:700;color:var(--muted);white-space:nowrap;border-bottom:2px solid transparent;margin-bottom:-2px;text-decoration:none;}
        .cat-tab:hover{color:var(--text);}
        .cat-tab.active{color:var(--accent);border-bottom-color:var(--accent);}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:24px;}
        .card{background:var(--card);border:1px solid var(--border);overflow:hidden;display:block;color:var(--text);}
        .card:hover{box-shadow:0 4px 20px rgba(0,0,0,0.08);}
        .card img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block;}
        .card-body{padding:18px;}
        .card-cat{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--accent);font-weight:700;margin-bottom:8px;}
        .card-title{font-size:17px;font-weight:700;line-height:1.3;margin-bottom:8px;}
        .card-excerpt{font-size:13px;color:var(--muted);line-height:1.6;margin-bottom:12px;}
        .card-meta{font-size:12px;color:var(--muted);display:flex;gap:12px;}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'editorial', 'storeName' => $storeName])

    <section class="hero">
        <div class="hero-inner">
            <h1>Fashion <span>Editorial</span></h1>
            <p>Tips mix & match, panduan perawatan, dan tren fashion terkini dari tim ThriftHub.</p>
        </div>
    </section>

    <div class="main">
        <nav class="cat-tabs">
            <a href="{{ route('articles.index') }}" class="cat-tab {{ !$activeCategory ? 'active' : '' }}">Semua</a>
            @foreach($categories as $key => $label)
                <a href="{{ route('articles.index', ['category' => $key]) }}" class="cat-tab {{ $activeCategory === $key ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </nav>

        @if($articles->isEmpty())
            <div style="text-align:center;padding:80px 20px;color:var(--muted);">
                <p style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Belum ada artikel</p>
                <p>Konten editorial segera hadir.</p>
            </div>
        @else
            <div class="grid">
                @foreach($articles as $a)
                <a href="{{ route('articles.show', $a->slug) }}" class="card">
                    @if($a->cover_image_url)
                        <img src="{{ $a->cover_image_url }}" alt="{{ $a->title }}" loading="lazy">
                    @else
                        <div style="width:100%;aspect-ratio:16/9;background:#111827;display:flex;align-items:center;justify-content:center;font-size:32px;">📖</div>
                    @endif
                    <div class="card-body">
                        <p class="card-cat">{{ $categories[$a->category] ?? $a->category }}</p>
                        <p class="card-title">{{ $a->title }}</p>
                        @if($a->excerpt)<p class="card-excerpt">{{ Str::limit($a->excerpt, 100) }}</p>@endif
                        <div class="card-meta">
                            <span>{{ $a->author }}</span>
                            <span>{{ $a->read_time }} menit baca</span>
                            <span>{{ $a->published_at?->format('d M Y') }}</span>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
            <div style="margin-top:24px;">{{ $articles->links() }}</div>
        @endif
    </div>

</body>
</html>
