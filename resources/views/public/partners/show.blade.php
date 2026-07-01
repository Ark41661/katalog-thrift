<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $partner->store_name }} - {{ $storeName }}</title>
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
        .store-hero{background:#111827;padding:40px 0;position:relative;}
        .store-hero-inner{width:min(1280px,94%);margin:0 auto;display:flex;align-items:center;gap:24px;}
        .store-logo{width:80px;height:80px;border-radius:50%;object-fit:cover;border:3px solid #374151;flex-shrink:0;}
        .store-name{font-size:clamp(24px,4vw,40px);font-weight:900;color:#fff;line-height:1;}
        .store-verified{display:inline-block;background:#1d4ed8;color:#fff;font-size:11px;font-weight:700;padding:3px 8px;border-radius:3px;margin-left:8px;vertical-align:middle;}
        .store-meta{color:#9ca3af;font-size:14px;margin-top:6px;}
        .store-stats{display:flex;gap:24px;margin-top:16px;}
        .store-stat-num{font-size:24px;font-weight:900;color:#fff;}
        .store-stat-label{font-size:11px;color:#6b7280;margin-top:2px;}
        .store-links{display:flex;gap:8px;margin-top:12px;flex-wrap:wrap;}
        .store-link{display:inline-flex;align-items:center;gap:6px;padding:6px 12px;background:#1f2937;color:#9ca3af;font-size:12px;font-weight:600;text-decoration:none;border-radius:3px;}
        .store-link:hover{background:#374151;color:#fff;}
        .main{width:min(1280px,94%);margin:28px auto 60px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;margin-bottom:40px;}
        .card{background:#fff;border:1px solid #e5e7eb;overflow:hidden;position:relative;}
        .card img{width:100%;aspect-ratio:3/4;object-fit:cover;display:block;}
        .card-body{padding:12px;}
        .card-brand{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:3px;}
        .card-name{font-size:14px;font-weight:700;margin-bottom:6px;}
        .card-price{font-size:16px;font-weight:900;color:#dc2626;}
        .card-size{font-size:12px;color:#6b7280;margin-left:6px;}
        .badge-sold{position:absolute;top:10px;left:10px;background:#111827;color:#fff;font-size:10px;font-weight:900;letter-spacing:2px;padding:4px 8px;}
        .badge-new{position:absolute;top:10px;right:10px;background:#dc2626;color:#fff;font-size:10px;font-weight:900;letter-spacing:1px;padding:4px 8px;}
        .section-title{font-size:13px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;}
        .reviews-list{display:flex;flex-direction:column;gap:12px;}
        .review-item{background:#fff;border:1px solid #e5e7eb;padding:16px;}
        .review-header{display:flex;justify-content:space-between;align-items:center;margin-bottom:8px;}
        .review-user{font-size:14px;font-weight:700;}
        .review-date{font-size:12px;color:#9ca3af;}
        .stars{color:#f59e0b;}
        .review-product{font-size:12px;color:#6b7280;margin-bottom:6px;}
        .review-comment{font-size:14px;color:#374151;line-height:1.5;}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'mitra', 'storeName' => $storeName])

    <section class="store-hero">
        <div class="store-hero-inner">
            @if($partner->status !== 'approved')
            <div style="position:absolute;top:0;left:0;right:0;background:#f59e0b;color:#111827;text-align:center;padding:8px;font-size:13px;font-weight:700;z-index:10;">
                ⚠️ Preview Admin — Toko ini berstatus <strong>{{ strtoupper($partner->status) }}</strong> dan belum tampil ke publik
            </div>
            @endif
            <img src="{{ $partner->logo_url }}" alt="{{ $partner->store_name }}" class="store-logo">
            <div>
                <div>
                    <span class="store-name">{{ $partner->store_name }}</span>
                    @if($partner->is_verified)<span class="store-verified">✓ Verified</span>@endif
                    @if($partner->tier_name && $partner->tier_name !== 'bronze')
                        <span style="display:inline-block;font-size:12px;color:#f59e0b;margin-left:8px;background:#1f2937;padding:3px 10px;border-radius:3px;">{{ $partner->tier_badge ?? '🏆' }} {{ ucfirst($partner->tier_name) }}</span>
                    @endif
                </div>
                <div class="store-meta">
                    @if($partner->location)📍 {{ $partner->location }} · @endif
                    Bergabung {{ $partner->approved_at?->format('M Y') ?? $partner->created_at->format('M Y') }}
                </div>
                <div class="store-stats">
                    <div><div class="store-stat-num">{{ $products->count() }}</div><div class="store-stat-label">Produk</div></div>
                    <div><div class="store-stat-num">{{ number_format($partner->average_rating, 1) }} ⭐</div><div class="store-stat-label">Rating</div></div>
                    <div><div class="store-stat-num">{{ $partner->review_count }}</div><div class="store-stat-label">Review</div></div>
                    <div><div class="store-stat-num">{{ $followerCount ?? $partner->follower_count ?? 0 }}</div><div class="store-stat-label">Pengikut</div></div>
                </div>
                @if(auth()->check() && auth()->user()->isMember())
                <div style="margin-top:10px;">
                    <form method="POST" action="{{ route('partner.follow', $partner) }}" style="display:inline;">
                        @csrf
                        @php $isFollowing = $isFollowing ?? false; @endphp
                        <button type="submit" style="padding:8px 18px;background:{{ $isFollowing ? '#374151' : '#fff' }};color:{{ $isFollowing ? '#9ca3af' : '#111827' }};border:0;font-size:13px;font-weight:700;cursor:pointer;border-radius:3px;">
                            {{ $isFollowing ? '✓ Mengikuti' : '+ Ikuti Toko' }}
                        </button>
                    </form>
                </div>
                @endif
                <div class="store-links">
                    @if($partner->whatsapp)<a href="https://wa.me/{{ $partner->whatsapp }}" target="_blank" class="store-link">💬 WhatsApp</a>@endif
                    @if($partner->shopee_url)<a href="{{ $partner->shopee_url }}" target="_blank" class="store-link">🛒 Shopee</a>@endif
                    @if($partner->tokopedia_url)<a href="{{ $partner->tokopedia_url }}" target="_blank" class="store-link">🟢 Tokopedia</a>@endif
                    @if($partner->instagram_url)<a href="{{ $partner->instagram_url }}" target="_blank" class="store-link">📷 Instagram</a>@endif
                </div>
            </div>
        </div>
    </section>

    <div class="main">
        @if($partner->description)
            <p style="color:#374151;font-size:15px;line-height:1.6;margin-bottom:28px;max-width:640px;">{{ $partner->description }}</p>
        @endif

        <p class="section-title">Produk ({{ $products->count() }})</p>
        @if($products->isEmpty())
            <p style="color:#6b7280;margin-bottom:40px;">Belum ada produk dari toko ini.</p>
        @else
            <div class="grid">
                @foreach($products as $p)
                <a href="{{ route('catalog.show', $p->slug) }}" style="text-decoration:none;color:inherit;">
                    <div class="card">
                        <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
                        @if($p->is_sold)<span class="badge-sold">SOLD</span>@endif
                        @if($p->is_new_arrival && !$p->is_sold)<span class="badge-new">NEW</span>@endif
                        <div class="card-body">
                            <p class="card-brand">{{ $p->brand }}</p>
                            <p class="card-name">{{ $p->name }}</p>
                            <p><span class="card-price">Rp {{ number_format($p->price, 0, ',', '.') }}</span><span class="card-size">Size {{ $p->size }}</span></p>
                        </div>
                    </div>
                </a>
                @endforeach
            </div>
        @endif

        @if($reviews->isNotEmpty())
            <p class="section-title">Review Terbaru</p>
            <div class="reviews-list">
                @foreach($reviews as $r)
                <div class="review-item">
                    <div class="review-header">
                        <span class="review-user">{{ $r->user->name }}</span>
                        <span class="review-date">{{ $r->created_at->format('d M Y') }}</span>
                    </div>
                    <div class="review-product">untuk <a href="{{ route('catalog.show', $r->product->slug) }}" style="color:#dc2626;">{{ $r->product->name }}</a></div>
                    <div class="stars">{{ str_repeat('★', $r->rating) }}{{ str_repeat('☆', 5 - $r->rating) }}</div>
                    @if($r->comment)<p class="review-comment" style="margin-top:6px;">{{ $r->comment }}</p>@endif
                </div>
                @endforeach
            </div>
        @endif
    </div>

</body>
</html>
