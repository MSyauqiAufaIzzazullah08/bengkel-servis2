
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Pesanan Spare Part | BengkelServis</title>

    <style>
        :root {
            --navy: #102b55;
            --navy-dark: #0b1e3b;
            --orange: #ffa51f;
            --orange-light: #fff1d7;
            --bg: #f3f6fc;
            --white: #fff;
            --muted: #71819b;
            --border: #d9e3f2;
            --green: #15803d;
            --red: #b91c1c;
        }

        * { box-sizing: border-box; margin: 0; padding: 0; }

        body {
            font-family: Arial, sans-serif;
            background: var(--bg);
            color: var(--navy);
            min-height: 100vh;
        }

        a { text-decoration: none; color: inherit; }

        .navbar {
            background: white;
            padding: 18px 7%;
            border-bottom: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        .logo { font-size: 20px; font-weight: 800; }
        .logo span { color: var(--orange); }
        .nav-link { font-size: 12px; font-weight: bold; }

        .container {
            max-width: 1050px;
            margin: 32px auto;
            padding: 0 20px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 12px;
        }

        .heading { margin-bottom: 25px; }
        h1 { font-size: 26px; margin-bottom: 8px; }

        .subtitle {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .btn {
            display: inline-block;
            background: var(--navy);
            color: white;
            border-radius: 7px;
            padding: 12px 16px;
            font-size: 12px;
            font-weight: bold;
            text-align: center;
        }

        .btn:hover { background: #234d86; }

        .hero {
            background: linear-gradient(115deg, var(--navy-dark), #234d86);
            color: white;
            padding: 25px;
            border-radius: 13px;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .hero h2 { font-size: 19px; margin-bottom: 9px; }
        .hero p { font-size: 12px; color: #e2eaf7; line-height: 1.8; }
        .hero-icon { font-size: 43px; }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 15px;
            margin-bottom: 28px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 11px;
            padding: 19px;
        }

        .stat-label { color: var(--muted); font-size: 12px; margin-bottom: 10px; }
        .stat-number { font-size: 25px; font-weight: 800; }
        .stat-icon { float: right; background: var(--orange-light); padding: 8px; border-radius: 8px; }
        .section-title { font-size: 18px; margin-bottom: 15px; }

        .order-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }

        .order-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 21px;
            box-shadow: 0 3px 10px #102b5507;
            min-width: 0;
        }

        .order-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 15px;
            border-bottom: 1px solid var(--border);
        }

        .order-code { color: var(--muted); font-size: 11px; margin-bottom: 6px; }
        .order-top h3 { font-size: 17px; }

        .badge {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: bold;
            background: var(--orange-light);
            color: #8a4b00;
            white-space: nowrap;
        }

        .badge.success { background: #dcfce7; color: var(--green); }
        .badge.danger { background: #fee2e2; color: var(--red); }

        .order-date { color: var(--muted); font-size: 12px; margin: 13px 0; }
        .detail-title { font-size: 13px; font-weight: bold; margin: 15px 0 10px; }

        .item {
            display: flex;
            justify-content: space-between;
            gap: 12px;
            padding: 10px 0;
            border-bottom: 1px solid #edf1f8;
            font-size: 12px;
        }

        .item-name { font-weight: bold; line-height: 1.6; }
        .item-sub { color: var(--muted); margin-top: 4px; }
        .item-price { text-align: right; white-space: nowrap; }

        .summary {
            margin-top: 16px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
            display: flex;
            justify-content: space-between;
            gap: 12px;
            align-items: center;
        }

        .summary span { color: var(--muted); font-size: 12px; }
        .total { font-size: 19px; font-weight: 800; }

        .address {
            background: #f6f8fc;
            border: 1px solid var(--border);
            border-radius: 8px;
            padding: 12px;
            margin-top: 15px;
            font-size: 12px;
            line-height: 1.7;
            overflow-wrap: anywhere;
        }

        .address strong { display: block; margin-bottom: 4px; }

        .shipping {
            margin-top: 12px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .payment-area {
            margin-top: 15px;
            padding-top: 14px;
            border-top: 1px solid var(--border);
        }

        .empty {
            background: white;
            border: 1px dashed #bdcce0;
            border-radius: 12px;
            padding: 45px 20px;
            text-align: center;
        }

        .empty-icon { font-size: 43px; margin-bottom: 13px; }
        .empty h3 { margin-bottom: 10px; }

        .empty p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
            margin-bottom: 18px;
        }

        footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--muted);
            font-size: 11px;
        }

        @media (max-width: 700px) {
            .order-list, .stats { grid-template-columns: 1fr; }
            .heading { align-items: flex-start; }
        }

        @media (max-width: 480px) {
            .navbar { padding: 16px 5%; }
            .container { padding: 0 14px; }
            .hero-icon { display: none; }
            .order-top { flex-direction: column; }
        }
    </style>
</head>
<body>

<header class="navbar">
    <a href="/dashboard-pelanggan" class="logo">🔧 Bengkel<span>Servis</span></a>
    <a href="/dashboard-pelanggan" class="nav-link">← Dashboard Pelanggan</a>
</header>

<main class="container">
    <div class="breadcrumb">Beranda / Pesanan / Pesanan Spare Part</div>

    <div class="heading">
        <h1>Pesanan Spare Part</h1>
        <p class="subtitle">Pantau pesanan suku cadang, total transaksi, dan informasi pengirimanmu.</p>
    </div>

    <section class="hero">
        <div>
            <h2>Pesanan Suku Cadangmu 🧰</h2>
            <p>Semua pesanan yang ditampilkan adalah pesanan milik akunmu. Periksa rincian barang dan status pesanan di sini.</p>
        </div>
        <div class="hero-icon">📦</div>
    </section>

    @php
        $totalPesanan = $orders->count();

        $pesananSelesai = $orders->filter(function ($order) {
            return in_array(strtolower((string) $order->status), [
                'completed', 'delivered', 'selesai'
            ]);
        })->count();

        $totalBelanja = $orders->sum(function ($order) {
            return (float) $order->total_harga;
        });
    @endphp

    <section class="stats">
        <div class="stat-card">
            <span class="stat-icon">📦</span>
            <p class="stat-label">Total Pesanan</p>
            <div class="stat-number">{{ $totalPesanan }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">✅</span>
            <p class="stat-label">Pesanan Selesai</p>
            <div class="stat-number">{{ $pesananSelesai }}</div>
        </div>
        <div class="stat-card">
            <span class="stat-icon">💳</span>
            <p class="stat-label">Total Belanja</p>
            <div class="stat-number">Rp {{ number_format($totalBelanja, 0, ',', '.') }}</div>
        </div>
    </section>

    @if (session('success'))
        <div style="background:#dcfce7;color:#166534;padding:12px;border-radius:8px;margin-bottom:18px;">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div style="background:#fee2e2;color:#991b1b;padding:12px;border-radius:8px;margin-bottom:18px;">
            {{ session('error') }}
        </div>
    @endif

    <h2 class="section-title">Riwayat Pesanan</h2>

    @if ($orders->isEmpty())
        <section class="empty">
            <div class="empty-icon">🛒</div>
            <h3>Belum Ada Pesanan</h3>
            <p>Kamu belum memiliki pesanan spare part. Silakan kunjungi katalog spare part untuk melakukan pembelian.</p>
            <a href="/sparepart-pelanggan" class="btn">Lihat Spare Part</a>
        </section>
    @else
        <div class="order-list">
            @foreach ($orders as $order)
                @php
                    $status = strtolower(trim((string) $order->status));
                    $statusBayar = strtolower(trim((string) ($order->pembayaran->status ?? '')));

                    $badgeClass = '';
                    if (in_array($status, ['completed', 'delivered', 'selesai'])) {
                        $badgeClass = 'success';
                    } elseif (in_array($status, ['cancelled', 'canceled', 'dibatalkan'])) {
                        $badgeClass = 'danger';
                    }
                @endphp

                <article class="order-card">
                    <div class="order-top">
                        <div>
                            <p class="order-code">Nomor Pesanan #{{ $order->id_order }}</p>
                            <h3>Pesanan Spare Part</h3>
                        </div>
                        <span class="badge {{ $badgeClass }}">
                            {{ $order->status ?: 'Belum ada status' }}
                        </span>
                    </div>

                    <p class="order-date">
                        📅 Tanggal:
                        {{ $order->tanggal_order
                            ? \Carbon\Carbon::parse($order->tanggal_order)->format('d M Y')
                            : 'Belum tersedia' }}
                    </p>

                    <p class="detail-title">Rincian Barang</p>

                    @forelse ($order->orderDetail as $detail)
                        <div class="item">
                            <div>
                                <div class="item-name">
                                    {{ $detail->sparePart->nama_sparepart
                                        ?? $detail->sparePart->nama_spare_part
                                        ?? 'Spare part' }}
                                </div>
                                <div class="item-sub">
                                    {{ number_format((float) $detail->harga, 0, ',', '.') }}
                                    × {{ $detail->jumlah }}
                                </div>
                            </div>
                            <div class="item-price">
                                Rp {{ number_format((float) $detail->harga * (int) $detail->jumlah, 0, ',', '.') }}
                            </div>
                        </div>
                    @empty
                        <p class="shipping">Rincian barang belum tersedia.</p>
                    @endforelse

                    <div class="summary">
                        <span>Total pesanan</span>
                        <div class="total">
                            Rp {{ number_format((float) $order->total_harga, 0, ',', '.') }}
                        </div>
                    </div>

                    <div class="address">
                        <strong>📍 Alamat Pengiriman</strong>
                        {{ $order->alamat_pengiriman ?: 'Alamat belum tersedia.' }}
                    </div>

                    @if ($order->pengiriman)
                        <div class="shipping">
                            <strong>🚚 Informasi Pengiriman</strong><br>
                            Status: {{ $order->pengiriman->status ?: 'Belum tersedia' }}

                            @if ($order->pengiriman->ekspedisi)
                                <br>Ekspedisi: {{ $order->pengiriman->ekspedisi->nama_ekspedisi }}
                            @endif

                            @if ($order->pengiriman->estimasi_tiba)
                                <br>Estimasi tiba:
                                {{ \Carbon\Carbon::parse($order->pengiriman->estimasi_tiba)->format('d M Y') }}
                            @endif
                        </div>
                    @else
                        <p class="shipping">🚚 Informasi pengiriman belum tersedia.</p>
                    @endif

                    <div class="payment-area">
                        @if ($statusBayar === 'lunas' || $statusBayar === 'paid' || $statusBayar === 'berhasil')
                            <span class="badge success">✓ Pembayaran Lunas</span>
                        @elseif (in_array($statusBayar, ['menunggu', 'pending']))
                            <span class="badge">⏳ Menunggu Verifikasi</span>
                        @elseif ($order->pembayaran)
                            <p class="shipping">
                                💳 Status pembayaran: {{ $order->pembayaran->status }}
                            </p>
                            <a href="{{ url('/pembayaran-sparepart/' . $order->id_order) }}" class="btn">
                                Coba Pembayaran Lagi
                            </a>
                        @else
                            <a href="{{ url('/pembayaran-sparepart/' . $order->id_order) }}" class="btn">
                                💳 Bayar Sekarang
                            </a>
                        @endif

                        @if ($order->pembayaran)
                            <p class="shipping">
                                💳 Status pembayaran: {{ $order->pembayaran->status ?: 'Belum tersedia' }}
                                @if ($order->pembayaran->metodePembayaran)
                                    <br>Metode: {{ $order->pembayaran->metodePembayaran->nama_metode }}
                                @endif
                            </p>
                        @else
                            <p class="shipping">Pembayaran belum diajukan.</p>
                        @endif
                    </div>
                </article>
            @endforeach
        </div>
    @endif
</main>

<footer>&copy; {{ date('Y') }} BengkelServis — Portal Pelanggan</footer>

</body>
</html>
