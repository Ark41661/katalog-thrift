<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login Mitra - {{ config('catalog.store_name') }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#111827;min-height:100vh;display:grid;place-items:center;}
        .card{width:min(440px,94%);background:#fff;padding:36px;}
        .logo{font-size:22px;font-weight:900;letter-spacing:2px;color:#111827;margin-bottom:4px;}
        .badge{display:inline-block;background:#111827;color:#fff;font-size:10px;font-weight:700;letter-spacing:2px;padding:3px 8px;margin-bottom:20px;}
        label{display:block;font-size:11px;font-weight:700;letter-spacing:1.5px;text-transform:uppercase;color:#374151;margin-bottom:6px;margin-top:16px;}
        input{width:100%;padding:11px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        input:focus{outline:2px solid #111827;outline-offset:-2px;}
        .btn{width:100%;padding:13px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;margin-top:20px;letter-spacing:.5px;}
        .btn:hover{background:#374151;}
        .error{background:#fee2e2;color:#991b1b;padding:10px 12px;font-size:13px;margin-bottom:16px;}
        .links{margin-top:16px;text-align:center;font-size:13px;color:#6b7280;}
        .links a{color:#111827;font-weight:700;}
        hr{border:0;border-top:1px solid #e5e7eb;margin:20px 0;}
    </style>
</head>
<body>
    <div class="card">
        <div class="logo">{{ config('catalog.store_name') }}</div>
        <span class="badge">PORTAL MITRA</span>

        @if($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('partner.login.submit') }}">
            @csrf
            <label>Email Mitra</label>
            <input type="email" name="email" value="{{ old('email') }}" required autofocus>
            <label>Password</label>
            <input type="password" name="password" required>
            <button type="submit" class="btn">Masuk ke Dashboard</button>
        </form>

        <hr>
        <div class="links">
            Belum terdaftar? <a href="{{ route('partner.register') }}">Daftar jadi mitra</a>
        </div>
        <div class="links" style="margin-top:8px;">
            <a href="{{ route('catalog.index') }}">← Kembali ke katalog</a>
        </div>
    </div>
</body>
</html>
