
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Spare Part | BengkelServis</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, Helvetica, sans-serif;
            background: #f8fafc;
            color: #1e293b;
        }

        header {
            background: white;
            padding: 20px 8%;
            box-shadow: 0 2px 10px #0000000d;
        }

        header a {
            color: #0f766e;
            text-decoration: none;
            font-weight: bold;
        }

        main {
            max-width: 1050px;
            margin: 50px auto;
            padding: 0 22px;
        }

        .hero {
            background: linear-gradient(135deg, #ecfdf5, #f0fdfa);
            border-radius: 20px;
            padding: 40px;
            text-align: center;
        }

        .hero-icon {
            font-size: 65px;
            margin-bottom: 15px;
        }

        h1 {
            color: #0f766e;
            font-size: 32px;
        }

        .hero p {
            max-width: 650px;
            margin: 0 auto 25px;
            line-height: 1.8;
            color: #64748b;
        }

        .info-grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
            gap: 20px;
            margin-top: 30px;
        }

        .info-card {
            background: white;
            padding: 25px;
            border-radius: 14px;
            border: 1px solid #e2e8f0;
        }

        .info-card h3 {
            margin-top: 0;
        }

        .info-card p {
            color: #64748b;
            line-height: 1.7;
        }

        .btn {
            display: inline-block;
            padding: 12px 20px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            border-radius: 8px;
            font-weight: bold;
        }

        footer {
            margin-top: 50px;
            padding: 25px;
            background: white;
            text-align: center;
            color: #64748b;
        }

        @media (max-width: 600px) {
            header {
                padding: 18px;
            }

            .hero {
                padding: 25px;
            }

            h1 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="/">🔧 BengkelServis — Kembali ke Beranda</a>
</header>

<main>
    <section class="hero">
        <div class="hero-icon">⚙️</div>

        <h1>Spare Part Kendaraan</h1>

        <p>
            Temukan informasi mengenai suku cadang yang mendukung
            perawatan dan perbaikan kendaraan Anda melalui BengkelServis.
        </p>

        <a href="/login" class="btn">
            Login untuk Melanjutkan
        </a>
    </section>

    <section class="info-grid">
        <article class="info-card">
            <h3>🔩 Suku Cadang</h3>
            <p>
                Suku cadang digunakan untuk mengganti komponen kendaraan
                yang rusak atau sudah perlu diperbarui.
            </p>
        </article>

        <article class="info-card">
            <h3>🛠️ Perawatan Kendaraan</h3>
            <p>
                Pemilihan suku cadang yang sesuai membantu mendukung
                perawatan dan kinerja kendaraan.
            </p>
        </article>

        <article class="info-card">
            <h3>📦 Pemesanan</h3>
            <p>
                Pelanggan dapat masuk ke akun untuk melanjutkan proses
                pemesanan melalui sistem.
            </p>
        </article>
    </section>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis
</footer>

</body>
</html>
