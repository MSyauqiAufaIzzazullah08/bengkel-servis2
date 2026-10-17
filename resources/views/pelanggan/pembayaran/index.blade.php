
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pembayaran Saya | BengkelServis</title>

    <style>
        :root {
            --navy: #102B55;
            --navy-light: #173B75;
            --orange: #F5A623;
            --orange-light: #FFF2D9;
            --background: #F4F7FC;
            --text: #172033;
            --muted: #66758C;
            --border: #DCE4EF;
            --white: #FFFFFF;
            --green-bg: #DCFCE7;
            --green-text: #166534;
            --red-bg: #FEE2E2;
            --red-text: #991B1B;
            --yellow-bg: #FFF2D9;
            --yellow-text: #945600;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        header {
            min-height: 72px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            padding: 16px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 16px;
        }

        .logo {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            white-space: nowrap;
        }

        .logo span {
            color: var(--orange);
        }

        .header-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .header-link:hover {
            color: #A45F00;
        }

        main {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 22px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 18px;
        }

        .heading {
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 16px;
            margin-bottom: 25px;
        }

        h1 {
            color: var(--navy);
            font-size: 28px;
            margin-bottom: 8px;
        }

        h2 {
            color: var(--navy);
            font-size: 19px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
        }

        .btn {
            display: inline-flex;
            justify-content: center;
            align-items: center;
            gap: 7px;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 12px 17px;
            background: var(--navy);
            color: white;
            text-decoration: none;
            font-family: inherit;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: background .2s, transform .2s;
        }

        .btn:hover {
            background: var(--navy-light);
        }

        .btn-orange {
            background: var(--orange);
            color: var(--navy);
        }

        .btn-orange:hover {
            background: #E99A12;
        }

        .btn:disabled {
            background: #CBD5E1;
            color: #64748B;
            cursor: not-allowed;
        }

        .message {
            padding: 14px 16px;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 13px;
            background: var(--green-bg);
            color: var(--green-text);
            border: 1px solid #BBF7D0;
        }

        .message.error {
            background: var(--red-bg);
            color: var(--red-text);
            border-color: #FECACA;
        }

        .message.info {
            background: #E8EFFB;
            color: var(--navy);
            border-color: #C8D8F1;
        }

        .stats {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .stat {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 23px;
            box-shadow: 0 4px 15px rgba(16, 43, 85, .04);
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: flex;
            align-items: center;
            justify-content: center;
            border-radius: 11px;
            background: var(--orange-light);
            font-size: 21px;
            margin-bottom: 14px;
        }

        .stat p {
            font-size: 13px;
            color: var(--muted);
            margin-bottom: 8px;
        }

        .stat strong {
            color: var(--navy);
            font-size: 27px;
        }

        .section-title {
            margin-bottom: 17px;
        }

        .booking-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 23px;
            box-shadow: 0 4px 15px rgba(16, 43, 85, .04);
            min-width: 0;
        }

        .card-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            padding-bottom: 17px;
            border-bottom: 1px solid var(--border);
        }

        .booking-code {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 7px;
        }

        .service-name {
            color: var(--navy);
            font-size: 18px;
            margin-bottom: 5px;
            overflow-wrap: anywhere;
        }

        .status {
            display: inline-block;
            padding: 7px 10px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            white-space: nowrap;
            background: var(--yellow-bg);
            color: var(--yellow-text);
        }

        .status.success {
            background: var(--green-bg);
            color: var(--green-text);
        }

        .status.failed {
            background: var(--red-bg);
            color: var(--red-text);
        }

        .status.neutral {
            background: #E8EFFB;
            color: var(--navy);
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 19px 14px;
            padding: 20px 0;
        }

        .detail-label {
            font-size: 12px;
            color: var(--muted);
            margin-bottom: 6px;
        }

        .detail-value {
            color: var(--text);
            font-size: 13px;
            font-weight: bold;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .price {
            color: var(--navy);
            font-size: 17px;
        }

        .price-mark {
            display: inline-block;
            width: 4px;
            height: 18px;
            background: var(--orange);
            border-radius: 4px;
            margin-right: 5px;
            vertical-align: middle;
        }

        .payment-box {
            border-top: 1px solid var(--border);
            padding-top: 18px;
        }

        .payment-box h3 {
            color: var(--navy);
            font-size: 15px;
            margin-bottom: 12px;
        }

        .payment-form label {
            display: block;
            color: var(--navy);
            font-size: 12px;
            font-weight: bold;
            margin-bottom: 7px;
        }

        .payment-form select {
            width: 100%;
            padding: 12px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            background: white;
            color: var(--text);
            margin-bottom: 12px;
            font-size: 13px;
        }

        .payment-form select:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245, 166, 35, .15);
        }

        .payment-form .btn {
            width: 100%;
        }

        .payment-info {
            background: #F3F6FB;
            border: 1px solid var(--border);
            border-left: 4px solid var(--orange);
            border-radius: 9px;
            padding: 14px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.8;
            overflow-wrap: anywhere;
        }

        .payment-info strong {
            color: var(--navy);
        }

        .empty {
            grid-column: 1 / -1;
            text-align: center;
            background: white;
            border: 1px dashed #C4D0E0;
            border-radius: 14px;
            padding: 55px 20px;
        }

        .empty .emoji {
            font-size: 42px;
            margin-bottom: 14px;
        }

        .empty h2 {
            margin-bottom: 8px;
        }

        .empty p {
            color: var(--muted);
            font-size: 13px;
            line-height: 1.7;
            margin: 10px 0 20px;
        }

        footer {
            text-align: center;
            padding: 25px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .booking-list {
                grid-template-columns: 1fr;
            }

            .stats {
                gap: 12px;
            }

            .stat {
                padding: 18px;
            }
        }

        @media (max-width: 560px) {
            header {
                padding: 15px 18px;
            }

            .logo {
                font-size: 18px;
            }

            main {
                margin: 25px auto;
                padding: 0 14px;
            }

            .stats {
                grid-template-columns: 1fr;
            }

            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .card-top {
                flex-direction: column;
            }

            h1 {
                font-size: 24px;
            }

            .card {
                padding: 18px;
            }
        }
    </style>
