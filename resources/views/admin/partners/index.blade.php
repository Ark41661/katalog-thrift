<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kelola Mitra - Admin</title>
    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:#f3f4f6;color:#111827;}
        .sidebar{position:fixed;top:0;left:0;width:220px;height:100vh;background:#0f172a;padding:24px 0;}
        .sidebar-brand{padding:0 20px 20px;border-bottom:1px solid #1e293b;}
        .sidebar-brand .name{font-size:15px;font-weight:900;letter-spacing:2px;color:#fff;}
        .sidebar-brand .role{font-size:11px;color:#64748b;margin-top:2px;}
        .sidebar-nav a{display:flex;align-items:center;gap:10px;padding:10px 20px;font-size:13px;font-weight:600;color:#94a3b8;text-decoration:none;}
        .sidebar-nav a:hover,.sidebar-nav a.active{background:#1e293b;color:#fff;}
        .sidebar-nav .badge-red{background:#dc2626;color:#fff;font-size:10px;padding:2px 6px;border-radius:999px;margin-left:auto;}
        .sidebar-footer{position:absolute;bottom:0;left:0;right:0;padding:16px 20px;border-top:1px solid #1e293b;}
        .main{margin-left:220px;padding:28px 32px;}
        .head{display:flex;justify-content:space-between;align-items:center;margin-bottom:16px;}
        .head h1{font-size:22px;font-weight:900;}
        .tabs{display:flex;gap:0;margin-bottom:20px;border-bottom:2px solid #e5e7eb;}
        .tab{padding:10px 18px;font-size:13px;font-weight:700;text-decoration:none;color:#6b7280;border-bottom:2px solid transparent;margin-bottom:-2px;}
        .tab.active{color:#dc2626;border-bottom-color:#dc2626;}
        .card{background:#fff;border:1px solid #e5e7eb;}
        table{width:100%;border-collapse:collapse;}
        th,td{padding:12px 14px;font-size:13px;text-align:left;border-bottom:1px solid #f1f5f9;vertical-align:middle;}
        th{font-weight:700;background:#f9fafb;}
        .badge{display:inline-block;padding:3px 8px;font-size:11px;font-weight:700;border-radius:3px;}
        .badge-pending{background:#fef3c7;color:#92400e;}
        .badge-approved{background:#dcfce7;color:#166534;}
        .badge-rejected{background:#fee2e2;color:#991b1b;}
        .badge-suspended{background:#f3f4f6;color:#6b7280;}
        .badge-verified{background:#dbeafe;color:#1d4ed8;}
        .btn{display:inline-block;padding:6px 10px;font-size:11px;font-weight:700;text-decoration:none;border:0;cursor:pointer;white-space:nowrap;}
        .btn-approve{background:#16a34a;color:#fff;}
        .btn-reject{background:#dc2626;color:#fff;}
        .btn-suspend{background:#f59e0b;color:#fff;}
        .btn-outline{background:#fff;color:#111827;border:1px solid #e5e7eb;}
        .btn-verified{background:#1d4ed8;color:#fff;}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:16px;border:1px solid #bbf7d0;}
        .modal-overlay{display:none;position:fixed;inset:0;background:rgba(0,0,0,0.5);z-index:100;align-items:center;justify-content:center;}
        .modal-overlay.open{display:flex;}
        .modal{background:#fff;padding:28px;width:min(440px,94%);border-radius:4px;}
        .modal h3{font-size:18px;font-weight:900;margin-bottom:12px;}
        .modal textarea{width:100%;padding:10px;border:1px solid #d1d5db;font-size:14px;min-height:80px;font-family:inherit;}
        .modal-actions{display:flex;gap:8px;margin-top:16px;}
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
            <a href="{{ route('admin.partners.index') }}" class="active">🏪 Kelola Mitra
                @if($pendingCount > 0)<span class="badge-red">{{ $pendingCount }}</span>@endif
            </a>
            <a href="{{ route('admin.products.index') }}">📦 Semua Produk</a>
            <a href="{{ route('admin.reviews.index') }}">⭐ Review</a>
            <a href="{{ route('admin.reports.index') }}">🚨 Laporan</a>
        </nav>
        <div class="sidebar-footer">
            <form method="POST" action="{{ route('admin.logout') }}" class="logout-form">
                @csrf <button type="submit">⬅ Logout</button>
            </form>
        </div>
    </aside>

    <main class="main">
        <div class="head"><h1>Kelola Mitra</h1></div>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="tabs">
            @foreach(['pending'=>'Pending','approved'=>'Disetujui','rejected'=>'Ditolak','suspended'=>'Ditangguhkan','all'=>'Semua'] as $s => $label)
                <a href="{{ route('admin.partners.index', ['status' => $s]) }}" class="tab {{ $activeStatus === $s ? 'active' : '' }}">{{ $label }}</a>
            @endforeach
        </div>

        <div class="card">
            <table>
                <thead><tr><th>Nama Toko</th><th>Pemilik</th><th>WA</th><th>Status</th><th>Daftar</th><th>Aksi</th></tr></thead>
                <tbody>
                    @forelse($partners as $p)
                    <tr>
                        <td>
                            <strong>{{ $p->store_name }}</strong>
                            @if($p->is_verified) <span class="badge badge-verified">✓ Verified</span> @endif
                            <br><small style="color:#6b7280;">{{ $p->location }}</small>
                        </td>
                        <td>{{ $p->user->name }}<br><small style="color:#6b7280;">{{ $p->user->email }}</small></td>
                        <td>{{ $p->whatsapp }}</td>
                        <td><span class="badge badge-{{ $p->status }}">{{ ucfirst($p->status) }}</span></td>
                        <td>{{ $p->created_at->format('d M Y') }}</td>
                        <td>
                            <div style="display:flex;gap:4px;flex-wrap:wrap;">
                                @if($p->status === 'pending')
                                    <form method="POST" action="{{ route('admin.partners.approve', $p) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-approve">✓ Setujui</button>
                                    </form>
                                    <button class="btn btn-reject" onclick="openReject({{ $p->id }})">✗ Tolak</button>
                                @endif
                                @if($p->status === 'approved')
                                    <button class="btn btn-suspend" onclick="openSuspend({{ $p->id }})">⏸ Tangguhkan</button>
                                    <form method="POST" action="{{ route('admin.partners.verified', $p) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-verified">{{ $p->is_verified ? '✗ Cabut Badge' : '✓ Verified' }}</button>
                                    </form>
                                @endif
                                @if($p->status === 'suspended')
                                    <form method="POST" action="{{ route('admin.partners.approve', $p) }}">
                                        @csrf @method('PUT')
                                        <button type="submit" class="btn btn-approve">Aktifkan</button>
                                    </form>
                                @endif
                                <a href="{{ route('partners.show', $p->store_slug) }}" target="_blank" class="btn btn-outline">Lihat</a>
                            </div>
                        </td>
                    </tr>
                    @empty
                    <tr><td colspan="6" style="text-align:center;padding:40px;color:#6b7280;">Tidak ada mitra dengan status ini.</td></tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        <div style="margin-top:16px;">{{ $partners->links() }}</div>
    </main>

    {{-- Modal Tolak --}}
    <div class="modal-overlay" id="modal-reject">
        <div class="modal">
            <h3>Tolak Pendaftaran</h3>
            <form method="POST" id="form-reject" action="">
                @csrf @method('PUT')
                <textarea name="reason" placeholder="Alasan penolakan..." required></textarea>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-reject" style="padding:10px 20px;font-size:13px;">Tolak</button>
                    <button type="button" class="btn btn-outline" style="padding:10px 20px;font-size:13px;" onclick="closeModal('modal-reject')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    {{-- Modal Tangguhkan --}}
    <div class="modal-overlay" id="modal-suspend">
        <div class="modal">
            <h3>Tangguhkan Mitra</h3>
            <form method="POST" id="form-suspend" action="">
                @csrf @method('PUT')
                <textarea name="reason" placeholder="Alasan penangguhan (opsional)..."></textarea>
                <div class="modal-actions">
                    <button type="submit" class="btn btn-suspend" style="padding:10px 20px;font-size:13px;">Tangguhkan</button>
                    <button type="button" class="btn btn-outline" style="padding:10px 20px;font-size:13px;" onclick="closeModal('modal-suspend')">Batal</button>
                </div>
            </form>
        </div>
    </div>

    <script>
        const rejectBase   = "{{ url(config('admin.entry_path') . '/mitra') }}";
        function openReject(id) {
            document.getElementById('form-reject').action = rejectBase + '/' + id + '/reject';
            document.getElementById('modal-reject').classList.add('open');
        }
        function openSuspend(id) {
            document.getElementById('form-suspend').action = rejectBase + '/' + id + '/suspend';
            document.getElementById('modal-suspend').classList.add('open');
        }
        function closeModal(id) { document.getElementById(id).classList.remove('open'); }
    </script>
</body>
</html>
