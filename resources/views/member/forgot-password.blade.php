<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Lupa Password - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .main{width:min(420px,94%);margin:60px auto;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:32px;}
        .card h1{font-size:24px;font-weight:900;margin-bottom:8px;}
        .card p{color:#6b7280;font-size:14px;margin-bottom:20px;line-height:1.5;}
        label{display:block;font-size:12px;font-weight:700;margin-bottom:6px;}
        input[type=email]{width:100%;padding:11px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        .btn{width:100%;padding:13px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;margin-top:16px;}
        .btn:hover{background:#374151;}
        .link-back{display:block;text-align:center;margin-top:16px;font-size:13px;color:#6b7280;text-decoration:none;}
        .link-back:hover{color:#111827;}
        .success-box{background:#dcfce7;color:#166534;padding:12px;font-size:13px;border:1px solid #bbf7d0;margin-bottom:16px;word-break:break-all;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
        </div>
    </header>
    <div class="main">
        <div class="card">
            <h1>Lupa Password</h1>
            <p>Masukkan email yang terdaftar. Kami akan kirim link reset password.</p>
            @if(session('success'))
                <div class="success-box">{{ session('success') }}</div>
            @endif
            @if(session('reset_link') && app()->environment('local'))
                <div class="success-box">
                    <strong>Link reset (local test):</strong><br>
                    <a href="{{ session('reset_link') }}" style="color:#166534;">{{ session('reset_link') }}</a>
                </div>
            @endif
            @if($errors->any())
                <div style="background:#fee2e2;color:#991b1b;padding:12px;font-size:13px;margin-bottom:16px;border:1px solid #fecaca;">
                    @foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach
                </div>
            @endif
            <form method="POST" action="{{ route('member.forgot.submit') }}">
                @csrf
                <label>Email</label>
                <input type="email" name="email" value="{{ old('email') }}" required>
                <button type="submit" class="btn">Kirim Link Reset</button>
            </form>
            <a href="{{ route('member.login') }}" class="link-back">← Kembali ke Login</a>
        </div>
    </div>
</body>
</html>
