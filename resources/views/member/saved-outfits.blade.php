<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Outfit Tersimpan - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f7f7f5;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;position:sticky;top:0;z-index:100;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .topbar-nav a:hover{color:#dc2626;}
        .container{width:min(1280px,94%);margin:32px auto 60px;}
        h1{font-size:28px;font-weight:900;margin-bottom:20px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(300px,1fr));gap:20px;}
        .card{background:#fff;border:1px solid #e5e7eb;overflow:hidden;}
        .flatlay{display:grid;gap:2px;background:#f3f4f6;}
        .flatlay-2{grid-template-columns:1fr 1fr;}
        .flatlay-3{grid-template-columns:1fr 1fr 1fr;}
        .flatlay img{width:100%;aspect-ratio:1;object-fit:cover;display:block;}
        .card-info{padding:14px 16px 10px;border-bottom:1px solid #e5e7eb;}
        .card-title{font-size:15px;font-weight:900;margin-bottom:4px;}
        .card-meta{font-size:12px;color:#6b7280;}
        .card-actions{display:flex;gap:0;border-top:1px solid #e5e7eb;}
        .card-btn{flex:1;text-align:center;padding:10px;font-size:12px;font-weight:700;border:0;cursor:pointer;text-decoration:none;display:block;}
        .btn-view{background:#f3f4f6;color:#111827;}
        .btn-view:hover{background:#e5e7eb;}
        .btn-remove{background:#fee2e2;color:#dc2626;}
        .btn-remove:hover{background:#fecaca;}
        .empty{text-align:center;padding:80px 20px;color:#6b7280;}
        .empty h2{font-size:24px;color:#111827;margin-bottom:8px;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.lookbook') }}">← Lookbook</a>
                <a href="{{ route('wishlist.index') }}">♥ Wishlist Produk</a>
                <form method="POST" action="{{ route('member.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>♥ Outfit Tersimpan ({{ $saves->count() }})</h1>

        @if($saves->isEmpty())
            <div class="empty">
                <h2>Belum ada outfit tersimpan</h2>
                <p>Simpan outfit favorit dari halaman Lookbook.</p>
                <a href="{{ route('catalog.lookbook') }}" style="color:#dc2626;font-weight:700;display:inline-block;margin-top:16px;">← Ke Lookbook</a>
            </div>
        @else
            <div class="grid">
                @foreach($saves as $save)
                @php
                    $outfit = $save->outfit;
                    $prodCount = $outfit->products->count();
                    $flatClass = $prodCount >= 3 ? 'flatlay-3' : 'flatlay-2';
                @endphp
                <div class="card">
                    <div class="flatlay {{ $flatClass }}">
                        @foreach($outfit->products->take(3) as $p)
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
                        @endforeach
                    </div>
                    <div class="card-info">
                        <p class="card-title">{{ $outfit->title }}</p>
                        <p class="card-meta">
                            {{ $outfit->products->count() }} item ·
                            Rp {{ number_format($outfit->products->sum('price'), 0, ',', '.') }}
                            @if($outfit->style_type) · {{ ucfirst($outfit->style_type) }}@endif
                        </p>
                    </div>
                    <div class="card-actions">
                        <a href="{{ route('outfit.share', $outfit->share_token) }}" class="card-btn btn-view">Lihat Outfit</a>
                        <form method="POST" action="{{ route('outfit.save.toggle', $outfit) }}" style="flex:1;display:flex;">
                            @csrf
                            <button type="submit" class="card-btn btn-remove" style="width:100%;">Hapus</button>
                        </form>
                    </div>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
