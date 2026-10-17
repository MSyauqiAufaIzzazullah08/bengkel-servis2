<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>Layanan Servis | BengkelServis</title>

    <style>
        :root {
            --navy: #102b55;
            --navy-dark: #0b1e3b;
            --navy-light: #234d86;
            --orange: #ffa51f;
            --orange-light: #fff1d7;
            --background: #f3f6fc;
            --white: #ffffff;
            --text: #102b55;
            --muted: #71819b;
            --border: #d9e3f2;
            --green: #15803d;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
            min-height: 100vh;
        }

        a {
            text-decoration: none;
            color: inherit;
        }

        .navbar {
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 18px 7%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .logo {
            color: var(--navy);
            font-size: 20px;
            font-weight: 800;
        }

        .logo span {
            color: var(--orange);
        }

        .nav-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .nav-link:hover {
            color: #c77700;
        }

        .container {
            max-width: 1100px;
            margin: 32px auto;
            padding: 0 22px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 13px;
        }

        .page-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 18px;
            margin-bottom: 24px;
        }

        h1 {
            font-size: 27px;
            margin-bottom: 8px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .btn {
            display: inline-block;
            padding: 12px 17px;
            border-radius: 7px;
            background: var(--navy);
            color: white;
            font-size: 12px;
            font-weight: bold;
            border: 1px solid var(--navy);
            text-align: center;
            transition: 0.2s;
        }

        .btn:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }

        .btn-orange {
            background: var(--orange);
            border-color: var(--orange);
            color: var(--navy-dark);
        }

        .btn-orange:hover {
            background: #f3a018;
        }

        .hero {
            background: linear-gradient(115deg, var(--navy-dark), var(--navy-light));
            color: white;
            padding: 28px;
            border-radius: 14px;
            margin-bottom: 27px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: 0 8px 20px #102b5517;
        }

        .hero h2 {
            font-size: 20px;
            margin-bottom: 9px;
        }

        .hero p {
            color: #e2eaf7;
            font-size: 13px;
            line-height: 1.8;
            max-width: 600px;
        }

        .hero-icon {
            font-size: 48px;
            background: #ffffff15;
            border: 1px solid #ffffff20;
            border-radius: 14px;
            padding: 14px;
        }

        .section-heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 12px;
            margin-bottom: 16px;
        }

        .section-heading h2 {
            font-size: 18px;
        }

        .count {
            background: var(--orange-light);
            color: #8a4b00;
            padding: 7px 11px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .service-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 21px;
            box-shadow: 0 3px 10px #102b5507;
            display: flex;
            flex-direction: column;
            min-height: 245px;
            transition: 0.2s;
        }

        .service-card:hover {
            border-color: var(--orange);
            box-shadow: 0 8px 22px #102b5510;
            transform: translateY(-3px);
        }

        .service-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: var(--orange-light);
            border-radius: 10px;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .category {
            display: inline-block;
            width: fit-content;
            background: #edf3fc;
            color: var(--navy);
            border-radius: 20px;
            padding: 5px 9px;
            font-size: 10px;
            font-weight: bold;
            margin-bottom: 10px;
        }

        .service-card h3 {
            font-size: 16px;
            margin-bottom: 10px;
            line-height: 1.5;
        }

        .description {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
            margin-bottom: 14px;
            flex: 1;
            overflow-wrap: anywhere;
        }

        .service-info {
            border-top: 1px solid var(--border);
            padding-top: 14px;
            margin-top: auto;
        }

        .price-label {
            color: var(--muted);
            font-size: 11px;
            margin-bottom: 5px;
        }

        .price {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            margin-bottom: 13px;
        }

        .duration {
            display: inline-block;
            color: #8a4b00;
            background: var(--orange-light);
            border-radius: 6px;
            padding: 6px 9px;
            font-size: 11px;
            margin-bottom: 14px;
        }

        .service-info .btn {
            display: block;
            width: 100%;
        }

        .empty-state {
            background: white;
            border: 1px dashed #bdcce0;
            border-radius: 12px;
            text-align: center;
            padding: 45px 20px;
        }

        .empty-icon {
            font-size: 42px;
            margin-bottom: 14px;
        }

        .empty-state h3 {
            margin-bottom: 9px;
        }

        .empty-state p {
            color: var(--muted);
            font-size: 13px;
            margin-bottom: 18px;
            line-height: 1.7;
        }

        footer {
            padding: 24px 15px;
            text-align: center;
            color: var(--muted);
            font-size: 11px;
        }

        @media (max-width: 850px) {
            .service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .navbar {
                padding: 16px 5%;
            }

            .container {
                margin: 24px auto;
                padding: 0 15px;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            h1 {
                font-size: 23px;
            }

            .hero {
                padding: 22px;
            }

            .hero-icon {
                display: none;
            }

            .service-grid {
                grid-template-columns: 1fr;
            }

            .service-card {
                min-height: 220px;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <a href="/dashboard-pelanggan" class="logo">
        🔧 Bengkel<span>Servis</span>
    </a>

    <a href="/dashboard-pelanggan" class="nav-link">
        ← Dashboard Pelanggan
    </a>
</header>

<main class="container">

    <div class="breadcrumb">
        Beranda / Layanan Servis
    </div>

    <div class="page-heading">
        <div>
            <h1>Layanan Servis</h1>
            <p class="subtitle">
                Temukan layanan perawatan dan perbaikan kendaraan
                sesuai kebutuhanmu.
            </p>
        </div>

        <a href="/booking-pelanggan/create" class="btn">
            + Booking Servis
        </a>
    </div>

    <section class="hero">
        <div>
            <h2>Rawat Kendaraanmu Bersama BengkelServis! 🛠️</h2>
            <p>
                Pilih layanan yang kamu butuhkan, periksa harga dan
                estimasi pengerjaannya, lalu ajukan booking servis
                dengan mudah melalui portal pelanggan.
            </p>
        </div>

        <div class="hero-icon">🚘</div>
    </section>

    <div class="section-heading">
        <h2>Daftar Layanan Tersedia</h2>

        <span class="count">
            {{ $layanans->count() }} layanan
        </span>
    </div>

    @if ($layanans->isEmpty())

        <section class="empty-state">
            <div class="empty-icon">🔧</div>

            <h3>Belum Ada Layanan</h3>

            <p>
                Saat ini belum ada layanan servis yang tersedia.
                Silakan kembali lagi nanti.
            </p>

            <a href="/dashboard-pelanggan" class="btn">
                Kembali ke Dashboard
            </a>
        </section>

    @else

        <div class="service-grid">

            @foreach ($layanans as $layanan)

                <article class="service-card">

                    <div class="service-icon">🔧</div>

                    @if (!empty($layanan->kategori))
                        <span class="category">
                            {{ $layanan->kategori }}
                        </span>
                    @endif

                    <h3>{{ $layanan->nama_layanan }}</h3>

                    <p class="description">
                        {{ $layanan->deskripsi ?: 'Layanan perawatan dan perbaikan kendaraan sesuai kebutuhan pelanggan.' }}
                    </p>

                    <div class="service-info">

                        <p class="price-label">Harga layanan</p>

                        <div class="price">
                            Rp {{ number_format((float) $layanan->harga, 0, ',', '.') }}
                        </div>

                        @if (!empty($layanan->estimasi_waktu))
                            <div class="duration">
                                ⏱ Estimasi: {{ $layanan->estimasi_waktu }}
                            </div>
                        @endif

                        <a
                            href="{{ url('/booking-pelanggan/create') }}"
                            class="btn btn-orange"
                        >
                            Booking Layanan →
                        </a>

                    </div>

                </article>

            @endforeach

        </div>

    @endif

</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
```