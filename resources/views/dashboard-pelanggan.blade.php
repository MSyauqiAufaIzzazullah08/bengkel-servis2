
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Pelanggan | BengkelServis</title>

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
            color: inherit;
        }

        button {
            font-family: inherit;
            cursor: pointer;
        }

        .topbar {
            min-height: 76px;
            background: var(--white);
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 14px 5%;
            gap: 20px;
        }

        .brand {
            display: flex;
            align-items: center;
            gap: 11px;
            font-size: 20px;
            font-weight: 800;
            color: var(--navy);
        }

        .brand-name span {
            color: var(--orange);
        }

        .brand-icon {
            width: 42px;
            height: 42px;
            display: grid;
            place-items: center;
            background: var(--orange-light);
            border-radius: 12px;
            font-size: 22px;
        }

        .topbar-right {
            display: flex;
            align-items: center;
            gap: 15px;
        }

        .profile {
            display: flex;
            align-items: center;
            gap: 10px;
        }

        .avatar {
            width: 40px;
            height: 40px;
            border-radius: 50%;
            background: var(--orange-light);
            color: var(--navy);
            display: grid;
            place-items: center;
            font-weight: 800;
        }

        .profile-name {
            font-size: 13px;
            font-weight: 700;
            color: var(--navy);
        }

        .profile-label {
            color: var(--muted);
            font-size: 11px;
            margin-top: 3px;
        }

        .logout {
            border: 1px solid #F2D6A7;
            color: var(--navy);
            background: var(--orange-light);
            border-radius: 9px;
            padding: 10px 14px;
            font-size: 12px;
            font-weight: 700;
            transition: .2s;
        }

        .logout:hover {
            background: var(--orange);
        }

        .layout {
            display: flex;
            min-height: calc(100vh - 76px);
        }

        .sidebar {
            width: 250px;
            flex-shrink: 0;
            color: white;
            background: linear-gradient(
                180deg,
                var(--navy) 0%,
                #0B2145 100%
            );
            padding: 27px 15px;
        }

        .sidebar-label {
            margin: 0 12px 15px;
            color: var(--orange-light);
            font-size: 10px;
            font-weight: 800;
            letter-spacing: 1.2px;
        }

        .nav-link {
            display: flex;
            align-items: center;
            gap: 12px;
            padding: 12px 13px;
            border-radius: 9px;
            margin-bottom: 6px;
            color: rgba(255,255,255,.82);
            font-size: 12px;
            font-weight: 600;
            transition: .2s;
        }

        .nav-link:hover {
            background: rgba(255,255,255,.10);
            color: white;
        }

        .nav-link.active {
            background: var(--orange);
            color: var(--navy);
            font-weight: 800;
        }

        .nav-icon {
            width: 22px;
            text-align: center;
            font-size: 16px;
        }

        .sidebar-help {
            margin-top: 30px;
            background: rgba(255,255,255,.07);
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 12px;
            padding: 15px;
        }

        .sidebar-help strong {
            font-size: 13px;
            color: white;
        }

        .sidebar-help p {
            color: rgba(255,255,255,.7);
            font-size: 11px;
            line-height: 1.7;
            margin: 7px 0 10px;
        }

        .sidebar-help a {
            color: var(--orange-light);
            font-size: 12px;
            font-weight: 700;
        }

        /* BACKGROUND FOTO DASHBOARD */
        .main {
            flex: 1;
            min-width: 0;
            padding: 32px;
            background-color: #F4F7FC;
            background-image:
                linear-gradient(
                    rgba(244, 247, 252, 0.88),
                    rgba(244, 247, 252, 0.88)
                ),
                url("{{ asset('assets/images/bengkel3.jpg') }}");
            background-size: cover;
            background-position: center;
            background-repeat: no-repeat;
            background-attachment: fixed;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 18px;
        }

        .page-heading {
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 25px;
        }

        .page-heading h1 {
            color: var(--navy);
            font-size: 27px;
            margin: 0 0 8px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 13px;
            margin: 0;
        }

        .button-primary {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            background: var(--navy);
            color: white;
            border-radius: 9px;
            padding: 12px 17px;
            font-size: 12px;
            font-weight: 700;
            border: 1px solid var(--navy);
            transition: .2s;
            white-space: nowrap;
        }

        .button-primary:hover {
            background: var(--navy-light);
            transform: translateY(-1px);
        }

        .welcome-banner {
            background: linear-gradient(
                115deg,
                var(--navy),
                var(--navy-light)
            );
            border-radius: 15px;
            padding: 30px;
            color: white;
            display: flex;
            align-items: center;
            justify-content: space-between;
            gap: 20px;
            margin-bottom: 27px;
            position: relative;
            overflow: hidden;
            box-shadow: 0 10px 25px rgba(16,43,85,.13);
        }

        .welcome-banner::after {
            content: "";
            position: absolute;
            width: 180px;
            height: 180px;
            border: 25px solid rgba(245,166,35,.12);
            border-radius: 50%;
            right: 80px;
            top: -90px;
            pointer-events: none;
        }

        .welcome-banner h2 {
            margin: 0 0 12px;
            font-size: 23px;
            position: relative;
            z-index: 1;
        }

        .welcome-banner p {
            color: rgba(255,255,255,.85);
            font-size: 13px;
            line-height: 1.8;
            max-width: 580px;
            margin: 0 0 20px;
            position: relative;
            z-index: 1;
        }

        .button-light {
            display: inline-block;
            background: var(--orange);
            color: var(--navy);
            padding: 12px 16px;
            border-radius: 8px;
            font-size: 12px;
            font-weight: 800;
            position: relative;
            z-index: 1;
            transition: .2s;
        }

        .button-light:hover {
            background: #FFD58A;
        }

        .banner-art {
            font-size: 72px;
            padding: 10px 25px;
            position: relative;
            z-index: 1;
        }

        .section-title {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin: 30px 0 16px;
            gap: 15px;
        }

        .section-title h2 {
            color: var(--navy);
            font-size: 17px;
            margin: 0;
        }

        .section-title span {
            font-size: 11px;
            color: var(--muted);
        }

        .stats-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 17px;
        }

        .stat-card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(16,43,85,.03);
        }

        .stat-top {
            display: flex;
            align-items: center;
            justify-content: space-between;
            margin-bottom: 17px;
        }

        .stat-icon {
            width: 43px;
            height: 43px;
            display: grid;
            place-items: center;
            border-radius: 11px;
            background: var(--orange-light);
            font-size: 20px;
        }

        .stat-label {
            color: var(--muted);
            font-size: 12px;
        }

        .stat-value {
            color: var(--navy);
            font-size: 22px;
            font-weight: 800;
            margin-top: 7px;
        }

        .service-grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 17px;
        }

        .service-card {
            display: block;
            background: white;
            border: 1px solid var(--border);
            border-radius: 13px;
            padding: 22px;
            transition: .2s;
        }

        .service-card:hover {
            border-color: var(--orange);
            box-shadow: 0 8px 24px rgba(16,43,85,.08);
            transform: translateY(-3px);
        }

        .service-icon {
            width: 46px;
            height: 46px;
            display: grid;
            place-items: center;
            background: var(--orange-light);
            border-radius: 11px;
            font-size: 22px;
            margin-bottom: 16px;
        }

        .service-card h3 {
            color: var(--navy);
            font-size: 14px;
            margin: 0 0 9px;
        }

        .service-card p {
            font-size: 12px;
            color: var(--muted);
            line-height: 1.8;
            min-height: 42px;
            margin: 0 0 17px;
        }

        .service-link {
            color: #A45F00;
            font-size: 12px;
            font-weight: 800;
        }

        .bottom-note {
            margin-top: 30px;
            background: white;
            border: 1px solid var(--border);
            border-left: 4px solid var(--orange);
            border-radius: 12px;
            padding: 19px 22px;
            display: flex;
            align-items: center;
            gap: 14px;
        }

        .bottom-note-icon {
            font-size: 25px;
        }

        .bottom-note strong {
            color: var(--navy);
            font-size: 13px;
        }

        .bottom-note p {
            font-size: 11px;
            color: var(--muted);
            margin: 5px 0 0;
            line-height: 1.7;
        }

        footer {
            padding: 28px 0 5px;
            color: var(--muted);
            font-size: 11px;
            text-align: center;
        }

        @media (max-width: 1050px) {
            .sidebar {
                width: 220px;
            }

            .main {
                padding: 25px;
            }

            .stats-grid,
            .service-grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }
        }

        @media (max-width: 700px) {
            .topbar {
                padding: 13px 16px;
            }

            .profile-info {
                display: none;
            }

            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 12px;
                border-bottom: 1px solid var(--border);
                display: flex;
                overflow-x: auto;
                gap: 5px;
            }

            .sidebar-label,
            .nav-icon,
            .sidebar-help {
                display: none;
            }

            .nav-link {
                white-space: nowrap;
                margin: 0;
                padding: 11px;
            }

            .main {
                padding: 22px 16px;
                background-attachment: scroll;
            }

            .page-heading {
                align-items: flex-start;
                flex-direction: column;
            }

            .welcome-banner {
                padding: 23px;
            }

            .welcome-banner h2 {
                font-size: 20px;
            }

            .banner-art {
                display: none;
            }

            .stats-grid,
            .service-grid {
                grid-template-columns: 1fr;
            }

            .service-card p {
                min-height: 0;
            }

            .section-title {
                align-items: flex-start;
            }
        }
    </style>
