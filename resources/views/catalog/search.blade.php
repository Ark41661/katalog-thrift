<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Cari: {{ $query ?? 'Semua Produk' }} - {{ $storeName }}</title>
    <meta name="description" content="Cari produk thrift fashion di {{ $storeName }}. Temukan hoodie, jaket, kaos, celana dan banyak lagi.">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f7f7f5;color:#111827;}
        :root{--border:#e5e7eb;--muted:#6b7280;--accent:#dc2626;--dark:#111827;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;flex-shrink:0;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);text-decoration:none;}
        .hero{background:var(--dark);padding:32px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;}
        .hero h1{font-size:clamp(28px,4vw,48px);font-weight:900;color:#fff;line-height:.92;}
        .hero p{color:#9ca3af;font-size:15px;margin-top:8px;}
        .search-box{margin-top:20px;display:flex;gap:0;max-width:560px;}
        .search-box input{flex:1;padding:14px 16px;border:0;font-size:15px;font-family:inherit;background:#fff;}
        .search-box button{padding:14px 24px;background:var(--accent);color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;}
        .main{width:min(1280px,94%);margin:28px auto 60px;}
        .result-count{font-size:14px;color:var(--muted);margin-bottom:20px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
        .card{background:#fff;border:1px solid var(--border);overflow:hidden;display:block;text-decoration:none;color:inherit;}
        .card img{width:100%;aspect-ratio:3/4;object-fit:cover;display:block;}
        .card-body{padding:12px;}
        .card-brand{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:var(--muted);margin-bottom:3px;}
        .card-name{font-size:14px;font-weight:700;margin-bottom:6px;}
        .card-price{font-size:16px;font-weight:900;color:var(--accent);}
        .card-store{font-size:12px;color:#1d4ed8;margin-top:4px;}
        .empty{text-align:center;padding:60px 20px;color:var(--muted);}
        .empty h2{font-size:24px;color:#111827;margin-bottom:8px;}
        .empty a{color:var(--accent);font-weight:700;}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'katalog', 'storeName' => $storeName])

    <section class="hero">
        <div class="hero-inner">
            <h1>CARI PRODUK</h1>
            <p>Temukan produk thrift favoritmu dari berbagai toko mitra.</p>
            <form class="search-box" action="{{ route('search.index') }}" method="GET">
                <input type="text" name="q" value="{{ $query }}" placeholder="Cari hoodie, jaket, brand Nike..." autofocus>
                <button type="submit">Cari</button>
            </form>
        </div>
    </section>

    <div class="main">
        @if($query)
            <p class="result-count">{{ $products->count() }} hasil untuk "<strong>{{ $query }}</strong>"</p>
        @endif

        @if($products->isEmpty())
            <div class="empty">
                @if($query)
                    <h2>Tidak ada hasil</h2>
                    <p>Coba gunakan kata kunci lain atau <a href="{{ route('catalog.index') }}">lihat semua produk</a>.</p>
                @else
                    <h2>Cari produk</h2>
                    <p>Ketik kata kunci di atas untuk mulai mencari.</p>
                @endif
            </div>
        @else
            <div class="grid">
                @foreach($products as $product)
                <a href="{{ route('catalog.show', $product->slug) }}" class="card">
                    <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                    <div class="card-body">
                        <p class="card-brand">{{ $product->brand }}</p>
                        <p class="card-name">{{ $product->name }}</p>
                        <p class="card-price">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        @if($product->partner)<p class="card-store">🏪 {{ $product->partner->store_name }}</p>@endif
                    </div>
                </a>
                @endforeach
            </div>
        @endif
    </div>

    <script>
        // Live search
        const searchInput = document.querySelector('.search-box input');
        if (searchInput) {
            let timeout;
            searchInput.addEventListener('input', function() {
                clearTimeout(timeout);
                timeout = setTimeout(() => {
                    if (this.value.length >= 2) {
                        fetch('{{ route("search.ajax") }}?q=' + encodeURIComponent(this.value))
                            .then(r => r.json())
                            .then(data => {
                                // Minimal live preview
                            });
                    }
                }, 400);
            });
        }
    </script>
</body>
</html>
