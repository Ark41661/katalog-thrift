<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas & Testimoni - {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text); line-height: 1.6;}
        a{text-decoration:none;color:inherit;}
        
        /* TOPBAR */
        .topbar{position:sticky;top:0;z-index:100;background:#fff;border-bottom:1px solid var(--border);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;color:var(--text);}
        .topbar-nav{display:flex;gap:20px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        
        /* HEADER */
        .page-header { background: #fff; border-bottom: 1px solid var(--border); padding: 48px 24px; text-align: center; }
        .page-title { font-size: 32px; font-weight: 900; margin-bottom: 12px; }
        .page-desc { color: var(--muted); max-width: 600px; margin: 0 auto; font-size: 16px; }
        
        /* CONTENT */
        .content-wrap { width: min(1280px, 94%); margin: 40px auto; display: grid; grid-template-columns: 2fr 1fr; gap: 40px; align-items: start; }
        @media(max-width: 960px) { .content-wrap { grid-template-columns: 1fr; flex-direction: column-reverse; display: flex; } }
        
        /* MASONRY GALLERY */
        .masonry { column-count: 3; column-gap: 20px; }
        @media(max-width: 768px) { .masonry { column-count: 2; } }
        @media(max-width: 480px) { .masonry { column-count: 1; } }
        .ugc-card { break-inside: avoid; margin-bottom: 20px; background: #fff; border: 1px solid var(--border); overflow: hidden; }
        .ugc-card img { width: 100%; display: block; object-fit: cover; }
        .ugc-info { padding: 14px; }
        .ugc-caption { font-size: 14px; color: var(--text); margin-bottom: 8px; }
        .ugc-author { font-size: 12px; font-weight: 700; color: var(--muted); }
        .ugc-product { display: block; margin-top: 10px; font-size: 11px; padding: 6px 10px; background: #f9fafb; border: 1px solid var(--border); color: var(--accent); font-weight: 700; text-align: center; }
        
        /* FORM SUBMIT */
        .submit-panel { background: #fff; border: 1px solid var(--border); padding: 32px 24px; position: sticky; top: 80px; }
        .submit-panel h3 { font-size: 20px; font-weight: 900; margin-bottom: 16px; }
        .form-group { margin-bottom: 16px; }
        .form-group label { display: block; font-size: 12px; font-weight: 700; margin-bottom: 6px; color: var(--muted); }
        .form-group input, .form-group textarea { width: 100%; padding: 12px; border: 1px solid var(--border); font-family: inherit; font-size: 14px; }
        .btn-submit { width: 100%; background: var(--dark); color: #fff; border: 0; padding: 14px; font-weight: 700; cursor: pointer; transition: background .2s; }
        .btn-submit:hover { background: #374151; }
        .alert-success { background: #dcfce7; color: #166534; padding: 12px; margin-bottom: 20px; border: 1px solid #bbf7d0; font-size: 14px; font-weight: 700; }
        
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('catalog.lookbook') }}">Lookbook</a>
                <a href="{{ route('articles.index') }}">Editorial</a>
                <a href="{{ route('ugc.index') }}" class="active">Komunitas</a>
            </nav>
        </div>
    </header>

    <div class="page-header">
        <h1 class="page-title">Komunitas & Testimoni</h1>
        <p class="page-desc">Lihat bagaimana pelanggan kami merayakan gaya hidup slow fashion. Bagikan foto outfit Anda dan jadilah inspirasi bagi orang lain!</p>
    </div>

    <div class="content-wrap">
        <div>
            @if($photos->isEmpty())
                <div style="text-align:center; padding: 60px 20px; border: 1px dashed var(--border);">
                    <p style="font-size: 32px; margin-bottom: 10px;">📸</p>
                    <h3 style="font-size: 18px; margin-bottom: 5px;">Belum Ada Foto</h3>
                    <p style="color: var(--muted); font-size: 14px;">Jadilah yang pertama membagikan outfit Anda!</p>
                </div>
            @else
                <div class="masonry">
                    @foreach($photos as $photo)
                    <div class="ugc-card">
                        <img src="{{ asset('storage/' . $photo->photo) }}" alt="UGC by {{ $photo->submitter_name }}">
                        <div class="ugc-info">
                            @if($photo->caption)
                                <p class="ugc-caption">"{{ $photo->caption }}"</p>
                            @endif
                            <p class="ugc-author">— {{ $photo->submitter_name }} @if($photo->submitter_instagram) <span style="font-weight:400; color:var(--muted);">(IG: {{ $photo->submitter_instagram }})</span>@endif</p>
                            @if($photo->product)
                                <a href="{{ route('catalog.show', $photo->product->slug) }}" class="ugc-product">Lihat Produk: {{ $photo->product->name }}</a>
                            @endif
                        </div>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div>
            <div class="submit-panel">
                @if(session('ugc_success'))
                    <div class="alert-success">{{ session('ugc_success') }}</div>
                @endif
                <h3>Kirim Foto Anda</h3>
                <p style="font-size: 13px; color: var(--muted); margin-bottom: 20px;">Punya foto keren dengan produk kami? Kirimkan di sini, admin akan memilih foto terbaik untuk ditampilkan.</p>
                
                <form action="{{ route('ugc.submit') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Nama Anda *</label>
                        <input type="text" name="submitter_name" required>
                    </div>
                    <div class="form-group">
                        <label>Username Instagram (Opsional)</label>
                        <input type="text" name="submitter_instagram" placeholder="@username">
                    </div>
                    <div class="form-group">
                        <label>Upload Foto *</label>
                        <input type="file" name="photo" accept="image/*" required>
                    </div>
                    <div class="form-group">
                        <label>Cerita/Caption (Opsional)</label>
                        <textarea name="caption" rows="3" placeholder="Ceritakan gaya Anda..."></textarea>
                    </div>
                    <button type="submit" class="btn-submit">Kirim Foto</button>
                </form>
            </div>
        </div>
    </div>

</body>
</html>
