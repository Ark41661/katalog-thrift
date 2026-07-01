<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8"><meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan - {{ $storeName }}</title>
    <style>
        :root{--bg:#f7f7f5;--text:#111111;--muted:#6b7280;--card:#fff;--accent:#dc2626;--accent-dark:#b91c1c;--border:#e5e7eb;--dark:#111827;}
        *{box-sizing:border-box;margin:0;padding:0;}
        body{font-family:-apple-system,"Helvetica Neue",Arial,sans-serif;background:var(--bg);color:var(--text);}
        a{text-decoration:none;color:inherit;}
        .topbar{position:sticky;top:0;z-index:100;background:rgba(255,255,255,0.95);border-bottom:1px solid var(--border);backdrop-filter:blur(8px);}
        .topbar-inner{width:min(1280px,94%);margin:0 auto;display:flex;justify-content:space-between;align-items:center;height:56px;gap:12px;}
        .topbar-brand{font-size:17px;font-weight:900;letter-spacing:2px;flex-shrink:0;color:var(--text);}
        .topbar-nav{display:flex;gap:16px;align-items:center;flex-wrap:wrap;}
        .topbar-nav a{font-size:13px;font-weight:600;color:var(--muted);white-space:nowrap;}
        .topbar-nav a:hover,.topbar-nav a.active{color:var(--accent);}
        .topbar-auth{display:flex;gap:8px;align-items:center;flex-shrink:0;}
        .btn-login{font-size:13px;font-weight:600;color:var(--muted);padding:6px 12px;border:1px solid var(--border);}
        .btn-register{font-size:13px;font-weight:700;color:#fff;background:var(--dark);padding:6px 14px;}
        @media(max-width:900px){.topbar-nav{display:none;}}
        .container{width:min(680px,94%);margin:48px auto;}
        .title{font-size:28px;font-weight:900;margin-bottom:6px;}
        .sub{font-size:14px;color:var(--muted);margin-bottom:28px;}
        .card{background:var(--card);border:1px solid var(--border);padding:28px;}
        .form-group{margin-bottom:18px;}
        .form-group label{display:block;font-size:12px;font-weight:700;text-transform:uppercase;letter-spacing:.5px;color:var(--muted);margin-bottom:6px;}
        .form-group input,.form-group textarea,.form-group select{width:100%;padding:10px 12px;font-size:14px;border:1px solid var(--border);background:var(--bg);font-family:inherit;}
        .form-group input:focus,.form-group textarea:focus,.form-group select:focus{outline:2px solid var(--accent);outline-offset:-1px;}
        .form-group textarea{resize:vertical;min-height:120px;}
        .btn-submit{padding:12px 24px;background:var(--accent);color:#fff;font-size:14px;font-weight:700;border:0;cursor:pointer;}
        .btn-submit:hover{background:var(--accent-dark);}
        .alert-success{background:#dcfce7;color:#166534;padding:12px 16px;font-size:13px;margin-bottom:20px;border:1px solid #bbf7d0;}
    </style>
</head>
<body>
    @include('partials.public-nav', ['activeNav' => '', 'storeName' => $storeName])

    <div class="container">
        <h1 class="title">Laporan</h1>
        <p class="sub">Laporkan bug, berikan saran, atau laporkan konten yang tidak pantas di platform {{ $storeName }}.</p>

        @if(session('success'))
            <div class="alert-success">{{ session('success') }}</div>
        @endif

        <div class="card">
            <form method="POST" action="{{ route('web-report.store') }}">
                @csrf
                <div class="form-group">
                    <label>Nama</label>
                    <input type="text" name="name" value="{{ old('name', auth()->user()->name ?? '') }}" required maxlength="100">
                    @error('name')<small style="color:var(--accent);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Email</label>
                    <input type="email" name="email" value="{{ old('email', auth()->user()->email ?? '') }}" required maxlength="100">
                    @error('email')<small style="color:var(--accent);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Kategori</label>
                    <select name="category" required>
                        <option value="">Pilih kategori</option>
                        <option value="bug" {{ old('category') === 'bug' ? 'selected' : '' }}>Bug / Error</option>
                        <option value="saran" {{ old('category') === 'saran' ? 'selected' : '' }}>Saran</option>
                        <option value="konten_tidak_pantas" {{ old('category') === 'konten_tidak_pantas' ? 'selected' : '' }}>Konten Tidak Pantas</option>
                        <option value="penyalahgunaan" {{ old('category') === 'penyalahgunaan' ? 'selected' : '' }}>Penyalahgunaan</option>
                        <option value="lainnya" {{ old('category') === 'lainnya' ? 'selected' : '' }}>Lainnya</option>
                    </select>
                    @error('category')<small style="color:var(--accent);">{{ $message }}</small>@enderror
                </div>
                <div class="form-group">
                    <label>Pesan</label>
                    <textarea name="message" required maxlength="1000">{{ old('message') }}</textarea>
                    @error('message')<small style="color:var(--accent);">{{ $message }}</small>@enderror
                </div>
                <button type="submit" class="btn-submit">Kirim Laporan</button>
            </form>
        </div>
    </div>
</body>
</html>
