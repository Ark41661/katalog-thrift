<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pendaftaran Terkirim - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;min-height:100vh;display:grid;place-items:center;}
        .card{width:min(480px,94%);background:#fff;border:1px solid #e5e7eb;padding:48px 40px;text-align:center;}
        .icon{font-size:64px;margin-bottom:20px;}
        h1{font-size:28px;font-weight:900;margin-bottom:10px;}
        p{color:#6b7280;font-size:15px;line-height:1.6;margin-bottom:8px;}
        .steps{text-align:left;background:#f9fafb;border:1px solid #e5e7eb;padding:20px;margin:24px 0;border-radius:4px;}
        .step{display:flex;gap:12px;align-items:flex-start;margin-bottom:12px;}
        .step:last-child{margin-bottom:0;}
        .step-num{width:24px;height:24px;background:#111827;color:#fff;border-radius:50%;font-size:12px;font-weight:700;display:flex;align-items:center;justify-content:center;flex-shrink:0;}
        .step-text{font-size:13px;color:#374151;padding-top:3px;}
        .btn{display:inline-block;padding:12px 24px;background:#111827;color:#fff;font-size:14px;font-weight:700;text-decoration:none;margin-top:8px;}
        .btn:hover{background:#374151;}
    </style>
</head>
<body>
    <div class="card">
        <div class="icon">🎉</div>
        <h1>Pendaftaran Terkirim!</h1>
        <p>Terima kasih telah mendaftar sebagai mitra <strong>{{ $storeName }}</strong>.</p>

        <div class="steps">
            <div class="step"><span class="step-num">1</span><span class="step-text">Pendaftaran kamu sedang ditinjau oleh tim admin kami.</span></div>
            <div class="step"><span class="step-num">2</span><span class="step-text">Proses verifikasi membutuhkan waktu 1×24 jam.</span></div>
            <div class="step"><span class="step-num">3</span><span class="step-text">Setelah disetujui, kamu bisa login ke portal mitra dan mulai upload produk.</span></div>
        </div>

        <a href="{{ route('catalog.index') }}" class="btn">← Kembali ke Katalog</a>
    </div>
</body>
</html>
