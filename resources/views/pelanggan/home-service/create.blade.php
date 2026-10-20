
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Pengajuan Home Service | BengkelServis</title>

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
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: var(--text);
            background: var(--background);
        }

        header {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 18px 6%;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
        }

        .brand {
            color: var(--navy);
            font-size: 21px;
            font-weight: 800;
            text-decoration: none;
        }

        .brand span {
            color: var(--orange);
        }

        .back {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
            text-decoration: none;
        }

        main {
            max-width: 900px;
            margin: 35px auto;
            padding: 0 18px;
        }

        .intro {
            padding: 27px;
            border-radius: 16px;
            color: white;
            background: linear-gradient(
                120deg,
                var(--navy),
                var(--navy-light)
            );
            margin-bottom: 24px;
        }

        .intro .icon {
            font-size: 35px;
            margin-bottom: 8px;
        }

        .intro h1 {
            margin: 0 0 8px;
            font-size: 25px;
        }

        .intro p {
            margin: 0;
            color: rgba(255,255,255,.85);
            font-size: 13px;
            line-height: 1.8;
        }

        .card {
            padding: 28px;
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            box-shadow: 0 8px 25px rgba(16,43,85,.05);
        }

        .card h2 {
            margin: 0 0 5px;
            color: var(--navy);
            font-size: 19px;
        }

        .subtitle {
            margin: 0 0 25px;
            color: var(--muted);
            font-size: 13px;
        }

        .grid {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 20px;
        }

        .field {
            margin-bottom: 19px;
        }

        .full {
            grid-column: 1 / -1;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 13px;
            font-weight: 700;
        }

        input,
        select,
        textarea {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            background: white;
            color: var(--text);
            font: inherit;
            font-size: 13px;
        }

        textarea {
            min-height: 105px;
            resize: vertical;
        }

        input:focus,
        select:focus,
        textarea:focus {
            outline: 3px solid rgba(245,166,35,.18);
            border-color: var(--orange);
        }

        .readonly {
            background: #F1F5F9;
            color: #475569;
        }

        .hint {
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
            line-height: 1.6;
        }

        .error-box {
            margin-bottom: 22px;
            padding: 15px;
            border: 1px solid #FECACA;
            border-radius: 9px;
            color: #991B1B;
            background: #FEF2F2;
            font-size: 13px;
        }

        .error-box ul {
            padding-left: 20px;
            margin: 5px 0 0;
        }

        .actions {
            display: flex;
            justify-content: flex-end;
            gap: 12px;
            margin-top: 10px;
        }

        .button {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            border: 0;
            border-radius: 8px;
            padding: 13px 18px;
            font-size: 13px;
            font-weight: 700;
            text-decoration: none;
            cursor: pointer;
        }

        .button-cancel {
            color: var(--navy);
            background: #E8EEF6;
        }

        .button-submit {
            color: var(--navy);
            background: var(--orange);
        }

        .button-submit:hover {
            background: #FFD58A;
        }

        footer {
            padding: 25px 15px;
            text-align: center;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 650px) {
            header {
                padding: 16px;
            }

            main {
                margin-top: 22px;
            }

            .intro,
            .card {
                padding: 21px;
            }

            .grid {
                grid-template-columns: 1fr;
                gap: 0;
            }

            .full {
                grid-column: auto;
            }

            .actions {
                flex-direction: column-reverse;
            }

            .button {
                width: 100%;
            }
        }
    </style>
</head>

<body>
<header>
    <a href="/dashboard-pelanggan" class="brand">
        <span>Bengkel</span>Servis
    </a>

    <a href="/dashboard-pelanggan" class="back">
        ← Kembali ke Dashboard
    </a>
</header>

