<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pertanyaan - {{ $partner->store_name }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#111827;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1f2937;}
        .sidebar-brand .name{font-size:14px;font-weight:900;letter-spacing:1px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#6b7280;margin-top:2px;}
        .sidebar-nav{padding:16px 0;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#9ca3af;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1f2937;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1f2937;}
        .main{margin-left:220px;padding:28px 32px;}
        .head h1{font-size:22px;font-weight:900;margin-bottom:4px;}
        .head p{color:#6b7280;font-size:13px;margin-bottom:20px;}
        .list{display:flex;flex-direction:column;gap:12px;}
        .item{background:#fff;border:1px solid #e5e7eb;padding:20px;}
        .q-header{display:flex;justify-content:space-between;align-items:flex-start;margin-bottom:8px;}
        .q-user{font-size:14px;font-weight:700;}
        .q-product{font-size:12px;color:#dc2626;}
        .q-product a{color:#dc2626;}
        .q-date{font-size:12px;color:#6b7280;}
        .q-text{font-size:14px;color:#374151;margin-bottom:12px;padding:12px;background:#f9fafb;border-left:3px solid #e5e7eb;}
        .q-answer{background:#f0fdf4;padding:12px;margin-bottom:10px;border-left:3px solid #16a34a;}
        .q-answer .label{font-size:11px;font-weight:700;color:#16a34a;margin-bottom:4px;}
        .q-answer p{font-size:14px;color:#374151;}
        textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;resize:vertical;min-height:60px;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .empty{text-align:center;padding:60px;color:#6b7280;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#9ca3af;font-size:13px;font-weight:600;cursor:pointer;}
    </style>
</head>
<body>
    <aside class="sidebar">
        <div class="sidebar-brand">
            <div class="name">{{ $partner->store_name }}</div>
            <div class="role">Portal Mitra</div>
        </div>
        <nav class="sidebar-nav">
            <a href="{{ route('partner.dashboard') }}">📊 Dashboard</a>
            <a href="{{ route('partner.products.index') }}">📦 Produk Saya</a>
            <a href="{{ route('partner.analytics') }}">📈 Analitik</a>
            <a href="{{ route('partner.questions.index') }}" class="active">❓ Pertanyaan</a>
            <a href="{{ route('partner.notifications') }}">🔔 Notifikasi</a>
            <a href="{{ route('partner.profile') }}">🏪 Profil Toko</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('partner.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="head">
            <h1>❓ Pertanyaan Produk</h1>
            <p>Jawab pertanyaan dari pembeli tentang produk kamu.</p>
        </div>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if($questions->isEmpty())
            <div class="empty"><p>Belum ada pertanyaan dari pembeli.</p></div>
        @else
            <div class="list">
                @foreach($questions as $q)
                <div class="item">
                    <div class="q-header">
                        <div>
                            <span class="q-user">{{ $q->user->name }}</span>
                            <span class="q-product">bertanya tentang <a href="{{ route('catalog.show', $q->product->slug) }}" target="_blank">{{ $q->product->name }}</a></span>
                        </div>
                        <span class="q-date">{{ $q->created_at->diffForHumans() }}</span>
                    </div>
                    <div class="q-text">{{ $q->question }}</div>
                    @if($q->answer)
                        <div class="q-answer">
                            <div class="label">✓ Jawabanmu</div>
                            <p>{{ $q->answer }}</p>
                        </div>
                    @else
                        <form method="POST" action="{{ route('partner.questions.answer', $q) }}">
                            @csrf @method('PUT')
                            <textarea name="answer" placeholder="Tulis jawaban..." required></textarea>
                            <button type="submit" class="btn btn-dark" style="margin-top:8px;">Kirim Jawaban</button>
                        </form>
                    @endif
                </div>
                @endforeach
            </div>
            <div style="margin-top:16px;">{{ $questions->links() }}</div>
        @endif
    </main>
</body>
</html>
