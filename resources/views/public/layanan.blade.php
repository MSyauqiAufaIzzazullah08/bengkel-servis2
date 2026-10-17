
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Layanan Servis | BengkelServis</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            box-shadow: 0 2px 10px #0000000d;
        }

        header a {
            color: #0f766e;
            text-decoration: none;
            font-weight: bold;
        }

        main {
            max-width: 1150px;
            margin: 45px auto;
            padding: 0 22px;
        }

        .heading {
            margin-bottom: 30px;
        }

        .heading h1 {
            color: #0f766e;
            font-size: 32px;
        }

        .heading p {
            color: #64748b;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(auto-fit, minmax(250px, 1fr));
            gap: 22px;
        }

        .card {
            background: white;
            border: 1px solid #e2e8f0;
            border-radius: 15px;
            padding: 25px;
            transition: 0.2s;
        }

        .card:hover {
            transform: translateY(-4px);
            box-shadow: 0 12px 25px #0f172a0d;
        }

        .icon {
            font-size: 35px;
        }

        .card h2 {
            font-size: 20px;
        }

        .description {
            color: #64748b;
            line-height: 1.7;
        }

        .price {
            color: #0f766e;
            font-size: 20px;
            font-weight: bold;
        }

        .btn {
            display: inline-block;
            padding: 11px 16px;
            border-radius: 8px;
            background: #0f766e;
            color: white;
            text-decoration: none;
            font-weight: bold;
            font-size: 14px;
        }

        .empty {
            padding: 25px;
            background: white;
            border-radius: 12px;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: #64748b;
            background: white;
            margin-top: 60px;
        }

        @media (max-width: 600px) {
            header {
                padding: 18px;
                gap: 15px;
                flex-wrap: wrap;
            }

            .heading h1 {
                font-size: 27px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="/">🔧 BengkelServis</a>
    <a href="/">← Kembali ke Beranda</a>
</header>

<main>
    <div class="heading">
        <h1>Daftar Layanan Servis</h1>
        <p>
            Temukan layanan yang sesuai dengan kebutuhan kendaraan Anda.
            Anda dapat melihat daftar layanan tanpa login.
        </p>
    </div>

    @if ($layanans->isEmpty())
        <div class="empty">
            <h3>Belum ada layanan servis</h3>
            <p>
                Saat ini belum ada layanan yang tersedia.
                Silakan kunjungi kembali nanti.
            </p>
        </div>
    @else
        <div class="grid">
            @foreach ($layanans as $layanan)
                <article class="card">
                    <div class="icon">🛠️</div>

                    <h2>
                        {{ $layanan->nama_layanan
                            ?? $layanan->nama
                            ?? 'Layanan Servis' }}
                    </h2>

                    <p class="description">
                        {{ $layanan->deskripsi
                            ?? 'Layanan perawatan dan perbaikan kendaraan dari BengkelServis.' }}
                    </p>

                    <p class="price">
                        Rp {{ number_format((float) $layanan->harga, 0, ',', '.') }}
                    </p>

                    <a href="/login" class="btn">
                        Login untuk Memesan
                    </a>
                </article>
            @endforeach
        </div>
    @endif
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis
</footer>

</body>
</html>
