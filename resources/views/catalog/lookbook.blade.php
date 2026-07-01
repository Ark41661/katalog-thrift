<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lookbook Interaktif - {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        /* TOPBAR */
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
        .hero{background:var(--dark);padding:48px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;text-align:center;}
        .hero-eyebrow{font-size:11px;letter-spacing:3px;text-transform:uppercase;color:#6b7280;margin-bottom:12px;}
        .hero-title{font-size:clamp(36px,5vw,68px);font-weight:900;line-height:.92;color:#fff;letter-spacing:-1px;margin-bottom:16px;}
        .hero-desc{font-size:15px;color:#9ca3af;line-height:1.6;margin:0 auto 24px;max-width:540px;}
        /* SECTION WRAP */
        .section-wrap{width:min(1280px,94%);margin:40px auto 60px;}
        /* CINEMATIC LOOKBOOK */
        .cinematic-grid { display: grid; grid-template-columns: repeat(auto-fill, minmax(360px, 1fr)); gap: 32px; }
        .cinematic-card { background: var(--card); border: 1px solid var(--border); overflow: hidden; display: flex; flex-direction: column; }
        .cinematic-image-wrap { position: relative; width: 100%; aspect-ratio: 4/5; overflow: hidden; background: #111; }
        .cinematic-image-wrap > img, .cinematic-image-wrap > video { width: 100%; height: 100%; object-fit: cover; opacity: 0.95; transition: transform .5s, opacity .5s; display: block; }
        .cinematic-card:hover .cinematic-image-wrap > img, .cinematic-card:hover .cinematic-image-wrap > video { transform: scale(1.03); opacity: 1; }
        .cinematic-video-badge{position:absolute;top:12px;left:12px;background:rgba(0,0,0,0.6);color:#fff;font-size:10px;font-weight:900;letter-spacing:1.5px;padding:4px 8px;z-index:5;}
        
        .flatlay-fallback { display: grid; gap: 2px; background: #f3f4f6; width: 100%; aspect-ratio: 4/5; align-content: start; }
        .flatlay-fallback-2 { grid-template-columns: 1fr 1fr; grid-template-rows: 50% 50%; }
        .flatlay-fallback img { width: 100%; height: 100%; object-fit: cover; }
        
        /* HOTSPOTS */
        .hotspot { position: absolute; width: 22px; height: 22px; background: rgba(255,255,255,0.95); border-radius: 50%; box-shadow: 0 0 0 4px rgba(255,255,255,0.25); transform: translate(-50%, -50%); cursor: pointer; z-index: 10; animation: pulse 2s infinite; display: flex; align-items: center; justify-content: center; }
        .hotspot:hover { animation: none; box-shadow: 0 0 0 4px rgba(255,255,255,0.5); }
        .hotspot::after { content: ''; width: 8px; height: 8px; background: var(--dark); border-radius: 50%; }
        @keyframes pulse { 0% { box-shadow: 0 0 0 0 rgba(255,255,255,0.5); } 70% { box-shadow: 0 0 0 10px rgba(255,255,255,0); } 100% { box-shadow: 0 0 0 0 rgba(255,255,255,0); } }
        
        /* Hotspot Tooltip */
        .hotspot-tooltip { position: absolute; bottom: 100%; left: 50%; transform: translateX(-50%) translateY(-10px); background: #fff; padding: 10px 14px; border-radius: 4px; box-shadow: 0 10px 25px rgba(0,0,0,0.2); opacity: 0; visibility: hidden; transition: all .2s; width: max-content; max-width: 200px; text-align: center; pointer-events: none; z-index: 20; }
        .hotspot:hover .hotspot-tooltip { opacity: 1; visibility: visible; transform: translateX(-50%) translateY(-16px); }
        .hotspot-tooltip::after { content: ''; position: absolute; top: 100%; left: 50%; transform: translateX(-50%); border: 6px solid transparent; border-top-color: #fff; }
        .hotspot-tooltip-name { font-size: 12px; font-weight: 700; color: var(--text); margin-bottom: 4px; white-space: normal; line-height: 1.2; }
        .hotspot-tooltip-price { font-size: 13px; font-weight: 900; color: var(--accent); }
        
        /* Outfit info */
        .curated-info{padding:20px 24px 14px; border-bottom:1px solid var(--border);}
        .curated-title{font-size:18px;font-weight:900;margin-bottom:6px; letter-spacing: -0.5px;}
        .curated-meta{display:flex;gap:10px;align-items:center;flex-wrap:wrap;}
        .curated-style{font-size:11px;background:#fef3c7;color:#92400e;padding:4px 10px;font-weight:700;}
        .curated-price{font-size:14px;font-weight:700;color:var(--accent);}
        .curated-by{font-size:11px;color:var(--muted);}
        
        /* Item list */
        .curated-items{padding:14px 24px;}
        .curated-item{display:flex;align-items:center;gap:12px;padding:8px 0;border-bottom:1px solid #f9fafb;}
        .curated-item:last-child{border-bottom:0;}
        .curated-item img{width:42px;height:42px;object-fit:cover;border:1px solid var(--border);flex-shrink:0;}
        .curated-item-info{flex:1;min-width:0;}
        .curated-item-name{font-size:13px;font-weight:700;white-space:nowrap;overflow:hidden;text-overflow:ellipsis;}
        .curated-item-meta{font-size:11px;color:var(--muted);margin-top: 2px;}
        .curated-item-price{font-size:13px;font-weight:900;color:var(--accent);flex-shrink:0;}
        
        /* Outfit actions */
        .curated-actions{display:flex;gap:0;border-top:1px solid var(--border);}
        .curated-btn{flex:1;text-align:center;padding:12px 6px;font-size:13px;font-weight:700;border:0;cursor:pointer;text-decoration:none;display:block;transition:background .15s;}
        .curated-btn-wa{background:#25d366;color:#fff;}
        .curated-btn-wa:hover{background:#1ebe5d;}
        .curated-btn-save{background:#f9fafb;color:var(--muted);border-right:1px solid var(--border);}
        .curated-btn-save:hover{background:#fee2e2;color:var(--accent);}
        .curated-btn-save.saved{background:#fee2e2;color:var(--accent);}
        .curated-btn-share{background:#f9fafb;color:var(--muted);border-right:1px solid var(--border);}
        .curated-btn-share:hover{background:#eff6ff;color:#1d4ed8;}
        
        /* SHARE TOAST */
        .toast{position:fixed;bottom:24px;right:24px;background:var(--dark);color:#fff;padding:12px 20px;font-size:13px;font-weight:600;border-radius:4px;z-index:999;display:none;animation:fadeIn .2s;}
        @keyframes fadeIn{from{opacity:0;transform:translateY(8px)}to{opacity:1;transform:translateY(0)}}
        
        @media(max-width:1024px){.cinematic-grid{grid-template-columns:1fr 1fr;}}
        @media(max-width:640px){.cinematic-grid{grid-template-columns:1fr;}}
    </style>
</head>
<body>
@include('partials.public-nav', ['activeNav' => 'lookbook', 'storeName' => $storeName])

{{-- HERO --}}
<section class="hero">
    <div class="hero-inner">
        <p class="hero-eyebrow">{{ $catalogSeason }}</p>
        <h1 class="hero-title">CINEMATIC<br>LOOKBOOK</h1>
        <p class="hero-desc">Eksplorasi gaya hidup melalui lensa thrift. Sentuh titik interaktif pada model untuk melihat detail produk yang dikenakan.</p>
    </div>
</section>

<div class="section-wrap">
    @if($curatedOutfits->isNotEmpty())
        <div class="cinematic-grid">
            @foreach($curatedOutfits as $outfit)
            @php
                $isSaved   = in_array($outfit->id, $savedOutfitIds);
                // Calculate total price using loaded items
                $totalPrice = $outfit->items->sum(fn($item) => $item->product ? $item->product->price : 0);
                $availProds = collect();
                foreach($outfit->items as $item) {
                    if ($item->product && !$item->product->is_sold) {
                        $availProds->push($item->product);
                    }
                }
                $bundleMsg  = "Halo, saya tertarik outfit \"{$outfit->title}\": " . $availProds->pluck('name')->implode(', ') . ". Apakah semua tersedia?";
                $bundleWa   = "https://wa.me/{$whatsappNumber}?text=" . urlencode($bundleMsg);
            @endphp
            <div class="cinematic-card">
                <div class="cinematic-image-wrap">
                    @if($outfit->cover_image || $outfit->cover_video)
                        @if($outfit->cover_video)
                            <span class="cinematic-video-badge">▶ VIDEO</span>
                            @if(str_contains($outfit->cover_video, 'youtube.com') || str_contains($outfit->cover_video, 'youtu.be'))
                                @php
                                    preg_match('/(?:youtube\.com\/(?:watch\?v=|embed\/)|youtu\.be\/)([a-zA-Z0-9_-]+)/', $outfit->cover_video, $ytMatch);
                                    $ytId = $ytMatch[1] ?? null;
                                @endphp
                                @if($ytId)
                                <iframe src="https://www.youtube.com/embed/{{ $ytId }}?autoplay=0&mute=1&loop=1&playlist={{ $ytId }}" style="width:100%;height:100%;border:0;position:absolute;inset:0;" allow="autoplay" title="{{ $outfit->title }}"></iframe>
                                @endif
                            @else
                                <video src="{{ $outfit->cover_video }}" muted loop playsinline autoplay style="position:absolute;inset:0;"></video>
                            @endif
                        @endif
                        @if($outfit->cover_image)
                        <img src="{{ Str::startsWith($outfit->cover_image, 'http') ? $outfit->cover_image : asset('storage/'.$outfit->cover_image) }}" alt="{{ $outfit->title }}" style="{{ $outfit->cover_video ? 'opacity:0;position:absolute;' : '' }}">
                        @endif
                        {{-- HOTSPOTS --}}
                        @foreach($outfit->items as $item)
                            @if($item->product && $item->hotspot_x !== null && $item->hotspot_y !== null)
                                <a href="{{ route('catalog.show', $item->product->slug) }}" class="hotspot" style="left: {{ $item->hotspot_x }}%; top: {{ $item->hotspot_y }}%;">
                                    <div class="hotspot-tooltip">
                                        <div class="hotspot-tooltip-name">{{ $item->product->name }}</div>
                                        <div class="hotspot-tooltip-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</div>
                                    </div>
                                </a>
                            @endif
                        @endforeach
                    @else
                        {{-- FALLBACK TO FLATLAY IF NO COVER IMAGE --}}
                        <div class="flatlay-fallback {{ $outfit->items->count() >= 3 ? 'flatlay-fallback-2' : '' }}">
                            @foreach($outfit->items->take(4) as $item)
                                @if($item->product)
                                <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                                @endif
                            @endforeach
                        </div>
                    @endif
                </div>
                <div class="curated-info">
                    <p class="curated-title">{{ $outfit->title }}</p>
                    <div class="curated-meta">
                        @if($outfit->style_type)<span class="curated-style">{{ ucfirst($outfit->style_type) }}</span>@endif
                        <span class="curated-price">Total: Rp {{ number_format($totalPrice, 0, ',', '.') }}</span>
                        @if($outfit->partner)<span class="curated-by">oleh {{ $outfit->partner->store_name }}</span>@endif
                    </div>
                </div>
                <div class="curated-items">
                    @foreach($outfit->items as $item)
                    @if($item->product)
                    <div class="curated-item">
                        <img src="{{ $item->product->image_url }}" alt="{{ $item->product->name }}">
                        <div class="curated-item-info">
                            <a href="{{ route('catalog.show', $item->product->slug) }}" class="curated-item-name" style="display:block; text-decoration:none; color:inherit;">{{ $item->product->name }}</a>
                            <div class="curated-item-meta">{{ $item->product->brand }} · Size {{ $item->product->size }}</div>
                        </div>
                        <span class="curated-item-price">Rp {{ number_format($item->product->price, 0, ',', '.') }}</span>
                    </div>
                    @endif
                    @endforeach
                </div>
                <div class="curated-actions">
                    @auth
                    <form method="POST" action="{{ route('outfit.save.toggle', $outfit) }}" style="flex:1;display:flex;">
                        @csrf
                        <button type="submit" class="curated-btn curated-btn-save {{ $isSaved ? 'saved' : '' }}">
                            {{ $isSaved ? '♥ Tersimpan' : '♡ Simpan' }}
                        </button>
                    </form>
                    @endauth
                    @php
                        $shareUrl = $outfit->share_token ? route('outfit.share', $outfit->share_token) : '#';
                        $shareTitle = addslashes($outfit->title);
                    @endphp
                    <button class="curated-btn curated-btn-share" onclick="shareOutfit('{{ $shareUrl }}', '{{ $shareTitle }}')">
                        🔗 Share
                    </button>
                    @if($availProds->isNotEmpty())
                    <a href="{{ $bundleWa }}" target="_blank" rel="noopener noreferrer" class="curated-btn curated-btn-wa">
                        💬 Beli Bundle
                    </a>
                    @endif
                </div>
            </div>
            @endforeach
        </div>
    @else
        <div style="text-align:center; padding: 60px 20px; color: var(--muted);">
            <p style="font-size:48px; margin-bottom:16px;">📸</p>
            <h2 style="font-size:24px; font-weight:700; color:var(--text); margin-bottom:8px;">Lookbook Kosong</h2>
            <p>Admin atau mitra belum menambahkan lookbook bergaya sinematik.</p>
        </div>
    @endif
</div>

<div class="toast" id="toast"></div>
<script>
const STORE = "{{ $storeName }}";
function shareOutfit(url, title) {
    if (navigator.share) {
        navigator.share({ title: title + ' — ' + STORE, url: url });
    } else {
        navigator.clipboard.writeText(url).then(() => showToast('Link outfit disalin! 🔗'));
    }
}
function showToast(msg) {
    const t = document.getElementById('toast');
    t.textContent = msg; t.style.display = 'block';
    setTimeout(() => { t.style.display = 'none'; }, 2500);
}
</script>
</body>
</html>
