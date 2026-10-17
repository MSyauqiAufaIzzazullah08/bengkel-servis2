
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Spare Part | BengkelServis</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            padding: 30px 15px;
            font-family: Arial, sans-serif;
            background: #f3f6fc;
            color: #102b55;
        }
        .card {
            max-width: 520px;
            margin: 30px auto;
            padding: 25px;
            background: white;
            border: 1px solid #d9e3f2;
            border-radius: 14px;
            box-shadow: 0 5px 20px #102b5510;
        }
        h2 { margin-top: 0; }
        .info {
            background: #f3f6fc;
            padding: 16px;
            border-radius: 8px;
            line-height: 1.9;
            margin: 20px 0;
        }
        .total { font-size: 27px; font-weight: bold; }
        label {
            display: block;
            margin-bottom: 8px;
            font-weight: bold;
        }
        select, button {
            width: 100%;
            padding: 13px;
            border-radius: 7px;
            font-size: 14px;
        }
        select {
            border: 1px solid #d9e3f2;
            margin-bottom: 18px;
            background: white;
        }
        button {
            border: none;
            background: #102b55;
            color: white;
            font-weight: bold;
            cursor: pointer;
        }
        button:hover { background: #234d86; }
        .back {
            display: inline-block;
            margin-top: 18px;
            color: #102b55;
            text-decoration: none;
        }
        .error {
            color: #b91c1c;
            margin-bottom: 15px;
        }
        .notice {
            padding: 12px;
            background: #fff1d7;
            border-radius: 8px;
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 18px;
        }
        .success {
            padding: 12px;
            background: #dcfce7;
            color: #166534;
            border-radius: 8px;
            margin-bottom: 15px;
        }
    </style>
</head>
<body>
    <div class="card">
        <h2>💳 Pembayaran Spare Part</h2>
        <p>Nomor Pesanan #{{ $order->id_order }}</p>

        @if (session('success'))
            <div class="success">{{ session('success') }}</div>
        @endif

        <div class="info">
            <div>Total pembayaran</div>
            <div class="total">
                Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}
            </div>
            <div>
                <strong>Alamat pengiriman:</strong><br>
                {{ $order->alamat_pengiriman ?? $order->alamat ?? 'Belum tersedia' }}
            </div>
        </div>

        @if ($pembayaran && in_array(strtolower($pembayaran->status ?? ''), ['menunggu', 'pending']))
            <div class="notice">
                Pengajuan pembayaran sudah tercatat.
                <strong>Silakan tunggu verifikasi petugas.</strong>
            </div>
        @else
            <div class="notice">
                Pilih metode pembayaran. Status akan menjadi
                <strong>Menunggu Verifikasi</strong> sampai diperiksa petugas.
            </div>

            @if ($errors->any())
                <div class="error">
                    @foreach ($errors->all() as $error)
                        <div>{{ $error }}</div>
                    @endforeach
                </div>
            @endif

            <form method="POST"
                  action="{{ url('/pembayaran-sparepart/' . $order->id_order) }}">
                @csrf

                <label for="metode_pembayaran_id">Pilih Metode Pembayaran</label>
                <select name="metode_pembayaran_id"
                        id="metode_pembayaran_id" required>
                    <option value="">-- Pilih metode --</option>
                    @foreach ($metodePembayaran as $metode)
                        <option value="{{ $metode->id_metode }}"
                            {{ old('metode_pembayaran_id') == $metode->id_metode ? 'selected' : '' }}>
                            {{ $metode->nama_metode }}
                        </option>
                    @endforeach
                </select>

                <button type="submit">Kirim Pengajuan Pembayaran</button>
            </form>
        @endif

        <a class="back" href="{{ url('/order-pelanggan') }}">
            ← Kembali ke Pesanan Saya
        </a>
    </div>
</body>
</html>
