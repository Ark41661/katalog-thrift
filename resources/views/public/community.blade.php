<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Komunitas - {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;flex-shrink:0;}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        .hero{background:var(--dark);padding:48px 0;}
        .hero-inner{width:min(1280px,94%);margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:32px;align-items:center;}
        .hero h1{font-size:clamp(32px,4vw,56px);font-weight:900;color:#fff;line-height:.92;margin-bottom:12px;}
        .hero h1 span{color:var(--accent);}
        .hero p{color:#9ca3af;font-size:15px;line-height:1.6;}
        .hero-cta{margin-top:20px;}
        .btn-primary{display:inline-block;padding:12px 24px;background:var(--accent);color:#fff;font-size:14px;font-weight:700;}
        .btn-primary:hover{background:#b91c1c;}
        .main{width:min(1280px,94%);margin:32px auto 60px;}
        /* UGC MASONRY */
        .ugc-grid{columns:4;column-gap:12px;}
        .ugc-item{break-inside:avoid;margin-bottom:12px;position:relative;overflow:hidden;background:var(--card);border:1px solid var(--border);}
        .ugc-item img{width:100%;display:block;transition:transform .3s;}
        .ugc-item:hover img{transform:scale(1.03);}
        .ugc-caption{padding:10px 12px;}
        .ugc-user{font-size:12px;font-weight:700;margin-bottom:2px;}
        .ugc-instagram{font-size:11px;color:#1d4ed8;}
        .ugc-text{font-size:12px;color:var(--muted);margin-top:4px;line-height:1.4;}
        .ugc-product{font-size:11px;color:var(--accent);font-weight:600;margin-top:4px;}
        /* SUBMIT FORM */
        .submit-section{background:var(--dark);padding:48px 0;margin-top:40px;}
        .submit-inner{width:min(1280px,94%);margin:0 auto;display:grid;grid-template-columns:1fr 1fr;gap:48px;align-items:start;}
        .submit-copy h2{font-size:clamp(24px,3vw,36px);font-weight:900;color:#fff;margin-bottom:12px;}
        .submit-copy h2 span{color:var(--accent);}
        .submit-copy p{color:#9ca3af;font-size:14px;line-height:1.6;margin-bottom:16px;}
        .submit-steps{display:flex;flex-direction:column;gap:10px;}
        .submit-step{display:flex;align-items:center;gap:10px;color:#d1d5db;font-size:13px;}
        .step-num{width:24px;height:24px;border-radius:50%;background:var(--accent);color:#fff;font-size:11px;font-weight:900;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .submit-form{background:#1f2937;border:1px solid #374151;padding:28px;}
        .submit-form h3{color:#fff;font-size:18px;font-weight:900;margin-bottom:16px;}
        .form-group{margin-bottom:12px;}
        .form-group label{display:block;font-size:11px;font-weight:700;letter-spacing:1px;text-transform:uppercase;color:#9ca3af;margin-bottom:6px;}
        .form-group input,.form-group textarea,.form-group select{width:100%;padding:10px 12px;background:#111827;border:1px solid #374151;color:#fff;font-size:14px;font-family:inherit;}
        .form-group input::placeholder,.form-group textarea::placeholder{color:#6b7280;}
        .form-group textarea{min-height:70px;resize:vertical;}
        .form-submit{width:100%;padding:12px;background:var(--accent);color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;margin-top:4px;}
        .form-submit:hover{background:#b91c1c;}
        .alert-success{background:#dcfce7;color:#166534;padding:10px 14px;font-size:13px;margin-bottom:12px;border:1px solid #bbf7d0;}
        .footer{background:var(--dark);padding:24px 0;}
        .footer-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;flex-wrap:wrap;gap:12px;}
        .footer-brand{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .footer-copy{font-size:12px;color:#4b5563;}
        @media(max-width:860px){.ugc-grid{columns:2;}.hero-inner{grid-template-columns:1fr;}.submit-inner{grid-template-columns:1fr;}}
        @media(max-width:540px){.ugc-grid{columns:1;}}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => 'komunitas', 'storeName' => $storeName])

    <section class="hero">
        <div class="hero-inner">
            <div>
                <h1>Komunitas <span>ThriftHub</span></h1>
                <p>Foto nyata dari pelanggan yang memakai produk dari platform kami. Kamu juga bisa kirim foto dan tampil di sini!</p>
                <div class="hero-cta">
                    <a href="#kirim-foto" class="btn-primary">📸 Kirim Foto Kamu</a>
                </div>
            </div>
            <div style="display:grid;grid-template-columns:1fr 1fr;gap:8px;">
                @foreach($photos->take(4) as $ugc)
                    <img src="{{ $ugc->photo_url }}" alt="{{ $ugc->submitter_name }}" style="width:100%;aspect-ratio:1;object-fit:cover;border:2px solid #1f2937;" loading="lazy">
                @endforeach
            </div>
        </div>
    </section>

    <div class="main">
        @if($photos->isEmpty())
            <div style="text-align:center;padding:80px 20px;color:var(--muted);">
                <p style="font-size:48px;margin-bottom:16px;">📸</p>
                <p style="font-size:18px;font-weight:700;color:var(--text);margin-bottom:8px;">Belum ada foto komunitas</p>
                <p>Jadilah yang pertama kirim foto kamu!</p>
            </div>
        @else
            <div class="ugc-grid">
                @foreach($photos as $ugc)
                <div class="ugc-item">
                    <img src="{{ $ugc->photo_url }}" alt="{{ $ugc->submitter_name }}" loading="lazy">
                    <div class="ugc-caption">
                        <p class="ugc-user">{{ $ugc->submitter_name }}</p>
                        @if($ugc->submitter_instagram)
                            <p class="ugc-instagram">{{ '@' . ltrim($ugc->submitter_instagram, '@') }}</p>
                        @endif
                        @if($ugc->caption)<p class="ugc-text">{{ $ugc->caption }}</p>@endif
                        @if($ugc->product)
                            <a href="{{ route('catalog.show', $ugc->product->slug) }}" class="ugc-product">🏷 {{ $ugc->product->name }}</a>
                        @endif
                    </div>
                </div>
                @endforeach
            </div>
            <div style="margin-top:20px;">{{ $photos->links() }}</div>
        @endif
    </div>

    {{-- SUBMIT FORM --}}
    <section class="submit-section" id="kirim-foto">
        <div class="submit-inner">
            <div class="submit-copy">
                <h2>Tampil di <span>Komunitas</span></h2>
                <p>Kirim foto kamu memakai produk dari ThriftHub dan dapatkan kesempatan tampil di halaman komunitas kami.</p>
                <div class="submit-steps">
                    <div class="submit-step"><span class="step-num">1</span>Beli produk dari salah satu toko mitra kami</div>
                    <div class="submit-step"><span class="step-num">2</span>Foto diri kamu memakai produk tersebut</div>
                    <div class="submit-step"><span class="step-num">3</span>Kirim foto via form ini</div>
                    <div class="submit-step"><span class="step-num">4</span>Foto akan ditampilkan setelah disetujui admin</div>
                </div>
            </div>
            <div class="submit-form">
                <h3>📸 Kirim Foto</h3>
                @if(session('ugc_success'))
                    <div class="alert-success">{{ session('ugc_success') }}</div>
                @endif
                @if($errors->any())
                    <div style="background:#fee2e2;color:#991b1b;padding:10px 14px;font-size:13px;margin-bottom:12px;border:1px solid #fecaca;">
                        @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
                    </div>
                @endif
                <form method="POST" action="{{ route('ugc.submit') }}" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group">
                        <label>Nama Kamu *</label>
                        <input type="text" name="submitter_name" required placeholder="Nama lengkap">
                    </div>
                    <div class="form-group">
                        <label>Instagram (opsional)</label>
                        <input type="text" name="submitter_instagram" placeholder="username tanpa @">
                    </div>
                    <div class="form-group">
                        <label>Produk yang Dipakai (opsional)</label>
                        <select name="product_id">
                            <option value="">-- Pilih Produk --</option>
                            @foreach($products as $p)
                                <option value="{{ $p->id }}">{{ $p->name }} ({{ $p->partner?->store_name }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="form-group">
                        <label>Foto * (maks 10MB, format JPG/PNG/WEBP)</label>
                        <input type="file" name="photo" accept="image/*" required style="color:#9ca3af;">
                    </div>
                    <div class="form-group">
                        <label>Caption (opsional, maks 1000 karakter)</label>
                        <textarea name="caption" placeholder="Ceritakan tentang outfit kamu..." maxlength="1000"></textarea>
                    </div>
                    <button type="submit" class="form-submit">Kirim Foto →</button>
                </form>
            </div>
        </div>
    </section>

    <footer class="footer">
        <div class="footer-inner">
            <span class="footer-brand">{{ $storeName }}</span>
            <span class="footer-copy">© {{ date('Y') }} {{ $storeName }}</span>
        </div>
    </footer>
</body>
</html>
