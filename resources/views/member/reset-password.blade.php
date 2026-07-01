<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Reset Password - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .main{width:min(420px,94%);margin:60px auto;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:32px;}
        .card h1{font-size:24px;font-weight:900;margin-bottom:20px;}
        label{display:block;font-size:12px;font-weight:700;margin-bottom:6px;margin-top:14px;}
        label:first-of-type{margin-top:0;}
        input[type=password]{width:100%;padding:11px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        .btn{width:100%;padding:13px;background:#111827;color:#fff;border:0;font-size:14px;font-weight:700;cursor:pointer;margin-top:20px;}
        .error-box{background:#fee2e2;color:#991b1b;padding:12px;font-size:13px;margin-bottom:16px;border:1px solid #fecaca;}
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
            <h1>Reset Password</h1>
            @if($errors->any())
                <div class="error-box">@foreach($errors->all() as $e)<p>• {{ $e }}</p>@endforeach</div>
            @endif
            <form method="POST" action="{{ route('member.reset.submit') }}">
                @csrf
                <input type="hidden" name="token" value="{{ $token }}">
                <label>Email</label>
                <input type="email" name="email" value="{{ $email }}" readonly>
                <label>Password Baru <span style="font-weight:400;color:#9ca3af">(min. 8 karakter)</span></label>
                <input type="password" name="password" required>
                <label>Konfirmasi Password</label>
                <input type="password" name="password_confirmation" required>
                <button type="submit" class="btn">Reset Password</button>
            </form>
        </div>
    </div>
</body>
</html>
