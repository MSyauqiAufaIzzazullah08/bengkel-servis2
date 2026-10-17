
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">

    <title>BengkelServis | Servis Kendaraan</title>

    <style>
        * {
            margin: 0;
            padding: 0;
            box-sizing: border-box;
            scroll-behavior: smooth;
        }

        body {
            font-family: Arial, Helvetica, sans-serif;
            color: #1e293b;
            background: #f8fafc;
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        .navbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 18px 8%;
            background: white;
            box-shadow: 0 2px 12px #0000000d;
            position: sticky;
            top: 0;
            z-index: 100;
        }

        .logo {
            color: #0f766e;
            font-size: 23px;
            font-weight: bold;
        }

        .nav-links {
            display: flex;
            align-items: center;
            gap: 25px;
        }

        .nav-links a {
            color: #475569;
            font-size: 14px;
            font-weight: 600;
        }

        .nav-links a:hover {
            color: #0f766e;
        }

        .btn {
            display: inline-block;
            padding: 12px 22px;
            border-radius: 8px;
            font-weight: bold;
            font-size: 14px;
            transition: 0.2s;
        }

        .btn-primary {
            background: #0f766e;
            color: white;
        }

        .btn-primary:hover {
            background: #115e59;
        }

        .btn-outline {
            border: 1px solid #0f766e;
            color: #0f766e !important;
        }

        .hero {
            padding: 90px 8%;
            background: linear-gradient(135deg, #ecfdf5, #f0fdfa);
            display: grid;
            grid-template-columns: 1.2fr 0.8fr;
            align-items: center;
            gap: 45px;
        }

        .hero-label {
            display: inline-block;
            background: #ccfbf1;
            color: #115e59;
            padding: 7px 13px;
            border-radius: 30px;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 20px;
        }

        .hero h1 {
            font-size: clamp(32px, 4vw, 48px);
            line-height: 1.2;
            margin-bottom: 20px;
            color: #0f172a;
        }

        .hero h1 span {
            color: #0f766e;
        }

        .hero p {
            color: #64748b;
            font-size: 16px;
            max-width: 540px;
            margin-bottom: 28px;
        }

        .hero-buttons {
            display: flex;
            flex-wrap: wrap;
            gap: 12px;
        }

        .hero-visual {
            min-height: 300px;
            border-radius: 25px;
            background: linear-gradient(145deg, #0f766e, #134e4a);
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            color: white;
            text-align: center;
            padding: 35px;
            box-shadow: 0 20px 45px #0f766e30;
        }

        .hero-icon {
            font-size: 90px;
            margin-bottom: 15px;
        }

        .hero-visual h2 {
            font-size: 25px;
            margin-bottom: 8px;
        }

        .hero-visual p {
            color: #ccfbf1;
            margin: 0;
            font-size: 14px;
        }

        section {
            padding: 75px 8%;
        }

        .section-heading {
            text-align: center;
            max-width: 650px;
            margin: 0 auto 40px;
        }

        .section-heading h2 {
            font-size: 32px;
            margin-bottom: 12px;
            color: #0f172a;
        }

        .section-heading p {
            color: #64748b;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .service-card {
            display: block;
            color: inherit;
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 16px;
            padding: 25px;
            transition: transform 0.2s, box-shadow 0.2s, border-color 0.2s;
            cursor: pointer;
        }

        .service-card:hover {
            transform: translateY(-6px);
            border-color: #0f766e;
            box-shadow: 0 14px 30px #0f172a12;
        }

        .service-card:focus-visible {
            outline: 3px solid #0f766e;
            outline-offset: 4px;
        }

        .service-icon {
            width: 55px;
            height: 55px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 12px;
            background: #ccfbf1;
            font-size: 27px;
            margin-bottom: 20px;
        }

        .service-card h3 {
            font-size: 19px;
            margin-bottom: 10px;
            color: #0f172a;
        }

        .service-card p {
            font-size: 14px;
            color: #64748b;
            margin-bottom: 18px;
        }

        .service-link {
            color: #0f766e;
            font-size: 14px;
            font-weight: bold;
        }

        .steps {
            background: #f1f5f9;
        }

        .step-grid {
            display: grid;
            grid-template-columns: repeat(3, 1fr);
            gap: 25px;
        }

        .step-card {
            background: white;
            border-radius: 14px;
            padding: 25px;
            text-align: center;
        }

        .step-number {
            width: 45px;
            height: 45px;
            margin: 0 auto 15px;
            display: flex;
            align-items: center;
            justify-content: center;
            background: #0f766e;
            color: white;
            border-radius: 50%;
            font-size: 20px;
            font-weight: bold;
        }

        .step-card h3 {
            margin-bottom: 8px;
        }

        .step-card p {
            font-size: 14px;
            color: #64748b;
        }

        .cta {
            padding: 65px 8%;
            text-align: center;
            background: #0f766e;
            color: white;
        }

        .cta h2 {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .cta p {
            color: #ccfbf1;
            margin-bottom: 25px;
        }

        .cta .btn {
            background: white;
            color: #0f766e;
        }

        footer {
            padding: 25px 8%;
            text-align: center;
            color: #64748b;
            background: white;
            font-size: 13px;
        }

        @media (max-width: 800px) {
            .navbar {
                padding: 16px 5%;
                flex-wrap: wrap;
                gap: 15px;
            }

            .nav-links {
                gap: 14px;
                flex-wrap: wrap;
            }

            .hero {
                grid-template-columns: 1fr;
                padding: 60px 6%;
            }

            .hero-visual {
                min-height: 230px;
            }

            section {
                padding: 55px 6%;
            }

            .service-grid,
            .step-grid {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 480px) {
            .navbar {
                justify-content: center;
            }

            .nav-links {
                justify-content: center;
            }

            .hero-buttons {
                flex-direction: column;
            }

            .hero-buttons .btn {
                text-align: center;
            }
        }
    </style>
</head>

<body>

<header class="navbar">
    <a href="/" class="logo">🔧 BengkelServis</a>

    <nav class="nav-links">
        <a href="/">Beranda</a>
        <a href="#layanan">Layanan</a>
        <a href="#cara-kerja">Cara Kerja</a>
        <a href="/login" class="btn btn-outline">Login</a>
    </nav>
</header>

<main>
    <section class="hero">
        <div>
            <span class="hero-label">Solusi Servis Kendaraan Anda</span>

            <h1>
                Servis Kendaraan Jadi Lebih
                <span>Mudah dan Praktis</span>
            </h1>

            <p>
                Temukan layanan servis kendaraan, lakukan booking,
                dan pantau proses servis melalui satu sistem
                yang terintegrasi.
            </p>

            <div class="hero-buttons">
                <a href="#layanan" class="btn btn-primary">
                    Lihat Layanan
                </a>

                <a href="/login" class="btn btn-outline">
                    Mulai Sekarang
                </a>
            </div>
        </div>

        <div class="hero-visual">
            <div class="hero-icon">🚗</div>
            <h2>Kendaraan Terawat</h2>
            <p>Perawatan lebih mudah, pelayanan lebih teratur.</p>
        </div>
    </section>

    <section id="layanan">
        <div class="section-heading">
            <h2>Layanan Kami</h2>
            <p>
                Klik salah satu layanan untuk melihat informasi
                lebih lanjut.
            </p>
        </div>

        <div class="service-grid">

            <a href="/layanan-publik" class="service-card">
                <div class="service-icon">🛠️</div>
                <h3>Servis Kendaraan</h3>
                <p>
                    Lihat daftar layanan perawatan dan perbaikan
                    kendaraan yang tersedia di bengkel kami.
                </p>
                <span class="service-link">Lihat layanan →</span>
            </a>

            <a href="/home-service-publik" class="service-card">
                <div class="service-icon">🏠</div>
                <h3>Home Service</h3>
                <p>
                    Kenali layanan servis kendaraan di lokasi Anda
                    sesuai cakupan dan ketersediaan montir.
                </p>
                <span class="service-link">Lihat detail →</span>
            </a>

            <a href="/sparepart-publik" class="service-card">
                <div class="service-icon">⚙️</div>
                <h3>Spare Part</h3>
                <p>
                    Kenali kebutuhan suku cadang untuk mendukung
                    perawatan kendaraan Anda.
                </p>
                <span class="service-link">Lihat detail →</span>
            </a>

        </div>
    </section>

    <section class="steps" id="cara-kerja">
        <div class="section-heading">
            <h2>Bagaimana Cara Menggunakannya?</h2>
            <p>
                Mulai dari mengenal layanan sampai melakukan pemesanan.
            </p>
        </div>

        <div class="step-grid">
            <article class="step-card">
                <div class="step-number">1</div>
                <h3>Lihat Layanan</h3>
                <p>
                    Kenali layanan bengkel sebelum membuat akun.
                </p>
            </article>

            <article class="step-card">
                <div class="step-number">2</div>
                <h3>Daftar dan Login</h3>
                <p>
                    Masuk ke akun pelanggan untuk mengakses fitur pemesanan.
                </p>
            </article>

            <article class="step-card">
                <div class="step-number">3</div>
                <h3>Pesan Servis</h3>
                <p>
                    Lanjutkan pemesanan melalui portal pelanggan.
                </p>
            </article>
        </div>
    </section>

    <section class="cta">
        <h2>Rawat Kendaraan Anda Bersama Kami</h2>
        <p>
            Masuk ke akun Anda untuk melanjutkan ke layanan pelanggan.
        </p>
        <a href="/login" class="btn">Login Pelanggan</a>
    </section>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis.
    Sistem Manajemen Bengkel dan Layanan Servis Kendaraan.
</footer>

</body>
</html>
