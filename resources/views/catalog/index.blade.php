<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $storeName }} - {{ $catalogTitle }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#ffffff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        /* TOPBAR */
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.7);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;color:var(--text);}
        .topbar-nav{display:flex;gap:20px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:10px;align-items:center;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-login:hover{color:var(--text);border-color:#9ca3af;}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        /* HERO */
        .hero{background:var(--dark);padding:0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;display:grid;grid-template-columns:0.9fr 1.1fr;min-height:420px;}
        .hero-copy{padding:48px 0;display:flex;flex-direction:column;justify-content:center;}
        .hero-season{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#6b7280;margin-bottom:10px;}
        .hero-title{font-size:clamp(40px,6vw,80px);font-weight:900;line-height:.9;color:#fff;letter-spacing:-1px;margin-bottom:16px;}
        .hero-sub{font-size:15px;color:#9ca3af;line-height:1.6;max-width:380px;margin-bottom:20px;}
        .hero-chips{display:flex;flex-wrap:wrap;gap:8px;}
        .chip{display:inline-flex;align-items:center;gap:7px;padding:7px 14px;border:1px solid #374151;border-radius:999px;font-size:13px;font-weight:600;color:#9ca3af;}
        .chip:hover{border-color:#6b7280;color:#fff;}
        .chip img{width:18px;height:18px;}
        .chip-cta{background:var(--accent);border-color:var(--accent);color:#fff;}
        .chip-cta:hover{background:var(--accent-dark);border-color:var(--accent-dark);}
        .hero-search{margin-top:20px;display:flex;gap:0;max-width:520px;border:1px solid #374151;border-radius:999px;overflow:hidden;background:#fff;}
        .hero-search input{flex:1;padding:12px 18px;border:0;font-size:14px;font-family:inherit;background:transparent;color:#111827;}
        .hero-search input::placeholder{color:#6b7280;}
        .hero-search button{padding:0 20px;background:var(--accent);color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;}
        .hero-search button:hover{background:var(--accent-dark);}
        .hero-image{overflow:hidden;}
        .hero-image img{width:100%;height:100%;object-fit:cover;object-position:center;}
        /* NEW ARRIVALS */
        .section-wrap{width:min(1280px,94%);margin:0 auto;}
        .new-arrivals{padding:28px 0 0;}
        .section-label{font-size:11px;font-weight:900;letter-spacing:2.5px;text-transform:uppercase;color:var(--muted);margin-bottom:14px;}
        .na-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(200px,1fr));gap:12px;}
        .na-card{background:var(--card);border:1px solid var(--border);overflow:hidden;position:relative;display:block;color:var(--text);}
        .na-card img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .na-card-body{padding:10px 12px;}
        .na-card-body strong{display:block;font-size:13px;margin-bottom:2px;}
        .na-card-body span{font-size:12px;color:var(--muted);}
        .badge-new-abs{position:absolute;top:8px;left:8px;background:var(--accent);color:#fff;font-size:9px;font-weight:900;letter-spacing:1.5px;padding:3px 7px;}
        /* CAT TABS */
        .cat-tabs{display:flex;gap:0;overflow-x:auto;scrollbar-width:none;border-bottom:2px solid var(--border);margin-top:24px;}
        .cat-tabs::-webkit-scrollbar{display:none;}
        .cat-tab{display:inline-flex;align-items:center;gap:5px;padding:10px 16px;font-size:13px;font-weight:700;color:var(--muted);white-space:nowrap;border-bottom:2px solid transparent;margin-bottom:-2px;}
        .cat-tab:hover{color:var(--text);}
        .cat-tab.active{color:var(--accent);border-bottom-color:var(--accent);}
        .cat-count{font-size:11px;background:#f3f4f6;color:var(--muted);padding:1px 6px;border-radius:999px;font-weight:600;}
        .cat-tab.active .cat-count{background:#fee2e2;color:var(--accent);}
        /* FILTER */
        .filter-bar{background:var(--card);border:1px solid var(--border);padding:14px 18px;margin-top:0;}
        .filter-bar form{display:flex;flex-wrap:wrap;gap:10px;align-items:flex-end;}
        .filter-group{display:flex;flex-direction:column;gap:4px;}
        .filter-group label{font-size:10px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:var(--muted);}
        .filter-group select,.filter-group input[type=number]{padding:8px 10px;border:1px solid var(--border);font-size:13px;font-family:inherit;background:#fff;min-width:110px;}
        .filter-group input[type=number]{min-width:80px;}
        .filter-actions{display:flex;gap:8px;align-items:flex-end;margin-left:auto;}
        .btn-filter{border:0;padding:9px 16px;font-size:13px;font-weight:700;cursor:pointer;}
        .btn-filter-apply{background:var(--dark);color:#fff;}
        .btn-filter-reset{background:#fff;color:var(--text);border:1px solid var(--border);display:inline-block;}
        .filter-active{font-size:12px;color:var(--accent);font-weight:700;align-self:center;}
        /* BRAND CHIPS */
        .brand-picker{background:var(--card);border:1px solid var(--border);padding:14px 18px;margin-top:0;}
        .brand-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:var(--muted);margin-bottom:10px;}
        .brand-chips{display:flex;gap:8px;flex-wrap:wrap;}
        .brand-chip{border:1px solid var(--border);background:#fff;color:var(--dark);padding:7px 14px;cursor:pointer;font-weight:700;font-size:12px;display:inline-flex;align-items:center;gap:7px;transition:all .15s;}
        .brand-chip.active{background:var(--dark);color:#fff;border-color:var(--dark);}
        .brand-chip:hover:not(.active){background:#f3f4f6;border-color:#9ca3af;}
        .brand-chip-icon{display:flex;align-items:center;justify-content:center;width:28px;height:18px;flex-shrink:0;}
        .brand-chip-icon img{max-width:28px;max-height:18px;width:auto;height:auto;object-fit:contain;}
        .brand-chip.active .brand-chip-icon img{filter:brightness(0) invert(1);}
        /* PRODUCT GRID */
        .catalog-heading{font-size:32px;font-weight:900;line-height:.95;color:var(--accent);margin:20px 0 12px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(230px,1fr));gap:16px;margin-bottom:40px;}
        .card{background:var(--card);border:1px solid var(--border);overflow:hidden;display:flex;flex-direction:column;position:relative;}
        .card-img-wrap{position:relative;overflow:hidden;}
        .card-img-wrap img{width:100%;aspect-ratio:3/4;object-fit:cover;display:block;transition:transform .4s;}
        .card:hover .card-img-wrap img{transform:scale(1.04);}
        .sold-overlay{position:absolute;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;}
        .sold-stamp{background:var(--dark);color:#fff;font-size:18px;font-weight:900;letter-spacing:4px;padding:8px 18px;border:2px solid #fff;transform:rotate(-8deg);}
        .badge-new-card{position:absolute;top:10px;right:10px;background:var(--accent);color:#fff;font-size:9px;font-weight:900;letter-spacing:1.5px;padding:3px 7px;}
        .badge-sizechart{position:absolute;top:10px;left:10px;background:var(--dark);color:#fff;font-size:9px;font-weight:700;letter-spacing:1px;padding:3px 7px;}
        .card-body{padding:12px;display:flex;flex-direction:column;gap:6px;flex:1;}
        .card-badges{display:flex;gap:5px;flex-wrap:wrap;}
        .badge{display:inline-block;font-size:11px;background:#f3f4f6;color:var(--dark);padding:3px 7px;border-radius:2px;}
        .badge-partner{background:#eff6ff;color:#1d4ed8;font-size:11px;}
        .badge-verified{background:#dbeafe;color:#1d4ed8;}
        .card-name{font-size:14px;font-weight:700;line-height:1.3;}
        .card-price{font-size:18px;font-weight:900;}
        .card-price.sold{text-decoration:line-through;color:var(--muted);font-size:14px;}
        .card-meta{font-size:12px;color:var(--muted);}
        .card-store{font-size:11px;color:#1d4ed8;font-weight:600;display:flex;align-items:center;gap:4px;}
        .channel-links{display:flex;flex-wrap:wrap;gap:5px;margin-top:auto;}
        .channel-link{display:inline-flex;align-items:center;justify-content:center;width:32px;height:32px;border:1px solid var(--border);background:#fff;}
        .channel-link img{width:16px;height:16px;}
        .actions{display:flex;gap:6px;margin-top:6px;}
        .btn{border:1px solid transparent;text-align:center;padding:9px 10px;font-size:13px;font-weight:700;flex:1;}
        .btn-primary{background:var(--accent);color:#fff;}
        .btn-primary:hover{background:var(--accent-dark);}
        .btn-secondary{border-color:var(--border);color:var(--dark);background:#fff;}
        .sold-label{text-align:center;font-size:12px;font-weight:700;color:var(--muted);padding:8px;background:#f9fafb;border-top:1px solid var(--border);}
        .empty-state{text-align:center;padding:60px 20px;color:var(--muted);}
        .empty-state h3{font-size:22px;color:var(--text);margin-bottom:8px;}
        @media(max-width:960px){.hero-inner{grid-template-columns:1fr;}.hero-image{min-height:280px;}}
        @media(max-width:640px){.filter-bar form{flex-direction:column;align-items:stretch;}.filter-actions{margin-left:0;}}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'katalog', 'storeName' => $storeName])

    {{-- HERO --}}
    <section class="hero">
        <div class="hero-inner">
            <div class="hero-copy">
                <p class="hero-season">{{ $catalogSeason }}</p>
                <h1 class="hero-title">{{ $catalogTitle }}</h1>
                <p class="hero-sub">{{ $storeTagline }}</p>
                <div class="hero-chips">
                    <a class="chip chip-cta" href="{{ route('partner.register') }}">🏪 Jadi Mitra</a>
                </div>
                <form class="hero-search" action="{{ route('search.index') }}" method="GET">
                    <input type="text" name="q" value="{{ request('q') }}" placeholder="Cari hoodie, jaket, brand Nike..." autocomplete="off">
                    <button type="submit">Cari</button>
                </form>
            </div>
            <div class="hero-image">
                <img src="{{ $coverImage }}" alt="{{ $storeName }}">
            </div>
        </div>
    </section>

    <div class="section-wrap">
        {{-- NEW ARRIVALS --}}
        @if($newArrivals->isNotEmpty())
        <section class="new-arrivals">
            <p class="section-label">New Arrivals</p>
            <div class="na-grid">
                @foreach($newArrivals as $item)
                <a href="{{ route('catalog.show', $item->slug) }}" class="na-card">
                    <span class="badge-new-abs">NEW</span>
                    <img src="{{ $item->image_url }}" alt="{{ $item->name }}">
                    <div class="na-card-body">
                        <strong>{{ $item->name }}</strong>
                        <span>Rp {{ number_format($item->price, 0, ',', '.') }} · {{ $item->partner?->store_name }}</span>
                    </div>
                </a>
                @endforeach
            </div>
        </section>
        @endif

        {{-- CATEGORY TABS --}}
        <nav class="cat-tabs">
            <a href="{{ route('catalog.index', array_merge($filters, ['category' => ''])) }}"
               class="cat-tab {{ empty($filters['category'] ?? '') ? 'active' : '' }}">
                Semua <span class="cat-count">{{ array_sum($categoryCounts->toArray()) }}</span>
            </a>
            @foreach($productTypes as $typeKey => $type)
                @if(($categoryCounts[$typeKey] ?? 0) > 0)
                <a href="{{ route('catalog.index', array_merge($filters, ['category' => $typeKey])) }}"
                   class="cat-tab {{ ($filters['category'] ?? '') === $typeKey ? 'active' : '' }}">
                    {{ $type['emoji'] }} {{ $type['label'] }}
                    <span class="cat-count">{{ $categoryCounts[$typeKey] }}</span>
                </a>
                @endif
            @endforeach
        </nav>

        {{-- FILTER BAR --}}
        <div class="filter-bar">
            <form method="GET" action="{{ route('catalog.index') }}">
                @if(!empty($filters['category']))
                    <input type="hidden" name="category" value="{{ $filters['category'] }}">
                @endif
                <div class="filter-group">
                    <label>Toko</label>
                    <select name="partner">
                        <option value="">Semua Toko</option>
                        @foreach($allPartners as $p)
                            <option value="{{ $p->id }}" {{ ($filters['partner'] ?? '') == $p->id ? 'selected' : '' }}>{{ $p->store_name }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Ukuran</label>
                    <select name="size">
                        <option value="">Semua</option>
                        @foreach($allSizes as $s)
                            <option value="{{ $s }}" {{ ($filters['size'] ?? '') === $s ? 'selected' : '' }}>{{ $s }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="filter-group">
                    <label>Harga Min</label>
                    <input type="number" name="min_price" value="{{ $filters['min_price'] ?? '' }}" placeholder="0" min="0" step="10000">
                </div>
                <div class="filter-group">
                    <label>Harga Max</label>
                    <input type="number" name="max_price" value="{{ $filters['max_price'] ?? '' }}" placeholder="500000" min="0" step="10000">
                </div>
                <div class="filter-group">
                    <label>Status</label>
                    <select name="availability">
                        <option value="">Semua</option>
                        <option value="available" {{ ($filters['availability'] ?? '') === 'available' ? 'selected' : '' }}>Tersedia</option>
                        <option value="sold" {{ ($filters['availability'] ?? '') === 'sold' ? 'selected' : '' }}>Sold</option>
                    </select>
                </div>
                <div class="filter-actions">
                    @if(array_filter($filters))
                        <span class="filter-active">Filter aktif</span>
                        <a href="{{ route('catalog.index') }}" class="btn-filter btn-filter-reset">Reset</a>
                    @endif
                    <button type="submit" class="btn-filter btn-filter-apply">Terapkan</button>
                </div>
            </form>
        </div>

        {{-- BRAND CHIPS --}}
        @php
            $brands = $products->pluck('brand')->unique()->sort()->values();
            $brandLogos = config('brands', []);
            $activeCategory = $filters['category'] ?? '';
            $activeCatLabel = $activeCategory && isset($productTypes[$activeCategory])
                ? $productTypes[$activeCategory]['emoji'] . ' ' . $productTypes[$activeCategory]['label']
                : 'SEMUA PRODUK';
        @endphp
        <div class="brand-picker">
            <p class="brand-title">Filter Brand</p>
            <div class="brand-chips">
                <button type="button" class="brand-chip active" data-brand="all">Semua</button>
                @foreach($brands as $brand)
                    <button type="button" class="brand-chip" data-brand="{{ $brand }}">
                        @if(!empty($brandLogos[$brand]) && file_exists(public_path($brandLogos[$brand])))
                            <span class="brand-chip-icon"><img src="{{ $brandLogos[$brand] }}" alt="{{ $brand }}"></span>
                        @endif
                        {{ $brand }}
                    </button>
                @endforeach
            </div>
        </div>

        {{-- PRODUCT GRID --}}
        <h2 class="catalog-heading">{{ $activeCatLabel }}</h2>

        @if($products->isEmpty())
            <div class="empty-state">
                <h3>Tidak ada produk</h3>
                <p>Coba ubah filter atau <a href="{{ route('catalog.index') }}" style="color:var(--accent)">reset pencarian</a>.</p>
            </div>
        @else
        <section class="grid">
            @foreach($products as $product)
                @php
                    $msg = "Halo, saya tertarik dengan {$product->name} (Size {$product->size}). Apakah masih tersedia?";
                    $waNum = $product->partner?->whatsapp ?: $whatsappNumber;
                    $waLink = "https://wa.me/{$waNum}?text=" . urlencode($msg);
                    $shopLink = $product->shopee_url ?: $socialLinks['shopee'];
                    $wishlistIds = auth()->check() ? auth()->user()->wishlists()->pluck('product_id')->toArray() : [];
                    $inWishlist = in_array($product->id, $wishlistIds);
                @endphp
                <article class="card" data-brand="{{ $product->brand }}">
                    <div class="card-img-wrap">
                        <img src="{{ $product->image_url }}" alt="{{ $product->name }}" loading="lazy">
                        @if($product->is_sold)
                            <div class="sold-overlay"><span class="sold-stamp">SOLD</span></div>
                        @elseif($product->is_new_arrival)
                            <span class="badge-new-card">NEW</span>
                        @endif
                        @if(!empty($product->size_chart))
                            <span class="badge-sizechart">📏 Size Chart</span>
                        @endif
                    </div>
                    <div class="card-body">
                        <div class="card-badges">
                            <span class="badge">{{ $product->brand }}</span>
                            @if($product->partner)
                                <a href="{{ route('partners.show', $product->partner->store_slug) }}" class="badge badge-partner">
                                    {{ $product->partner->is_verified ? '✓ ' : '' }}{{ $product->partner->store_name }}
                                </a>
                            @endif
                        </div>
                        <p class="card-name">{{ $product->name }}</p>
                        <p class="card-price {{ $product->is_sold ? 'sold' : '' }}">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                        <p class="card-meta">Size {{ $product->size }} · Kondisi {{ $product->condition }}</p>
                        @if(!$product->is_sold)
                        <div class="channel-links">
                            <a class="channel-link" href="{{ $waLink }}" target="_blank" rel="noopener noreferrer" title="WhatsApp"><img src="/icons/whatsapp.jpg" alt="WA"></a>
                            <a class="channel-link" href="{{ $shopLink }}" target="_blank" rel="noopener noreferrer" title="Shopee"><img src="/icons/shopee.webp" alt="Shopee"></a>
                            @auth
                            <form method="POST" action="{{ route('wishlist.toggle', $product->slug) }}" style="display:inline;">
                                @csrf
                                <button type="submit" class="channel-link" title="Wishlist" style="cursor:pointer;font-size:14px;border:1px solid var(--border);background:#fff;width:32px;height:32px;">{{ $inWishlist ? '♥' : '♡' }}</button>
                            </form>
                            @endauth
                        </div>
                        <div class="actions">
                            <a class="btn btn-secondary" href="{{ route('catalog.show', $product->slug) }}">Detail</a>
                            <a class="btn btn-primary" href="{{ $waLink }}" target="_blank" rel="noopener noreferrer">Chat</a>
                        </div>
                        @else
                        <div class="actions">
                            <a class="btn btn-secondary" href="{{ route('catalog.show', $product->slug) }}">Lihat Detail</a>
                        </div>
                        <div class="sold-label">Sudah terjual</div>
                        @endif
                    </div>
                </article>
            @endforeach
        </section>
        @endif


    </div>

    <script>
        (() => {
            const chips = document.querySelectorAll('.brand-chip');
            const cards = document.querySelectorAll('.card[data-brand]');
            chips.forEach(chip => {
                chip.addEventListener('click', () => {
                    chips.forEach(c => c.classList.remove('active'));
                    chip.classList.add('active');
                    const sel = chip.dataset.brand;
                    cards.forEach(card => {
                        card.style.display = (sel === 'all' || card.dataset.brand === sel) ? 'flex' : 'none';
                    });
                });
            });
        })();
    </script>
</body>
</html>
