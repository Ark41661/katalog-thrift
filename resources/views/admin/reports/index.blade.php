<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Produk - Admin</title>
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
        .card{background:#fff;border:1px solid #e5e7eb;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:11px 12px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;vertical-align:top;}
        th{font-weight:700;background:#f9fafb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-resolved{background:#dcfce7;color:#166534;}
        .badge-ignored{background:#f3f4f6;color:#6b7280;}
        .btn{display:inline-block;padding:6px 10px;font-size:11px;font-weight:700;text-decoration:none;border:0;cursor:pointer;}
        .btn-resolve{background:#16a34a;color:#fff;}
        .btn-ignore{background:#6b7280;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        form.logout-form button{background:none;border:0;color:#94a3b8;font-size:13px;font-weight:600;cursor:pointer;}
        .reason-labels{harga_tidak_wajar:'Harga Tidak Wajar',foto_palsu:'Foto Palsu',produk_tidak_sesuai:'Produk Tidak Sesuai',lainnya:'Lainnya'}
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
        <div class="head"><h1>Laporan Produk ({{ $reports->total() }})</h1></div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <table>
                <thead><tr><th>Pelapor</th><th>Produk</th><th>Alasan</th><th>Detail</th><th>Status</th><th>Tanggal</th><th>Aksi</th></tr></thead>
                <tbody>
                    @php $reasonLabels = ['harga_tidak_wajar'=>'Harga Tidak Wajar','foto_palsu'=>'Foto Palsu','produk_tidak_sesuai'=>'Produk Tidak Sesuai','lainnya'=>'Lainnya']; @endphp
                    @foreach($reports as $r)
                    <tr>
                        <td>{{ $r->user->name }}</td>
                        <td><a href="{{ route('catalog.show', $r->product->slug) }}" target="_blank" style="color:#dc2626;">{{ $r->product->name }}</a><br><small style="color:#6b7280;">{{ $r->product->partner?->store_name }}</small></td>
                        <td>{{ $reasonLabels[$r->reason] ?? $r->reason }}</td>
                        <td style="max-width:180px;font-size:12px;color:#374151;">{{ $r->detail ?? '—' }}</td>
                        <td><span class="badge badge-{{ $r->status }}">{{ ucfirst($r->status) }}</span></td>
                        <td>{{ $r->created_at->format('d M Y') }}</td>
                        <td>
                            <a href="{{ route('admin.reports.show', $r) }}" class="btn" style="background:#111827;color:#fff;margin-bottom:4px;">Detail</a>
                            @if($r->status === 'pending')
                            <div style="display:flex;gap:4px;margin-top:4px;">
                                <form method="POST" action="{{ route('admin.reports.resolve', $r) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-resolve">✓ Selesai</button>
                                </form>
                                <form method="POST" action="{{ route('admin.reports.ignore', $r) }}">
                                    @csrf @method('PUT')
                                    <button type="submit" class="btn btn-ignore">Abaikan</button>
                                </form>
                            </div>
                            @endif
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $reports->links() }}</div>
    </main>
</body>
</html>
