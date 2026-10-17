<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Keranjang Spare Part | BengkelServis</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #102b55;
            background: #f3f6fc;
            font-size: 14px;
        }

        header {
            background: white;
            border-bottom: 1px solid #d9e3f2;
            padding: 18px 7%;
            display: flex;
            justify-content: space-between;
            gap: 15px;
        }

        a { text-decoration: none; color: #102b55; }
        .logo { font-size: 20px; font-weight: bold; }
        .logo span { color: #f5a623; }

        main { width: min(950px, 92%); margin: 32px auto; }

        h1 { font-size: 26px; margin-bottom: 8px; }
        h2 { font-size: 17px; }

        .muted { color: #71829d; line-height: 1.6; }

        .card {
            background: white;
            border: 1px solid #d9e3f2;
            border-radius: 12px;
            padding: 22px;
            margin-top: 22px;
        }

        .message {
            padding: 13px;
            border-radius: 8px;
            margin-top: 16px;
        }

        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }

        .item {
            display: grid;
            grid-template-columns: 1fr auto auto;
            gap: 20px;
            align-items: center;
            padding: 17px 0;
            border-bottom: 1px solid #e6edf6;
        }

        .item h3 { font-size: 15px; margin: 0 0 7px; }
        .price { font-weight: bold; }

        .btn {
            display: inline-block;
            border: 0;
            border-radius: 7px;
            padding: 11px 14px;
            font-size: 12px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
        }

        .primary { background: #102b55; color: white; }
        .primary:hover { background: #1d4278; }
        .secondary { background: #fff1d6; color: #8a5700; }
        .danger { background: #fff1f2; color: #be123c; }

        .total {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            font-size: 18px;
            font-weight: bold;
            padding: 22px 0;
        }

        .actions {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            flex-wrap: wrap;
        }

        .empty {
            text-align: center;
            padding: 35px 10px;
            color: #71829d;
            line-height: 1.8;
        }

        footer {
            text-align: center;
            color: #71829d;
            padding: 25px;
            font-size: 12px;
        }

        @media (max-width: 600px) {
            .item { grid-template-columns: 1fr auto; gap: 10px; }
            .item form { grid-column: 1 / -1; }
            header { padding: 16px 5%; }
            h1 { font-size: 23px; }
        }
    </style>
</head>
<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">
        🔧 Bengkel<span>Servis</span>
    </a>
    <a href="/sparepart-pelanggan">← Katalog Spare Part</a>
</header>

<main>
    <h1>Keranjang Spare Part</h1>
    <p class="muted">
        Periksa barang dan jumlah sebelum melanjutkan pemesanan.
    </p>

    @if (session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="message error">
            <strong>Periksa kembali data:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <section class="card">
        <h2>Barang Pilihanmu</h2>

        @if (count($keranjang) === 0)
            <div class="empty">
                <div style="font-size:38px">🛒</div>
                <strong>Keranjang Masih Kosong</strong>
                <p>Tambahkan spare part dari katalog terlebih dahulu.</p>

                <a href="/sparepart-pelanggan" class="btn primary">
                    Lihat Katalog
                </a>
            </div>
        @else
            @foreach ($keranjang as $id => $item)
                <div class="item">
                    <div>
                        <h3>{{ $item['nama'] }}</h3>
                        <div class="muted">
                            {{ $item['jumlah'] }} ×
                            Rp {{ number_format($item['harga'], 0, ',', '.') }}
                        </div>

                        @if ($item['jumlah'] > ($item['stok'] ?? 0))
                            <div style="color:#be123c;font-size:12px;margin-top:6px">
                                Jumlah melebihi stok terbaru
                                ({{ $item['stok'] ?? 0 }}).
                                Sesuaikan barang sebelum checkout.
                            </div>
                        @endif
                    </div>

                    <div class="price">
                        Rp {{ number_format($item['harga'] * $item['jumlah'], 0, ',', '.') }}
                    </div>

                    <form
                        action="{{ url('/keranjang-sparepart/' . $id) }}"
                        method="POST"
                        onsubmit="return confirm('Hapus barang ini dari keranjang?')"
                    >
                        @csrf
                        @method('DELETE')

                        <button class="btn danger" type="submit">
                            Hapus
                        </button>
                    </form>
                </div>
            @endforeach

            <div class="total">
                <span>Total Sementara</span>
                <span>
                    Rp {{ number_format($total, 0, ',', '.') }}
                </span>
            </div>

            <p class="muted">
                Total ini masih sementara. Harga dan stok akan divalidasi
                kembali saat pesanan diproses.
            </p>

            <div class="actions">
                <a href="/sparepart-pelanggan" class="btn secondary">
                    ← Tambah Barang Lain
                </a>

                <a href="{{ url('/checkout-sparepart') }}"
                   class="btn primary">
                    Checkout — tahap berikutnya →
                </a>
            </div>
        @endif
    </section>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
