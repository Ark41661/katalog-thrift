<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pengaturan Landing - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .wrap{max-width:640px;}
        .head{margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .head p{font-size:13px;color:#6b7280;margin-top:4px;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=text],input[type=url],textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{resize:vertical;min-height:80px;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .btn{display:inline-block;padding:11px 20px;font-size:14px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .form-actions{display:flex;gap:10px;margin-top:4px;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .preview-hero{background:#111827;padding:24px;margin-top:12px;border-radius:4px;}
        .preview-title{font-size:28px;font-weight:900;color:#fff;line-height:.92;margin-bottom:8px;}
        .preview-sub{font-size:14px;color:#9ca3af;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="name">{{ $storeName }}</div><div class="role">Super Admin</div></div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.articles.index') }}">📖 Editorial</a>
            <a href="{{ route('admin.ugc.index') }}">📸 Komunitas UGC</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="wrap">
            <div class="head">
                <h1>Pengaturan Landing Page</h1>
                <p>Ubah teks hero dan gambar cover halaman utama.</p>
            </div>

            @if(session('success'))<div class="alert-success">{{ session('success') }}</div>@endif

            <form method="POST" action="{{ route('admin.landing.update') }}">
                @csrf
                <div class="section">
                    <p class="section-title">Hero Section</p>

                    <label>Judul Hero</label>
                    <input type="text" name="hero_title" value="{{ env('LANDING_HERO_TITLE', config('catalog.catalog_title')) }}" required
                           oninput="document.getElementById('prev-title').textContent = this.value">
                    <p class="hint">Gunakan Enter (\\n) untuk baris baru. Contoh: THRIFT\nHUB</p>

                    <label>Subtitle</label>
                    <textarea name="hero_subtitle" oninput="document.getElementById('prev-sub').textContent = this.value">{{ env('LANDING_HERO_SUBTITLE', config('catalog.store_tagline')) }}</textarea>

                    <label>Teks Tombol CTA</label>
                    <input type="text" name="hero_cta_text" value="{{ env('LANDING_HERO_CTA_TEXT', 'Lihat Katalog') }}">

                    <label>URL Gambar Cover</label>
                    <input type="url" name="hero_image" value="{{ env('LANDING_HERO_IMAGE', config('catalog.cover_image')) }}" placeholder="https://...">

                    <p class="hint" style="margin-top:12px;">Preview:</p>
                    <div class="preview-hero">
                        <div class="preview-title" id="prev-title">{{ env('LANDING_HERO_TITLE', config('catalog.catalog_title')) }}</div>
                        <div class="preview-sub" id="prev-sub">{{ env('LANDING_HERO_SUBTITLE', config('catalog.store_tagline')) }}</div>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark">Simpan Perubahan</button>
                    <a href="{{ route('landing') }}" target="_blank" class="btn btn-outline">Lihat Landing →</a>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
