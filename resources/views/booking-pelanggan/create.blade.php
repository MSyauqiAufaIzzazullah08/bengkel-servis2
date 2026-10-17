
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Booking Servis | BengkelServis</title>

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
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 6%;
            gap: 15px;
        }

        .logo {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
        }

        .logo span {
            color: var(--orange);
        }

        .back-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .back-link:hover {
            color: #A45F00;
        }

        .container {
            max-width: 1050px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 18px;
        }

        .page-heading {
            margin-bottom: 24px;
        }

        .page-heading h1 {
            color: var(--navy);
            font-size: 28px;
            margin-bottom: 7px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 14px;
        }

        .booking-layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: white;
            padding: 28px;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 5px 20px rgba(16,43,85,.04);
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            margin-bottom: 24px;
            padding-bottom: 19px;
            border-bottom: 1px solid var(--border);
        }

        .heading-icon {
            width: 47px;
            height: 47px;
            display: grid;
            place-items: center;
            background: var(--orange-light);
            border-radius: 12px;
            font-size: 23px;
            flex-shrink: 0;
        }

        .card-heading h2 {
            color: var(--navy);
            font-size: 18px;
        }

        .card-heading p {
            color: var(--muted);
            font-size: 12px;
            margin-top: 3px;
        }

        .form-grid {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 0 18px;
        }

        .group {
            margin-bottom: 17px;
            min-width: 0;
        }

        .full-width {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 7px;
            font-weight: bold;
            font-size: 13px;
            color: var(--navy);
        }

        .required {
            color: #C2410C;
        }

        input, select, textarea {
            display: block;
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            background: white;
            color: var(--text);
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus, select:focus, textarea:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245,166,35,.18);
        }

        textarea {
            resize: vertical;
            min-height: 100px;
        }

        .field-help {
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 20px;
            font-size: 13px;
            line-height: 1.7;
        }

        .alert-error {
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
        }

        .alert-success {
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #BBF7D0;
        }

        .alert ul {
            padding-left: 20px;
            margin-top: 5px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 10px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            border: 1px solid transparent;
            border-radius: 8px;
            padding: 12px 17px;
            font-family: inherit;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary {
            flex: 1;
            color: white;
            background: var(--navy);
            border-color: var(--navy);
        }

        .btn-primary:hover {
            background: var(--navy-light);
        }

        .btn-secondary {
            color: var(--navy);
            background: white;
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: var(--background);
        }

        .info-card {
            background: linear-gradient(145deg, var(--navy), var(--navy-light));
            color: white;
            border-radius: 14px;
            padding: 23px;
            margin-bottom: 17px;
            overflow: hidden;
            position: relative;
        }

        .info-card h3 {
            font-size: 17px;
            margin-bottom: 9px;
        }

        .info-card p {
            font-size: 12px;
            color: rgba(255,255,255,.82);
            line-height: 1.8;
        }

        .info-card .info-symbol {
            font-size: 29px;
            margin-bottom: 12px;
        }

        .steps-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
        }

        .steps-card h3 {
            font-size: 15px;
            color: var(--navy);
            margin-bottom: 17px;
        }

        .step {
            display: flex;
            align-items: flex-start;
            gap: 11px;
            margin-bottom: 18px;
        }

        .step:last-child {
            margin-bottom: 0;
        }

        .step-number {
            width: 29px;
            height: 29px;
            flex-shrink: 0;
            display: grid;
            place-items: center;
            border-radius: 9px;
            background: var(--orange-light);
            color: var(--navy);
            font-size: 12px;
            font-weight: 800;
        }

        .step strong {
            display: block;
            color: var(--navy);
            font-size: 12px;
            margin-bottom: 3px;
        }

        .step p {
            color: var(--muted);
            font-size: 11px;
            line-height: 1.7;
        }

        .empty-vehicle {
            padding: 12px;
            border-radius: 8px;
            background: var(--orange-light);
            color: #854D0E;
            font-size: 12px;
            margin-top: 8px;
            line-height: 1.7;
        }

        .empty-vehicle a {
            display: inline-block;
            color: var(--navy);
            font-weight: bold;
            text-decoration: underline;
        }

        footer {
            text-align: center;
            padding: 25px 15px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .booking-layout {
                grid-template-columns: 1fr;
            }

            .side-panel {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 15px;
            }

            .info-card {
                margin-bottom: 0;
            }
        }

        @media (max-width: 560px) {
            header {
                padding: 15px 18px;
            }

            .logo {
                font-size: 18px;
            }

            .container {
                margin: 25px auto;
                padding: 0 14px;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .card {
                padding: 20px;
            }

            .form-grid {
                grid-template-columns: 1fr;
            }

            .full-width {
                grid-column: auto;
            }

            .side-panel {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
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
        ← Dashboard Pelanggan
    </a>
</header>

<main class="container">
    <div class="breadcrumb">Beranda / Booking Servis / Booking Baru</div>

    <div class="page-heading">
        <h1>Booking Servis Kendaraan</h1>
        <p>Pilih kendaraan, cabang bengkel, dan layanan yang kamu inginkan.</p>
    </div>

    @if (session('error'))
        <div class="alert alert-error">
            {{ session('error') }}
        </div>
    @endif

    @if (session('success'))
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if ($errors->any())
        <div class="alert alert-error">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="booking-layout">
        <section class="card">
            <div class="card-heading">
                <div class="heading-icon">📅</div>
                <div>
                    <h2>Form Booking Servis</h2>
                    <p>Lengkapi data untuk mengajukan jadwal servis.</p>
                </div>
            </div>

            <form action="{{ url('/booking-pelanggan') }}" method="POST">
                @csrf

                <div class="form-grid">
                    <div class="group full-width">
                        <label for="id_kendaraan">
                            Kendaraan <span class="required">*</span>
                        </label>

                        <select name="id_kendaraan" id="id_kendaraan" required>
                            <option value="">-- Pilih kendaraan --</option>
                            @foreach ($kendaraan as $item)
                                <option
                                    value="{{ $item->id_kendaraan }}"
                                    {{ old('id_kendaraan') == $item->id_kendaraan ? 'selected' : '' }}
                                >
                                    {{ $item->merk }} {{ $item->model }} - {{ $item->nopol }}
                                </option>
                            @endforeach
                        </select>

                        @if (count($kendaraan) == 0)
                            <div class="empty-vehicle">
                                Kamu belum memiliki kendaraan.
                                <a href="/kendaraan-pelanggan">Tambahkan kendaraan dahulu.</a>
                            </div>
                        @else
                            <p class="field-help">
                                Pilih kendaraan yang akan diservis.
                            </p>
                        @endif
                    </div>

                    <div class="group full-width">
                        <label for="id_cabang">
                            Cabang Bengkel <span class="required">*</span>
                        </label>

                        <select name="id_cabang" id="id_cabang" required>
                            <option value="">-- Pilih cabang bengkel --</option>
                            @foreach ($cabang as $item)
                                <option
                                    value="{{ $item->id_cabang }}"
                                    {{ old('id_cabang') == $item->id_cabang ? 'selected' : '' }}
                                >
                                    {{ $item->nama_cabang }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group full-width">
                        <label for="layanan_id">
                            Layanan Servis <span class="required">*</span>
                        </label>

                        <select name="layanan_id" id="layanan_id" required>
                            <option value="">-- Pilih layanan servis --</option>
                            @foreach ($layanan as $item)
                                <option
                                    value="{{ $item->id_layanan }}"
                                    {{ old('layanan_id') == $item->id_layanan ? 'selected' : '' }}
                                >
                                    {{ $item->nama_layanan }} - Rp {{ number_format($item->harga, 0, ',', '.') }}
                                </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="group">
                        <label for="tanggal_booking">
                            Tanggal Booking <span class="required">*</span>
                        </label>

                        <input
                            type="date"
                            name="tanggal_booking"
                            id="tanggal_booking"
                            min="{{ date('Y-m-d') }}"
                            value="{{ old('tanggal_booking') }}"
                            required
                        >
                    </div>

                    <div class="group">
                        <label for="waktu_booking">
                            Waktu Booking <span class="required">*</span>
                        </label>

                        <input
                            type="time"
                            name="waktu_booking"
                            id="waktu_booking"
                            value="{{ old('waktu_booking') }}"
                            required
                        >
                    </div>

                    <div class="group full-width">
                        <label for="catatan">Catatan (Opsional)</label>

                        <textarea
                            name="catatan"
                            id="catatan"
                            rows="4"
                            placeholder="Ceritakan keluhan kendaraan kamu..."
                        >{{ old('catatan') }}</textarea>

                        <p class="field-help">
                            Contoh: mesin sulit dinyalakan, rem berbunyi, atau perlu ganti oli.
                        </p>
                    </div>
                </div>

                <div class="form-actions">
                    <a href="/dashboard-pelanggan" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button
                        type="submit"
                        class="btn btn-primary"
                        @if (count($kendaraan) == 0) disabled style="opacity:.55;cursor:not-allowed" @endif
                    >
                        Ajukan Booking →
                    </button>
                </div>
            </form>
        </section>

        <aside class="side-panel">
            <div class="info-card">
                <div class="info-symbol">🔧</div>
                <h3>Servis Lebih Terencana</h3>
                <p>
                    Pilih layanan dan jadwal yang sesuai dengan kebutuhan kendaraanmu.
                    Pengajuan booking akan menunggu konfirmasi dari pihak bengkel.
                </p>
            </div>

            <div class="steps-card">
                <h3>Langkah Booking</h3>

                <div class="step">
                    <div class="step-number">1</div>
                    <div>
                        <strong>Pilih Kendaraan</strong>
                        <p>Tentukan kendaraan yang akan diservis.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">2</div>
                    <div>
                        <strong>Pilih Layanan & Cabang</strong>
                        <p>Tentukan layanan dan lokasi bengkel.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">3</div>
                    <div>
                        <strong>Tentukan Jadwal</strong>
                        <p>Pilih tanggal, waktu, dan tambahkan catatan bila perlu.</p>
                    </div>
                </div>

                <div class="step">
                    <div class="step-number">4</div>
                    <div>
                        <strong>Tunggu Konfirmasi</strong>
                        <p>Pantau status pengajuan pada halaman booking kamu.</p>
                    </div>
                </div>
            </div>
        </aside>
    </div>
</main>

<footer>
    © {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

</body>
</html>
