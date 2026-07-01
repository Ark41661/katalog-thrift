<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->title }} - {{ $storeName }}</title>
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
        /* ARTICLE */
        .article-wrap{width:min(760px,94%);margin:40px auto 60px;}
        .article-cat{font-size:11px;letter-spacing:2px;text-transform:uppercase;color:var(--accent);font-weight:700;margin-bottom:12px;}
        .article-title{font-size:clamp(28px,4vw,48px);font-weight:900;line-height:.95;margin-bottom:16px;}
        .article-meta{display:flex;gap:16px;font-size:13px;color:var(--muted);margin-bottom:24px;padding-bottom:20px;border-bottom:1px solid var(--border);}
        .article-cover{width:100%;height:auto;display:block;margin-bottom:32px;border:1px solid var(--border);}
        /* Plain text content */
        .article-content{font-size:16px;line-height:1.8;color:#1f2937;white-space:pre-line;word-break:break-word;}
        .article-content p{margin-bottom:16px;}
        /* Related products */
        .related-products{margin-top:40px;padding-top:32px;border-top:1px solid var(--border);}
        .related-products h3{font-size:18px;font-weight:900;margin-bottom:16px;}
        .product-row{display:flex;gap:12px;overflow-x:auto;scrollbar-width:none;padding-bottom:4px;}
        .product-row::-webkit-scrollbar{display:none;}
        .product-mini{flex-shrink:0;width:140px;background:var(--card);border:1px solid var(--border);overflow:hidden;display:block;color:var(--text);}
        .product-mini img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .product-mini-body{padding:8px;}
        .product-mini-name{font-size:12px;font-weight:700;margin-bottom:3px;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .product-mini-price{font-size:12px;color:var(--accent);font-weight:700;}
        /* Related articles */
        .related-articles{margin-top:40px;padding-top:32px;border-top:1px solid var(--border);}
        .related-articles h3{font-size:18px;font-weight:900;margin-bottom:16px;}
        .related-grid{display:grid;grid-template-columns:repeat(3,1fr);gap:16px;}
        .related-card{background:var(--card);border:1px solid var(--border);overflow:hidden;display:block;color:var(--text);}
        .related-card img{width:100%;aspect-ratio:16/9;object-fit:cover;display:block;}
        .related-card-body{padding:12px;}
        .related-card-title{font-size:13px;font-weight:700;line-height:1.3;}
        /* Share */
        .share-bar{background:var(--card);border:1px solid var(--border);padding:16px 20px;display:flex;align-items:center;gap:12px;margin-top:32px;flex-wrap:wrap;}
        .share-bar p{font-size:13px;font-weight:700;}
        .share-btn{padding:8px 16px;font-size:12px;font-weight:700;border:0;cursor:pointer;text-decoration:none;display:inline-block;}
        .share-wa{background:#25d366;color:#fff;}
        .share-copy{background:#f3f4f6;color:var(--text);border:1px solid var(--border);}
        .toast{position:fixed;bottom:24px;right:24px;background:var(--dark);color:#fff;padding:12px 20px;font-size:13px;font-weight:600;z-index:999;display:none;}
        @media(max-width:640px){.related-grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'editorial', 'storeName' => $storeName])

    <div class="article-wrap">
        <p class="article-cat">
            <a href="{{ route('articles.index', ['category' => $article->category]) }}" style="color:var(--accent);">
                {{ ['mix-match'=>'Mix & Match','tips-perawatan'=>'Tips Perawatan','tren'=>'Tren Fashion','panduan'=>'Panduan'][$article->category] ?? $article->category }}
            </a>
        </p>
        <h1 class="article-title">{{ $article->title }}</h1>
        <div class="article-meta">
            <span>{{ $article->author }}</span>
            <span>{{ $article->read_time }} menit baca</span>
            <span>{{ $article->published_at?->format('d F Y') }}</span>
        </div>

        @if($article->cover_image_url)
            <img src="{{ $article->cover_image_url }}" alt="{{ $article->title }}" class="article-cover">
        @endif

        <div class="article-content">{{ $article->content }}</div>

        {{-- Produk yang disebut di artikel --}}
        @php $relatedProds = $article->relatedProducts(); @endphp
        @if($relatedProds->isNotEmpty())
        <div class="related-products">
            <h3>Produk yang Dibahas</h3>
            <div class="product-row">
                @foreach($relatedProds as $p)
                <a href="{{ route('catalog.show', $p->slug) }}" class="product-mini">
                    <img src="{{ $p->image_url }}" alt="{{ $p->name }}" loading="lazy">
                    <div class="product-mini-body">
                        <p class="product-mini-name">{{ $p->name }}</p>
                        <p class="product-mini-price">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif

        {{-- Share --}}
        <div class="share-bar">
            <p>Bagikan artikel ini:</p>
            <a href="https://wa.me/?text={{ urlencode($article->title . ' — ' . request()->url()) }}" target="_blank" class="share-btn share-wa">💬 WhatsApp</a>
            <button class="share-btn share-copy" onclick="copyUrl()">🔗 Salin Link</button>
        </div>

        {{-- Artikel terkait --}}
        @if($related->isNotEmpty())
        <div class="related-articles">
            <h3>Artikel Terkait</h3>
            <div class="related-grid">
                @foreach($related as $r)
                <a href="{{ route('articles.show', $r->slug) }}" class="related-card">
                    @if($r->cover_image_url)<img src="{{ $r->cover_image_url }}" alt="{{ $r->title }}" loading="lazy">@endif
                    <div class="related-card-body">
                        <p class="related-card-title">{{ $r->title }}</p>
                    </div>
                </a>
                @endforeach
            </div>
        </div>
        @endif
    </div>

    <div class="toast" id="toast"></div>
    <script>
        function copyUrl() {
            navigator.clipboard.writeText(window.location.href).then(() => {
                const t = document.getElementById('toast');
                t.textContent = 'Link disalin!'; t.style.display = 'block';
                setTimeout(() => t.style.display = 'none', 2000);
            });
        }
    </script>
</body>
</html>
