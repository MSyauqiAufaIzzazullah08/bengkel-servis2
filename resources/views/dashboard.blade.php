<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Dashboard | BengkelServis</title>

    <link rel="stylesheet"
          href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap"
          rel="stylesheet">

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

        /* SIDEBAR - MENGIKUTI DASHBOARD AWAL */
        .sidebar {
            position: fixed;
            inset: 0 auto 0 0;
            width: 250px;
            height: 100vh;
            overflow-y: auto;
            flex-shrink: 0;
            padding: 25px 17px;
            color: white;
            background: linear-gradient(
                180deg,
                var(--navy) 0%,
                #0B2145 100%
            );
            box-shadow: 4px 0 18px rgba(16,43,85,.08);
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
            display: flex;
            align-items: center;
            gap: 9px;
            padding: 11px 12px;
            border-radius: 8px;
            font-size: 13px;
            color: rgba(255,255,255,.82);
            transition: .2s;
        }

        .menu a:hover {
            background: rgba(255,255,255,.10);
            color: white;
            transform: translateX(2px);
        }

        .menu a.active {
            background: var(--orange);
            color: var(--navy);
            font-weight: bold;
            box-shadow: 0 4px 12px rgba(245,166,35,.20);
        }

        .menu-icon {
            width: 16px;
            flex-shrink: 0;
            text-align: center;
        }

        .logout-sidebar {
            width: 100%;
            margin-top: 25px;
            padding: 10px 12px;
            border: 1px solid rgba(255,255,255,.15);
            border-radius: 7px;
            background: rgba(255,255,255,.05);
            color: white;
            text-align: left;
            font-size: 12px;
        }

        .logout-sidebar:hover {
            background: var(--orange);
            color: var(--navy);
        }

        /* KONTEN UTAMA */
        .main {
            flex: 1;
            min-width: 0;
            margin-left: 250px;
        }

        .topbar {
            background: white;
            border-bottom: 1px solid var(--border);
            padding: 18px 30px;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
            box-shadow: 0 2px 10px rgba(16,43,85,.04);
        }

        .topbar strong {
            color: var(--navy);
            font-size: 13px;
        }

        .user-area {
            display: flex;
            align-items: center;
            gap: 16px;
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
            font-size: 12px;
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

        /* BANNER */
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
            box-shadow: 0 10px 24px rgba(16,43,85,.15);
            position: relative;
            overflow: hidden;
        }

        .hero::after {
            content: "";
            position: absolute;
            width: 190px;
            height: 190px;
            border: 30px solid rgba(245,166,35,.12);
            border-radius: 50%;
            right: 55px;
            top: -95px;
            pointer-events: none;
        }

        .hero h2 {
            font-size: 22px;
            margin-bottom: 12px;
            color: white;
            position: relative;
            z-index: 1;
        }

        .hero p {
            font-size: 13px;
            line-height: 1.8;
            max-width: 650px;
            color: rgba(255,255,255,.88);
            position: relative;
            z-index: 1;
        }

        .hero-icon {
            font-size: 54px;
            position: relative;
            z-index: 1;
        }

        /* KARTU MENU */
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
            min-width: 0;
            background: white;
            border: 1px solid var(--border);
            border-radius: 12px;
            padding: 20px;
            box-shadow: 0 2px 8px rgba(16,43,85,.04);
            transition: transform .2s, box-shadow .2s, border-color .2s;
        }

        .card:hover {
            transform: translateY(-3px);
            border-color: var(--orange);
            box-shadow: 0 10px 24px rgba(16,43,85,.10);
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
                width: 220px;
            }

            .main {
                margin-left: 220px;
            }
        }

        @media (max-width: 650px) {
            .layout {
                display: block;
            }

            .sidebar {
                position: relative;
                width: 100%;
                height: auto;
                max-height: none;
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

            .main {
                margin-left: 0;
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
@php
    $namaUser = session('user_nama', 'Pengguna');
    $roleId = (int) session('role_id', 0);

    $namaRole = match ($roleId) {
        1 => 'Super Admin',
        2 => 'Admin Cabang',
        3 => 'Petugas Servis',
        4 => 'Teknisi',
        5 => 'Montir Lapangan',
        6 => 'Pelanggan',
        default => 'Pengguna',
    };

    /*
     * Struktur menu dipisahkan berdasarkan kategori.
     * URL mengikuti route yang telah digunakan di proyek.
     */
    $kategoriMenu = [];

    if ($roleId === 1) {
        $kategoriMenu = [
            'MENU UTAMA' => [
                ['Pengguna', '/user', 'fa-users'],
                ['Role Pengguna', '/role', 'fa-shield-alt'],
                ['Admin Cabang', '/admincabang', 'fa-user-tie'],
            ],
            'OPERASIONAL' => [
                ['Data Cabang', '/cabang', 'fa-building'],
                ['Operasional Cabang', '/operasional', 'fa-chart-line'],
                ['Data Pelanggan', '/pelanggan', 'fa-users'],
                ['Data Kendaraan', '/kendaraan', 'fa-car'],
                ['Booking Servis', '/booking', 'fa-calendar-check'],
                ['Layanan Servis', '/layanan', 'fa-tools'],
                ['Detail Servis', '/detailservis', 'fa-clipboard-list'],
                ['Penugasan Teknisi', '/penugasan', 'fa-user-gear'],
                ['Data Teknisi', '/teknisi', 'fa-hard-hat'],
                ['Montir Lapangan', '/montirlapangan', 'fa-screwdriver-wrench'],
            ],
            'INVENTARIS & TRANSAKSI' => [
                ['Kategori Spare Part', '/kategori-sparepart', 'fa-folder'],
                ['Data Spare Part', '/sparepart', 'fa-gears'],
                ['Kelola Pesanan Spare Part', '/admin-pesanan-sparepart', 'fa-box-open'],
                ['Detail Pesanan', '/orderdetail', 'fa-boxes-stacked'],
                ['Pengiriman', '/pengiriman', 'fa-truck'],
                ['Ekspedisi', '/ekspedisi', 'fa-truck-fast'],
                ['Promo', '/promo', 'fa-tags'],
                ['Metode Pembayaran', '/metodepembayaran', 'fa-credit-card'],
                ['Data Pembayaran', '/pembayaran', 'fa-money-bill-wave'],
                ['Rating & Ulasan', '/rating', 'fa-star'],
            ],
        ];
    } elseif ($roleId === 2) {
        $kategoriMenu = [
            'MENU UTAMA' => [
                ['Dashboard Cabang', '/dashboard', 'fa-house'],
                ['Data Cabang', '/cabang', 'fa-building'],
                ['Operasional Cabang', '/operasional', 'fa-chart-line'],
            ],
            'OPERASIONAL SERVIS' => [
                ['Booking Servis', '/booking', 'fa-calendar-check'],
                ['Layanan Servis', '/layanan', 'fa-tools'],
                ['Detail Servis', '/detailservis', 'fa-clipboard-list'],
                ['Data Pelanggan', '/pelanggan', 'fa-users'],
                ['Data Kendaraan', '/kendaraan', 'fa-car'],
                ['Data Teknisi', '/teknisi', 'fa-hard-hat'],
            ],
            'INVENTARIS & TRANSAKSI' => [
                ['Spare Part', '/sparepart', 'fa-gears'],
                ['Pesanan Spare Part', '/order', 'fa-box-open'],
                ['Pembayaran', '/pembayaran', 'fa-credit-card'],
                ['Rating & Ulasan', '/rating', 'fa-star'],
            ],
        ];
    } elseif ($roleId === 3) {
        $kategoriMenu = [
            'MENU UTAMA' => [
                ['Booking Servis', '/booking', 'fa-calendar-check'],
                ['Layanan Servis', '/layanan', 'fa-tools'],
                ['Detail Servis', '/detailservis', 'fa-clipboard-list'],
            ],
            'DATA PELANGGAN & KENDARAAN' => [
                ['Data Pelanggan', '/pelanggan', 'fa-users'],
                ['Data Kendaraan', '/kendaraan', 'fa-car'],
                ['Penugasan Teknisi', '/penugasan', 'fa-user-gear'],
            ],
        ];
    } elseif ($roleId === 4) {
        $kategoriMenu = [
            'MENU UTAMA' => [
                ['Penugasan Teknisi', '/penugasan', 'fa-user-gear'],
                ['Booking Servis', '/booking', 'fa-calendar-check'],
                ['Detail Servis', '/detailservis', 'fa-clipboard-list'],
                ['Layanan Servis', '/layanan', 'fa-tools'],
            ],
        ];
    } elseif ($roleId === 5) {
        $kategoriMenu = [
            'MENU UTAMA' => [
                ['Servis Panggilan', '/homeservice', 'fa-motorcycle'],
                ['Booking Servis', '/booking', 'fa-calendar-check'],
            ],
        ];
    }
    
    $kartuMenu = [];
    foreach ($kategoriMenu as $namaKategori => $daftarMenu) {
        foreach ($daftarMenu as $menu) {
            $kartuMenu[] = [
                'kategori' => $namaKategori,
                'nama' => $menu[0],
                'url' => $menu[1],
                'ikon' => $menu[2],
            ];
        }
    }
@endphp

<div class="layout">
    <aside class="sidebar">
        <div class="logo">🔧 <span>Bengkel</span>Servis</div>

        @foreach ($kategoriMenu as $namaKategori => $daftarMenu)
            <div class="menu-label">{{ $namaKategori }}</div>
            <nav class="menu">
                @foreach ($daftarMenu as $menu)
                    @if ($menu[1] === '/dashboard')
                        <a href="{{ $menu[1] }}" class="active">
                            <span class="menu-icon"><i class="fas {{ $menu[2] }}"></i></span>
                            {{ $menu[0] }}
                        </a>
                    @else
                        <a href="{{ $menu[1] }}">
                            <span class="menu-icon"><i class="fas {{ $menu[2] }}"></i></span>
                            {{ $menu[0] }}
                        </a>
                    @endif
                @endforeach
            </nav>
        @endforeach

        @if ($roleId === 6)
            <div class="menu-label">MENU PELANGGAN</div>
            <nav class="menu">
                <a href="/dashboard-pelanggan">
                    <span class="menu-icon"><i class="fas fa-house"></i></span>
                    Dashboard Pelanggan
                </a>
                <a href="/booking-pelanggan">
                    <span class="menu-icon"><i class="fas fa-calendar-check"></i></span>
                    Booking Saya
                </a>
                <a href="/kendaraan-pelanggan">
                    <span class="menu-icon"><i class="fas fa-car"></i></span>
                    Kendaraan Saya
                </a>
                <a href="/order-pelanggan">
                    <span class="menu-icon"><i class="fas fa-box"></i></span>
                    Pesanan Saya
                </a>
            </nav>
        @endif

        <form action="/logout" method="POST"
              onsubmit="return confirm('Yakin ingin keluar dari akun?')">
            @csrf
            <button type="submit" class="logout-sidebar">
                <i class="fas fa-sign-out-alt"></i> Keluar
            </button>
        </form>
    </aside>

    <div class="main">
        <header class="topbar">
            <div><strong>Panel Administrasi</strong></div>

            <div class="user-area">
                <div class="user-info">
                    <strong>{{ $namaUser }}</strong>
                    <span>{{ $namaRole }}</span>
                </div>
                <form action="/logout" method="POST"
                      onsubmit="return confirm('Yakin ingin keluar dari akun?')">
                    @csrf
                    <button type="submit" class="logout">Keluar ↗</button>
                </form>
            </div>
        </header>

        <main class="content">
            <div class="breadcrumb">Beranda / Dashboard {{ $namaRole }}</div>

            <div class="heading">
                <h1>Dashboard {{ $namaRole }}</h1>
                <p class="subtitle">
                    Kelola informasi dan operasional BengkelServis sesuai peran akun kamu.
                </p>
            </div>

            <section class="hero">
                <div>
                    <h2>Selamat datang, {{ $namaUser }}! 👋</h2>
                    <p>
                        Ini adalah pusat pengelolaan sistem BengkelServis.
                        Pilih menu di bawah untuk mengakses informasi dan
                        pekerjaan sesuai peran akun kamu.
                    </p>
                </div>
                <div class="hero-icon">🏢</div>
            </section>

            <h2 class="section-title">Manajemen Sistem</h2>

            @if ($roleId === 6)
                <div class="grid">
                    <a class="card" href="/dashboard-pelanggan">
                        <div class="card-icon"><i class="fas fa-house"></i></div>
                        <h3>Dashboard Pelanggan</h3>
                        <p>Buka dashboard pelanggan untuk mengelola layanan kamu.</p>
                        <span class="card-link">Buka dashboard →</span>
                    </a>
                </div>
            @else
                <div class="grid">
                    @forelse ($kartuMenu as $menu)
                        <a class="card" href="{{ $menu['url'] }}">
                            <div class="card-icon">
                                <i class="fas {{ $menu['ikon'] }}"></i>
                            </div>
                            <h3>{{ $menu['nama'] }}</h3>
                            <p>
                                @switch($menu['nama'])
                                    @case('Pengguna')
                                        Kelola akun pengguna, data login, dan status akun.
                                        @break
                                    @case('Role Pengguna')
                                        Kelola pembagian peran dan hak akses sistem.
                                        @break
                                    @case('Admin Cabang')
                                        Kelola akun administrator cabang.
                                        @break
                                    @case('Data Cabang')
                                    @case('Cabang Bengkel')
                                        Kelola informasi cabang bengkel.
                                        @break
                                    @case('Operasional Cabang')
                                        Kelola data operasional cabang.
                                        @break
                                    @case('Data Pelanggan')
                                        Lihat dan kelola data pelanggan bengkel.
                                        @break
                                    @case('Data Kendaraan')
                                        Kelola kendaraan yang terdaftar pada sistem.
                                        @break
                                    @case('Booking Servis')
                                        Pantau booking servis yang masuk.
                                        @break
                                    @case('Layanan Servis')
                                        Kelola jenis layanan dan harga servis.
                                        @break
                                    @case('Detail Servis')
                                        Kelola rincian pengerjaan servis.
                                        @break
                                    @case('Penugasan Teknisi')
                                        Kelola penugasan teknisi.
                                        @break
                                    @case('Data Teknisi')
                                        Kelola data teknisi bengkel.
                                        @break
                                    @case('Montir Lapangan')
                                        Kelola data montir lapangan.
                                        @break
                                    @case('Spare Part')
                                    @case('Data Spare Part')
                                        Pantau stok dan informasi suku cadang.
                                        @break
                                    @case('Kategori Spare Part')
                                        Kelola kategori suku cadang.
                                        @break
                                    @case('Kelola Pesanan Spare Part')
                                    @case('Pesanan Spare Part')
                                        Lihat pesanan pelanggan dan status pesanan.
                                        @break
                                    @case('Detail Pesanan')
                                        Lihat rincian pesanan suku cadang.
                                        @break
                                    @case('Pengiriman')
                                        Kelola informasi pengiriman barang.
                                        @break
                                    @case('Ekspedisi')
                                        Kelola data jasa ekspedisi.
                                        @break
                                    @case('Promo')
                                        Kelola promo dan periode berlakunya.
                                        @break
                                    @case('Metode Pembayaran')
                                        Kelola metode pembayaran.
                                        @break
                                    @case('Data Pembayaran')
                                    @case('Pembayaran')
                                        Pantau transaksi pembayaran pelanggan.
                                        @break
                                    @case('Rating & Ulasan')
                                        Lihat penilaian pelanggan terhadap layanan.
                                        @break
                                    @default
                                        Akses informasi sesuai menu yang tersedia.
                                @endswitch
                            </p>
                            <span class="card-link">
                                Buka menu <i class="fas fa-arrow-right"></i>
                            </span>
                        </a>
                    @empty
                        <div class="card">
                            <div class="card-icon"><i class="fas fa-circle-info"></i></div>
                            <h3>Menu Belum Tersedia</h3>
                            <p>Belum ada menu yang dikonfigurasi untuk role ini.</p>
                        </div>
                    @endforelse
                </div>
            @endif

            <div class="notice">
                <strong>💡 Informasi</strong><br>
                Tampilan dashboard menggunakan desain yang seragam.
                Menu yang ditampilkan mengikuti konfigurasi role, sedangkan
                hak akses setiap halaman tetap ditentukan oleh route dan middleware.
            </div>
        </main>

        <footer>
            © {{ date('Y') }} BengkelServis — Panel {{ $namaRole }}
        </footer>
    </div>
</div>
</body>
</html>
