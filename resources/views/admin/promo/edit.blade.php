<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Promo Landing - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{margin-bottom:24px;}
        .head h1{font-size:22px;font-weight:900;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:24px;max-width:800px;}
        .form-group{margin-bottom:20px;}
        .form-group label{display:block;font-size:13px;font-weight:700;margin-bottom:6px;color:#374151;}
        .form-group input[type=text], .form-group input[type=url]{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:4px;font-size:14px;}
        .btn-primary{background:#111827;color:#fff;padding:10px 16px;font-weight:700;border:none;cursor:pointer;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ config('catalog.store_name') }}</div>
            <div class="role">Super Admin</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.partners.index') }}">🏪 Kelola Mitra</a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.reviews.index') }}">⭐ Review</a>
            <a href="{{ route('admin.outfits.index') }}">✦ Outfit Kurasi</a>
            <a href="{{ route('admin.articles.index') }}">📝 Editorial / Blog</a>
            <a href="{{ route('admin.ugc.index') }}">📸 Komunitas (UGC)</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
            <a href="{{ route('admin.landing.edit') }}" class="active">✨ Promo Landing</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head">
            <h1>Pengaturan Promo Landing</h1>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('admin.landing.update') }}">
                @csrf @method('POST')
                <div class="form-group">
                    <label>Judul Hero <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="hero_title" value="{{ old('hero_title', $hero_title) }}" required>
                </div>
                <div class="form-group">
                    <label>Sub‑judul Hero <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="hero_sub" value="{{ old('hero_sub', $hero_sub) }}" required>
                </div>
                <div class="form-group">
                    <label>Cover Image URL <span style="color:#dc2626;">*</span></label>
                    <input type="url" name="cover_image" value="{{ old('cover_image', $cover_image) }}" required>
                </div>
                <div class="form-group">
                    <label>Season / Tagline <span style="color:#dc2626;">*</span></label>
                    <input type="text" name="season" value="{{ old('season', $season) }}" required>
                </div>
                <button type="submit" class="btn-primary">Simpan Pengaturan</button>
            </form>
        </div>
    </main>
</body>
</html>
