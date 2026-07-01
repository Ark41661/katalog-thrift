<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Saya - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .topbar-nav a:hover{color:#111827;}
        .container{width:min(680px,94%);margin:32px auto;}
        h1{font-size:28px;font-weight:900;margin-bottom:20px;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:28px;}
        .card-header{display:flex;align-items:center;gap:16px;margin-bottom:24px;padding-bottom:16px;border-bottom:1px solid #f1f5f9;}
        .avatar{width:64px;height:64px;border-radius:50%;background:#111827;color:#fff;display:flex;align-items:center;justify-content:center;font-size:24px;font-weight:900;}
        .user-info h2{font-size:18px;font-weight:900;}
        .user-info p{font-size:13px;color:#6b7280;}
        label{display:block;font-size:12px;font-weight:700;margin-bottom:6px;margin-top:16px;}
        label:first-of-type{margin-top:0;}
        input[type=text],textarea{width:100%;padding:11px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{resize:vertical;min-height:80px;}
        .btn{width:100%;padding:13px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;margin-top:20px;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .member-links{display:flex;gap:8px;flex-wrap:wrap;margin-top:20px;}
        .member-links a{padding:10px 16px;background:#fff;border:1px solid #e5e7eb;text-decoration:none;color:#111827;font-size:13px;font-weight:600;}
        .member-links a:hover{background:#f9fafb;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('wishlist.index') }}">♥ Wishlist</a>
                <a href="{{ route('member.notifications') }}">🔔 Notifikasi</a>
                <form method="POST" action="{{ route('member.logout') }}" style="display:inline;">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="container">
        <h1>Profil Saya</h1>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        <div class="card">
            <div class="card-header">
                <div class="avatar">{{ substr($user->name, 0, 1) }}</div>
                <div class="user-info">
                    <h2>{{ $user->name }}</h2>
                    <p>{{ $user->email }} · {{ $user->tier_badge }} {{ $user->tier_name }} · {{ $user->points }} poin</p>
                </div>
            </div>
            <form method="POST" action="{{ route('member.profile.update') }}">
                @csrf @method('PUT')
                <label>Nama</label>
                <input type="text" name="name" value="{{ $user->name }}" required>
                <label>No. HP</label>
                <input type="text" name="phone" value="{{ $user->phone }}" placeholder="08xxx">
                <label>Bio</label>
                <textarea name="bio" placeholder="Ceritakan tentang dirimu...">{{ $user->bio }}</textarea>
                <button type="submit" class="btn">Simpan Perubahan</button>
            </form>
        </div>
        <div class="member-links">
            <a href="{{ route('wishlist.index') }}">♥ Wishlist</a>
            <a href="{{ route('outfit.saved') }}">✦ Outfit Tersimpan</a>
            <a href="{{ route('member.following') }}">👥 Toko Diikuti</a>
            <a href="{{ route('member.badges') }}">🏆 Badges</a>
            <a href="{{ route('member.notifications') }}">🔔 Notifikasi</a>
        </div>
    </div>
</body>
</html>
