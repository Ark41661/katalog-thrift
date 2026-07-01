<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Jadi Mitra - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .main{width:min(680px,94%);margin:40px auto 60px;}
        .hero{background:#111827;padding:36px 40px;margin-bottom:24px;}
        .hero h1{font-size:clamp(28px,4vw,48px);font-weight:900;color:#fff;line-height:.95;margin-bottom:10px;}
        .hero p{color:#9ca3af;font-size:15px;}
        .section{background:#fff;border:1px solid #e5e7eb;padding:28px;margin-bottom:16px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:20px;padding-bottom:10px;border-bottom:1px solid #f1f5f9;}
        label{display:block;font-size:12px;font-weight:700;letter-spacing:.5px;color:#374151;margin-bottom:6px;margin-top:16px;}
        label:first-of-type{margin-top:0;}
        input[type=text],input[type=email],input[type=password],input[type=url],textarea{width:100%;padding:11px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{resize:vertical;min-height:80px;}
        .error-msg{color:#dc2626;font-size:12px;margin-top:4px;}
        .hint{font-size:12px;color:#9ca3af;margin-top:4px;}
        .btn{display:inline-block;padding:13px 28px;font-size:14px;font-weight:700;border:0;cursor:pointer;text-decoration:none;}
        .btn-dark{background:#111827;color:#fff;width:100%;}
        .btn-dark:hover{background:#374151;}
        .benefits{display:grid;grid-template-columns:1fr 1fr;gap:12px;margin-bottom:24px;}
        .benefit{background:#fff;border:1px solid #e5e7eb;padding:16px;}
        .benefit-icon{font-size:24px;margin-bottom:8px;}
        .benefit-title{font-size:14px;font-weight:700;margin-bottom:4px;}
        .benefit-desc{font-size:13px;color:#6b7280;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <a href="{{ route('catalog.index') }}" style="font-size:13px;color:#6b7280;text-decoration:none;">← Kembali ke katalog</a>
        </div>
    </header>

    <div class="main">
        <div class="hero">
            <h1>DAFTAR<br>JADI MITRA</h1>
            <p>Promosikan produk thrift & fashion kamu ke lebih banyak pembeli.</p>
        </div>

        <div class="benefits">
            <div class="benefit"><div class="benefit-icon">🆓</div><div class="benefit-title">Gratis</div><div class="benefit-desc">Tidak ada biaya pendaftaran atau komisi.</div></div>
            <div class="benefit"><div class="benefit-icon">📦</div><div class="benefit-title">Kelola Sendiri</div><div class="benefit-desc">Upload dan edit produk kapan saja.</div></div>
            <div class="benefit"><div class="benefit-icon">🔗</div><div class="benefit-title">Multi Channel</div><div class="benefit-desc">Link langsung ke Shopee, Tokopedia, WA.</div></div>
            <div class="benefit"><div class="benefit-icon">⭐</div><div class="benefit-title">Reputasi</div><div class="benefit-desc">Bangun rating toko dari review pembeli.</div></div>
        </div>

        @if($errors->any())
            <div style="background:#fee2e2;color:#991b1b;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #fecaca;">
                @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
            </div>
        @endif

        <form method="POST" action="{{ route('partner.register.submit') }}">
            @csrf

            <div class="section">
                <p class="section-title">Info Toko</p>
                <label>Nama Toko *</label>
                <input type="text" name="store_name" value="{{ old('store_name') }}" required placeholder="Contoh: Vintage Corner">
                @error('store_name')<p class="error-msg">{{ $message }}</p>@enderror

                <label>Deskripsi Toko</label>
                <textarea name="description" placeholder="Ceritakan tentang toko kamu...">{{ old('description') }}</textarea>

                <label>Lokasi</label>
                <input type="text" name="location" value="{{ old('location') }}" placeholder="Kota, Provinsi">

                <label>Nomor WhatsApp *</label>
                <input type="text" name="whatsapp" value="{{ old('whatsapp') }}" required placeholder="628xxx">
                @error('whatsapp')<p class="error-msg">{{ $message }}</p>@enderror
            </div>

            <div class="section">
                <p class="section-title">Akun Login</p>
                <label>Nama Pemilik *</label>
                <input type="text" name="owner_name" value="{{ old('owner_name') }}" required>
                @error('owner_name')<p class="error-msg">{{ $message }}</p>@enderror

                <label>Email *</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                @error('email')<p class="error-msg">{{ $message }}</p>@enderror

                <label>Password * <span style="font-weight:400;color:#9ca3af">(min. 8 karakter)</span></label>
                <input type="password" name="password" required>
                @error('password')<p class="error-msg">{{ $message }}</p>@enderror

                <label>Konfirmasi Password *</label>
                <input type="password" name="password_confirmation" required>
            </div>

            <div class="section">
                <p class="section-title">Link Toko (Opsional)</p>
                <label>Shopee</label>
                <input type="url" name="shopee_url" value="{{ old('shopee_url') }}" placeholder="https://shopee.co.id/...">

                <label>Tokopedia</label>
                <input type="url" name="tokopedia_url" value="{{ old('tokopedia_url') }}" placeholder="https://tokopedia.com/...">

                <label>Instagram</label>
                <input type="url" name="instagram_url" value="{{ old('instagram_url') }}" placeholder="https://instagram.com/...">
            </div>

            <button type="submit" class="btn btn-dark">Kirim Pendaftaran →</button>
            <p class="hint" style="text-align:center;margin-top:12px;">Pendaftaran akan diverifikasi admin dalam 1×24 jam.</p>
        </form>
    </div>
</body>
</html>