</head>

<body>
<header class="topbar">
    <a href="/dashboard-pelanggan" class="brand">
        <span class="brand-icon">🔧</span>
        <span class="brand-name"><span>Bengkel</span>Servis</span>
    </a>

    <div class="topbar-right">
        <div class="profile">
            <div class="avatar">
                {{ strtoupper(substr(session('user_nama', 'P'), 0, 1)) }}
            </div>

            <div class="profile-info">
                <div class="profile-name">
                    {{ session('user_nama', 'Pelanggan') }}
                </div>
                <div class="profile-label">Akun Pelanggan</div>
            </div>
        </div>

        <form action="/logout" method="POST"
              onsubmit="return confirm('Yakin ingin keluar dari akun?')">
            @csrf
            <button type="submit" class="logout">Keluar ↗</button>
        </form>
    </div>
</header>

<div class="layout">
    <aside class="sidebar">
        <p class="sidebar-label">MENU UTAMA</p>

        <a href="/dashboard-pelanggan" class="nav-link active">
            <span class="nav-icon">▦</span> Dashboard
        </a>

        <a href="/layanan-pelanggan" class="nav-link">
            <span class="nav-icon">🔧</span> Layanan Servis
        </a>

        <a href="/booking-pelanggan" class="nav-link">
            <span class="nav-icon">📋</span> Booking Servis Saya
        </a>

        <a href="/booking-pelanggan/create" class="nav-link">
            <span class="nav-icon">📅</span> Booking Servis Baru
        </a>  
