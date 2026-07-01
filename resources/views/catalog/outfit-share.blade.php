<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $outfit->title }} - {{ $storeName }}</title>
    <meta property="og:title" content="{{ $outfit->title }} — {{ $storeName }}">
    <meta property="og:description" content="Outfit {{ $outfit->products->count() }} item · Total Rp {{ number_format($outfit->products->sum('price'), 0, ',', '.') }}">
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f7f7f5;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:100;}
        .topbar-inner{width:min(900px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .container{width:min(900px,94%);margin:32px auto 60px;}
        .outfit-header{margin-bottom:24px;}
        .outfit-header h1{font-size:clamp(24px,4vw,40px);font-weight:900;margin-bottom:8px;}
        .outfit-meta{display:flex;gap:12px;align-items:center;flex-wrap:wrap;font-size:13px;color:#6b7280;}
        .style-tag{background:#fef3c7;color:#92400e;padding:3px 10px;font-size:11px;font-weight:700;}
        /* FLAT-LAY BESAR */
        .flatlay-hero{display:grid;gap:3px;background:#f3f4f6;margin-bottom:24px;border:1px solid #e5e7eb;}
        .flatlay-2{grid-template-columns:1fr 1fr;}
        .flatlay-3{grid-template-columns:1fr 1fr 1fr;}
        .flatlay-4{grid-template-columns:1fr 1fr;grid-template-rows:1fr 1fr;}
        .flatlay-hero img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        /* ITEM LIST */
        .items-section h2{font-size:18px;font-weight:900;margin-bottom:14px;}
        .item-card{display:flex;align-items:center;gap:14px;background:#fff;border:1px solid #e5e7eb;padding:14px;margin-bottom:10px;}
        .item-card:hover{border-color:#9ca3af;}
        .item-img{width:72px;height:72px;object-fit:cover;border:1px solid #e5e7eb;flex-shrink:0;}
        .item-info{flex:1;}
        .item-brand{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:3px;}
        .item-name{font-size:15px;font-weight:700;margin-bottom:4px;}
        .item-meta{font-size:12px;color:#6b7280;}
        .item-price{font-size:16px;font-weight:900;color:#dc2626;margin-bottom:8px;}
        .item-store{font-size:11px;color:#1d4ed8;font-weight:600;}
        .item-actions{display:flex;gap:6px;flex-shrink:0;flex-direction:column;}
        .btn{padding:8px 14px;font-size:12px;font-weight:700;border:0;cursor:pointer;text-decoration:none;display:inline-block;text-align:center;white-space:nowrap;}
        .btn-wa{background:#25d366;color:#fff;}
        .btn-detail{background:#f3f4f6;color:#111827;border:1px solid #e5e7eb;}
        /* TOTAL & BUNDLE CTA */
        .total-bar{background:#111827;padding:20px 24px;display:flex;justify-content:space-between;align-items:center;gap:16px;flex-wrap:wrap;margin-bottom:24px;}
        .total-bar-left h3{color:#fff;font-size:18px;margin-bottom:4px;}
        .total-bar-left p{color:#9ca3af;font-size:13px;}
        .btn-bundle{padding:12px 24px;background:#dc2626;color:#fff;font-size:14px;font-weight:700;text-decoration:none;white-space:nowrap;}
        .btn-bundle:hover{background:#b91c1c;}
        /* SHARE */
        .share-bar{background:#fff;border:1px solid #e5e7eb;padding:16px 20px;display:flex;align-items:center;gap:12px;flex-wrap:wrap;margin-bottom:24px;}
        .share-bar p{font-size:13px;font-weight:700;flex:1;}
        .share-url{flex:2;padding:8px 12px;border:1px solid #e5e7eb;font-size:12px;color:#6b7280;background:#f9fafb;min-width:200px;}
        .btn-copy{padding:9px 16px;background:#111827;color:#fff;font-size:12px;font-weight:700;border:0;cursor:pointer;}
        /* SAVE */
        .save-bar{text-align:center;margin-bottom:24px;}
        .toast{position:fixed;bottom:24px;right:24px;background:#111827;color:#fff;padding:12px 20px;font-size:13px;font-weight:600;z-index:999;display:none;}
        @media(max-width:600px){.item-card{flex-wrap:wrap;}.item-actions{flex-direction:row;width:100%;}}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <a href="{{ route('catalog.lookbook') }}" style="font-size:13px;color:#6b7280;text-decoration:none;">← Lookbook</a>
        </div>
    </header>

    <div class="container">
        <div class="outfit-header">
            <h1>{{ $outfit->title }}</h1>
            <div class="outfit-meta">
                @if($outfit->style_type)<span class="style-tag">{{ ucfirst($outfit->style_type) }}</span>@endif
                <span>{{ $outfit->products->count() }} item</span>
                <span>Total: Rp {{ number_format($outfit->products->sum('price'), 0, ',', '.') }}</span>
                @if($outfit->description)<span>{{ $outfit->description }}</span>@endif
            </div>
        </div>

        {{-- FLAT-LAY BESAR --}}
        @php
            $prodCount = $outfit->products->count();
            $flatClass = $prodCount >= 4 ? 'flatlay-4' : ($prodCount === 3 ? 'flatlay-3' : 'flatlay-2');
        @endphp
        <div class="flatlay-hero {{ $flatClass }}">
            @foreach($outfit->products->take(4) as $p)
                <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
            @endforeach
        </div>

        {{-- SHARE BAR --}}
        <div class="share-bar">
            <p>🔗 Bagikan outfit ini</p>
            <input type="text" class="share-url" id="share-url" value="{{ request()->url() }}" readonly>
            <button class="btn-copy" onclick="copyUrl()">Salin Link</button>
        </div>

        {{-- SAVE BUTTON --}}
        @auth
        @php $isSaved = in_array($outfit->id, $savedIds); @endphp
        <div class="save-bar">
            <form method="POST" action="{{ route('outfit.save.toggle', $outfit) }}">
                @csrf
                <button type="submit" style="padding:11px 28px;background:{{ $isSaved ? '#fee2e2' : '#111827' }};color:{{ $isSaved ? '#dc2626' : '#fff' }};border:0;font-size:14px;font-weight:700;cursor:pointer;">
                    {{ $isSaved ? '♥ Tersimpan di Koleksi Kamu' : '♡ Simpan Outfit Ini' }}
                </button>
            </form>
        </div>
        @endauth

        {{-- ITEM LIST --}}
        <div class="items-section">
            <h2>Item dalam Outfit</h2>
            @foreach($outfit->products as $p)
            @php
                $waMsg  = "Halo {$storeName}, saya tertarik dengan {$p->name} (Size {$p->size}) dari outfit \"{$outfit->title}\". Apakah masih tersedia?";
                $waLink = "https://wa.me/{$waNumber}?text=" . urlencode($waMsg);
            @endphp
            <div class="item-card">
                <img class="item-img" src="{{ $p->image_url }}" alt="{{ $p->name }}">
                <div class="item-info">
                    <p class="item-brand">{{ $p->brand }}</p>
                    <p class="item-name">{{ $p->name }}</p>
                    <p class="item-meta">Size {{ $p->size }} · Kondisi {{ $p->condition }}</p>
                    @if($p->partner)<p class="item-store">🏪 {{ $p->partner->store_name }}</p>@endif
                    <p class="item-price">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                </div>
                <div class="item-actions">
                    @if(!$p->is_sold)
                        <a href="{{ $waLink }}" target="_blank" class="btn btn-wa">💬 Chat</a>
                    @endif
                    <a href="{{ route('catalog.show', $p->slug) }}" class="btn btn-detail">Detail</a>
                </div>
            </div>
            @endforeach
        </div>

        {{-- BUNDLE CTA --}}
        @php
            $availProds = $outfit->products->where('is_sold', false);
            $bundleMsg  = "Halo {$storeName}, saya tertarik outfit \"{$outfit->title}\": " . $availProds->pluck('name')->implode(', ') . ". Apakah semua tersedia?";
            $bundleWa   = "https://wa.me/{$waNumber}?text=" . urlencode($bundleMsg);
        @endphp
        @if($availProds->isNotEmpty())
        <div class="total-bar">
            <div class="total-bar-left">
                <h3>Total Outfit: Rp {{ number_format($outfit->products->sum('price'), 0, ',', '.') }}</h3>
                <p>Tanya semua item sekaligus via WhatsApp</p>
            </div>
            <a href="{{ $bundleWa }}" target="_blank" class="btn-bundle">💬 Tanya Semua Item</a>
        </div>
        @endif
    </div>

    <div class="toast" id="toast"></div>
    <script>
        function copyUrl() {
            const input = document.getElementById('share-url');
            input.select(); document.execCommand('copy');
            const t = document.getElementById('toast');
            t.textContent = 'Link disalin! 🔗'; t.style.display = 'block';
            setTimeout(() => t.style.display = 'none', 2000);
        }
    </script>
</body>
</html>
