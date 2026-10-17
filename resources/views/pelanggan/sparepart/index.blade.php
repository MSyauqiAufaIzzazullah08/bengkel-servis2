
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Katalog Spare Part | BengkelServis</title>

    <style>
        * { box-sizing: border-box; }

        :root {
            --navy: #102b55;
            --orange: #f5a623;
            --bg: #f3f6fc;
            --border: #d9e3f2;
            --muted: #71829d;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--navy);
            font-size: 14px;
        }

        header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        a { text-decoration: none; }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: var(--navy);
        }

        .logo span { color: var(--orange); }

        .nav-links {
            display: flex;
            gap: 18px;
            flex-wrap: wrap;
        }

        .nav-links a {
            color: var(--navy);
            font-size: 12px;
            font-weight: bold;
        }

        .container {
            width: min(1100px, 92%);
            margin: 32px auto;
        }

        .breadcrumb {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 10px;
        }

        h1 { font-size: 26px; margin: 0 0 8px; }
        h2 { font-size: 17px; }

        .subtitle {
            color: var(--muted);
            line-height: 1.6;
            margin: 0 0 23px;
        }

        .hero {
            padding: 25px;
            background: linear-gradient(110deg, #102b55, #244b83);
            color: white;
            border-radius: 13px;
            margin-bottom: 25px;
        }

        .hero h2 { margin: 0 0 8px; }
        .hero p { margin: 0; line-height: 1.7; color: #e1e9f6; }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 15px;
        }

        .toolbar h2 { margin: 0; }

        .badge {
            display: inline-block;
            padding: 6px 10px;
            background: #fff1d6;
            color: #9a6200;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
        }

        .product {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 4px 14px #102b5508;
            display: flex;
            flex-direction: column;
        }

        .product-icon {
            width: 48px;
            height: 48px;
            border-radius: 11px;
            background: #fff1d6;
            display: grid;
            place-items: center;
            font-size: 25px;
            margin-bottom: 15px;
        }

        .category {
            font-size: 11px;
            color: #9a6200;
            background: #fff5e3;
            border-radius: 20px;
            padding: 5px 8px;
            display: inline-block;
            margin-bottom: 10px;
        }

        .product h3 {
            font-size: 16px;
            margin: 0 0 8px;
        }

        .description {
            color: var(--muted);
            line-height: 1.6;
            min-height: 40px;
            margin: 0 0 12px;
        }

        .price {
            font-size: 21px;
            font-weight: bold;
            margin: 0 0 8px;
        }

        .stock {
            color: #16834b;
            font-size: 12px;
            margin-bottom: 15px;
        }

        .product form { margin-top: auto; }

        label {
            display: block;
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        input {
            width: 100%;
            border: 1px solid #cbd7e8;
            padding: 11px;
            border-radius: 7px;
            margin-bottom: 12px;
        }

        .btn {
            display: inline-block;
            text-align: center;
            border: 0;
            border-radius: 7px;
            padding: 12px 15px;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
        }

        .btn-primary {
            background: var(--navy);
            color: white;
            width: 100%;
        }

        .btn-primary:hover { background: #1c4074; }

        .message {
            padding: 13px;
            border-radius: 8px;
            margin-bottom: 18px;
        }

        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }

        .empty {
            background: white;
            border: 1px dashed #cbd7e8;
            border-radius: 12px;
            padding: 40px 20px;
            text-align: center;
            color: var(--muted);
            line-height: 1.7;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .grid { grid-template-columns: repeat(2, minmax(0, 1fr)); }
        }

        @media (max-width: 540px) {
            header { padding: 16px 5%; align-items: flex-start; }
            .nav-links { gap: 10px; justify-content: flex-end; }
            .grid { grid-template-columns: 1fr; }
            h1 { font-size: 23px; }
            .container { margin-top: 24px; }
        }
    </style>
</head>
<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">🔧 Bengkel<span>Servis</span></a>

    <nav class="nav-links">
        <a href="/dashboard-pelanggan">Dashboard</a>
        <a href="/order-pelanggan">Pesanan Saya</a>
        <a href="/keranjang-sparepart">🛒 Keranjang</a>
    </nav>
</header>

<main class="container">
    <div class="breadcrumb">Beranda / Spare Part / Katalog</div>

    <h1>Katalog Spare Part</h1>
    <p class="subtitle">
        Temukan suku cadang kendaraan yang kamu butuhkan.
    </p>

    <section class="hero">
        <h2>🧰 Suku Cadang Kendaraan Berkualitas</h2>
        <p>
            Pilih spare part, tentukan jumlahnya, lalu tambahkan ke keranjang.
            Stok akan diperiksa kembali saat pesanan diproses.
        </p>
    </section>

    @if (session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif

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

    <div class="toolbar">
        <h2>Spare Part Tersedia</h2>
        <span class="badge">{{ $spareparts->count() }} barang</span>
    </div>

    @if ($spareparts->isEmpty())
        <div class="empty">
            <div style="font-size:36px">📦</div>
            <strong>Belum Ada Spare Part Tersedia</strong>
            <p>Silakan cek kembali nanti atau hubungi pihak bengkel.</p>
        </div>
    @else
        <div class="grid">
            @foreach ($spareparts as $item)
                <article class="product">
                    <div class="product-icon">🔩</div>

                    <div>
                        <span class="category">
                            {{ $item->kategori->nama_kategori ?? 'Spare Part' }}
                        </span>
                    </div>

                    <h3>{{ $item->nama }}</h3>

                    <p class="description">
                        {{ $item->deskripsi ?: 'Suku cadang kendaraan tersedia untuk kebutuhan servis.' }}
                    </p>

                    <p class="price">
                        Rp {{ number_format($item->harga, 0, ',', '.') }}
                    </p>

                    <div class="stock">
                        ✓ Stok tersedia: {{ $item->stok }}
                    </div>

                    <form action="/keranjang-sparepart" method="POST">
                        @csrf

                        <input
                            type="hidden"
                            name="id_sparepart"
                            value="{{ $item->id_sparepart }}">

                        <label for="jumlah-{{ $item->id_sparepart }}">
                            Jumlah
                        </label>

                        <input
                            type="number"
                            id="jumlah-{{ $item->id_sparepart }}"
                            name="jumlah"
                            min="1"
                            max="{{ $item->stok }}"
                            value="1"
                            required>

                        <button type="submit" class="btn btn-primary">
                            🛒 Tambahkan ke Keranjang
                        </button>
                    </form>
                </article>
            @endforeach
        </div>
    @endif
</main>

<footer>&copy; {{ date('Y') }} BengkelServis — Portal Pelanggan</footer>

</body>
</html>
