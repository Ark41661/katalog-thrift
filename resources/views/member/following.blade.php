<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Toko Diikuti - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .container{width:min(720px,94%);margin:32px auto;}
        h1{font-size:28px;font-weight:900;margin-bottom:20px;}
        .list{display:flex;flex-direction:column;gap:12px;}
        .item{background:#fff;border:1px solid #e5e7eb;padding:16px;display:flex;align-items:center;gap:14px;}
        .item img{width:48px;height:48px;border-radius:50%;object-fit:cover;}
        .info{flex:1;}
        .info .name{font-size:15px;font-weight:700;}
        .info .meta{font-size:12px;color:#6b7280;margin-top:2px;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;border:0;cursor:pointer;text-decoration:none;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .empty{text-align:center;padding:60px 20px;color:#6b7280;}
        .empty h2{font-size:24px;color:#111827;margin-bottom:8px;}
        .empty a{color:#dc2626;font-weight:700;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('wishlist.index') }}">♥ Wishlist</a>
                <a href="{{ route('member.profile') }}">Profil</a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="container">
        <h1>👥 Toko Diikuti ({{ $follows->count() }})</h1>
        @if($follows->isEmpty())
            <div class="empty">
                <h2>Belum mengikuti toko</h2>
                <p>Temukan toko favorit dan klik ikuti di halaman toko.</p>
                <a href="{{ route('partners.index') }}">Lihat Toko Mitra →</a>
            </div>
        @else
            <div class="list">
                @foreach($follows as $f)
                <div class="item">
                    <img src="{{ $f->partner->logo_url }}" alt="{{ $f->partner->store_name }}">
                    <div class="info">
                        <a href="{{ route('partners.show', $f->partner->store_slug) }}" class="name" style="text-decoration:none;color:inherit;">{{ $f->partner->store_name }}</a>
                        <div class="meta">{{ $f->partner->products->where('is_active', true)->count() }} produk · {{ $f->partner->follower_count }} pengikut</div>
                    </div>
                    <form method="POST" action="{{ route('partner.follow', $f->partner) }}">
                        @csrf
                        <button type="submit" class="btn btn-outline">Berhenti Ikuti</button>
                    </form>
                </div>
                @endforeach
            </div>
        @endif
    </div>
</body>
</html>
