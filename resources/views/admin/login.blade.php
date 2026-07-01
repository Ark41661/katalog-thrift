<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Admin Login</title>
    <style>
        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f3f4f6;
            color: #111827;
            min-height: 100vh;
            display: grid;
            place-items: center;
        }
        .card {
            width: min(420px, 92%);
            background: #fff;
            border: 1px solid #e5e7eb;
            padding: 24px;
        }
        h1 { margin: 0 0 8px; }
        p { margin: 0 0 16px; color: #6b7280; }
        label { font-size: 13px; font-weight: 700; display: block; margin-bottom: 6px; }
        input {
            width: 100%;
            padding: 10px 12px;
            margin-bottom: 12px;
            border: 1px solid #d1d5db;
        }
        button {
            width: 100%;
            border: 0;
            background: #111827;
            color: #fff;
            padding: 11px 12px;
            font-weight: 700;
            cursor: pointer;
        }
        .error {
            margin-bottom: 12px;
            background: #fee2e2;
            color: #991b1b;
            padding: 10px;
            font-size: 14px;
        }
    </style>
</head>
<body>
    <main class="card">
        <h1>Admin Login</h1>
        <p>Halaman ini khusus admin toko.</p>

        @if ($errors->any())
            <div class="error">{{ $errors->first() }}</div>
        @endif

        <form method="POST" action="{{ route('admin.login.submit') }}">
            @csrf
            <label for="username">Username</label>
            <input id="username" name="username" type="text" value="{{ old('username') }}" required>

            <label for="password">Password</label>
            <input id="password" name="password" type="password" required>

            <button type="submit">Masuk Admin</button>
        </form>
    </main>
</body>
</html>
