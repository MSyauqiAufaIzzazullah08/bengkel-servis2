
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Checkout Spare Part | BengkelServis</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #102b52;
        }

        header {
            background: #fff;
            padding: 18px 7%;
            border-bottom: 1px solid #dce5f2;
            display: flex;
            justify-content: space-between;
            align-items: center;
        }

        .logo {
            font-size: 20px;
            font-weight: bold;
            color: #102b52;
            text-decoration: none;
        }

        header a:last-child {
            color: #102b52;
            font-size: 13px;
            text-decoration: none;
        }

        .container {
            width: min(100% - 32px, 900px);
            margin: 32px auto;
        }

        .heading { margin-bottom: 22px; }

        .breadcrumb {
            font-size: 12px;
            color: #71829b;
            margin-bottom: 12px;
        }

        h1 { font-size: 27px; margin: 0 0 9px; }

        .subtitle {
            color: #71829b;
            font-size: 14px;
            line-height: 1.6;
        }

        .layout {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 20px;
            align-items: start;
        }

        .card {
            background: white;
            border: 1px solid #d9e3f1;
            border-radius: 13px;
            padding: 23px;
            box-shadow: 0 5px 18px #102b5208;
            margin-bottom: 18px;
        }

        h2 { font-size: 17px; margin: 0 0 18px; }

        label {
            display: block;
            font-size: 13px;
            font-weight: bold;
            margin-bottom: 8px;
        }

        textarea {
            width: 100%;
            min-height: 115px;
            resize: vertical;
            border: 1px solid #cbd7e8;
            border-radius: 8px;
            padding: 12px;
            font: inherit;
            font-size: 14px;
        }

        textarea:focus {
            outline: 2px solid #dbeafe;
            border-color: #183b70;
        }

        .hint {
            font-size: 12px;
            color: #71829b;
            line-height: 1.6;
            margin-top: 8px;
        }

        .item {
            padding: 14px 0;
            border-bottom: 1px solid #e5ebf4;
        }

        .item:first-of-type { padding-top: 0; }

        .item:last-of-type { border-bottom: 0; }

        .item-name {
            font-size: 14px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .item-info {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            color: #71829b;
            font-size: 12px;
        }

        .subtotal {
            color: #102b52;
            font-weight: bold;
            white-space: nowrap;
        }

        .total {
            border-top: 1px solid #dce5f2;
            margin-top: 10px;
            padding-top: 18px;
            display: flex;
            justify-content: space-between;
            gap: 10px;
            font-weight: bold;
        }

        .total-price {
            font-size: 21px;
            color: #102b52;
            text-align: right;
        }

        .btn {
            display: block;
            width: 100%;
            border: 0;
            border-radius: 8px;
            padding: 13px;
            text-align: center;
            text-decoration: none;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
        }

        .btn-primary {
            background: #102b52;
            color: white;
        }

        .btn-primary:hover { background: #1d447a; }

        .btn-secondary {
            background: #fff1d6;
            color: #975b00;
            margin-top: 10px;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            font-size: 13px;
            margin-bottom: 16px;
        }

        .alert-error {
            background: #fee2e2;
            color: #991b1b;
        }

        .error {
            color: #b91c1c;
            font-size: 12px;
            margin-top: 6px;
        }

        .secure {
            background: #eef5ff;
            border-radius: 8px;
            padding: 13px;
            font-size: 12px;
            color: #365579;
            line-height: 1.6;
            margin-bottom: 16px;
        }

        footer {
            text-align: center;
            color: #71829b;
            padding: 25px;
            font-size: 12px;
        }

        @media (max-width: 650px) {
            .layout { grid-template-columns: 1fr; }
            .container { margin-top: 22px; }
            .card { padding: 18px; }
            h1 { font-size: 23px; }
        }
    </style>
</head>
<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">🔧 BengkelServis</a>
    <a href="/keranjang-sparepart">← Kembali ke Keranjang</a>
</header>

<main class="container">
    <div class="heading">
        <div class="breadcrumb">Beranda / Spare Part / Checkout</div>
        <h1>Checkout Spare Part</h1>
        <div class="subtitle">
            Periksa kembali barang pesanan dan masukkan alamat pengiriman.
        </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Pesanan belum dapat diproses:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-error">{{ session('error') }}</div>
    @endif

    <form action="/checkout-sparepart" method="POST">
        @csrf

        <div class="layout">
            <section>
                <div class="card">
                    <h2>📍 Informasi Pengiriman</h2>

                    <label for="alamat_pengiriman">
                        Alamat Lengkap Penerima *
                    </label>

                    <textarea
                        id="alamat_pengiriman"
                        name="alamat_pengiriman"
                        maxlength="255"
                        placeholder="Masukkan nama jalan, nomor rumah, desa, kecamatan, dan kota..."
                        required>{{ old('alamat_pengiriman', $pelanggan->alamat ?? '') }}</textarea>

                    @error('alamat_pengiriman')
                        <div class="error">{{ $message }}</div>
                    @enderror

                    <p class="hint">
                        Pastikan alamat benar agar pesanan dapat dikirim
                        ke tempat yang sesuai.
                    </p>
                </div>

                <div class="card">
                    <h2>👤 Data Pelanggan</h2>

                    <div class="item">
                        <div class="item-name">
                            {{ $pelanggan->nama ?? 'Pelanggan' }}
                        </div>
                        <div class="item-info">
                            <span>{{ $pelanggan->email ?? '' }}</span>
                        </div>
                    </div>

                    <p class="hint">
                        Pesanan akan dicatat atas akun pelanggan yang sedang
                        login.
                    </p>
                </div>
            </section>

            <aside>
                <div class="card">
                    <h2>🛒 Ringkasan Pesanan</h2>

                    @foreach ($items as $item)
                        <div class="item">
                            <div class="item-name">
                                {{ $item['nama'] }}
                            </div>

                            <div class="item-info">
                                <span>
                                    {{ $item['jumlah'] }} ×
                                    Rp {{ number_format($item['harga'], 0, ',', '.') }}
                                </span>

                                <span class="subtotal">
                                    Rp {{ number_format($item['subtotal'], 0, ',', '.') }}
                                </span>
                            </div>

                            <div class="hint">
                                Stok tersedia saat ini: {{ $item['stok'] }}
                            </div>
                        </div>
                    @endforeach

                    <div class="total">
                        <span>Total Belanja</span>
                        <span class="total-price">
                            Rp {{ number_format($total, 0, ',', '.') }}
                        </span>
                    </div>

                    <p class="hint">
                        Harga dan stok akan diperiksa kembali saat pesanan
                        disimpan.
                    </p>

                    <div class="secure">
                        🔒 Dengan menekan tombol di bawah, kamu mengonfirmasi
                        alamat dan jumlah barang yang dipesan.
                    </div>

                    <button type="submit" class="btn btn-primary">
                        Buat Pesanan →
                    </button>

                    <a href="/keranjang-sparepart"
                       class="btn btn-secondary">
                        ← Periksa Keranjang
                    </a>
                </div>
            </aside>
        </div>
    </form>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
