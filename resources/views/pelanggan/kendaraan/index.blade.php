
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Kendaraan Saya | BengkelServis</title>

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
            display: flex;
            justify-content: space-between;
            align-items: center;
            padding: 16px 6%;
            border-bottom: 1px solid var(--border);
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

        .top-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .top-link:hover {
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
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 14px;
            color: var(--muted);
            line-height: 1.7;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            gap: 7px;
            background: var(--navy);
            color: white;
            padding: 12px 17px;
            border-radius: 8px;
            font-size: 13px;
            font-weight: bold;
            border: 1px solid var(--navy);
            cursor: pointer;
            transition: .2s;
            white-space: nowrap;
        }

        .btn:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }

        .message {
            padding: 14px 16px;
            background: #DCFCE7;
            color: #166534;
            border: 1px solid #BBF7D0;
            border-radius: 9px;
            margin-bottom: 18px;
            font-size: 13px;
        }

        .message.error {
            background: #FEE2E2;
            color: #991B1B;
            border-color: #FECACA;
        }

        .message ul {
            padding-left: 20px;
            margin-top: 6px;
        }

        .toolbar {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 15px;
            margin-bottom: 18px;
        }

        .toolbar h2 {
            color: var(--navy);
            font-size: 19px;
        }

        .count {
            background: var(--orange-light);
            color: #8A5700;
            padding: 7px 12px;
            border-radius: 20px;
            font-size: 12px;
            font-weight: bold;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 22px;
        }

        .card {
            background: var(--white);
            border: 1px solid var(--border);
            border-radius: 15px;
            overflow: hidden;
            box-shadow: 0 4px 15px rgba(16,43,85,.035);
            transition: transform .2s, box-shadow .2s;
            min-width: 0;
        }

        .card:hover {
            transform: translateY(-3px);
            box-shadow: 0 10px 25px rgba(16,43,85,.08);
        }

        .vehicle-photo {
            width: 100%;
            height: 220px;
            background: #E8EEF6;
            display: flex;
            align-items: center;
            justify-content: center;
            overflow: hidden;
            border-bottom: 1px solid var(--border);
        }

        .vehicle-photo img {
            width: 100%;
            height: 100%;
            object-fit: cover;
            display: block;
        }

        .photo-placeholder {
            display: flex;
            flex-direction: column;
            align-items: center;
            justify-content: center;
            gap: 10px;
            color: var(--muted);
            font-size: 13px;
            text-align: center;
            padding: 15px;
        }

        .photo-placeholder span {
            font-size: 45px;
        }

        .card-content {
            padding: 22px;
        }

        .vehicle-category {
            display: inline-block;
            background: var(--orange-light);
            color: var(--navy);
            padding: 6px 10px;
            border-radius: 20px;
            font-size: 10px;
            font-weight: 800;
            letter-spacing: .4px;
            margin-bottom: 12px;
        }

        .card h2 {
            color: var(--navy);
            font-size: 20px;
            margin-bottom: 4px;
            line-height: 1.4;
            overflow-wrap: anywhere;
        }

        .plate {
            display: inline-block;
            background: #F8FAFD;
            border: 1px solid #C8D4E4;
            border-bottom: 3px solid var(--orange);
            padding: 8px 13px;
            border-radius: 7px;
            font-weight: 800;
            font-size: 14px;
            margin: 12px 0;
            color: var(--navy);
            letter-spacing: .5px;
        }

        .detail {
            font-size: 13px;
            color: var(--muted);
            line-height: 2;
        }

        .detail strong {
            color: var(--text);
        }

        .actions {
            display: flex;
            justify-content: space-between;
            align-items: center;
            flex-wrap: wrap;
            gap: 12px;
            margin-top: 20px;
            border-top: 1px solid var(--border);
            padding-top: 16px;
        }

        .booking-link {
            color: var(--navy);
            font-size: 12px;
            font-weight: bold;
        }

        .booking-link:hover {
            color: #A45F00;
        }

        .delete {
            border: 1px solid #FECACA;
            background: #FEF2F2;
            color: #B91C1C;
            padding: 9px 12px;
            border-radius: 7px;
            cursor: pointer;
            font-size: 12px;
            font-weight: bold;
            font-family: inherit;
        }

        .delete:hover {
            background: #FEE2E2;
        }

        .empty {
            background: var(--white);
            border: 1px dashed #C4D0E0;
            border-radius: 14px;
            text-align: center;
            padding: 55px 20px;
            grid-column: 1 / -1;
        }

        .empty .emoji {
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
            margin: 10px 0 20px;
        }

        footer {
            text-align: center;
            padding: 30px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 900px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 560px) {
            .grid {
                grid-template-columns: 1fr;
            }

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

            .vehicle-photo {
                height: 230px;
            }

            .card-content {
                padding: 19px;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">
        🔧 <span>Bengkel</span>Servis
    </a>

    <a class="top-link" href="/dashboard-pelanggan">
        ← Dashboard
    </a>
</header>

<main>
    <div class="breadcrumb">Beranda / Kendaraan / Kendaraan Saya</div>

    <div class="heading">
        <div>
            <h1>Kendaraan Saya</h1>
            <p class="subtitle">
                Kelola kendaraan dan foto kendaraanmu untuk memudahkan proses booking servis.
            </p>
        </div>

        <a class="btn" href="/kendaraan-pelanggan/create">
            + Tambah Kendaraan
        </a>
    </div>

    @if (session('success'))
        <div class="message">✓ {{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="message error">
            <strong>Periksa kembali data kamu:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="toolbar">
        <h2>Daftar Kendaraan</h2>
        <span class="count">{{ $kendaraans->count() }} kendaraan</span>
    </div>

    <div class="grid">
        @forelse ($kendaraans as $kendaraan)
            <article class="card">
                <div class="vehicle-photo">
                    @if (!empty($kendaraan->foto))
                        <img
                            src="{{ asset('storage/' . $kendaraan->foto) }}"
                            alt="Foto {{ $kendaraan->merk }} {{ $kendaraan->model }}"
                            loading="lazy"
                            onerror="this.style.display='none'; this.nextElementSibling.style.display='flex';"
                        >

                        <div class="photo-placeholder" style="display:none">
                            <span>🚘</span>
                            Foto kendaraan tidak tersedia
                        </div>
                    @else
                        <div class="photo-placeholder">
                            <span>🚘</span>
                            Belum ada foto kendaraan
                        </div>
                    @endif
                </div>

                <div class="card-content">
                    <span class="vehicle-category">KENDARAAN SAYA</span>

                    <h2>{{ $kendaraan->merk }} {{ $kendaraan->model }}</h2>

                    <div class="plate">{{ $kendaraan->nopol }}</div>

                    <p class="detail">
                        Tahun kendaraan:
                        <strong>{{ $kendaraan->tahun }}</strong>
                        <br>
                        Pemilik:
                        <strong>{{ $pelanggan->nama }}</strong>
                    </p>

                    <div class="actions">
                        <a class="booking-link" href="/booking-pelanggan/create">
                            Booking Servis →
                        </a>

                        <form
                            action="/kendaraan-pelanggan/{{ $kendaraan->id_kendaraan }}"
                            method="POST"
                            onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')"
                        >
                            @csrf
                            @method('DELETE')

                            <button class="delete" type="submit">
                                Hapus
                            </button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">
                <div class="emoji">🚗</div>

                <h2>Belum Ada Kendaraan</h2>

                <p>
                    Tambahkan kendaraanmu terlebih dahulu sebelum membuat booking servis.
                </p>

                <a class="btn" href="/kendaraan-pelanggan/create">
                    + Tambah Kendaraan Pertama
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
