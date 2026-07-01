<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Notifikasi - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .container{width:min(720px,94%);margin:32px auto;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:20px;}
        .head h1{font-size:24px;font-weight:900;}
        .btn-small{padding:8px 14px;font-size:12px;font-weight:600;background:#fff;border:1px solid #e5e7eb;cursor:pointer;text-decoration:none;color:#111827;}
        .list{display:flex;flex-direction:column;gap:8px;}
        .item{background:#fff;border:1px solid #e5e7eb;padding:16px;display:flex;gap:12px;align-items:flex-start;}
        .item.unread{border-left:3px solid #dc2626;background:#fef2f2;}
        .item .icon{font-size:24px;flex-shrink:0;}
        .info{flex:1;}
        .info .message{font-size:14px;line-height:1.4;}
        .info .time{font-size:12px;color:#6b7280;margin-top:4px;}
        .empty{text-align:center;padding:60px 20px;color:#6b7280;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('wishlist.index') }}">♥ Wishlist</a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="head">
            <h1>🔔 Notifikasi</h1>
            @if($notifications->where('read_at', null)->count() > 0)
                <form method="POST" action="{{ route('member.notifications.read-all') }}">
                    @csrf
                    <button type="submit" class="btn-small">Tandai Semua Dibaca</button>
                </form>
            @endif
        </div>
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
                        <form method="POST" action="{{ route('member.notification.read', $n->id) }}">
                            @csrf
                            <button type="submit" style="background:none;border:0;font-size:11px;color:#6b7280;cursor:pointer;">Tandai</button>
                        </form>
                    @endunless
                </div>
                @endforeach
            </div>
            <div style="margin-top:16px;">{{ $notifications->links() }}</div>
        @endif
    </div>
</body>
</html>