</head>

<body>
<header>
    <a href="/dashboard-pelanggan" class="logo">
        🔧 <span>Bengkel</span>Servis
    </a>

    <a href="/dashboard-pelanggan" class="header-link">
        ← Dashboard Pelanggan
    </a>
</header>

<main>
    <div class="breadcrumb">Beranda / Pembayaran / Pembayaran Saya</div>

    <div class="heading">
        <div>
            <h1>Pembayaran Saya</h1>
            <p class="subtitle">
                Pantau tagihan booking servis dan riwayat pembayaranmu.
            </p>
        </div>

        <a href="/booking-pelanggan/create" class="btn btn-orange">
            + Booking Servis
        </a>
    </div>

    @if(session('success'))
        <div class="message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    @if(session('info'))
        <div class="message info">{{ session('info') }}</div>
    @endif

    @if($errors->any())
        <div class="message error">
            {{ $errors->first() }}
        </div>
    @endif

    <div class="stats">
        <div class="stat">
            <div class="stat-icon">💳</div>
            <p>Total Transaksi</p>
            <strong>{{ $totalTransaksi }}</strong>
        </div>

        <div class="stat">
            <div class="stat-icon">⏳</div>
            <p>Menunggu Pembayaran</p>
            <strong>{{ $menungguPembayaran }}</strong>
        </div>

        <div class="stat">
            <div class="stat-icon">✅</div>
            <p>Pembayaran Berhasil</p>
            <strong>{{ $pembayaranBerhasil }}</strong>
        </div>
    </div>

    <h2 class="section-title">Tagihan Booking Servis</h2>

    <div class="booking-list">
        @forelse($bookings as $booking)
            @php
                $transaksi = $pembayaran->get($booking->id_booking);

                $statusBooking = strtolower(trim((string) $booking->status_booking));

                $servisSelesai = in_array(
                    $statusBooking,
                    ['completed', 'selesai'],
                    true
                );

                $statusPembayaran = strtolower(
                    trim((string) ($transaksi->status ?? ''))
                );

                $sudahLunas = in_array(
                    $statusPembayaran,
                    ['berhasil', 'paid', 'lunas'],
                    true
                );

                $pembayaranGagal = in_array(
                    $statusPembayaran,
                    ['gagal', 'ditolak', 'dibatalkan'],
                    true
                );
            @endphp

            <article class="card">
                <div class="card-top">
                    <div>
                        <p class="booking-code">
                            Booking #{{ $booking->id_booking }}
                        </p>

                        <h2 class="service-name">
                            {{ $booking->nama_layanan }}
                        </h2>

                        <p class="subtitle">
                            {{ $booking->merk }} {{ $booking->model }}
                            — {{ $booking->nopol }}
                        </p>
                    </div>

                    @if($sudahLunas)
                        <span class="status success">Lunas</span>
                    @elseif($pembayaranGagal)
                        <span class="status failed">
                            {{ $transaksi->status }}
                        </span>
                    @elseif($transaksi)
                        <span class="status">Menunggu Pembayaran</span>
                    @elseif($servisSelesai)
                        <span class="status">Belum Dibayar</span>
                    @else
                        <span class="status neutral">
                            {{ $booking->status_booking }}
                        </span>
                    @endif
                </div>

                <div class="details">
                    <div>
                        <p class="detail-label">🏢 Cabang Bengkel</p>
                        <p class="detail-value">
                            {{ $booking->nama_cabang }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">📅 Tanggal Servis</p>
                        <p class="detail-value">
                            {{ \Carbon\Carbon::parse($booking->tanggal_booking)->format('d M Y') }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">🕒 Waktu Servis</p>
                        <p class="detail-value">
                            {{ substr($booking->waktu_booking, 0, 5) }} WIB
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">📋 Status Booking</p>
                        <p class="detail-value">
                            {{ $booking->status_booking }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">💰 Total Tagihan</p>
                        <p class="detail-value price">
                            <span class="price-mark"></span>
                            Rp {{ number_format((float) $booking->harga, 0, ',', '.') }}
                        </p>
                    </div>

                    @if($transaksi)
                        <div>
                            <p class="detail-label">💳 Metode Pembayaran</p>
                            <p class="detail-value">
                                {{ $transaksi->nama_metode ?? 'Belum tersedia' }}
                            </p>
                        </div>
                    @endif
                </div>

                <div class="payment-box">
                    @if($sudahLunas)
                        <div class="payment-info">
                            <strong>✓ Pembayaran berhasil</strong><br>
                            Tagihan booking ini sudah dinyatakan lunas.

                            @if($transaksi->tanggal_bayar)
                                <br><strong>Tanggal bayar:</strong>
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d M Y H:i') }}
                            @endif
                        </div>

                    @elseif(!$servisSelesai)
                        <div class="payment-info">
                            <strong>⏳ Pembayaran belum tersedia</strong><br>
                            Kamu bisa melakukan pembayaran setelah servis
                            selesai dikerjakan oleh pihak bengkel.

                            <br><br>
                            Status booking saat ini:
                            <strong>{{ $booking->status_booking }}</strong>
                        </div>

                    @elseif(!$transaksi || $pembayaranGagal)
                        @if($metodePembayaran->isNotEmpty())
                            <h3>Bayar Tagihan Servis</h3>

                            <form
                                class="payment-form"
                                action="/pembayaran-pelanggan/{{ $booking->id_booking }}"
                                method="POST"
                            >
                                @csrf

                                <label for="metode-{{ $booking->id_booking }}">
                                    Pilih Metode Pembayaran
                                </label>

                                <select
                                    name="metode_pembayaran_id"
                                    id="metode-{{ $booking->id_booking }}"
                                    required
                                >
                                    <option value="">-- Pilih metode --</option>

                                    @foreach($metodePembayaran as $metode)
                                        <option value="{{ $metode->id_metode }}">
                                            {{ $metode->nama_metode }}
                                        </option>
                                    @endforeach
                                </select>

                                <button type="submit" class="btn btn-orange">
                                    Bayar Sekarang →
                                </button>
                            </form>
                        @else
                            <div class="payment-info">
                                <strong>Metode pembayaran belum tersedia.</strong><br>
                                Silakan hubungi pihak bengkel untuk mendapatkan
                                informasi pembayaran.
                            </div>
                        @endif

                    @else
                        <div class="payment-info">
                            <strong>Status pembayaran:</strong>
                            {{ $transaksi->status }}

                            @if($transaksi->tanggal_bayar)
                                <br>
                                <strong>Tanggal bayar:</strong>
                                {{ \Carbon\Carbon::parse($transaksi->tanggal_bayar)->format('d M Y H:i') }}
                            @endif

                            @if($statusPembayaran === 'pending')
                                <br><br>
                                Transaksi sudah dicatat. Pembayaran akan dinyatakan
                                lunas setelah dikonfirmasi oleh pihak bengkel.
                            @endif
                        </div>
                    @endif
                </div>
            </article>

        @empty
            <div class="empty">
                <div class="emoji">🧾</div>
                <h2>Belum Ada Tagihan</h2>

                <p>
                    Kamu belum memiliki booking servis. Buat booking terlebih
                    dahulu agar informasi tagihan ditampilkan di sini.
                </p>

                <a href="/booking-pelanggan/create" class="btn btn-orange">
                    Buat Booking Servis →
                </a>
            </div>
        @endforelse
    </div>
</main>

<footer>
    © {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>
</body>
</html>
