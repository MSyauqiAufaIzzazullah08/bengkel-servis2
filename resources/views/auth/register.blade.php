
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Daftar Pelanggan | BengkelServis</title>

    <style>
        :root {
            --navy: #102B55;
            --navy-light: #173B75;
            --orange: #F5A623;
            --orange-light: #FFF2D9;
            --background: #F4F7FC;
            --text: #172033;
            --muted: #66758C;
            --border: #DCE4EF;
        }

        * { box-sizing: border-box; }

        body {
            margin: 0;
            min-height: 100vh;
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
            display: flex;
            flex-direction: column;
        }

        header {
            padding: 20px 8%;
            background: white;
            border-bottom: 1px solid var(--border);
        }

        .logo {
            color: var(--navy);
            font-size: 23px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo span { color: var(--orange); }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 35px 15px;
            background:
                radial-gradient(circle at 10% 15%, #E2EAF7 0, transparent 30%),
                radial-gradient(circle at 90% 85%, #FFF0D3 0, transparent 27%),
                var(--background);
        }

        .card {
            width: 100%;
            max-width: 560px;
            background: white;
            padding: 35px;
            border: 1px solid var(--border);
            border-radius: 18px;
            box-shadow: 0 18px 50px rgba(16,43,85,.10);
        }

        .heading {
            text-align: center;
            margin-bottom: 25px;
        }

        .heading .icon {
            width: 58px;
            height: 58px;
            border-radius: 15px;
            background: var(--orange-light);
            display: grid;
            place-items: center;
            font-size: 29px;
            margin: 0 auto 16px;
        }

        h1 {
            color: var(--navy);
            font-size: 27px;
            margin: 0 0 8px;
        }

        p {
            color: var(--muted);
            line-height: 1.7;
            font-size: 14px;
        }

        .group { margin-bottom: 17px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 13px;
            color: var(--navy);
        }

        input, textarea {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 14px;
            font-family: inherit;
            color: var(--text);
            background: white;
            transition: .2s;
        }

        input:focus, textarea:focus {
            outline: 3px solid rgba(245,166,35,.2);
            border-color: var(--orange);
        }

        textarea {
            min-height: 90px;
            resize: vertical;
        }

        button {
            width: 100%;
            padding: 14px;
            border: 1px solid var(--navy);
            border-radius: 8px;
            background: var(--navy);
            color: white;
            font-weight: bold;
            font-size: 15px;
            cursor: pointer;
            transition: .2s;
        }

        button:hover {
            background: var(--navy-light);
            box-shadow: 0 5px 14px rgba(16,43,85,.18);
        }

        .message {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 17px;
            font-size: 13px;
            line-height: 1.7;
        }

        .error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .success {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #BBF7D0;
        }

        .message ul {
            padding-left: 20px;
            margin-top: 6px;
        }

        .links {
            text-align: center;
            margin-top: 22px;
            font-size: 14px;
            color: var(--muted);
        }

        .links a {
            color: #A45F00;
            text-decoration: none;
            font-weight: bold;
        }

        .links a:hover { color: var(--navy); }

        footer {
            text-align: center;
            padding: 20px;
            background: white;
            border-top: 1px solid var(--border);
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 600px) {
            header { padding: 18px 5%; }
            main { padding: 22px 13px; }
            .card { padding: 26px 20px; }
            h1 { font-size: 24px; }
        }
    </style>
</head>

<body>
<header>
    <a href="/" class="logo">🔧 <span>Bengkel</span>Servis</a>
</header>

<main>
    <div class="card">
        <div class="heading">
            <div class="icon">👤</div>
            <h1>Buat Akun Pelanggan</h1>
            <p>
                Daftar untuk memesan layanan servis dan mengelola
                kendaraan Anda melalui BengkelServis.
            </p>
        </div>

        @if (session('error'))
            <div class="message error">{{ session('error') }}</div>
        @endif

        @if ($errors->any())
            <div class="message error">
                <strong>Periksa kembali data berikut:</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        @if (session('success'))
            <div class="message success">{{ session('success') }}</div>
        @endif

        <form action="/register" method="POST">
            @csrf

            <div class="group">
                <label for="nama">Nama Lengkap</label>
                <input
                    id="nama"
                    name="nama"
                    value="{{ old('nama') }}"
                    maxlength="100"
                    autocomplete="name"
                    placeholder="Masukkan nama lengkap"
                    required
                >
            </div>

            <div class="group">
                <label for="email">Email</label>
                <input
                    id="email"
                    name="email"
                    type="email"
                    value="{{ old('email') }}"
                    maxlength="100"
                    autocomplete="email"
                    placeholder="contoh@email.com"
                    required
                >
            </div>

            <div class="group">
                <label for="no_hp">Nomor HP</label>
                <input
                    id="no_hp"
                    name="no_hp"
                    type="tel"
                    value="{{ old('no_hp') }}"
                    maxlength="20"
                    autocomplete="tel"
                    placeholder="Masukkan nomor HP"
                    required
                >
            </div>

            <div class="group">
                <label for="alamat">Alamat</label>
                <textarea
                    id="alamat"
                    name="alamat"
                    maxlength="255"
                    autocomplete="street-address"
                    placeholder="Masukkan alamat lengkap"
                    required
                >{{ old('alamat') }}</textarea>
            </div>

            <div class="group">
                <label for="password">Password</label>
                <input
                    id="password"
                    name="password"
                    type="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Minimal 8 karakter"
                    required
                >
            </div>

            <div class="group">
                <label for="password_confirmation">Konfirmasi Password</label>
                <input
                    id="password_confirmation"
                    name="password_confirmation"
                    type="password"
                    minlength="8"
                    autocomplete="new-password"
                    placeholder="Ulangi password"
                    required
                >
            </div>

            <button type="submit">Buat Akun Sekarang →</button>
        </form>

        <div class="links">
            <div>Sudah punya akun? <a href="/login">Login di sini</a></div>
            <p><a href="/">← Kembali ke halaman utama</a></p>
        </div>
    </div>
</main>

<footer>&copy; {{ date('Y') }} BengkelServis</footer>
</body>
</html>