<!-- MENU HOME SERVICE -->
<a href="/home-service-pelanggan" class="nav-link">
    <span class="nav-icon">🏠</span> Home Service
</a>


        <a href="/kendaraan-pelanggan" class="nav-link">
            <span class="nav-icon">🚗</span> Kendaraan Saya
        </a>

        <a href="/sparepart-pelanggan" class="nav-link">
            <span class="nav-icon">🔩</span> Katalog Spare Part
        </a>

        <a href="/order-pelanggan" class="nav-link">
            <span class="nav-icon">🛒</span> Pesanan Spare Part
        </a>

        <a href="/pembayaran-pelanggan" class="nav-link">
            <span class="nav-icon">💳</span> Pembayaran
        </a>

        <a href="/rating-pelanggan" class="nav-link">
            <span class="nav-icon">⭐</span> Rating & Ulasan
        </a>

        <div class="sidebar-help">
            <strong>Butuh bantuan?</strong>
            <p>
                Pastikan kamu memilih layanan dan cabang yang sesuai
                sebelum melakukan booking atau membeli spare part.
            </p>
            <a href="/layanan-pelanggan">Lihat layanan →</a>
        </div>
    </aside>

    <main class="main">
        <div class="breadcrumb">Beranda / Dashboard</div>

        <div class="page-heading">
            <div>
                <h1>Dashboard Pelanggan</h1>
                <p>Kelola servis dan kebutuhan kendaraanmu dengan mudah.</p>
            </div>

            <a href="/booking-pelanggan/create" class="button-primary">
                + Booking Servis
            </a>
        </div>

        <section class="welcome-banner">
            <div>
                <h2>Halo, {{ session('user_nama', 'Pelanggan') }}! 👋</h2>
                <p>
                    Selamat datang di portal pelanggan BengkelServis.
                    Kamu bisa memesan layanan, memilih cabang bengkel,
                    mengelola kendaraan, dan membeli spare part dari satu tempat.
                </p>
                <a href="/booking-pelanggan/create" class="button-light">
                    Buat Booking Sekarang →
                </a>
            </div>

            <div class="banner-art">🚘</div>
        </section>

        <div class="section-title">
            <h2>Ringkasan Akun</h2>
            <span>Informasi portal pelanggan</span>
        </div>

        <section class="stats-grid">
            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Layanan Servis</span>
                    <span class="stat-icon">🔧</span>
                </div>
                <div class="stat-value">Servis</div>
                <div class="stat-label">Perawatan kendaraan</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Booking Servis</span>
                    <span class="stat-icon">📅</span>
                </div>
                <div class="stat-value">Booking</div>
                <div class="stat-label">Jadwalkan kunjunganmu</div>
            </div>

            <div class="stat-card">
                <div class="stat-top">
                    <span class="stat-label">Kendaraan Saya</span>
                    <span class="stat-icon">🚗</span>
                </div>
                <div class="stat-value">Kendaraan</div>
                <div class="stat-label">Kelola kendaraanmu</div>
            </div>
        </section>

        <div class="section-title">
            <h2>Akses Cepat</h2>
            <span>Pilih fitur yang kamu butuhkan</span>
        </div>

        <section class="service-grid">
            <a href="/layanan-pelanggan" class="service-card">
                <div class="service-icon">🔧</div>
                <h3>Layanan Servis</h3>
                <p>
                    Lihat pilihan layanan perawatan dan perbaikan kendaraan.
                </p>
                <span class="service-link">Jelajahi layanan →</span>
            </a>

            <a href="/booking-pelanggan/create" class="service-card">
                <div class="service-icon">📅</div>
                <h3>Booking Servis Baru</h3>
                <p>
                    Tentukan kendaraan, cabang bengkel, layanan, dan jadwal servis.
                </p>
                <span class="service-link">Buat booking →</span>
            </a>
