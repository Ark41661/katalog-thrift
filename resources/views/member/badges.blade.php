<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Badges & Poin - {{ $storeName }}</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .topbar{background:#fff;border-bottom:1px solid #e5e7eb;}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;text-decoration:none;color:#111827;}
        .topbar-nav{display:flex;gap:16px;align-items:center;}
        .topbar-nav a{font-size:13px;font-weight:600;color:#6b7280;text-decoration:none;}
        .container{width:min(720px,94%);margin:32px auto;}
        .tier-card{background:linear-gradient(135deg,#111827,#1f2937);color:#fff;padding:28px;border-radius:4px;margin-bottom:24px;display:flex;justify-content:space-between;align-items:center;}
        .tier-card .tier-icon{font-size:48px;}
        .tier-card .tier-info h2{font-size:22px;font-weight:900;}
        .tier-card .tier-info p{color:#9ca3af;font-size:14px;margin-top:4px;}
        .tier-card .points{font-size:36px;font-weight:900;color:#f59e0b;}
        .tier-progress{background:#374151;height:8px;border-radius:4px;margin-top:12px;overflow:hidden;}
        .tier-progress-bar{height:100%;background:#f59e0b;border-radius:4px;}
        .section-title{font-size:13px;font-weight:900;letter-spacing:1.5px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;}
        .badges-grid{display:grid;grid-template-columns:repeat(auto-fill,minmax(140px,1fr));gap:12px;margin-bottom:32px;}
        .badge-item{background:#fff;border:1px solid #e5e7eb;padding:20px;text-align:center;}
        .badge-item .icon{font-size:36px;margin-bottom:8px;}
        .badge-item .name{font-size:13px;font-weight:700;}
        .badge-item .type{font-size:11px;color:#6b7280;margin-top:3px;}
        .badge-empty{background:#fff;border:1px dashed #e5e7eb;padding:20px;text-align:center;color:#6b7280;font-size:13px;margin-bottom:32px;}
        .log-item{background:#fff;border:1px solid #e5e7eb;padding:12px 16px;display:flex;justify-content:space-between;align-items:center;margin-bottom:6px;}
        .log-item .desc{font-size:13px;}
        .log-item .points{font-size:13px;font-weight:700;color:#059669;}
        .log-item .time{font-size:11px;color:#6b7280;}
        .empty-log{text-align:center;padding:40px;color:#6b7280;}
    </style>
</head>
<body>
    <header class="topbar">
        <div class="topbar-inner">
            <a href="{{ route('catalog.index') }}" class="topbar-brand">{{ $storeName }}</a>
            <nav class="topbar-nav">
                <a href="{{ route('catalog.index') }}">Katalog</a>
                <a href="{{ route('member.profile') }}">Profil</a>
                <form method="POST" action="{{ route('member.logout') }}">
                    @csrf
                    <button type="submit" style="background:none;border:0;font-size:13px;font-weight:600;color:#6b7280;cursor:pointer;">Logout</button>
                </form>
            </nav>
        </div>
    </header>
    <div class="container">
        <div class="tier-card">
            <div>
                <div class="tier-info">
                    <h2>{{ $user->tier_badge }} {{ $user->tier_name }}</h2>
                    <p>Level keanggotaan kamu</p>
                </div>
                <div class="tier-progress" style="max-width:300px;">
                    @php
                        $tiers = ['regular'=>0, 'silver'=>100, 'gold'=>500, 'platinum'=>1000];
                        $nextTier = null; $currentMin = 0; $nextMax = 100;
                        foreach ($tiers as $t => $min) {
                            if ($user->points >= $min) { $currentMin = $min; $nextTier = $t; }
                        }
                        $keys = array_keys($tiers);
                        $nextIdx = array_search($nextTier, $keys) + 1;
                        $nextMax = $tiers[$keys[$nextIdx]] ?? $user->points;
                        $progress = $nextMax > $currentMin ? (($user->points - $currentMin) / ($nextMax - $currentMin)) * 100 : 100;
                    @endphp
                    <div class="tier-progress-bar" style="width: {{ min(100, $progress) }}%;"></div>
                </div>
            </div>
            <div class="points">{{ number_format($user->points) }}</div>
        </div>

        <p class="section-title">🏆 Badges</p>
        @if($badges->isEmpty())
            <div class="badge-empty">Kamu belum memiliki badge. Dapatkan dengan aktif di komunitas!</div>
        @else
            <div class="badges-grid">
                @foreach($badges as $b)
                <div class="badge-item">
                    <div class="icon">{{ $b->badge_icon ?? '🏆' }}</div>
                    <div class="name">{{ $b->badge_name }}</div>
                    <div class="type">{{ $b->badge_type }}</div>
                </div>
                @endforeach
            </div>
        @endif

        <p class="section-title">📊 Aktivitas Terbaru</p>
        @if($logs->isEmpty())
            <div class="empty-log">Belum ada aktivitas.</div>
        @else
            @foreach($logs as $log)
            <div class="log-item">
                <div>
                    <div class="desc">{{ $log->description ?? $log->activity_type }}</div>
                    <div class="time">{{ $log->created_at->diffForHumans() }}</div>
                </div>
                <div class="points">+{{ $log->points_earned }}</div>
            </div>
            @endforeach
        @endif
    </div>
</body>
</html>
