
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>BengkelServis | Servis Kendaraan</title>
    <meta name="description" content="Temukan layanan servis kendaraan, booking, home service, dan spare part melalui BengkelServis.">

    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">

    <style>
        :root {
            --primary: #087f73;
            --primary-dark: #075e56;
            --mint: #e9fbf5;
            --text: #172b3c;
            --muted: #64748b;
            --border: #e1e9ee;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        html {
            scroll-behavior: smooth;
            scroll-padding-top: 85px;
        }

        body {
            font-family: 'Inter', sans-serif;
            color: var(--text);
            background: #f7f9fc;
            line-height: 1.6;
        }

        a {
            color: inherit;
            text-decoration: none;
        }

        .container {
            width: min(1200px, calc(100% - 48px));
            margin: auto;
        }

        /* NAVBAR */
        .navbar {
            position: sticky;
            top: 0;
            z-index: 100;
            background: rgba(255, 255, 255, .97);
            border-bottom: 1px solid #edf1f4;
            backdrop-filter: blur(12px);
        }

        .nav-inner {
            min-height: 76px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 24px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            color: var(--primary);
            font-size: 21px;
            font-weight: 800;
            letter-spacing: -.7px;
        }

        .brand-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border-radius: 13px;
            background: #e3faf3;
            font-size: 22px;
        }

        .nav-menu {
            display: flex;
            align-items: center;
            gap: 30px;
        }

        .nav-menu > a:not(.btn) {
            color: #475569;
            font-size: 13px;
            font-weight: 600;
        }

        .nav-menu > a:not(.btn):hover {
            color: var(--primary);
        }

        /* BUTTON */
        .btn {
            min-height: 45px;
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 8px;
            padding: 0 21px;
            border: 1px solid transparent;
            border-radius: 9px;
            font-size: 13px;
            font-weight: 700;
            cursor: pointer;
            transition: .2s ease;
        }

        .btn-primary {
            color: white;
            background: var(--primary);
        }

        .btn-primary:hover {
            background: var(--primary-dark);
            transform: translateY(-2px);
        }

        .btn-outline {
            color: var(--primary);
            background: transparent;
            border-color: var(--primary);
        }

        .btn-outline:hover {
            background: #e8faf5;
        }

        /* HERO - BACKGROUND FOTO BENGKEL */
        .hero {
            position: relative;
            isolation: isolate;
            min-height: 475px;
            display: flex;
            align-items: center;
            padding: 75px 0;
            overflow: hidden;
            background-image:
                linear-gradient(
                    90deg,
                    rgba(233, 251, 245, .97) 0%,
                    rgba(233, 251, 245, .92) 35%,
                    rgba(233, 251, 245, .76) 60%,
                    rgba(233, 251, 245, .48) 100%
                ),
                url("{{ asset('assets/images/bengkel2.jpeg') }}");
            background-size: cover;
            background-position: center 55%;
            background-repeat: no-repeat;
        }

        .hero-content {
            position: relative;
            z-index: 1;
            max-width: 680px;
        }

        .eyebrow {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            padding: 8px 14px;
            margin-bottom: 23px;
            border-radius: 30px;
            color: var(--primary-dark);
            background: rgba(201, 247, 235, .94);
            font-size: 11px;
            font-weight: 700;
        }

        .eyebrow-dot {
            width: 7px;
            height: 7px;
            border-radius: 50%;
            background: #0d9488;
        }

        .hero h1 {
            max-width: 660px;
            margin-bottom: 20px;
            font-size: clamp(36px, 4.5vw, 55px);
            font-weight: 800;
            line-height: 1.15;
            letter-spacing: -1.8px;
        }

        .hero h1 span {
            color: var(--primary);
        }

        .hero-description {
            max-width: 490px;
            margin-bottom: 29px;
            color: #405b63;
            font-size: 14px;
            line-height: 1.9;
        }

        .hero-actions {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-note {
            display: flex;
            align-items: center;
            gap: 9px;
            margin-top: 27px;
            color: #405b63;
            font-size: 11px;
        }

        .hero-note strong {
            color: var(--primary-dark);
        }

        /* SECTION */
        .section {
            padding: 78px 0;
        }

        .section-heading {
            max-width: 650px;
            margin: 0 auto 38px;
            text-align: center;
        }

        .section-label {
            margin-bottom: 9px;
            color: var(--primary);
            font-size: 11px;
            font-weight: 800;
            text-transform: uppercase;
            letter-spacing: 1.5px;
        }

        .section-heading h2 {
            margin-bottom: 11px;
            font-size: clamp(26px, 3vw, 34px);
            font-weight: 800;
            letter-spacing: -.8px;
        }

        .section-heading p {
            color: var(--muted);
            font-size: 13px;
        }

        /* KARTU LAYANAN */
        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 20px;
        }

        .service-card {
            display: block;
            padding: 27px;
            border: 1px solid var(--border);
            border-radius: 16px;
            color: inherit;
            background: white;
            transition: transform .25s, box-shadow .25s, border-color .25s;
        }

        .service-card:hover {
            transform: translateY(-5px);
            border-color: #a7e9dc;
            box-shadow: 0 15px 35px rgba(15, 76, 68, .07);
        }

        .service-card:focus-visible {
            outline: 3px solid var(--primary);
            outline-offset: 4px;
        }

        .service-icon {
            width: 52px;
            height: 52px;
            display: grid;
            place-items: center;
            margin-bottom: 19px;
            border-radius: 14px;
            background: #e3faf3;
            font-size: 25px;
        }

        .service-card h3 {
            margin-bottom: 10px;
            font-size: 17px;
        }

        .service-card p {
            min-height: 48px;
            margin-bottom: 17px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
        }

        .service-link {
            display: inline-flex;
            align-items: center;
            gap: 8px;
            color: var(--primary);
            font-size: 12px;
            font-weight: 800;
        }

        /* CARA KERJA */
        .steps-section {
            background: white;
        }

        .steps-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 25px;
        }

        .step {
            padding: 25px;
            text-align: center;
        }

        .step-number {
            width: 53px;
            height: 53px;
            display: grid;
            place-items: center;
            margin: 0 auto 17px;
            border-radius: 16px;
            color: var(--primary);
            background: #e3faf3;
            font-size: 20px;
            font-weight: 800;
        }

        .step h3 {
            margin-bottom: 8px;
            font-size: 16px;
        }

        .step p {
            color: var(--muted);
            font-size: 12px;
        }

        /* AJAKAN DAFTAR */
        .cta {
            padding: 0 0 75px;
            background: white;
        }

        .cta-box {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 25px;
            padding: 38px 42px;
            border-radius: 20px;
            color: white;
            background: linear-gradient(120deg, #087f73, #075e56);
        }

        .cta-box h2 {
            margin-bottom: 7px;
            font-size: 25px;
        }

        .cta-box p {
            color: #d7f4ee;
            font-size: 12px;
        }

        .cta-box .btn {
            flex-shrink: 0;
            color: var(--primary-dark);
            background: white;
        }

        /* FOOTER */
        .footer {
            padding: 30px 0;
            color: #d1e6e1;
            background: #102f32;
        }

        .footer-inner {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
        }

        .footer-brand {
            color: white;
            font-size: 16px;
            font-weight: 800;
        }

        .footer p {
            margin-top: 5px;
            color: #a7c7c0;
            font-size: 11px;
        }

        .footer-links {
            display: flex;
            flex-wrap: wrap;
            gap: 20px;
            font-size: 11px;
        }

        .footer-links a:hover {
            color: white;
        }

        /* RESPONSIVE */
        @media (max-width: 900px) {
            .hero {
                min-height: 440px;
                padding: 65px 0;
                background-position: center;
            }

            .service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 600px) {
            .container {
                width: min(100% - 32px, 1200px);
            }

            .nav-inner {
                min-height: 68px;
                gap: 12px;
            }

            .brand {
                gap: 8px;
                font-size: 17px;
            }

            .brand-icon {
                width: 36px;
                height: 36px;
                font-size: 19px;
            }

            .nav-menu {
                gap: 12px;
            }

            .nav-menu > a:not(.btn) {
                display: none;
            }

            .nav-menu .btn {
                min-height: 39px;
                padding: 0 14px;
                font-size: 11px;
            }

            .hero {
                min-height: 480px;
                padding: 62px 0;
                background-position: center;
                background-image:
                    linear-gradient(
                        90deg,
                        rgba(233, 251, 245, .95),
                        rgba(233, 251, 245, .79)
                    ),
                    url("{{ asset('assets/images/bengkel2.jpeg') }}");
            }

            .hero h1 {
                font-size: 36px;
                letter-spacing: -1.2px;
            }

            .hero-description {
                font-size: 13px;
            }

            .section {
                padding: 55px 0;
            }

            .service-grid,
            .steps-grid {
                grid-template-columns: 1fr;
            }

            .service-card p {
                min-height: auto;
            }

            .cta {
                padding-bottom: 50px;
            }

            .cta-box {
                align-items: flex-start;
                flex-direction: column;
                padding: 27px 23px;
            }

            .cta-box h2 {
                font-size: 22px;
            }

            .footer-inner {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>
<body>

    <!-- NAVBAR -->
    <header class="navbar">
        <div class="container nav-inner">
            <a href="/" class="brand">
                <span class="brand-icon">🔧</span>
                <span>BengkelServis</span>
            </a>

            <nav class="nav-menu" aria-label="Navigasi utama">
                <a href="#beranda">Beranda</a>
                <a href="#layanan">Layanan</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="{{ url('/login') }}" class="btn btn-outline">Login</a>
            </nav>
        </div>
    </header>

    <!-- HERO -->
    <section class="hero" id="beranda">
        <div class="container">
            <div class="hero-content">

                <div class="eyebrow">
                    <span class="eyebrow-dot"></span>
                    Solusi Servis Kendaraan Anda
                </div>

                <h1>
                    Servis Kendaraan Jadi Lebih
                    <span>Mudah dan Praktis</span>
                </h1>

                <p class="hero-description">
                    Temukan layanan servis kendaraan, lakukan booking,
                    dan pantau proses servis melalui satu sistem yang
                    terintegrasi. Perawatan kendaraan jadi lebih mudah
                    tanpa ribet.
                </p>

                <div class="hero-actions">
                    <a href="#layanan" class="btn btn-primary">
                        Lihat Layanan &rarr;
                    </a>

                    <a href="{{ url('/register') }}" class="btn btn-outline">
                        Mulai Sekarang
                    </a>
                </div>

                <div class="hero-note">
                    <span>✓</span>
                    <span>
                        <strong>Praktis dan terintegrasi</strong>
                        untuk kebutuhan servis kendaraanmu.
                    </span>
                </div>

            </div>
        </div>
    </section>

    <!-- LAYANAN: LINK PUBLIK DIPERTAHANKAN -->
    <section class="section" id="layanan">
        <div class="container">

            <div class="section-heading">
                <div class="section-label">Layanan Kami</div>
                <h2>Semua Kebutuhan Servis dalam Satu Tempat</h2>
                <p>
                    Pilih layanan yang sesuai dengan kebutuhan kendaraanmu.
                </p>
            </div>

            <div class="service-grid">

                <a href="{{ url('/layanan-publik') }}" class="service-card">
                    <div class="service-icon">🔧</div>
                    <h3>Servis Kendaraan</h3>
                    <p>
                        Lihat daftar layanan perawatan dan perbaikan kendaraan
                        yang tersedia di bengkel kami.
                    </p>
                    <span class="service-link">Lihat layanan &rarr;</span>
                </a>

                <a href="{{ url('/home-service-publik') }}" class="service-card">
                    <div class="service-icon">🏠</div>
                    <h3>Home Service</h3>
                    <p>
                        Kenali layanan servis kendaraan di lokasi Anda
                        sesuai cakupan dan ketersediaan montir.
                    </p>
                    <span class="service-link">Lihat detail &rarr;</span>
                </a>

                <a href="{{ url('/sparepart-publik') }}" class="service-card">
                    <div class="service-icon">⚙️</div>
                    <h3>Spare Part</h3>
                    <p>
                        Kenali kebutuhan suku cadang untuk mendukung
                        perawatan kendaraan Anda.
                    </p>
                    <span class="service-link">Lihat detail &rarr;</span>
                </a>

            </div>
        </div>
    </section>

    <!-- CARA KERJA -->
    <section class="section steps-section" id="cara-kerja">
        <div class="container">

            <div class="section-heading">
                <div class="section-label">Cara Kerja</div>
                <h2>Mulai Servis dengan Tiga Langkah</h2>
                <p>
                    Proses sederhana untuk membantu kebutuhan perawatan
                    kendaraanmu.
                </p>
            </div>

            <div class="steps-grid">
                <article class="step">
                    <div class="step-number">01</div>
                    <h3>Lihat Layanan</h3>
                    <p>
                        Kenali layanan bengkel sebelum membuat akun.
                    </p>
                </article>

                <article class="step">
                    <div class="step-number">02</div>
                    <h3>Daftar dan Login</h3>
                    <p>
                        Masuk ke akun pelanggan untuk mengakses fitur pemesanan.
                    </p>
                </article>

                <article class="step">
                    <div class="step-number">03</div>
                    <h3>Pesan Servis</h3>
                    <p>
                        Lanjutkan pemesanan melalui portal pelanggan.
                    </p>
                </article>
            </div>

        </div>
    </section>

    <!-- AJAKAN DAFTAR -->
    <section class="cta">
        <div class="container">
            <div class="cta-box">
                <div>
                    <h2>Rawat Kendaraanmu Bersama Kami</h2>
                    <p>
                        Daftar sekarang untuk melanjutkan ke layanan pelanggan.
                    </p>
                </div>

                <a href="{{ url('/register') }}" class="btn">
                    Daftar Sekarang &rarr;
                </a>
            </div>
        </div>
    </section>

    <!-- FOOTER -->
    <footer class="footer">
        <div class="container footer-inner">
            <div>
                <a href="/" class="footer-brand">🔧 BengkelServis</a>
                <p>
                    &copy; {{ date('Y') }} BengkelServis.
                    Sistem Manajemen Bengkel dan Layanan Servis Kendaraan.
                </p>
            </div>

            <div class="footer-links">
                <a href="#beranda">Beranda</a>
                <a href="#layanan">Layanan</a>
                <a href="#cara-kerja">Cara Kerja</a>
                <a href="{{ url('/login') }}">Login</a>
            </div>
        </div>
    </footer>

</body>
</html>
