<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{margin-bottom:20px;}
        .head h1{font-size:22px;font-weight:900;}
        .head a{font-size:13px;color:#6b7280;text-decoration:none;}
        .card{background:#fff;border:1px solid #e5e7eb;padding:24px;margin-bottom:20px;}
        .section-title{font-size:11px;font-weight:900;letter-spacing:2px;text-transform:uppercase;color:#6b7280;margin-bottom:16px;}
        .detail-row{display:flex;gap:40px;margin-bottom:14px;}
        .detail-row .label{font-size:12px;font-weight:700;color:#6b7280;width:120px;flex-shrink:0;}
        .detail-row .value{font-size:14px;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-resolved{background:#dcfce7;color:#166534;}
        .badge-ignored{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-resolve{background:#16a34a;color:#fff;}
        .btn-ignore{background:#6b7280;color:#fff;}
        textarea{width:100%;padding:10px 12px;border:1px solid #d1d5db;font-size:14px;font-family:inherit;resize:vertical;min-height:80px;}
        .timeline{position:relative;padding-left:24px;}
        .timeline::before{content:'';position:absolute;left:7px;top:0;bottom:0;width:2px;background:#e5e7eb;}
        .timeline-item{position:relative;margin-bottom:20px;}
        .timeline-item::before{content:'';position:absolute;left:-21px;top:2px;width:12px;height:12px;border-radius:50%;background:#d1d5db;border:2px solid #fff;}
        .timeline-item.resolved::before{background:#16a34a;}
        .timeline-item.ignored::before{background:#6b7280;}
        .timeline-item.submitted::before{background:#dc2626;}
        .timeline-time{font-size:12px;color:#6b7280;margin-bottom:4px;}
        .timeline-text{font-size:14px;}
        .timeline-admin{font-size:12px;color:#6b7280;margin-top:4px;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
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
            <a href="{{ route('admin.reports.index') }}" class="active">🚨 Laporan</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head">
            <a href="{{ route('admin.reports.index') }}">← Kembali ke daftar laporan</a>
            <h1>Detail Laporan #{{ $report->id }}</h1>
        </div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <p class="section-title">Informasi Laporan</p>
            @php $reasonLabels = ['harga_tidak_wajar'=>'Harga Tidak Wajar','foto_palsu'=>'Foto Palsu','produk_tidak_sesuai'=>'Produk Tidak Sesuai','lainnya'=>'Lainnya']; @endphp
            <div class="detail-row">
                <div class="label">Pelapor</div>
                <div class="value">{{ $report->user->name }} ({{ $report->user->email }})</div>
            </div>
            <div class="detail-row">
                <div class="label">Produk</div>
                <div class="value"><a href="{{ route('catalog.show', $report->product->slug) }}" target="_blank" style="color:#dc2626;">{{ $report->product->name }}</a><br><small style="color:#6b7280;">{{ $report->product->partner?->store_name ?? 'Tidak ada mitra' }}</small></div>
            </div>
            <div class="detail-row">
                <div class="label">Alasan</div>
                <div class="value">{{ $reasonLabels[$report->reason] ?? $report->reason }}</div>
            </div>
            <div class="detail-row">
                <div class="label">Detail</div>
                <div class="value">{{ $report->detail ?? '—' }}</div>
            </div>
            <div class="detail-row">
                <div class="label">Status</div>
                <div class="value"><span class="badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></div>
            </div>
            <div class="detail-row">
                <div class="label">Dikirim</div>
                <div class="value">{{ $report->created_at->format('d M Y H:i') }}</div>
            </div>
        </div>

        @if($report->status === 'pending')
        <div class="card">
            <p class="section-title">Tindakan Moderasi</p>
            <form method="POST" action="{{ route('admin.reports.resolve', $report) }}" style="margin-bottom:16px;">
                @csrf @method('PUT')
                <textarea name="resolution_note" placeholder="Catatan penyelesaian (opsional)"></textarea>
                <button type="submit" class="btn btn-resolve" style="margin-top:8px;">✓ Tandai Selesai</button>
            </form>
            <form method="POST" action="{{ route('admin.reports.ignore', $report) }}">
                @csrf @method('PUT')
                <textarea name="resolution_note" placeholder="Catatan pengabaian (opsional)"></textarea>
                <button type="submit" class="btn btn-ignore" style="margin-top:8px;">Abaikan Laporan</button>
            </form>
        </div>
        @endif

        <div class="card">
            <p class="section-title">Timeline Moderasi</p>
            <div class="timeline">
                <div class="timeline-item submitted">
                    <div class="timeline-time">{{ $report->created_at->format('d M Y H:i') }}</div>
                    <div class="timeline-text">Laporan dikirim oleh {{ $report->user->name }}</div>
                </div>
                @foreach($report->history as $h)
                <div class="timeline-item {{ $h->status }}">
                    <div class="timeline-time">{{ $h->created_at->format('d M Y H:i') }}</div>
                    <div class="timeline-text">Status diubah menjadi <strong>{{ ucfirst($h->status) }}</strong></div>
                    @if($h->note)
                        <div class="timeline-admin">Catatan: {{ $h->note }}</div>
                    @endif
                    @if($h->admin)
                        <div class="timeline-admin">Oleh: {{ $h->admin->name }}</div>
                    @endif
                </div>
                @endforeach
                @if($report->status === 'pending' && $report->history->isEmpty())
                    <p style="color:#6b7280;font-size:14px;">Menunggu tindakan moderator.</p>
                @endif
            </div>
        </div>
    </main>
</body>
</html>
