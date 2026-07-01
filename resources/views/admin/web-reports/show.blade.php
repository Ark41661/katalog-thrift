<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Detail Laporan Web - Admin</title>
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
        .card{background:#fff;border:1px solid #e5e7eb;padding:24px;max-width:700px;}
        .field{margin-bottom:16px;}
        .field-label{font-size:11px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:#6b7280;margin-bottom:4px;}
        .field-value{font-size:14px;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-resolved{background:#dcfce7;color:#166534;}
        .badge-ignored{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:8px 14px;font-size:12px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-resolve{background:#16a34a;color:#fff;}
        .btn-ignore{background:#6b7280;color:#fff;}
        .btn-back{background:#111827;color:#fff;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .message-box{background:#f9fafb;border:1px solid #e5e7eb;padding:16px;font-size:14px;line-height:1.6;white-space:pre-wrap;}
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
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan Produk</a>
            <a href="{{ route('admin.web-reports.index') }}" class="active">🌐 Laporan Web</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head">
            <a href="{{ route('admin.web-reports.index') }}" class="btn btn-back" style="margin-bottom:12px;">← Kembali</a>
            <h1>Detail Laporan Web</h1>
        </div>

        <div class="card">
            <div class="field">
                <div class="field-label">Pelapor</div>
                <div class="field-value">{{ $report->name }} ({{ $report->email }})</div>
            </div>
            <div class="field">
                <div class="field-label">Kategori</div>
                <div class="field-value">{{ str_replace('_', ' ', ucfirst($report->category)) }}</div>
            </div>
            <div class="field">
                <div class="field-label">Status</div>
                <div class="field-value"><span class="badge badge-{{ $report->status }}">{{ ucfirst($report->status) }}</span></div>
            </div>
            <div class="field">
                <div class="field-label">Tanggal</div>
                <div class="field-value">{{ $report->created_at->format('d M Y H:i') }}</div>
            </div>
            <div class="field">
                <div class="field-label">Pesan</div>
                <div class="message-box">{{ $report->message }}</div>
            </div>
            @if($report->resolver)
            <div class="field">
                <div class="field-label">Ditindak oleh</div>
                <div class="field-value">{{ $report->resolver->name }}</div>
            </div>
            @endif
            @if($report->resolution_note)
            <div class="field">
                <div class="field-label">Catatan</div>
                <div class="field-value">{{ $report->resolution_note }}</div>
            </div>
            @endif
            @if($report->status === 'pending')
            <div style="display:flex;gap:8px;margin-top:20px;padding-top:20px;border-top:1px solid #e5e7eb;">
                <form method="POST" action="{{ route('admin.web-reports.resolve', $report) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-resolve">✓ Tandai Selesai</button>
                </form>
                <form method="POST" action="{{ route('admin.web-reports.ignore', $report) }}">
                    @csrf @method('PUT')
                    <button type="submit" class="btn btn-ignore">Abaikan</button>
                </form>
            </div>
            @endif
        </div>
    </main>
</body>
</html>
