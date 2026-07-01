<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Badges - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;overflow-y:auto;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav{padding:16px 0;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;margin-bottom:4px;}
        .head p{color:#6b7280;font-size:13px;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:20px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;padding-bottom:8px;border-bottom:1px solid #f1f5f9;}
        .badges-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:20px;}
        .badge-item{background:#f9fafb;border:1px solid #e5e7eb;padding:20px;text-align:center;}
        .badge-item .icon{font-size:32px;margin-bottom:6px;}
        .badge-item .name{font-size:13px;font-weight:700;}
        .badge-item .type{font-size:11px;color:#6b7280;}
        .badge-item .btn-remove{margin-top:8px;font-size:11px;color:#dc2626;background:none;border:0;cursor:pointer;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:10px 8px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;}
        th{font-weight:700;background:#f9fafb;}
        label{display:block;font-size:12px;font-weight:700;margin-bottom:4px;margin-top:12px;}
        input,select,textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;}
        textarea{resize:vertical;min-height:60px;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;border:0;cursor:pointer;}
        .btn-dark{background:#111827;color:#fff;}
        .btn-danger{background:#dc2626;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        .two-col{display:grid;grid-template-columns:1fr 1fr;gap:12px;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        form.logout-form button:hover{color:#fff;}
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
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
            <a href="{{ route('admin.badges') }}" class="active">🏆 Badges</a>
            <a href="{{ route('admin.notifications') }}">🔔 Notifikasi</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="head">
            <h1>🏆 Kelola Badges</h1>
            <p>Buat dan kelola badge untuk member.</p>
        </div>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <p class="section-title">Buat Badge Baru</p>
            <form method="POST" action="{{ route('admin.badges.store') }}">
                @csrf
                <div class="two-col">
                    <div>
                        <label>Nama Badge</label>
                        <input type="text" name="badge_name" placeholder="e.g. Thrift Enthusiast" required>
                    </div>
                    <div>
                        <label>Icon</label>
                        <input type="text" name="badge_icon" placeholder="e.g. 🏆, 🌟, 👑">
                    </div>
                </div>
                <label>Tipe Badge</label>
                <select name="badge_type">
                    <option value="community">Komunitas</option>
                    <option value="review">Review</option>
                    <option value="loyalty">Loyalitas</option>
                    <option value="special">Spesial</option>
                </select>
                <label>Kriteria (opsional)</label>
                <textarea name="criteria" placeholder="e.g. Member dengan 10+ review"></textarea>
                <button type="submit" class="btn btn-dark" style="margin-top:12px;">Simpan Badge</button>
            </form>
        </div>

        <div class="card">
            <p class="section-title">Semua Badge ({{ $badges->count() }})</p>
            @if($badges->isEmpty())
                <p style="color:#6b7280;">Belum ada badge.</p>
            @else
                <div class="badges-grid">
                    @foreach($badges as $b)
                    <div class="badge-item">
                        <div class="icon">{{ $b->badge_icon ?? '🏆' }}</div>
                        <div class="name">{{ $b->badge_name }}</div>
                        <div class="type">{{ $b->badge_type }}</div>
                        <form method="POST" action="{{ route('admin.badges.destroy', $b) }}" onsubmit="return confirm('Hapus badge ini?')">
                            @csrf @method('DELETE')
                            <button type="submit" class="btn-remove">✕ Hapus</button>
                        </form>
                    </div>
                    @endforeach
                </div>
            @endif
        </div>

        <div class="card">
            <p class="section-title">Berikan Badge ke Member</p>
            <form method="POST" action="{{ route('admin.badges.assign') }}">
                @csrf
                <div class="two-col">
                    <div>
                        <label>Pilih Member</label>
                        <select name="user_id" required>
                            <option value="">-- Pilih Member --</option>
                            @foreach($members as $m)
                            <option value="{{ $m->id }}">{{ $m->name }} ({{ $m->email }})</option>
                            @endforeach
                        </select>
                    </div>
                    <div>
                        <label>Pilih Badge</label>
                        <select name="badge_id" required>
                            <option value="">-- Pilih Badge --</option>
                            @foreach($badges as $b)
                            <option value="{{ $b->id }}">{{ $b->badge_name }}</option>
                            @endforeach
                        </select>
                    </div>
                </div>
                <button type="submit" class="btn btn-dark" style="margin-top:12px;">Berikan Badge</button>
            </form>
        </div>
    </main>
</body>
</html>
