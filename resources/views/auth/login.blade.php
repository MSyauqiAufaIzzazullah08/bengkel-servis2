
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Login | BengkelServis</title>

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
            display: flex;
            align-items: center;
            justify-content: space-between;
        }

        .logo {
            color: var(--navy);
            font-size: 23px;
            font-weight: bold;
            text-decoration: none;
        }

        .logo span { color: var(--orange); }

        .header-label {
            color: var(--muted);
            font-size: 13px;
        }

        main {
            flex: 1;
            display: flex;
            align-items: center;
            justify-content: center;
            padding: 45px 18px;
            background:
                radial-gradient(circle at 10% 15%, #E2EAF7 0, transparent 30%),
                radial-gradient(circle at 90% 85%, #FFF0D3 0, transparent 27%),
                var(--background);
        }

        .login-layout {
            width: 100%;
            max-width: 950px;
            display: grid;
            grid-template-columns: 1fr 1fr;
            background: white;
            border: 1px solid var(--border);
            border-radius: 20px;
            overflow: hidden;
            box-shadow: 0 20px 55px rgba(16, 43, 85, .12);
        }

        .intro {
            padding: 45px 35px;
            color: white;
            background: linear-gradient(145deg, var(--navy), var(--navy-light));
            display: flex;
            flex-direction: column;
            justify-content: center;
            position: relative;
            overflow: hidden;
        }

        .intro::after {
            content: "";
            position: absolute;
            width: 220px;
            height: 220px;
            border: 30px solid rgba(245,166,35,.15);
            border-radius: 50%;
            right: -100px;
            bottom: -100px;
        }

        .intro-icon {
            width: 65px;
            height: 65px;
            display: grid;
            place-items: center;
            border-radius: 16px;
            background: var(--orange);
            color: var(--navy);
            font-size: 32px;
            margin-bottom: 25px;
            position: relative;
            z-index: 1;
        }

        .intro h2 {
            font-size: 30px;
            line-height: 1.3;
            margin: 0 0 15px;
            position: relative;
            z-index: 1;
        }

        .intro p {
            color: rgba(255,255,255,.83);
            font-size: 14px;
            line-height: 1.9;
            position: relative;
            z-index: 1;
        }

        .intro-note {
            margin-top: 25px;
            padding: 13px 15px;
            border-left: 3px solid var(--orange);
            background: rgba(255,255,255,.08);
            border-radius: 5px;
            font-size: 12px;
            line-height: 1.7;
            position: relative;
            z-index: 1;
        }

        .card {
            padding: 42px 35px;
            min-width: 0;
        }

        .heading {
            margin-bottom: 25px;
        }

        .heading .icon {
            width: 48px;
            height: 48px;
            border-radius: 13px;
            background: var(--orange-light);
            display: grid;
            place-items: center;
            font-size: 24px;
            margin-bottom: 17px;
        }

        h1 {
            color: var(--navy);
            font-size: 27px;
            margin: 0 0 8px;
        }

        p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .group { margin-bottom: 18px; }

        label {
            display: block;
            margin-bottom: 7px;
            font-size: 13px;
            font-weight: bold;
            color: var(--navy);
        }

        input {
            width: 100%;
            padding: 13px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 14px;
            background: #fff;
            color: var(--text);
            transition: .2s;
        }

        input:focus {
            outline: 3px solid rgba(245,166,35,.2);
            border-color: var(--orange);
        }

        button, .register-button {
            display: block;
            width: 100%;
            padding: 13px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            text-align: center;
            cursor: pointer;
            text-decoration: none;
            transition: .2s;
        }

        button {
            border: 1px solid var(--navy);
            background: var(--navy);
            color: white;
        }

        button:hover {
            background: var(--navy-light);
            box-shadow: 0 5px 14px rgba(16,43,85,.18);
        }

        .register-button {
            margin-top: 12px;
            border: 1px solid var(--orange);
            color: var(--navy);
            background: var(--orange-light);
        }

        .register-button:hover { background: #FFE5B0; }

        .message {
            padding: 12px;
            margin-bottom: 17px;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.6;
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

        .back-link {
            display: block;
            margin-top: 23px;
            text-align: center;
            text-decoration: none;
            color: var(--muted);
            font-size: 13px;
        }

        .back-link:hover { color: var(--navy); }

        footer {
            padding: 20px;
            background: white;
            border-top: 1px solid var(--border);
            text-align: center;
            color: var(--muted);
            font-size: 13px;
        }

        @media (max-width: 700px) {
            header { padding: 18px 5%; }
            .header-label { display: none; }
            main { padding: 25px 14px; }
            .login-layout { grid-template-columns: 1fr; }
            .intro { padding: 27px; }
            .intro h2 { font-size: 24px; }
            .intro-note { margin-top: 12px; }
            .card { padding: 28px 23px; }
        }
    </style>
</head>

<body>
<header>
    <a href="/" class="logo">🔧 <span>Bengkel</span>Servis</a>
    <div class="header-label">Sistem Manajemen Bengkel Terintegrasi</div>
</header>

<main>
    <div class="login-layout">
        <section class="intro">
            <div class="intro-icon">🔧</div>
            <h2>Servis Kendaraan Jadi Lebih Mudah</h2>
            <p>
                Selamat datang di BengkelServis. Kelola kebutuhan servis
                kendaraan, booking layanan, dan informasi transaksi
                melalui satu sistem terintegrasi.
            </p>
            <div class="intro-note">
                <strong>Praktis, aman, dan terorganisasi.</strong><br>
                Masuk menggunakan akun yang sudah terdaftar.
            </div>
        </section>

        <section class="card">
            <div class="heading">
                <div class="icon">🔐</div>
                <h1>Selamat Datang!</h1>
                <p>Login untuk melanjutkan ke akun BengkelServis Anda.</p>
            </div>

            @if (session('error'))
                <div class="message error">{{ session('error') }}</div>
            @endif

            @if ($errors->any())
                <div class="message error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            @if (session('success'))
                <div class="message success">{{ session('success') }}</div>
            @endif

            <form action="/login" method="POST">
                @csrf

                <div class="group">
                    <label for="email">Alamat Email</label>
                    <input
                        type="email"
                        id="email"
                        name="email"
                        value="{{ old('email') }}"
                        placeholder="Masukkan email Anda"
                        autocomplete="username"
                        required
                    >
                </div>

                <div class="group">
                    <label for="password">Password</label>
                    <input
                        type="password"
                        id="password"
                        name="password"
                        placeholder="Masukkan password"
                        autocomplete="current-password"
                        required
                    >
                </div>

                <button type="submit">Login ke Akun →</button>
            </form>

            <a href="/register" class="register-button">
                Belum punya akun? Daftar Sekarang
            </a>

            <a href="/" class="back-link">← Kembali ke halaman utama</a>
        </section>
    </div>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis
</footer>
</body>
</html>
