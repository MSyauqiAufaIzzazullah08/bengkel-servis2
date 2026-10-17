<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard Super Admin | BengkelServis</title>

    <style>
        :root {
            --navy: #102B55;
            --navy-light: #173B75;
            --orange: #F5A623;
            --orange-light: #FFD58A;
            --background: #F4F7FC;
            --white: #FFFFFF;
            --text: #172033;
            --muted: #66758C;
            --border: #DCE4EF;
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

        .layout {
            display: flex;
            min-height: 100vh;
        }

        .sidebar {
            width: 250px;
            flex-shrink: 0;
            padding: 25px 17px;
            color: white;
            background: linear-gradient(180deg, var(--navy), #0B2145);
            box-shadow: 4px 0 18px rgba(16, 43, 85, .08);
        }

        .logo {
            font-size: 21px;
            font-weight: 800;
            color: white;
            margin: 0 8px 35px;
            letter-spacing: -.5px;
        }

        .logo span {
            color: var(--orange);
        }

        .menu-label {
            color: var(--orange-light);
            font-size: 10px;
            font-weight: bold;
            letter-spacing: 1.2px;
            margin: 24px 10px 10px;
        }

        .menu {
            display: flex;
            flex-direction: column;
            gap: 5px;
        }

        .menu a {
            display: block;
            padding: 11px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: rgba(255, 255, 255, .82);
            transition: .2s;
        }

        .menu a:hover {
            background: rgba(255, 255, 255, .10);
            color: white;
            transform: translateX(2px);
        }

        .menu a.active {
            background: var(--orange);
            color: var(--navy);
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(245, 166, 35, .20);
        }

        .main {
            flex: 1;
            min-width: 0;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(16, 43, 85, .04);
        }

        .topbar > div:first-child strong {
            color: var(--navy);
        }

        .user-info {
            text-align: right;
            font-size: 13px;
        }

        .user-info strong {
            display: block;
            color: var(--navy);
        }

        .user-info span {
            display: block;
            color: var(--muted);
            font-size: 11px;
            margin-top: 4px;
        }

        .logout {
            background: #FFF4E5;
            color: #8A4B00;
            border: 1px solid #FFE0AE;
            border-radius: 7px;
            padding: 10px 13px;
            font-weight: bold;
            transition: .2s;
        }

        .logout:hover {
            background: var(--orange);
            color: var(--navy);
        }

        .content {
            padding: 30px;
            max-width: 1500px;
            margin: auto;
        }

        .breadcrumb {
            color: #8290A5;
            font-size: 12px;
            margin-bottom: 12px;
        }

        .heading {
            margin-bottom: 25px;
        }

        h1 {
            color: var(--navy);
            font-size: 27px;
            font-weight: 800;
            margin-bottom: 8px;
        }

        .subtitle {
            font-size: 13px;
            line-height: 1.6;
            color: var(--muted);
        }

        .hero {
            background: linear-gradient(
                115deg,
                var(--navy) 0%,
                var(--navy-light) 72%,
                #24549A 100%
            );
            color: white;
            border-radius: 15px;
            padding: 28px;
            margin-bottom: 28px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 20px;
            box-shadow: 0 10px 24px rgba(16, 43, 85, .15);
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            border: 30px solid rgba(245, 166, 35, .12);
            border-radius: 50%;
            right: 55px;
            top: -95px;
            pointer-events: none;
        }

        .hero h2 {
            font-size: 22px;
            margin-bottom: 12px;
            position: relative;
            z-index: 1;
        }

        .hero p {
            font-size: 13px;
            line-height: 1.8;
            max-width: 650px;
            color: rgba(255, 255, 255, .88);
            position: relative;
            z-index: 1;
        }

        .hero-icon {
            font-size: 54px;
            position: relative;
            z-index: 1;
        }

        .section-title {
            color: var(--navy);
            font-size: 19px;
            margin-bottom: 16px;
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(3, minmax(0, 1fr));
            gap: 16px;
        }

        .card {
            display: block;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(16, 43, 85, .04);
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }

        .card:hover {
            transform: translateY(-3px);
            border-color: var(--orange);
            box-shadow: 0 10px 24px rgba(16, 43, 85, .10);
        }

        .card-icon {
            width: 43px;
            height: 43px;
            border-radius: 10px;
            background: #FFF2D9;
            color: var(--navy);
            display: flex;
            justify-content: center;
            align-items: center;
            font-size: 22px;
            margin-bottom: 15px;
        }

        .card h3 {
            color: var(--navy);
            font-size: 15px;
            margin-bottom: 8px;
        }

        .card p {
            font-size: 12px;
            line-height: 1.6;
            color: var(--muted);
            min-height: 38px;
        }

        .card-link {
            display: inline-block;
            color: #A45F00;
            font-size: 12px;
            font-weight: bold;
            margin-top: 13px;
        }

        .card:hover .card-link {
            color: var(--navy);
        }

        .notice {
            margin-top: 24px;
            padding: 17px;
            border: 1px solid var(--border);
            border-left: 4px solid var(--orange);
            background: white;
            border-radius: 10px;
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        .notice strong {
            color: var(--navy);
        }

        footer {
            text-align: center;
            color: #8290A5;
            font-size: 11px;
            padding: 25px;
        }

        @media (max-width: 1050px) {
            .grid {
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .sidebar {
                width: 215px;
            }
        }

        @media (max-width: 650px) {
            .layout {
                display: block;
            }

            .sidebar {
                width: 100%;
                padding: 15px;
            }

            .logo {
                margin: 5px 8px 15px;
            }

            .menu {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
            }

            .menu-label {
                margin-top: 14px;
            }

            .topbar {
                padding: 15px;
            }

            .content {
                padding: 20px 15px;
            }

            .grid {
                grid-template-columns: 1fr;
            }

            .hero {
                padding: 22px;
            }

            .hero-icon {
                display: none;
            }

            h1 {
                font-size: 23px;
            }
        }
    </style>
</head>

<body>
<div class="layout">

    <aside class="sidebar">
        <div class="logo">🔧 <span>Bengkel</span>Servis</div>

        <div class="menu-label">MENU UTAMA</div>
        <nav class="menu">
            <a href="{{ url('/dashboard-super-admin') }}" class="active">📊 Dashboard</a>
            <a href="{{ url('/user') }}">👥 Pengguna</a>
            <a href="{{ url('/role') }}">🛡️ Role Pengguna</a>
            <a href="{{ url('/admincabang') }}">🧑‍💼 Admin Cabang</a>
        </nav>

        <div class="menu-label">OPERASIONAL</div>
        <nav class="menu">
            <a href="{{ url('/cabang') }}">🏢 Data Cabang</a>
            <a href="{{ url('/operasional') }}">📈 Operasional Cabang</a>
            <a href="{{ url('/pelanggan') }}">🙋 Data Pelanggan</a>
            <a href="{{ url('/kendaraan') }}">🚘 Data Kendaraan</a>
            <a href="{{ route('pengelolaan-booking.index') }}">📋 Booking Servis</a>
            <a href="{{ url('/layanan') }}">🔧 Layanan Servis</a>
            <a href="{{ url('/detailservis') }}">📝 Detail Servis</a>
            <a href="{{ url('/penugasan') }}">👨‍🔧 Penugasan Teknisi</a>
            <a href="{{ url('/teknisi') }}">🧰 Data Teknisi</a>
            <a href="{{ url('/montirlapangan') }}">🛠️ Montir Lapangan</a>
        </nav>

        <div class="menu-label">INVENTARIS & TRANSAKSI</div>
        <nav class="menu">
            <a href="{{ url('/kategori-sparepart') }}">🗂️ Kategori Spare Part</a>
            <a href="{{ url('/sparepart') }}">⚙️ Data Spare Part</a>
            <a href="{{ url('/admin-pesanan-sparepart') }}">🛒 Kelola Pesanan Spare Part</a>
            <a href="{{ url('/orderdetail') }}">📦 Detail Pesanan</a>
            <a href="{{ url('/pengiriman') }}">🚚 Pengiriman</a>
            <a href="{{ url('/ekspedisi') }}">🚛 Ekspedisi</a>
            <a href="{{ url('/promo') }}">🏷️ Promo</a>
            <a href="{{ url('/metodepembayaran') }}">💳 Metode Pembayaran</a>
            <a href="{{ url('/pembayaran') }}">💰 Data Pembayaran</a>
            <a href="{{ url('/rating') }}">⭐ Rating & Ulasan</a>
        </nav>
    </aside>

    <div class="main">
        <header class="topbar">
            <div>
                <strong>Panel Administrasi</strong>
            </div>

            <div style="display:flex;align-items:center;gap:16px">
                <div class="user-info">
                    <strong>{{ session('user_nama', 'Super Admin') }}</strong>
                    <span>Super Administrator</span>
                </div>

                <form action="{{ url('/logout') }}" method="POST"
                      onsubmit="return confirm('Yakin ingin keluar dari akun?')">
                    @csrf
                    <button type="submit" class="logout">Keluar ↗</button>
                </form>
            </div>
        </header>

        <main class="content">
            <div class="breadcrumb">Beranda / Dashboard Super Admin</div>

            <div class="heading">
                <h1>Dashboard Super Admin</h1>
                <p class="subtitle">
                    Kelola pengguna, cabang, layanan, dan seluruh operasional BengkelServis.
                </p>
            </div>

            <section class="hero">
                <div>
                    <h2>Selamat datang, {{ session('user_nama', 'Super Admin') }}! 👋</h2>
                    <p>
                        Ini adalah pusat kendali sistem BengkelServis.
                        Pilih menu di bawah untuk mengelola data dan memantau
                        kegiatan operasional bengkel.
                    </p>
                </div>
                <div class="hero-icon">🏢</div>
            </section>

            <h2 class="section-title">Manajemen Sistem</h2>

            <div class="grid">
                <a class="card" href="{{ url('/user') }}">
                    <div class="card-icon">👥</div>
                    <h3>Pengguna</h3>
                    <p>Kelola akun pengguna, data login, dan status akun.</p>
                    <span class="card-link">Kelola pengguna →</span>
                </a>

                <a class="card" href="{{ url('/admin-pesanan-sparepart') }}">
                    <div class="card-icon">📦</div>
                    <h3>Kelola Pesanan Spare Part</h3>
                    <p>Lihat pesanan pelanggan, periksa rincian barang, dan perbarui status pesanan.</p>
                    <span class="card-link">Kelola pesanan →</span>
                </a>

                <a class="card" href="{{ url('/role') }}">
                    <div class="card-icon">🛡️</div>
                    <h3>Role Pengguna</h3>
                    <p>Kelola pembagian peran dan hak akses sistem.</p>
                    <span class="card-link">Kelola role →</span>
                </a>

                <a class="card" href="{{ url('/cabang') }}">
                    <div class="card-icon">🏢</div>
                    <h3>Cabang Bengkel</h3>
                    <p>Kelola informasi cabang dan data operasionalnya.</p>
                    <span class="card-link">Kelola cabang →</span>
                </a>

                <a class="card" href="{{ url('/admincabang') }}">
                    <div class="card-icon">🧑‍💼</div>
                    <h3>Admin Cabang</h3>
                    <p>Atur akun dan penempatan administrator cabang.</p>
                    <span class="card-link">Kelola admin →</span>
                </a>

                <a class="card" href="{{ url('/layanan') }}">
                    <div class="card-icon">🔧</div>
                    <h3>Layanan Servis</h3>
                    <p>Kelola jenis layanan, harga, dan estimasi pengerjaan.</p>
                    <span class="card-link">Kelola layanan →</span>
                </a>

                <a class="card" href="{{ route('pengelolaan-booking.index') }}">
                    <div class="card-icon">📋</div>
                    <h3>Booking Servis</h3>
                    <p>Pantau data booking servis yang masuk ke sistem.</p>
                    <span class="card-link">Lihat booking →</span>
                </a>

                <a class="card" href="{{ url('/pelanggan') }}">
                    <div class="card-icon">🙋</div>
                    <h3>Data Pelanggan</h3>
                    <p>Lihat dan kelola data pelanggan bengkel.</p>
                    <span class="card-link">Kelola pelanggan →</span>
                </a>

                <a class="card" href="{{ url('/kendaraan') }}">
                    <div class="card-icon">🚘</div>
                    <h3>Data Kendaraan</h3>
                    <p>Kelola kendaraan yang terdaftar pada sistem.</p>
                    <span class="card-link">Kelola kendaraan →</span>
                </a>

                <a class="card" href="{{ url('/teknisi') }}">
                    <div class="card-icon">🧰</div>
                    <h3>Data Teknisi</h3>
                    <p>Kelola data teknisi yang bertugas di bengkel.</p>
                    <span class="card-link">Kelola teknisi →</span>
                </a>

                <a class="card" href="{{ url('/sparepart') }}">
                    <div class="card-icon">⚙️</div>
                    <h3>Spare Part</h3>
                    <p>Pantau persediaan dan informasi suku cadang.</p>
                    <span class="card-link">Kelola spare part →</span>
                </a>

                <a class="card" href="{{ url('/pembayaran') }}">
                    <div class="card-icon">💳</div>
                    <h3>Pembayaran</h3>
                    <p>Pantau transaksi dan status pembayaran pelanggan.</p>
                    <span class="card-link">Kelola pembayaran →</span>
                </a>

                <a class="card" href="{{ url('/rating') }}">
                    <div class="card-icon">⭐</div>
                    <h3>Rating & Ulasan</h3>
                    <p>Lihat penilaian pelanggan terhadap layanan bengkel.</p>
                    <span class="card-link">Lihat ulasan →</span>
                </a>
            </div>

            <div class="notice">
                <strong>💡 Informasi</strong><br>
                Menu yang tersedia mengikuti route dan hak akses yang telah
                dikonfigurasi dalam sistem. Pengelolaan booking dan pembayaran
                akan terintegrasi dengan alur servis pada tahap berikutnya.
            </div>
        </main>

        <footer>
            © {{ date('Y') }} BengkelServis — Panel Super Admin
        </footer>
    </div>
</div>
</body>
</html>
