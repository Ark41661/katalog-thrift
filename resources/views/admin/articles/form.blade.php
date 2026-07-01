<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $article->exists ? 'Edit Artikel' : 'Tambah Artikel' }} - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{margin-bottom:24px;}
        .head h1{font-size:22px;font-weight:900;}
        .head a{color:#2563eb;font-size:13px;text-decoration:none;margin-top:4px;display:inline-block;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:24px;max-width:900px;}
        .form-grid{display:grid;grid-template-columns:2fr 1fr;gap:24px;}
        @media(max-width:800px){.form-grid{grid-template-columns:1fr;}}
        .form-group{margin-bottom:20px;}
        .form-group label{display:block;font-size:13px;font-weight:700;margin-bottom:6px;color:#374151;}
        .form-group input[type=text],.form-group input[type=file],.form-group select,.form-group textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;border-radius:4px;font-size:14px;font-family:inherit;}
        .form-group select[multiple]{height:150px;}
        .form-help{font-size:11px;color:#6b7280;margin-top:4px;}
        .text-red{color:#dc2626;}
        .btn{display:inline-block;padding:10px 16px;font-size:13px;font-weight:700;text-decoration:none;border:0;cursor:pointer;width:100%;text-align:center;}
        .btn-primary{background:#111827;color:#fff;}
        .btn-primary:hover{background:#374151;}
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
            <a href="{{ route('admin.articles.index') }}" class="active">📝 Editorial / Blog</a>
            <a href="{{ route('admin.ugc.index') }}">📸 Komunitas (UGC)</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head">
            <h1>{{ $article->exists ? 'Edit Artikel' : 'Tambah Artikel Baru' }}</h1>
            <a href="{{ route('admin.articles.index') }}">&larr; Kembali ke Daftar Artikel</a>
        </div>

        <div class="card">
            <form action="{{ $article->exists ? route('admin.articles.update', $article) : route('admin.articles.store') }}" method="POST" enctype="multipart/form-data">
                @csrf
                @if($article->exists) @method('PUT') @endif
                
                <div class="form-grid">
                    <div>
                        <div class="form-group">
                            <label>Judul Artikel <span class="text-red">*</span></label>
                            <input type="text" name="title" value="{{ old('title', $article->title ?? '') }}" required>
                            @error('title') <p class="text-red" style="font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label>Isi Konten (HTML) <span class="text-red">*</span></label>
                            <textarea name="content" rows="18" style="font-family:monospace;" required>{{ old('content', $article->content ?? '') }}</textarea>
                            <p class="form-help">Gunakan tag HTML dasar seperti &lt;h2&gt;, &lt;p&gt;, &lt;ul&gt; untuk format tulisan.</p>
                            @error('content') <p class="text-red" style="font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>
                        
                        <div class="form-group">
                            <label>Kutipan Singkat (Excerpt)</label>
                            <textarea name="excerpt" rows="3">{{ old('excerpt', $article->excerpt ?? '') }}</textarea>
                            <p class="form-help">Muncul di daftar artikel. Biarkan kosong untuk generate otomatis dari konten.</p>
                        </div>
                    </div>

                    <div>
                        <div class="form-group">
                            <label>Kategori <span class="text-red">*</span></label>
                            <select name="category" required>
                                <option value="">Pilih Kategori</option>
                                @foreach(['mix-match' => 'Mix & Match', 'tips-perawatan' => 'Tips Perawatan', 'tren' => 'Tren Fashion', 'panduan' => 'Panduan Ukuran'] as $val => $label)
                                    <option value="{{ $val }}" {{ old('category', $article->category ?? '') == $val ? 'selected' : '' }}>{{ $label }}</option>
                                @endforeach
                            </select>
                            @error('category') <p class="text-red" style="font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label>Cover Image</label>
                            @if($article->exists && $article->cover_image)
                                <div style="margin-bottom:8px;">
                                    <img src="{{ Str::startsWith($article->cover_image, 'http') ? $article->cover_image : asset('storage/'.$article->cover_image) }}" style="width:100%;height:120px;object-fit:cover;border:1px solid #e5e7eb;">
                                </div>
                            @endif
                            <input type="file" name="cover_image" accept="image/*">
                            @error('cover_image') <p class="text-red" style="font-size:12px;margin-top:4px;">{{ $message }}</p> @enderror
                        </div>

                        <div class="form-group">
                            <label>Status Publikasi</label>
                            <select name="is_published">
                                <option value="1" {{ old('is_published', $article->is_published ?? 0) == 1 ? 'selected' : '' }}>Published (Publik)</option>
                                <option value="0" {{ old('is_published', $article->is_published ?? 0) == 0 ? 'selected' : '' }}>Draft (Disembunyikan)</option>
                            </select>
                        </div>

                        <div class="form-group">
                            <label>Produk Terkait (Opsional)</label>
                            <select name="related_product_ids[]" multiple>
                                @php $selectedProducts = old('related_product_ids', $article->related_product_ids ?? []); @endphp
                                @foreach($products as $p)
                                    <option value="{{ $p->id }}" {{ in_array($p->id, $selectedProducts) ? 'selected' : '' }}>{{ $p->name }} (Rp {{ number_format($p->price, 0, ',', '.') }})</option>
                                @endforeach
                            </select>
                            <p class="form-help">Tahan CTRL/CMD untuk memilih lebih dari satu.</p>
                        </div>
                        
                        <div style="padding-top:16px;border-top:1px solid #e5e7eb;">
                            <button type="submit" class="btn btn-primary">Simpan Artikel</button>
                        </div>
                    </div>
                </div>
            </form>
        </div>
    </main>
</body>
</html>
