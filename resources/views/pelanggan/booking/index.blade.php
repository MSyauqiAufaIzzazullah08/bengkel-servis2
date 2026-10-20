
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Booking Servis Saya | BengkelServis</title>

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
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 15px 6%;
            gap: 15px;
        }

        .logo {
            font-size: 21px;
            font-weight: 800;
            color: var(--navy);
        }

        .logo span {
            color: var(--orange);
        }

        .back-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
        }

        .back-link:hover {
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
            gap: 18px;
            margin-bottom: 25px;
        }

        h1 {
            color: var(--navy);
            font-size: 28px;
            margin-bottom: 9px;
        }

        .subtitle {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.7;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 1px solid var(--navy);
            background: var(--navy);
            color: white;
            padding: 12px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: .2s;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--navy-light);
            border-color: var(--navy-light);
            transform: translateY(-1px);
        }

        .message {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #BBF7D0;
            padding: 14px 17px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 14px;
        }

        .summary {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 18px;
            margin-bottom: 32px;
        }

        .summary-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 22px;
            box-shadow: 0 3px 12px rgba(16,43,85,.035);
        }

        .summary-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 17px;
        }

        .summary-icon {
            width: 43px;
            height: 43px;
            border-radius: 11px;
            background: var(--orange-light);
            display: flex;
            align-items: center;
            justify-content: center;
            font-size: 21px;
        }

        .summary-label {
            font-size: 13px;
            color: var(--muted);
        }

        .summary-number {
            font-size: 28px;
            font-weight: 800;
            color: var(--navy);
        }

        .summary-card small {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin-top: 5px;
        }

        .section-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 12px;
            margin-bottom: 17px;
        }

        .section-title {
            color: var(--navy);
            font-size: 19px;
        }

        .section-caption {
            color: var(--muted);
            font-size: 12px;
        }

        .booking-list {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 20px;
        }

        .booking-card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 23px;
            box-shadow: 0 4px 15px rgba(16,43,85,.035);
            transition: .2s;
            min-width: 0;
        }

        .booking-card:hover {
            border-color: #C7D4E6;
            box-shadow: 0 8px 24px rgba(16,43,85,.07);
        }

        .booking-top {
            display: flex;
            justify-content: space-between;
            align-items: flex-start;
            gap: 12px;
            margin-bottom: 18px;
        }

        .booking-id {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 7px;
        }

        .booking-title {
            color: var(--navy);
            font-size: 19px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .badges {
            display: flex;
            flex-wrap: wrap;
            gap: 6px;
            margin-top: 10px;
        }

        .type-badge {
            display: inline-block;
            background: #E8EEF8;
            color: var(--navy);
            padding: 5px 9px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
        }

        .type-home {
            background: #FFF2D9;
            color: #8A5700;
        }

        .status {
            display: inline-block;
            border-radius: 20px;
            padding: 7px 11px;
            font-size: 11px;
            font-weight: 800;
            white-space: nowrap;
        }

        .status-pending {
            background: #FFF2D9;
            color: #8A5700;
        }

        .status-confirmed {
            background: #DBEAFE;
            color: #1D4ED8;
        }

        .status-progress {
            background: #F3E8FF;
            color: #7E22CE;
        }

        .status-completed {
            background: #DCFCE7;
            color: #166534;
        }

        .status-cancelled {
            background: #FEE2E2;
            color: #B91C1C;
        }

        .status-paid {
            background: #D1FAE5;
            color: #065F46;
        }

        .details {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 17px 14px;
            padding: 18px 0;
            border-top: 1px solid var(--border);
            border-bottom: 1px solid var(--border);
        }

        .detail-label {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 6px;
        }

        .detail-value {
            color: var(--text);
            font-size: 13px;
            font-weight: 700;
            line-height: 1.6;
            overflow-wrap: anywhere;
        }

        .price {
            color: var(--navy);
        }

        .price::before {
            content: "";
            display: inline-block;
            width: 4px;
            height: 15px;
            background: var(--orange);
            border-radius: 4px;
            margin-right: 7px;
            vertical-align: -3px;
        }

        .notes {
            margin-top: 17px;
            background: #F8FAFD;
            border: 1px solid #E8EEF6;
            border-left: 3px solid var(--orange);
            border-radius: 9px;
            padding: 13px;
        }

        .notes strong {
            color: var(--navy);
            font-size: 13px;
        }

        .notes p {
            font-size: 13px;
            line-height: 1.7;
            color: #52627A;
            margin-top: 6px;
            overflow-wrap: anywhere;
        }

        .empty {
            grid-column: 1 / -1;
            text-align: center;
            background: var(--white);
            border: 1px dashed #C4D0E0;
            border-radius: 14px;
            padding: 55px 20px;
        }

        .empty-icon {
            width: 76px;
            height: 76px;
            display: grid;
            place-items: center;
            background: var(--orange-light);
            border-radius: 20px;
            font-size: 38px;
            margin: 0 auto 18px;
        }

        .empty h2 {
            color: var(--navy);
            font-size: 21px;
        }

        .empty p {
            color: var(--muted);
            font-size: 14px;
            line-height: 1.8;
            margin: 10px 0 22px;
        }

        footer {
            text-align: center;
            padding: 30px 15px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .summary,
            .booking-list {
                grid-template-columns: 1fr;
            }
        }

        @media (max-width: 520px) {
            header {
                padding: 15px 18px;
            }

            main {
                margin-top: 25px;
                padding: 0 15px;
            }

            .heading {
                align-items: flex-start;
                flex-direction: column;
            }

            h1 {
                font-size: 24px;
            }

            .booking-top {
                flex-direction: column;
            }

            .details {
                grid-template-columns: 1fr 1fr;
            }

            .section-heading {
                align-items: flex-start;
                flex-direction: column;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">
        🔧 <span>Bengkel</span>Servis
    </a>

    <a class="back-link" href="/dashboard-pelanggan">
        ← Dashboard
    </a>
</header>

<main>
    <div class="breadcrumb">
        Beranda / Booking Servis / Riwayat Pengajuan
    </div>

    <div class="heading">
        <div>
            <h1>Booking Servis Saya</h1>
            <p class="subtitle">
                Pantau riwayat servis bengkel dan Home Service,
                kendaraan, jadwal, serta status pengajuan kamu.
            </p>
        </div>

        <a class="btn" href="/booking-pelanggan/create">
            + Booking Servis Baru
        </a>
    </div>

    @if (session('success'))
        <div class="message">
            ✓ {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="message">
            {{ $errors->first() }}
        </div>
    @endif

    @php
        $totalPengajuan = $bookings->count();

        $totalMenunggu = $bookings->filter(function ($item) {
            return in_array(
                strtolower(trim($item->status ?? '')),
                ['pending', 'requested']
            );
        })->count();

        $totalSelesai = $bookings->filter(function ($item) {
            return in_array(
                strtolower(trim($item->status ?? '')),
                ['completed', 'complete', 'selesai', 'lunas', 'paid']
            );
        })->count();
    @endphp

    <section class="summary">
        <article class="summary-card">
            <div class="summary-top">
                <p class="summary-label">Total Pengajuan</p>
                <div class="summary-icon">📋</div>
            </div>

            <div class="summary-number">{{ $totalPengajuan }}</div>
            <small>Seluruh pengajuan servis kamu</small>
        </article>

        <article class="summary-card">
            <div class="summary-top">
                <p class="summary-label">Menunggu Konfirmasi</p>
                <div class="summary-icon">⏳</div>
            </div>

            <div class="summary-number">{{ $totalMenunggu }}</div>
            <small>Pengajuan yang masih menunggu</small>
        </article>

        <article class="summary-card">
            <div class="summary-top">
                <p class="summary-label">Selesai / Lunas</p>
                <div class="summary-icon">✅</div>
            </div>

            <div class="summary-number">{{ $totalSelesai }}</div>
            <small>Pengajuan yang selesai atau lunas</small>
        </article>
    </section>

    <div class="section-heading">
        <h2 class="section-title">Riwayat Pengajuan Servis</h2>

        <span class="section-caption">
            {{ $totalPengajuan }} pengajuan tercatat
        </span>
    </div>

    <section class="booking-list">
        @forelse ($bookings as $booking)
            @php
                $status = strtolower(trim($booking->status ?? 'pending'));

                $statusClass = match ($status) {
                    'pending', 'requested' => 'status-pending',

                    'confirmed', 'confirm', 'approved', 'disetujui'
                        => 'status-confirmed',

                    'in progress', 'in_progress', 'processing', 'diproses'
                        => 'status-progress',

                    'completed', 'complete', 'selesai'
                        => 'status-completed',

                    'paid', 'lunas'
                        => 'status-paid',

                    'cancelled', 'canceled', 'dibatalkan'
                        => 'status-cancelled',

                    default => 'status-pending',
                };

                $statusLabel = match ($status) {
                    'pending', 'requested' => 'Menunggu Konfirmasi',

                    'confirmed', 'confirm', 'approved', 'disetujui'
                        => 'Dikonfirmasi',

                    'in progress', 'in_progress', 'processing', 'diproses'
                        => 'Sedang Diproses',

                    'completed', 'complete', 'selesai'
                        => 'Selesai',

                    'paid', 'lunas'
                        => 'Lunas',

                    'cancelled', 'canceled', 'dibatalkan'
                        => 'Dibatalkan',

                    default => ucfirst($booking->status ?? 'Pending'),
                };

                $isHomeService =
                    ($booking->jenis_pengajuan ?? '') === 'Home Service';
            @endphp

            <article class="booking-card">
                <div class="booking-top">
                    <div>
                        <p class="booking-id">
                            @if ($isHomeService)
                                Kode Home Service #{{ $booking->id_booking }}
                            @else
                                Kode booking #{{ $booking->id_booking }}
                            @endif
                        </p>

                        <h3 class="booking-title">
                            {{ $booking->nama_layanan ?? 'Layanan tidak tersedia' }}
                        </h3>

                        <div class="badges">
                            <span class="type-badge {{ $isHomeService ? 'type-home' : '' }}">
                                {{ $booking->jenis_pengajuan ?? 'Servis Bengkel' }}
                            </span>
                        </div>
                    </div>

                    <span class="status {{ $statusClass }}">
                        {{ $statusLabel }}
                    </span>
                </div>

                <div class="details">
                    <div>
                        <p class="detail-label">🚘 Kendaraan</p>

                        <p class="detail-value">
                            {{ $booking->merk ?? '-' }}
                            {{ $booking->model ?? '' }}
                        </p>

                        <p class="detail-label" style="margin-top:5px">
                            {{ $booking->nopol ?? 'Nomor polisi tidak tersedia' }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">🏢 Cabang Bengkel</p>

                        <p class="detail-value">
                            {{ $booking->nama_cabang ?? 'Cabang tidak tersedia' }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">📅 Tanggal Servis</p>

                        <p class="detail-value">
                            @if (!empty($booking->tanggal_booking))
                                {{ \Carbon\Carbon::parse($booking->tanggal_booking)->translatedFormat('d F Y') }}
                            @else
                                -
                            @endif
                        </p>
                    </div>

                    <div>
                        @if ($isHomeService)
                            <p class="detail-label">🏠 Alamat Home Service</p>

                            <p class="detail-value">
                                {{ $booking->alamat_home_service ?? '-' }}
                            </p>
                        @else
                            <p class="detail-label">🕒 Waktu Servis</p>

                            <p class="detail-value">
                                {{ substr($booking->waktu_booking ?? '00:00', 0, 5) }}
                                WIB
                            </p>
                        @endif
                    </div>

                    <div>
                        <p class="detail-label">💰 Harga Layanan</p>

                        <p class="detail-value price">
                            Rp {{ number_format((float) ($booking->harga ?? 0), 0, ',', '.') }}
                        </p>
                    </div>

                    <div>
                        <p class="detail-label">Status Pengajuan</p>

                        <p class="detail-value">
                            {{ $statusLabel }}
                        </p>
                    </div>
                </div>

                @if (!empty($booking->catatan))
                    <div class="notes">
                        <strong>📝 Catatan Kamu</strong>
                        <p>{{ $booking->catatan }}</p>
                    </div>
                @endif
            </article>

        @empty
            <div class="empty">
                <div class="empty-icon">🛠️</div>

                <h2>Belum Ada Pengajuan Servis</h2>

                <p>
                    Kamu belum mempunyai booking bengkel atau Home Service.<br>
                    Pilih layanan dan buat pengajuan servis pertamamu.
                </p>

                <a class="btn" href="/booking-pelanggan/create">
                    Buat Booking Pertama →
                </a>
            </div>
        @endforelse
    </section>
</main>

<footer>
    © {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
