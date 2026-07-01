<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tulis Artikel - Admin</title>
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
        .wrap{max-width:800px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=text],input[type=url],select{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{width:100%;padding:12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;resize:vertical;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .checkbox-row{display:flex;align-items:center;gap:8px;margin-top:12px;}
        .checkbox-row input{width:auto;}
        .checkbox-row label{margin:0;font-weight:600;}
        .btn{display:inline-block;padding:11px 20px;font-size:14px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .form-actions{display:flex;gap:10px;margin-top:4px;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        /* Live preview */
        .preview-box{background:#f9fafb;border:1px solid #e5e7eb;padding:16px;margin-top:8px;font-size:14px;color:#374151;line-height:1.8;white-space:pre-line;min-height:100px;max-height:300px;overflow-y:auto;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand"><div class="name">{{ $storeName }}</div><div class="role">Super Admin</div></div>
        <nav class="sidebar-nav">
            <a href="{{ route('admin.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('admin.articles.index') }}" class="active">📖 Editorial</a>
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
                <h1>Tulis Artikel Baru</h1>
                <a href="{{ route('admin.articles.index') }}" class="btn btn-outline">← Kembali</a>
            </div>
            <form method="POST" action="{{ route('admin.articles.store') }}" enctype="multipart/form-data">
                @csrf
                <div class="section">
                    <p class="section-title">Info Artikel</p>
                    <label>Judul Artikel</label>
                    <input type="text" name="title" value="{{ old('title') }}" required placeholder="Contoh: 5 Cara Styling Hoodie untuk Sehari-hari">

                    <label>Kategori</label>
                    <select name="category" required>
                        <option value="">-- Pilih Kategori --</option>
                        @foreach($categories as $val => $label)
                            <option value="{{ $val }}" {{ old('category') === $val ? 'selected' : '' }}>{{ $label }}</option>
                        @endforeach
                    </select>

                    <label>Penulis</label>
                    <input type="text" name="author" value="{{ old('author', 'Admin') }}">

                    <label>Cover Image</label>
                    <p class="hint" style="margin-bottom:10px;">Upload file gambar (JPG/PNG/WEBP, maks 10MB). <strong>Jangan paste link Gemini</strong> — download dulu lalu upload.</p>
                    @error('cover_file')<p style="color:#dc2626;font-size:12px;margin-bottom:8px;">{{ $message }}</p>@enderror
                    @error('cover_image')<p style="color:#dc2626;font-size:12px;margin-bottom:8px;">{{ $message }}</p>@enderror
                    <div style="display:flex;gap:0;margin-bottom:10px;border:1px solid #e5e7eb;">
                        <button type="button" id="tab-file-btn" onclick="switchCoverTab('file')" style="flex:1;padding:8px;font-size:12px;font-weight:700;border:0;background:#111827;color:#fff;cursor:pointer;">Upload File</button>
                        <button type="button" id="tab-url-btn" onclick="switchCoverTab('url')" style="flex:1;padding:8px;font-size:12px;font-weight:700;border:0;background:#f9fafb;color:#6b7280;cursor:pointer;">Pakai URL</button>
                    </div>
                    <div id="cover-file-wrap">
                        <input type="file" name="cover_file" accept="image/jpeg,image/png,image/webp,image/gif" onchange="previewCover(this)" style="width:100%;padding:8px;border:1px solid #d1d5db;font-size:13px;">
                        <p class="hint">File besar akan otomatis dikompresi saat disimpan.</p>
                        <img id="cover-preview" src="" alt="Preview" style="margin-top:8px;max-width:200px;max-height:120px;object-fit:cover;border:1px solid #e5e7eb;display:none;">
                    </div>
                    <div id="cover-url-wrap" style="display:none;">
                        <input type="url" name="cover_image" value="{{ old('cover_image') }}" placeholder="https://..." oninput="previewCoverUrl(this.value)">
                        <img id="cover-url-preview" src="" alt="Preview" style="margin-top:8px;max-width:200px;max-height:120px;object-fit:cover;border:1px solid #e5e7eb;display:none;">
                    </div>

                    <label>Excerpt / Ringkasan <span style="font-weight:400;color:#9ca3af">(maks 300 karakter)</span></label>
                    <textarea name="excerpt" style="min-height:60px;" maxlength="300">{{ old('excerpt') }}</textarea>
                </div>

                <div class="section">
                    <p class="section-title">Isi Artikel</p>
                    <p class="hint" style="margin-bottom:10px;">Tulis artikel dengan teks biasa. Tekan Enter untuk baris baru. Tidak perlu HTML.</p>
                    <textarea name="content" style="min-height:400px;" required oninput="updatePreview(this.value)" placeholder="Tulis isi artikel di sini...&#10;&#10;Contoh:&#10;Hoodie adalah salah satu item fashion yang paling versatile...&#10;&#10;1. Padukan dengan celana cargo&#10;Celana cargo memberikan kesan streetwear yang kuat...">{{ old('content') }}</textarea>
                    <p class="hint">Preview:</p>
                    <div class="preview-box" id="preview">Preview akan muncul di sini...</div>
                </div>

                <div class="section">
                    <p class="section-title">Pengaturan</p>
                    <div class="checkbox-row">
                        <input type="checkbox" id="is_published" name="is_published" value="1" {{ old('is_published') ? 'checked' : '' }}>
                        <label for="is_published">Terbitkan sekarang (tampil ke publik)</label>
                    </div>
                </div>

                <div class="form-actions">
                    <button type="submit" class="btn btn-dark">Simpan Artikel</button>
                    <a href="{{ route('admin.articles.index') }}" class="btn btn-outline">Batal</a>
                </div>
            </form>
        </div>
    </main>
    <script>
        function updatePreview(text) {
            document.getElementById('preview').textContent = text || 'Preview akan muncul di sini...';
        }
        function switchCoverTab(tab) {
            const isFile = tab === 'file';
            document.getElementById('cover-file-wrap').style.display = isFile ? 'block' : 'none';
            document.getElementById('cover-url-wrap').style.display  = isFile ? 'none'  : 'block';
            document.getElementById('tab-file-btn').style.background = isFile ? '#111827' : '#f9fafb';
            document.getElementById('tab-file-btn').style.color      = isFile ? '#fff'    : '#6b7280';
            document.getElementById('tab-url-btn').style.background  = isFile ? '#f9fafb' : '#111827';
            document.getElementById('tab-url-btn').style.color       = isFile ? '#6b7280' : '#fff';
        }
        function previewCover(input) {
            const img = document.getElementById('cover-preview');
            if (input.files && input.files[0]) {
                img.src = URL.createObjectURL(input.files[0]);
                img.style.display = 'block';
            }
        }
        function previewCoverUrl(url) {
            const img = document.getElementById('cover-url-preview');
            if (url) { img.src = url; img.style.display = 'block'; img.onerror = () => img.style.display = 'none'; }
            else img.style.display = 'none';
        }
    </script>
</body>
</html>