<!-- KARTU HOME SERVICE -->
<a href="/home-service-pelanggan" class="service-card">
    <div class="service-icon">🏠</div>
    <h3>Home Service</h3>
    <p>
        Pesan layanan servis kendaraan di lokasi kamu
        tanpa perlu datang langsung ke bengkel.
    </p>
    <span class="service-link">Ajukan Home Service →</span>
</a>


            <a href="/booking-pelanggan" class="service-card">
                <div class="service-icon">📋</div>
                <h3>Booking Servis Saya</h3>
                <p>
                    Lihat riwayat booking, cabang pilihan, jadwal, dan status servis.
                </p>
                <span class="service-link">Lihat booking →</span>
            </a>

            <a href="/kendaraan-pelanggan" class="service-card">
                <div class="service-icon">🚗</div>
                <h3>Kendaraan Saya</h3>
                <p>
                    Kelola informasi dan foto kendaraan yang kamu gunakan untuk servis.
                </p>
                <span class="service-link">Lihat kendaraan →</span>
            </a>

            <a href="/sparepart-pelanggan" class="service-card">
                <div class="service-icon">🔩</div>
                <h3>Belanja Spare Part</h3>
                <p>
                    Lihat katalog suku cadang, periksa harga dan stok,
                    lalu pilih spare part yang kamu butuhkan.
                </p>
                <span class="service-link">Lihat katalog →</span>
            </a>

            <a href="/order-pelanggan" class="service-card">
                <div class="service-icon">🛒</div>
                <h3>Pesanan Spare Part</h3>
                <p>
                    Lihat informasi pemesanan suku cadang kendaraan.
                </p>
                <span class="service-link">Lihat pesanan →</span>
            </a>

            <a href="/pembayaran-pelanggan" class="service-card">
                <div class="service-icon">💳</div>
                <h3>Pembayaran</h3>
                <p>
                    Periksa riwayat dan status pembayaran dari transaksi kamu.
                </p>
                <span class="service-link">Lihat pembayaran →</span>
            </a>

            <a href="/rating-pelanggan" class="service-card">
                <div class="service-icon">⭐</div>
                <h3>Rating & Ulasan</h3>
                <p>
                    Berikan penilaian terhadap pelayanan bengkel.
                </p>
                <span class="service-link">Beri ulasan →</span>
            </a>
        </section>

        <section class="bottom-note">
            <div class="bottom-note-icon">💡</div>
            <div>
                <strong>Tips sebelum booking atau membeli spare part</strong>
                <p>
                    Pastikan data kendaraan sudah tersedia sebelum booking.
                    Untuk pembelian spare part, periksa nama barang, harga,
                    dan stok sebelum membuat pesanan.
                </p>
            </div>
        </section>

        <footer>
            © {{ date('Y') }} BengkelServis · Portal Pelanggan
        </footer>
    </main>
</div>
</body>
</html>
