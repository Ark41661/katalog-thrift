<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - Admin</title>
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
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .btn-small{padding:8px 14px;font-size:12px;color:#111827;background:#fff;border:1px solid #e5e7eb;cursor:pointer;font-weight:600;}
        .list{display:flex;flex-direction:column;gap:8px;}
        .item{background:#fff;border:1px solid #e5e7eb;padding:16px;display:flex;gap:12px;align-items:flex-start;}
        .item.unread{border-left:3px solid #dc2626;background:#fef2f2;}
        .item .icon{font-size:24px;flex-shrink:0;}
        .info{flex:1;}
        .info .message{font-size:14px;}
        .info .time{font-size:12px;color:#6b7280;margin-top:4px;}
        .empty{text-align:center;padding:60px;color:#6b7280;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
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
            <a href="{{ route('admin.badges') }}">🏆 Badges</a>
            <a href="{{ route('admin.notifications') }}" class="active">🔔 Notifikasi</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>
    <main class="main">
        <div class="head">
            <h1>🔔 Notifikasi Admin</h1>
            @if($notifications->where('read_at', null)->count() > 0)
                <form method="POST" action="{{ route('admin.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn-small">Tandai Semua Dibaca</button>
                </form>
            @endif
        </div>
        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif
        @if($notifications->isEmpty())
            <div class="empty"><p>Belum ada notifikasi.</p></div>
        @else
            <div class="list">
                @foreach($notifications as $n)
                <div class="item {{ $n->read_at ? '' : 'unread' }}">
                    <div class="icon">{{ $n->data['icon'] ?? '🔔' }}</div>
                    <div class="info">
                        <div class="message">{{ $n->data['message'] ?? $n->data['text'] ?? 'Notifikasi' }}</div>
                        <div class="time">{{ $n->created_at->diffForHumans() }}</div>
                    </div>
                    @unless($n->read_at)
                        <form method="POST" action="{{ route('admin.notification.read', $n->id) }}">
                            @csrf
                            <button type="submit" style="background:none;border:0;font-size:11px;color:#6b7280;cursor:pointer;">Tandai</button>
                        </form>
                    @endunless
                </div>
                @endforeach
            </div>
            <div style="margin-top:16px;">{{ $notifications->links() }}</div>
        @endif
    </main>
</body>
</html>