<main>
    <section class="intro">
        <div class="icon">🏠</div>
        <h1>Ajukan Home Service</h1>
        <p>
            Kendaraanmu bisa diservis di lokasi yang kamu tentukan.
            Lengkapi formulir berikut dan tunggu konfirmasi dari petugas bengkel.
        </p>
    </section>

    <section class="card">
        <h2>Formulir Permintaan Servis</h2>
        <p class="subtitle">
            Isi data kendaraan, lokasi, dan jadwal yang kamu inginkan.
        </p>

        @if ($errors->any())
            <div class="error-box">
                <strong>Pengajuan belum dapat diproses.</strong>
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="{{ route('pelanggan.home-service.store') }}" method="POST">
            @csrf

            <div class="grid">
                <div class="field full">
                    <label>Nama Pelanggan</label>
                    <input
                        type="text"
                        value="{{ $pelanggan->nama }}"
                        class="readonly"
                        readonly
                    >
                    <div class="hint">
                        Data pelanggan diambil dari akun yang sedang login.
                    </div>
                </div>

                <div class="field">
                    <label for="id_kendaraan">Kendaraan *</label>
                    <select id="id_kendaraan" name="id_kendaraan" required>
                        <option value="">-- Pilih Kendaraan --</option>
                        @foreach ($kendaraan as $item)
                            <option
                                value="{{ $item->id_kendaraan }}"
                                @selected(old('id_kendaraan') == $item->id_kendaraan)
                            >
                                {{ $item->merk ?? 'Kendaraan' }}
                                {{ $item->model ?? '' }}
                                - {{ $item->nopol ?? $item->nomor_polisi ?? $item->id_kendaraan }}
                            </option>
                        @endforeach
                    </select>

                    @if ($kendaraan->isEmpty())
                        <div class="hint">
                            Kamu belum memiliki data kendaraan.
                            Tambahkan kendaraan terlebih dahulu sebelum mengajukan servis.
                        </div>
                    @endif
                </div>

                <div class="field">
                    <label for="id_cabang">Cabang Bengkel *</label>
                    <select id="id_cabang" name="id_cabang" required>
                        <option value="">-- Pilih Cabang --</option>
                        @foreach ($cabang as $item)
                            <option
                                value="{{ $item->id_cabang }}"
                                @selected(old('id_cabang') == $item->id_cabang)
                            >
                                {{ $item->nama_cabang }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field full">
                    <label for="id_layanan_servis">Jenis Layanan</label>
                    <select id="id_layanan_servis" name="id_layanan_servis">
                        <option value="">-- Pilih Layanan (Opsional) --</option>
                        @foreach ($layanan as $item)
                            <option
                                value="{{ $item->id_layanan }}"
                                @selected(old('id_layanan_servis') == $item->id_layanan)
                            >
                                {{ $item->nama_layanan ?? $item->nama_layanan_servis ?? $item->nama ?? ('Layanan #' . $item->id_layanan) }}
                            </option>
                        @endforeach
                    </select>
                </div>

                <div class="field">
                    <label for="tanggal">Tanggal Servis *</label>
                    <input
                        type="date"
                        id="tanggal"
                        name="tanggal"
                        min="{{ date('Y-m-d') }}"
                        value="{{ old('tanggal') }}"
                        required
                    >
                </div>

                <div class="field">
                    <label>Status Pengajuan</label>
                    <input
                        type="text"
                        value="Menunggu Konfirmasi"
                        class="readonly"
                        readonly
                    >
                    <div class="hint">
                        Status awal akan tercatat sebagai Requested.
                    </div>
                </div>

                <div class="field full">
                    <label for="alamat">Alamat Lokasi Home Service *</label>
                    <textarea
                        id="alamat"
                        name="alamat"
                        placeholder="Masukkan alamat lengkap lokasi servis, termasuk patokan jika diperlukan."
                        required
                    >{{ old('alamat', $pelanggan->alamat) }}</textarea>
                </div>

                <div class="field full">
                    <label for="catatan">Keluhan atau Catatan</label>
                    <textarea
                        id="catatan"
                        name="catatan"
                        placeholder="Contoh: kendaraan sulit dinyalakan, ingin ganti oli, atau keluhan lainnya."
                    >{{ old('catatan') }}</textarea>
                </div>
            </div>

            <div class="actions">
                <a href="/dashboard-pelanggan" class="button button-cancel">
                    Batal
                </a>

                <button type="submit" class="button button-submit">
                    Kirim Pengajuan →
                </button>
            </div>
        </form>
    </section>
</main>

<footer>
    © {{ date('Y') }} BengkelServis · Layanan Home Service
</footer>
</body>
</html>
