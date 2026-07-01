<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Wishlist Saya - {{ config('catalog.store_name') }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;padding:0;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .topbar-nav a:hover{color:#111827;}
        .container{width:min(1280px,94%);margin:32px auto;}
        h1{font-size:28px;font-weight:900;margin-bottom:20px;}
        .grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(220px,1fr));gap:16px;}
        .card{background:#fff;border:1px solid #e5e7eb;overflow:hidden;}
        .card img{width:100%;aspect-ratio:3/4;object-fit:cover;display:block;}
        .card-body{padding:12px;}
        .card-brand{font-size:10px;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:3px;}
        .card-name{font-size:14px;font-weight:700;margin-bottom:6px;}
        .card-price{font-size:16px;font-weight:900;color:#dc2626;}
        .card-store{font-size:12px;color:#6b7280;margin-top:4px;}
        .card-actions{display:flex;gap:6px;margin-top:10px;}
        .btn{flex:1;text-align:center;padding:8px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-remove{background:#fee2e2;color:#991b1b;}
        .empty{text-align:center;padding:80px 20px;color:#6b7280;}
        .empty h2{font-size:24px;color:#111827;margin-bottom:8px;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ config('catalog.store_name') }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('wishlist.index') }}" style="color:#dc2626;">♥ Wishlist</a>
                <form method="POST" action="{{ route('member.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>

    <div class="container">
        <h1>♥ Wishlist Saya ({{ $wishlists->count() }})</h1>

        @if($wishlists->isEmpty())
            <div class="empty">
                <h2>Wishlist kosong</h2>
                <p>Simpan produk favorit kamu dengan klik ♡ di halaman produk.</p>
                <a href="{{ route('catalog.index') }}" style="color:#dc2626;font-weight:700;display:inline-block;margin-top:16px;">← Lihat Katalog</a>
            </div>
        @else
            <div class="grid">
                @foreach($wishlists as $w)
                    @php $p = $w->product; @endphp
                    <div class="card">
                        <a href="{{ route('catalog.show', $p->slug) }}">
                            <img src="{{ $p->image_url }}" alt="{{ $p->name }}">
                        </a>
                        <div class="card-body">
                            <p class="card-brand">{{ $p->brand }}</p>
                            <p class="card-name">{{ $p->name }}</p>
                            <p class="card-price">Rp {{ number_format($p->price, 0, ',', '.') }}</p>
                            @if($p->partner)
                                <p class="card-store">🏪 {{ $p->partner->store_name }}</p>
                            @endif
                            <div class="card-actions">
                                <a href="{{ route('catalog.show', $p->slug) }}" class="btn btn-dark">Detail</a>
                                <form method="POST" action="{{ route('wishlist.toggle', $p->slug) }}" style="flex:1;">
                                    @csrf
                                    <button type="submit" class="btn btn-remove" style="width:100%;">Hapus</button>
                                </form>
                            </div>
                        </div>
                    </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
