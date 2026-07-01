<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Profil Toko - {{ $partner->store_name }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#111827;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1f2937;}
        .sidebar-brand .name{font-size:14px;font-weight:900;letter-spacing:1px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#6b7280;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1f2937;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1f2937;}
        .main{margin-left:220px;padding:28px 32px;}
        .form-wrap{max-width:640px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=text],input[type=url],textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        input[type=file]{width:100%;padding:8px;border:1px solid #d1d5db;font-size:13px;}
        textarea{resize:vertical;min-height:80px;}
        .btn{display:inline-block;padding:11px 20px;font-size:14px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        .logo-preview{width:80px;height:80px;object-fit:cover;border-radius:50%;border:2px solid #e5e7eb;margin-bottom:12px;display:block;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $partner->store_name }}</div>
            <div class="role">Portal Mitra</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('partner.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('partner.products.index') }}">📦 Produk Saya</a>
            <a href="{{ route('partner.products.create') }}">➕ Tambah Produk</a>
            <a href="{{ route('partner.analytics') }}">📈 Analitik</a>
            <a href="{{ route('partner.questions.index') }}">❓ Pertanyaan</a>
            <a href="{{ route('partner.notifications') }}">🔔 Notifikasi</a>
            <a href="{{ route('partner.profile') }}" class="active">🏪 Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="form-wrap">
            <div class="head"><h1>Profil Toko</h1></div>

            @if(session('success'))
                <div class="alert-success">{{ session('success') }}</div>
            @endif

            <form method="POST" action="{{ route('partner.profile.update') }}" enctype="multipart/form-data">
                @csrf @method('PUT')

                <div class="section">
                    <p class="section-title">Info Toko</p>
                    <img src="{{ $partner->logo_url }}" alt="Logo" class="logo-preview">

                    <label>Nama Toko</label>
                    <input type="text" name="store_name" value="{{ old('store_name', $partner->store_name) }}" required>

                    <label>Deskripsi Toko</label>
                    <textarea name="description">{{ old('description', $partner->description) }}</textarea>

                    <label>Lokasi</label>
                    <input type="text" name="location" value="{{ old('location', $partner->location) }}" placeholder="Kota, Provinsi">

                    <label>Nomor WhatsApp</label>
                    <input type="text" name="whatsapp" value="{{ old('whatsapp', $partner->whatsapp) }}" placeholder="628xxx">

                    <label>Logo Toko <span style="font-weight:400;color:#9ca3af">(opsional, maks 1MB)</span></label>
                    <input type="file" name="logo_file" accept="image/*">
                </div>

                <div class="section">
                    <p class="section-title">Link Toko</p>
                    <label>Shopee</label>
                    <input type="url" name="shopee_url" value="{{ old('shopee_url', $partner->shopee_url) }}" placeholder="https://shopee.co.id/...">

                    <label>Tokopedia</label>
                    <input type="url" name="tokopedia_url" value="{{ old('tokopedia_url', $partner->tokopedia_url) }}" placeholder="https://tokopedia.com/...">

                    <label>Instagram</label>
                    <input type="url" name="instagram_url" value="{{ old('instagram_url', $partner->instagram_url) }}" placeholder="https://instagram.com/...">

                    <label>TikTok</label>
                    <input type="url" name="tiktok_url" value="{{ old('tiktok_url', $partner->tiktok_url) }}" placeholder="https://tiktok.com/...">
                </div>

                <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
            </form>
        </div>
    </main>
</body>
</html>
