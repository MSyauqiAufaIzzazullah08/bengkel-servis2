
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Berhasil | BengkelServis</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #102b52;
        }

        header {
            background: white;
            border-bottom: 1px solid #dce5f2;
            padding: 18px 7%;
        }

        .logo {
            color: #102b52;
            font-size: 20px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            width: min(100% - 32px, 680px);
            margin: 45px auto;
        }

        .card {
            background: white;
            border: 1px solid #d9e3f1;
            border-radius: 15px;
            padding: 30px;
            box-shadow: 0 8px 25px #102b5208;
        }

        .success-icon {
            width: 66px;
            height: 66px;
            margin: 0 auto 18px;
            border-radius: 50%;
            background: #dcfce7;
            color: #15803d;
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 35px;
        }

        h1 {
            font-size: 25px;
            text-align: center;
            margin: 0 0 12px;
        }

        .subtitle {
            text-align: center;
            color: #71829b;
            font-size: 14px;
            line-height: 1.7;
            margin-bottom: 25px;
        }

        .status {
            display: inline-block;
            padding: 7px 12px;
            border-radius: 20px;
            background: #fff1d6;
            color: #975b00;
            font-size: 12px;
            font-weight: bold;
        }

        .row {
            display: flex;
            justify-content: space-between;
            gap: 15px;
            padding: 13px 0;
            border-bottom: 1px solid #e5ebf4;
            font-size: 14px;
        }

        .row span:first-child { color: #71829b; }

        .row span:last-child {
            font-weight: bold;
            text-align: right;
        }

        h2 {
            font-size: 17px;
            margin: 25px 0 10px;
        }

        .item {
            padding: 12px 0;
            border-bottom: 1px solid #e5ebf4;
            font-size: 13px;
        }

        .item-detail {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            margin-top: 7px;
            color: #71829b;
        }

        .total {
            margin-top: 16px;
            padding-top: 12px;
            display: flex;
            justify-content: space-between;
            gap: 12px;
            font-weight: bold;
            font-size: 17px;
        }

        .total strong { text-align: right; }

        .btn {
            display: block;
            text-decoration: none;
            text-align: center;
            padding: 13px;
            border-radius: 8px;
            font-size: 14px;
            font-weight: bold;
            margin-top: 14px;
        }

        .primary { background: #102b52; color: white; }
        .secondary { background: #fff1d6; color: #975b00; }

        .hint {
            margin-top: 20px;
            padding: 13px;
            background: #eef5ff;
            color: #365579;
            border-radius: 8px;
            font-size: 12px;
            line-height: 1.7;
        }

        footer {
            text-align: center;
            color: #71829b;
            padding: 20px;
            font-size: 12px;
        }

        @media (max-width: 500px) {
            .card { padding: 20px; }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">🔧 BengkelServis</a>
</header>

<main>
    <section class="card">
        <div class="success-icon">✓</div>

        <h1>Pesanan Berhasil Dibuat!</h1>

        <p class="subtitle">
            Terima kasih telah memesan spare part di BengkelServis.
            Simpan informasi pesanan berikut untuk memudahkan pengecekan.
        </p>

        <div style="text-align:center; margin-bottom:20px;">
            <span class="status">
                {{ $order->status }}
            </span>
        </div>

        <div class="row">
            <span>Nomor Pesanan</span>
            <span>#{{ $order->id_order }}</span>
        </div>

        <div class="row">
            <span>Tanggal Pesanan</span>
            <span>{{ $order->tanggal_order }}</span>
        </div>

        <div class="row">
            <span>Alamat Pengiriman</span>
            <span>{{ $order->alamat_pengiriman }}</span>
        </div>

        <h2>Rincian Barang</h2>

        @foreach ($order->orderDetail as $detail)
            <div class="item">
                <strong>
                    {{ $detail->sparePart->nama ?? 'Spare part' }}
                </strong>

                <div class="item-detail">
                    <span>
                        {{ $detail->jumlah }} ×
                        Rp {{ number_format($detail->harga, 0, ',', '.') }}
                    </span>

                    <strong>
                        Rp {{ number_format($detail->jumlah * $detail->harga, 0, ',', '.') }}
                    </strong>
                </div>
            </div>
        @endforeach

        <div class="total">
            <span>Total Pesanan</span>
            <strong>
                Rp {{ number_format($order->total_harga, 0, ',', '.') }}
            </strong>
        </div>

        <div class="hint">
            Pesanan kamu tercatat di sistem. Status saat ini adalah
            <strong>{{ $order->status }}</strong>. Pembayaran dan pengiriman
            dapat dilanjutkan sesuai proses yang nantinya kita hubungkan.
        </div>

        <a href="/order-pelanggan" class="btn primary">
            Lihat Riwayat Pesanan
        </a>

        <a href="/sparepart-pelanggan" class="btn secondary">
            Kembali ke Katalog Spare Part
        </a>
    </section>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
