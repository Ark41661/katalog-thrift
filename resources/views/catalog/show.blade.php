<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $product->meta_title ?? $product->name }} - {{ $storeName }}</title>
    <meta name="description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    @if($product->meta_keywords)<meta name="keywords" content="{{ $product->meta_keywords }}">@endif
    <meta property="og:title" content="{{ $product->meta_title ?? $product->name }}">
    <meta property="og:description" content="{{ $product->meta_description ?? Str::limit(strip_tags($product->description), 160) }}">
    <meta property="og:image" content="{{ $product->image_url }}">
    <meta property="og:type" content="product">
    <link rel="canonical" href="{{ route('catalog.show', $product->slug) }}">
    <style>
        :root {
            --bg: #efefef;
            --text: #111111;
            --muted: #4b5563;
            --card: #ffffff;
            --accent: #dc2626;
            --accent-dark: #b91c1c;
            --border: #e4e4e7;
        }
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: "Arial Narrow", Arial, Helvetica, sans-serif;
            background: var(--bg);
            color: var(--text);
        }
        .container {
            width: min(1160px, 92%);
            margin: 22px auto 40px;
        }
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid #e4e4e7;backdrop-filter:blur(8px);margin-bottom:16px;}
        .topbar-inner{width:min(1160px,92%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;color:#111827;flex-shrink:0;text-decoration:none;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:#dc2626;}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:#6b7280;padding:6px 12px;border:1px solid #e4e4e7;text-decoration:none;background:none;cursor:pointer;font-family:inherit;}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:#111827;padding:6px 14px;text-decoration:none;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        .back {
            text-decoration: none;
            color: #111827;
            display: inline-block;
            margin-bottom: 14px;
            letter-spacing: .3px;
        }
        .detail {
            display: grid;
            grid-template-columns: 1.1fr 0.9fr;
            gap: 0;
            border: 1px solid var(--border);
            background: var(--card);
        }
        .visual {
            min-height: 620px;
            border-right: 1px solid var(--border);
        }
        .visual img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }
        .content {
            padding: 26px 24px 22px;
            display: flex;
            flex-direction: column;
            gap: 14px;
        }
        .title {
            margin: 0;
            font-size: clamp(32px, 5vw, 58px);
            line-height: .95;
            color: var(--accent);
            text-transform: uppercase;
        }
        .badge {
            display: inline-block;
            font-size: 12px;
            background: #f3f4f6;
            color: #111827;
            padding: 4px 8px;
            border-radius: 3px;
            width: fit-content;
        }
        .meta {
            margin: 0;
        }
        .meta-table {
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
            padding: 12px 0;
            display: grid;
            gap: 8px;
        }
        .row {
            display: grid;
            grid-template-columns: 120px 1fr;
            font-size: 14px;
        }
        .label {
            color: var(--muted);
            text-transform: uppercase;
            letter-spacing: 1px;
            font-size: 12px;
        }
        .price {
            margin: 0;
            font-size: 30px;
            font-weight: 700;
        }
        .description {
            margin: 0;
            color: #1f2937;
            line-height: 1.6;
            word-break: break-word;
            /* Collapse multiple blank lines menjadi satu baris saja */
            white-space: pre-wrap;
            max-height: 320px;
            overflow-y: auto;
        }
        .description::-webkit-scrollbar { width: 4px; }
        .description::-webkit-scrollbar-track { background: #f1f5f9; }
        .description::-webkit-scrollbar-thumb { background: #d1d5db; border-radius: 2px; }
        .cta {
            margin-top: auto;
            display: flex;
            gap: 8px;
        }
        .channel-links {
            display: flex;
            flex-wrap: wrap;
            gap: 8px;
            margin-top: 2px;
        }
        .channel-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            text-decoration: none;
            border: 1px solid var(--border);
            color: #111827;
            padding: 8px 10px;
            font-size: 13px;
        }
        .channel-link img {
            width: 16px;
            height: 16px;
        }
        .btn {
            display: inline-block;
            text-decoration: none;
            padding: 12px 16px;
            border-radius: 0;
            font-weight: 700;
            text-align: center;
            flex: 1;
        }
        .btn-primary {
            background: var(--accent);
            color: #fff;
        }
        .btn-primary:hover { background: var(--accent-dark); }
        .btn-secondary {
            border: 1px solid var(--border);
            color: #111827;
            background: #fff;
        }
        .section-title {
            margin: 30px 0 12px;
            font-size: 38px;
            line-height: .95;
            color: var(--accent);
        }
        .related {
            display: grid;
            grid-template-columns: repeat(auto-fill, minmax(220px, 1fr));
            gap: 16px;
        }
        .related-item {
            background: var(--card);
            border: 1px solid var(--border);
            overflow: hidden;
        }
        .related-item img {
            width: 100%;
            height: 170px;
            object-fit: cover;
        }
        .related-item .info {
            padding: 12px;
        }

        /* MITRA INFO */
        .mitra-card{margin-top:24px;background:#fff;border:1px solid var(--border);padding:20px;display:flex;align-items:center;gap:16px;}
        .mitra-logo{width:52px;height:52px;border-radius:50%;object-fit:cover;border:2px solid var(--border);flex-shrink:0;}
        .mitra-name{font-size:16px;font-weight:900;}
        .mitra-verified{display:inline-block;background:#dbeafe;color:#1d4ed8;font-size:10px;font-weight:700;padding:2px 6px;border-radius:3px;margin-left:6px;}
        .mitra-meta{font-size:13px;color:var(--muted);margin-top:3px;}
        .mitra-btn{margin-left:auto;padding:9px 16px;background:#111827;color:#fff;font-size:13px;font-weight:700;text-decoration:none;white-space:nowrap;flex-shrink:0;}
        .mitra-btn:hover{background:#374151;}

        /* REVIEW SECTION */
        .review-section{margin-top:28px;}
        .review-section h2{font-size:22px;font-weight:900;margin-bottom:16px;}
        .review-summary{display:flex;align-items:center;gap:20px;background:#fff;border:1px solid var(--border);padding:20px;margin-bottom:16px;}
        .review-avg{font-size:52px;font-weight:900;line-height:1;color:#111827;}
        .review-stars{color:#f59e0b;font-size:22px;}
        .review-count{font-size:13px;color:var(--muted);margin-top:4px;}
        .review-form{background:#fff;border:1px solid var(--border);padding:20px;margin-bottom:16px;}
        .review-form h3{font-size:16px;font-weight:700;margin-bottom:14px;}
        .star-input{display:flex;gap:6px;margin-bottom:4px;}
        .star-btn{font-size:32px;color:#d1d5db;cursor:pointer;transition:color .1s;user-select:none;line-height:1;}
        .star-btn:hover{color:#f59e0b;}
        .star-btn.hovered{color:#f59e0b;}
        .star-btn.selected{color:#f59e0b;}
        .review-form textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;resize:vertical;min-height:80px;}
        .review-form .btn-submit{margin-top:10px;padding:10px 20px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;}
        .review-list{display:flex;flex-direction:column;gap:12px;}
        .review-item{background:#fff;border:1px solid var(--border);padding:16px;}
        .review-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;}
        .review-user{font-size:14px;font-weight:700;}
        .review-date{font-size:12px;color:var(--muted);}
        .review-stars-sm{color:#f59e0b;font-size:14px;}
        .review-comment{font-size:14px;color:#374151;line-height:1.5;margin-top:6px;}
        .review-delete{font-size:12px;color:#dc2626;background:none;border:0;cursor:pointer;padding:0;}
        .alert-success{background:#dcfce7;color:#166534;padding:10px 14px;font-size:13px;margin-bottom:14px;border:1px solid #bbf7d0;}
        .login-prompt{background:#f9fafb;border:1px solid var(--border);padding:16px;text-align:center;font-size:14px;color:var(--muted);}
        .login-prompt a{color:#dc2626;font-weight:700;}

        /* REPORT */
        .report-btn{font-size:12px;color:var(--muted);background:none;border:0;cursor:pointer;padding:0;margin-top:8px;}
        .report-btn:hover{color:#dc2626;}
        .report-modal{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:200;align-items:center;justify-content:center;}
        .report-modal.open{display:flex;}
        .report-box{background:#fff;padding:28px;width:min(420px,94%);}
        .report-box h3{font-size:18px;font-weight:900;margin-bottom:14px;}
        .report-box select,.report-box textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;margin-bottom:10px;}
        .report-box textarea{min-height:70px;resize:vertical;}

        /* MIX & MATCH */
        .mixmatch { margin-top: 24px; }
        .mixmatch-header { display: flex; align-items: center; justify-content: space-between; margin-bottom: 16px; }
        .mixmatch-title { font-size: 22px; font-weight: 900; color: var(--text); }
        .mixmatch-subtitle { font-size: 13px; color: var(--muted); margin-top: 2px; }
        .mixmatch-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(200px, 1fr)); gap: 14px; }

        .mix-card { background: var(--card); border: 2px solid var(--border); overflow: hidden; cursor: pointer; transition: border-color .2s, box-shadow .2s; text-decoration: none; color: var(--text); display: block; }
        .mix-card:hover { border-color: var(--accent); box-shadow: 0 4px 20px rgba(220,38,38,0.12); }
        .mix-card.selected { border-color: var(--accent); box-shadow: 0 0 0 3px rgba(220,38,38,0.2); }

        .mix-card-photos { display: grid; grid-template-columns: 1fr 1fr; gap: 2px; background: #f3f4f6; }
        .mix-card-photos img { width: 100%; aspect-ratio: 1; object-fit: cover; display: block; }
        .mix-card-photos .mix-photo-single { grid-column: span 2; aspect-ratio: 4/3; }

        .mix-card-body { padding: 10px 12px; }
        .mix-card-label { font-size: 10px; letter-spacing: 1.5px; text-transform: uppercase; color: var(--muted); margin-bottom: 3px; }
        .mix-card-name { font-size: 13px; font-weight: 700; line-height: 1.3; margin-bottom: 4px; }
        .mix-card-price { font-size: 14px; font-weight: 900; color: var(--accent); }
        .mix-card-size { font-size: 11px; color: var(--muted); margin-left: 6px; }
        .mix-card-actions { display: flex; gap: 6px; margin-top: 8px; }
        .mix-btn { flex: 1; text-align: center; padding: 7px 6px; font-size: 11px; font-weight: 700; border: 0; cursor: pointer; text-decoration: none; display: block; }
        .mix-btn-wa { background: #25d366; color: #fff; }
        .mix-btn-wa:hover { background: #1ebe5d; }
        .mix-btn-detail { background: #f3f4f6; color: var(--text); border: 1px solid var(--border); }
        .mix-btn-detail:hover { background: #e5e7eb; }

        /* Bundle CTA */
        .bundle-cta { margin-top: 16px; background: #111827; padding: 18px 20px; display: flex; justify-content: space-between; align-items: center; gap: 16px; flex-wrap: wrap; }
        .bundle-cta-text h4 { color: #fff; font-size: 16px; margin: 0 0 4px; }
        .bundle-cta-text p { color: #9ca3af; font-size: 13px; margin: 0; }
        .bundle-cta-btn { background: var(--accent); color: #fff; padding: 11px 22px; font-size: 13px; font-weight: 700; text-decoration: none; white-space: nowrap; }
        .bundle-cta-btn:hover { background: var(--accent-dark); }

        @media (max-width: 860px) {
            .detail { grid-template-columns: 1fr; }
            .visual { min-height: 380px; border-right: 0; border-bottom: 1px solid var(--border); }
            .cta { flex-direction: column; }
            .mixmatch-grid { grid-template-columns: repeat(2, 1fr); }
        }
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'katalog', 'storeName' => $storeName])
    @php
        $message = "Halo {$storeName}, saya ingin pesan {$product->name} ({$product->size}) harga Rp " . number_format($product->price, 0, ',', '.') . ". Mohon info stoknya ya.";
        $whatsappLink = route('catalog.wa-click', $product->slug);
        $channelLinks = [
            ['label' => 'WhatsApp',  'url' => $whatsappLink,                                 'icon' => '/icons/whatsapp.jpg'],
            ['label' => 'Shopee',    'url' => $product->shopee_url ?: $socialLinks['shopee'], 'icon' => '/icons/shopee.webp'],
            ['label' => 'Instagram', 'url' => $socialLinks['instagram'],                      'icon' => '/icons/instagram.svg'],
            ['label' => 'TikTok',    'url' => $socialLinks['tiktok'],                         'icon' => '/icons/tiktok.jpg'],
        ];
    @endphp

    <main class="container">
        <a class="back" href="{{ route('catalog.index') }}">← Kembali ke katalog</a>

        <section class="detail">
            <div class="visual" style="position:relative;">
                <img src="{{ $product->image }}" alt="{{ $product->name }}">
                @if($product->is_sold)
                    <div style="position:absolute;inset:0;background:rgba(0,0,0,0.45);display:flex;align-items:center;justify-content:center;">
                        <span style="background:#111827;color:#fff;font-size:28px;font-weight:900;letter-spacing:5px;padding:12px 28px;border:3px solid #fff;transform:rotate(-8deg);">SOLD</span>
                    </div>
                @elseif($product->is_new_arrival)
                    <span style="position:absolute;top:14px;left:14px;background:#dc2626;color:#fff;font-size:11px;font-weight:900;letter-spacing:2px;padding:5px 12px;">NEW ARRIVAL</span>
                @endif
            </div>

            <div class="content">
                <span class="badge">{{ $product->brand }}</span>
                @if($product->product_type && isset($productTypes[$product->product_type]))
                    <span class="badge" style="background:#f0fdf4;color:#166534;margin-left:4px;">
                        {{ $productTypes[$product->product_type]['emoji'] }} {{ $productTypes[$product->product_type]['label'] }}
                    </span>
                @endif
                <h1 class="title">{{ $product->name }}</h1>

                @if($product->is_sold)
                    <p style="margin:0;font-size:14px;font-weight:700;color:#6b7280;background:#f3f4f6;padding:10px 14px;border-left:3px solid #9ca3af;">
                        Produk ini sudah terjual. Lihat produk lain yang tersedia.
                    </p>
                @endif

                <p class="price" style="{{ $product->is_sold ? 'text-decoration:line-through;color:#9ca3af;font-size:22px;' : '' }}">
                    Rp {{ number_format($product->price, 0, ',', '.') }}
                </p>
                <div class="meta-table">
                    <p class="row meta"><span class="label">Size</span> <span>{{ $product->size }}</span></p>
                    <p class="row meta"><span class="label">Kondisi</span> <span>{{ $product->condition }}</span></p>
                </div>

                @if($product->has_variants && $product->variants->isNotEmpty())
                <div style="border:1px solid var(--border);padding:12px;margin-bottom:12px;">
                    <p style="font-size:12px;font-weight:900;letter-spacing:1px;text-transform:uppercase;color:#6b7280;margin-bottom:8px;">Pilih Varian</p>
                    <div style="display:flex;flex-wrap:wrap;gap:8px;">
                        @foreach($product->variants as $v)
                        <label style="display:flex;align-items:center;gap:6px;padding:8px 12px;border:1px solid var(--border);cursor:pointer;font-size:13px;font-weight:600;">
                            <input type="radio" name="selected_variant" value="{{ $v->id }}" data-price="{{ $v->price }}" data-size="{{ $v->size }}" onchange="updateVariant(this)" {{ $loop->first ? 'checked' : '' }}>
                            <span>{{ $v->size }}</span>
                            <span style="color:#dc2626;font-weight:900;">Rp {{ number_format($v->price, 0, ',', '.') }}</span>
                            @if($v->stock !== null)<span style="font-size:11px;color:#6b7280;">(stok: {{ $v->stock }})</span>@endif
                        </label>
                        @endforeach
                    </div>
                </div>
                @endif

                <p class="description">{!! nl2br(e(preg_replace('/(\r?\n){3,}/', "\n\n", $product->description))) !!}</p>

                @if(!empty($product->size_chart))
                <div style="margin: 16px 0; border: 1px solid var(--border); background: #f9fafb;">
                    <p style="margin:0; padding: 10px 14px; font-weight: 900; border-bottom: 1px solid var(--border); font-size: 14px;">PANDUAN UKURAN ({{ $product->size_unit ?? 'cm' }})</p>
                    <table style="width: 100%; border-collapse: collapse; font-size: 13px; text-align: left;">
                        <thead>
                            <tr style="background: #fff; border-bottom: 1px solid var(--border);">
                                @foreach(array_keys($product->size_chart[0]) as $key)
                                <th style="padding: 10px 14px; color: var(--muted); text-transform: uppercase;">{{ $key }}</th>
                                @endforeach
                            </tr>
                        </thead>
                        <tbody>
                            @foreach($product->size_chart as $row)
                            <tr style="border-bottom: 1px solid #f3f4f6;">
                                @foreach($row as $val)
                                <td style="padding: 10px 14px;">{{ $val }}</td>
                                @endforeach
                            </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
                @endif

                @if(!$product->is_sold)
                <div class="channel-links">
                    @foreach ($channelLinks as $channel)
                        <a class="channel-link" href="{{ $channel['url'] }}" target="_blank" rel="noopener noreferrer">
                            <img src="{{ $channel['icon'] }}" alt="{{ $channel['label'] }}">
                            <span>{{ $channel['label'] }}</span>
                        </a>
                    @endforeach
                </div>
                <div class="cta">
                    <a class="btn btn-secondary" href="{{ route('catalog.index') }}">Lihat katalog</a>
                    @auth
                    <form method="POST" action="{{ route('wishlist.toggle', $product->slug) }}" style="flex:1;">
                        @csrf
                        @php $inWishlist = in_array($product->id, $wishlistIds); @endphp
                        <button type="submit" style="width:100%;padding:12px;border:1px solid var(--border);background:{{ $inWishlist ? '#fee2e2' : '#fff' }};color:{{ $inWishlist ? '#dc2626' : '#111827' }};font-size:14px;font-weight:700;cursor:pointer;">
                            {{ $inWishlist ? '♥ Tersimpan' : '♡ Simpan' }}
                        </button>
                    </form>
                    @endauth
                    <a class="btn btn-primary" href="{{ $whatsappLink }}" target="_blank" rel="noopener noreferrer" id="wa-order-btn" data-base-url="{{ $whatsappLink }}">Pesan via WhatsApp</a>
                </div>
                @else
                <div class="cta">
                    <a class="btn btn-secondary" href="{{ route('catalog.index') }}" style="flex:1;text-align:center;">← Lihat Produk Lain</a>
                </div>
                @endif
            </div>
        </section>

        {{-- STORYTELLING --}}
        @if($product->story)
        <section style="margin-top:24px;background:#fff;border:1px solid var(--border);padding:24px 28px;display:grid;grid-template-columns:auto 1fr;gap:20px;align-items:start;">
            <div style="font-size:48px;line-height:1;">✦</div>
            <div>
                <h3 style="margin:0 0 10px;font-size:20px;color:var(--accent);">Cerita di Balik Produk Ini</h3>
                <p style="margin:0;color:#374151;line-height:1.7;white-space:pre-line;">{!! nl2br(e($product->story)) !!}</p>
            </div>
        </section>
        @endif

        {{-- MITRA INFO --}}
        @if($product->partner)
        <div class="mitra-card">
            <img src="{{ $product->partner->logo_url }}" alt="{{ $product->partner->store_name }}" class="mitra-logo">
            <div>
                <div>
                    <span class="mitra-name">{{ $product->partner->store_name }}</span>
                    @if($product->partner->is_verified)<span class="mitra-verified">✓ Verified</span>@endif
                    @if($product->partner->tier_name && $product->partner->tier_name !== 'bronze')
                        <span style="display:inline-block;font-size:11px;color:#f59e0b;margin-left:6px;">{{ $product->partner->tier_badge ?? '🏆' }} {{ $product->partner->tier_name }}</span>
                    @endif
                </div>
                <div class="mitra-meta">
                    ⭐ {{ number_format($product->partner->average_rating, 1) }} · {{ $product->partner->review_count }} review · {{ $product->partner->follower_count ?? 0 }} pengikut
                    @if($product->partner->location) · 📍 {{ $product->partner->location }}@endif
                </div>
            </div>
            <div style="display:flex;gap:8px;align-items:center;margin-left:auto;flex-shrink:0;">
                @if(auth()->check() && auth()->user()->isMember())
                <form method="POST" action="{{ route('partner.follow', $product->partner) }}">
                    @csrf
                    @php $isFollowing = $isFollowingPartner ?? false; @endphp
                    <button type="submit" style="padding:9px 14px;background:{{ $isFollowing ? '#f3f4f6' : '#111827' }};color:{{ $isFollowing ? '#111827' : '#fff' }};border:{{ $isFollowing ? '1px solid #e5e7eb' : '0' }};font-size:13px;font-weight:700;cursor:pointer;">
                        {{ $isFollowing ? '✓ Mengikuti' : '+ Ikuti Toko' }}
                    </button>
                </form>
                @endif
                <a href="{{ route('partners.show', $product->partner->store_slug) }}" class="mitra-btn">Lihat Toko →</a>
            </div>
        </div>
        @endif

        {{-- REVIEW SECTION --}}
        <section class="review-section">
            <h2>Review Produk</h2>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <div class="review-summary">
                <div class="review-avg">{{ number_format($product->average_rating, 1) }}</div>
                <div>
                    <div class="review-stars">
                        @for($i=1;$i<=5;$i++){{ $i <= round($product->average_rating) ? '★' : '☆' }}@endfor
                    </div>
                    <div class="review-count">{{ $product->review_count }} review</div>
                </div>
            </div>

            @auth
                @if(!$userReview)
                <div class="review-form">
                    <h3>Tulis Review</h3>
                    <form method="POST" action="{{ route('review.store', $product->slug) }}">
                        @csrf
                        {{-- Hidden input yang menyimpan nilai rating --}}
                        <input type="hidden" name="rating" id="rating-value" value="">
                        <div class="star-input" id="star-input">
                            @for($i=1;$i<=5;$i++)
                                <span class="star-btn" data-value="{{ $i }}" title="{{ $i }} bintang">★</span>
                            @endfor
                        </div>
                        <p id="star-label" style="font-size:12px;color:#9ca3af;margin-bottom:10px;min-height:18px;"></p>
                        <textarea name="comment" placeholder="Ceritakan pengalamanmu dengan produk ini... (opsional)" maxlength="500"></textarea>
                        <button type="submit" class="btn-submit" id="review-submit" disabled style="opacity:.5;cursor:not-allowed;">Pilih bintang dulu</button>
                    </form>
                </div>
                @else
                <div class="review-form">
                    <h3>Review Kamu</h3>
                    <div class="review-stars-sm" style="font-size:20px;margin-bottom:8px;">
                        {{ str_repeat('★', $userReview->rating) }}{{ str_repeat('☆', 5 - $userReview->rating) }}
                    </div>
                    @if($userReview->comment)<p style="font-size:14px;color:#374151;">{{ $userReview->comment }}</p>@endif
                    <form method="POST" action="{{ route('review.destroy', $product->slug) }}" style="margin-top:10px;">
                        @csrf @method('DELETE')
                        <button type="submit" class="review-delete" onclick="return confirm('Hapus review?')">Hapus review saya</button>
                    </form>
                </div>
                @endif
            @else
                <div class="login-prompt">
                    <a href="{{ route('member.login') }}">Login</a> untuk memberikan review produk ini.
                </div>
            @endauth

            @if($product->reviews->isNotEmpty())
            <div class="review-list" style="margin-top:16px;">
                @foreach($product->reviews->sortByDesc('created_at') as $review)
                <div class="review-item">
                    <div class="review-header">
                        <div>
                            <span class="review-user">{{ $review->user->name }}</span>
                            <div class="review-stars-sm">{{ str_repeat('★', $review->rating) }}{{ str_repeat('☆', 5 - $review->rating) }}</div>
                        </div>
                        <span class="review-date">{{ $review->created_at->format('d M Y') }}</span>
                    </div>
                    @if($review->comment)<p class="review-comment">{{ $review->comment }}</p>@endif
                </div>
                @endforeach
            </div>
            @endif
        </section>

        {{-- Q&A SECTION --}}
        <section style="margin-top:28px;">
            <h2 style="font-size:22px;font-weight:900;margin-bottom:16px;">❓ Tanya Tentang Produk</h2>
            @if($product->questions->isNotEmpty())
            <div style="display:flex;flex-direction:column;gap:12px;margin-bottom:16px;">
                @foreach($product->questions as $q)
                <div style="background:#fff;border:1px solid var(--border);padding:16px;">
                    <div style="display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:6px;">
                        <span style="font-size:13px;font-weight:700;">{{ $q->user->name }}</span>
                        <span style="font-size:12px;color:#6b7280;">{{ $q->created_at->diffForHumans() }}</span>
                    </div>
                    <p style="font-size:14px;color:#374151;margin-bottom:8px;">{{ $q->question }}</p>
                    @if($q->answer)
                    <div style="background:#f0fdf4;padding:12px;border-left:3px solid #16a34a;">
                        <span style="font-size:11px;font-weight:700;color:#16a34a;">✓ Jawaban Mitra</span>
                        <p style="font-size:14px;color:#374151;margin-top:4px;">{{ $q->answer }}</p>
                    </div>
                    @else
                    <p style="font-size:12px;color:#9ca3af;">Menunggu jawaban dari mitra...</p>
                    @endif
                </div>
                @endforeach
            </div>
            @endif
            @if(auth()->check() && auth()->user()->isMember())
            <div style="background:#fff;border:1px solid var(--border);padding:20px;">
                <h3 style="font-size:14px;font-weight:700;margin-bottom:10px;">Ajukan Pertanyaan</h3>
                <form method="POST" action="{{ route('question.store', $product->slug) }}">
                    @csrf
                    <textarea name="question" placeholder="Tanya tentang ukuran, bahan, kondisi..." maxlength="500" style="width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;resize:vertical;min-height:60px;" required></textarea>
                    <button type="submit" style="margin-top:8px;padding:10px 18px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;">Kirim Pertanyaan</button>
                </form>
            </div>
            @else
            <div style="background:#f9fafb;border:1px solid var(--border);padding:16px;text-align:center;font-size:14px;color:#6b7280;">
                <a href="{{ route('member.login') }}" style="color:#dc2626;font-weight:700;">Login</a> untuk bertanya tentang produk ini.
            </div>
            @endif
        </section>

        {{-- REPORT BUTTON --}}
        @auth
        <div style="margin-top:12px;text-align:right;">
            <button class="report-btn" onclick="document.getElementById('report-modal').classList.add('open')">🚨 Laporkan produk ini</button>
        </div>
        <div class="report-modal" id="report-modal">
            <div class="report-box">
                <h3>Laporkan Produk</h3>
                <form method="POST" action="{{ route('report.store', $product->slug) }}">
                    @csrf
                    <select name="reason" required>
                        <option value="">-- Pilih Alasan --</option>
                        <option value="harga_tidak_wajar">Harga Tidak Wajar</option>
                        <option value="foto_palsu">Foto Palsu / Tidak Sesuai</option>
                        <option value="produk_tidak_sesuai">Produk Tidak Sesuai Deskripsi</option>
                        <option value="lainnya">Lainnya</option>
                    </select>
                    <textarea name="detail" placeholder="Detail laporan (opsional)..." maxlength="500"></textarea>
                    <div style="display:flex;gap:8px;">
                        <button type="submit" style="flex:1;padding:10px;background:#dc2626;color:#fff;border:0;font-weight:700;cursor:pointer;">Kirim Laporan</button>
                        <button type="button" style="flex:1;padding:10px;background:#f3f4f6;border:1px solid #e5e7eb;font-weight:700;cursor:pointer;" onclick="document.getElementById('report-modal').classList.remove('open')">Batal</button>
                    </div>
                </form>
            </div>
        </div>
        @endauth

        {{-- MIX & MATCH --}}
        @if($pairings->isNotEmpty())
        <section class="mixmatch">
            <div class="mixmatch-header">
                <div>
                    <h2 class="mixmatch-title">Mix & Match</h2>
                    <p class="mixmatch-subtitle">{{ $pairingLabel }} dari koleksi kami — klik untuk lihat detail</p>
                </div>
            </div>

            <div class="mixmatch-grid">
                @foreach($pairings as $pair)
                @php
                    $pairWaMsg  = "Halo {$storeName}, saya tertarik dengan {$pair->name} (Size {$pair->size}) untuk dipadukan dengan {$product->name}. Apakah masih tersedia?";
                    $pairWaLink = "https://wa.me/{$whatsappNumber}?text=" . urlencode($pairWaMsg);
                @endphp
                <div class="mix-card" onclick="window.location='{{ route('catalog.show', $pair->slug) }}'">
                    <div class="mix-card-photos">
                        {{-- Foto produk yang sedang dilihat (kecil) + foto pairing --}}
                        <img src="{{ $product->image }}" alt="{{ $product->name }}">
                        <img src="{{ $pair->image }}" alt="{{ $pair->name }}">
                    </div>
                    <div class="mix-card-body">
                        <p class="mix-card-label">{{ $pair->product_type === 'pants' ? 'Celana' : 'Hoodie' }} · {{ $pair->brand }}</p>
                        <p class="mix-card-name">{{ $pair->name }}</p>
                        <div style="display:flex;align-items:center;">
                            <span class="mix-card-price">Rp {{ number_format($pair->price, 0, ',', '.') }}</span>
                            <span class="mix-card-size">Size {{ $pair->size }}</span>
                        </div>
                        <div class="mix-card-actions" onclick="event.stopPropagation()">
                            <a href="{{ $pairWaLink }}" target="_blank" rel="noopener noreferrer" class="mix-btn mix-btn-wa">💬 Chat</a>
                            <a href="{{ route('catalog.show', $pair->slug) }}" class="mix-btn mix-btn-detail">Detail →</a>
                        </div>
                    </div>
                </div>
                @endforeach
            </div>

            {{-- Bundle CTA --}}
            @if(!$product->is_sold)
            @php
                $bundleMsg = "Halo {$storeName}, saya tertarik beli bundle: {$product->name} + " . $pairings->pluck('name')->implode(' / ') . ". Bisa info harga dan ketersediaannya?";
                $bundleWa  = "https://wa.me/{$whatsappNumber}?text=" . urlencode($bundleMsg);
            @endphp
            <div class="bundle-cta">
                <div class="bundle-cta-text">
                    <h4>Beli Sepaket Lebih Hemat?</h4>
                    <p>Chat admin untuk tanya bundle {{ $product->name }} + item pelengkap.</p>
                </div>
                <a href="{{ $bundleWa }}" target="_blank" rel="noopener noreferrer" class="bundle-cta-btn">💬 Tanya Bundle</a>
            </div>
            @endif
        </section>
        @endif

        {{-- RELATED PRODUCTS --}}
        @if ($relatedProducts->isNotEmpty())
            <section>
                <h2 class="section-title">RELATED<br>PRODUCTS</h2>
                <div class="related">
                    @foreach ($relatedProducts as $item)
                        <article class="related-item" style="position:relative;">
                            @if($item->is_new_arrival)
                                <span style="position:absolute;top:8px;left:8px;background:#dc2626;color:#fff;font-size:10px;font-weight:900;letter-spacing:1px;padding:3px 7px;z-index:1;">NEW</span>
                            @endif
                            <img src="{{ $item->image }}" alt="{{ $item->name }}">
                            <div class="info">
                                <strong>{{ $item->name }}</strong>
                                <p class="meta">Rp {{ number_format($item->price, 0, ',', '.') }}</p>
                                <a href="{{ route('catalog.show', $item->slug) }}">Lihat detail</a>
                            </div>
                        </article>
                    @endforeach
                </div>
            </section>
        @endif
    </main>

    <script>
    (() => {
        const stars     = document.querySelectorAll('.star-btn');
        const input     = document.getElementById('rating-value');
        const label     = document.getElementById('star-label');
        const submitBtn = document.getElementById('review-submit');
        const labels    = ['', 'Sangat Buruk', 'Buruk', 'Cukup', 'Bagus', 'Sangat Bagus'];

        if (!stars.length) return;

        let selected = 0;

        function paint(upTo) {
            stars.forEach((s, idx) => {
                s.classList.toggle('hovered', idx < upTo);
                s.classList.toggle('selected', idx < selected);
                if (upTo > 0) {
                    s.style.color = idx < upTo ? '#f59e0b' : '#d1d5db';
                } else {
                    s.style.color = idx < selected ? '#f59e0b' : '#d1d5db';
                }
            });
        }

        stars.forEach((star, idx) => {
            star.addEventListener('mouseenter', () => paint(idx + 1));
            star.addEventListener('mouseleave', () => paint(0));
            star.addEventListener('click', () => {
                selected = idx + 1;
                input.value = selected;
                label.textContent = selected + ' bintang — ' + labels[selected];
                paint(0);
                submitBtn.disabled = false;
                submitBtn.style.opacity = '1';
                submitBtn.style.cursor  = 'pointer';
                submitBtn.textContent   = 'Kirim Review';
            });
        });
    })();
    </script>

    <script>
    function updateVariant(el) {
        const price = el.dataset.price;
        const size = el.dataset.size;
        const priceEl = document.querySelector('.price');
        if (priceEl) {
            const formatted = new Intl.NumberFormat('id-ID').format(price);
            priceEl.textContent = 'Rp ' + formatted;
        }
        const sizeEl = document.querySelector('.meta-table .row.meta:nth-child(1) span:last-child');
        if (sizeEl) sizeEl.textContent = size;

        const waBtn = document.getElementById('wa-order-btn');
        if (waBtn) {
            const formatted = new Intl.NumberFormat('id-ID').format(price);
            const baseUrl = waBtn.dataset.baseUrl;
            waBtn.href = baseUrl + '?size=' + encodeURIComponent(size) + '&price=' + encodeURIComponent(price);
        }
    }
    </script>
</body>
</html>
