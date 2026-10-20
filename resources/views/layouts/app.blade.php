<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>@yield('title', 'BengkelServis') â€” BengkelServis</title>

    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/6.5.0/css/all.min.css">
    <link href="https://fonts.googleapis.com/css2?family=Inter:wght@300;400;500;600;700&family=Plus+Jakarta+Sans:wght@400;600;700;800&display=swap" rel="stylesheet">
    <link rel="stylesheet" href="{{ asset('assets/css/main.css') }}">
    <link rel="stylesheet" href="{{ asset('assets/css/components.css') }}">

    @yield('styles')
</head>
<body>

<div class="app-wrapper" id="app">

    {{-- ============================================================
         SIDEBAR
         ============================================================ --}}
    <aside class="sidebar" id="sidebar">

        <div class="sidebar-brand">
            <div class="sidebar-brand-icon">
                <i class="fas fa-wrench"></i>
            </div>
            <div class="sidebar-brand-text">
                <span class="sidebar-brand-title">BengkelServis</span>
                <span class="sidebar-brand-subtitle">Multi-Cabang</span>
            </div>
        </div>

        @php
            // pathAktif disediakan oleh View::composer di AppServiceProvider
            // Fallback jika diakses di luar konteks yang normal
            $pathAktif = $pathAktif ?? request()->path();
        @endphp

        <nav class="sidebar-nav" id="sidebarNav">
            
@if ((int) session('role_id') === 5)
    {{-- SIDEBAR KHUSUS MONTIR LAPANGAN --}}

    <div class="nav-section-title">Menu Utama</div>

    <div class="nav-item">
        <a href="/dashboard-montir-lapangan"
           class="nav-link {{ request()->is('dashboard-montir-lapangan') ? 'active' : '' }}"
           data-tooltip="Dashboard">
            <span class="nav-icon">
                <i class="fas fa-home"></i>
            </span>
            <span class="nav-text">Dashboard</span>
        </a>
    </div>

    
<div class="nav-item">
    <a href="{{ route('montir-lapangan.tugas.index') }}"#tugas-home-service"
       class="nav-link"
       data-tooltip="Tugas Home Service">
        <span class="nav-icon">
            <i class="fas fa-tools"></i>
        </span>
        <span class="nav-text">Tugas Home Service</span>
    </a>
</div>


@elseif ((int) session('role_id') === 3)



                {{-- SIDEBAR KHUSUS PETUGAS SERVICE --}}
                                {{-- DASHBOARD KHUSUS PETUGAS SERVICE --}}
                <div class="nav-item">
                    <a href="/dashboard-petugas-service"
                       class="nav-link {{ request()->is('dashboard-petugas-service') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>
<div class="nav-section-title">Menu Utama</div>

                <div class="nav-item">
                    <a href="/pengelolaan-booking"
                       class="nav-link {{ request()->is('pengelolaan-booking*') || request()->is('booking') ? 'active' : '' }}"
                       data-tooltip="Booking Servis">
                        <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="nav-text">Booking Servis</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/layanan"
                       class="nav-link {{ request()->is('layanan') || request()->is('layanan/*') ? 'active' : '' }}"
                       data-tooltip="Layanan Servis">
                        <span class="nav-icon"><i class="fas fa-tools"></i></span>
                        <span class="nav-text">Layanan Servis</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/detailservis"
                       class="nav-link {{ request()->is('detailservis*') ? 'active' : '' }}"
                       data-tooltip="Detail Servis">
                        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="nav-text">Detail Servis</span>
                    </a>
                </div>

                <div class="nav-section-title">Data Pelanggan &amp; Kendaraan</div>

                <div class="nav-item">
                    <a href="/pelanggan"
                       class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}"
                       data-tooltip="Data Pelanggan">
                        <span class="nav-icon"><i class="fas fa-users"></i></span>
                        <span class="nav-text">Data Pelanggan</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/kendaraan"
                       class="nav-link {{ request()->is('kendaraan*') ? 'active' : '' }}"
                       data-tooltip="Data Kendaraan">
                        <span class="nav-icon"><i class="fas fa-car"></i></span>
                        <span class="nav-text">Data Kendaraan</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/penugasan"
                       class="nav-link {{ request()->is('penugasan*') ? 'active' : '' }}"
                       data-tooltip="Penugasan Teknisi">
                        <span class="nav-icon"><i class="fas fa-user-check"></i></span>
                        <span class="nav-text">Penugasan Teknisi</span>
                    </a>
                </div>
            
                <div class="nav-item">
    
                <div class="nav-item">
                    <a href="/pembayaran"
                       class="nav-link {{ request()->is('pembayaran*') && !request()->is('pembayaran-home-service*') ? 'active' : '' }}"
                       data-tooltip="Pembayaran">
                        <span class="nav-icon"><i class="fas fa-credit-card"></i></span>
                        <span class="nav-text">Pembayaran</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ url('/pembayaran-home-service') }}"
                       class="nav-link {{ request()->is('pembayaran-home-service*') ? 'active' : '' }}"
                       data-tooltip="Pembayaran Home Service">
                        <span class="nav-icon"><i class="fas fa-house-user"></i></span>
                        <span class="nav-text">Pembayaran Home Service</span>
                    </a>
                </div>

                
<div class="nav-item">
    <a href="/laporan-pembayaran"
       class="nav-link {{ request()->is('laporan-pembayaran*') ? 'active' : '' }}"
       data-tooltip="Laporan Pembayaran">
        <span class="nav-icon">
            <i class="fas fa-file-pdf"></i>
        </span>
        <span class="nav-text">Laporan Pembayaran</span>
    </a>
</div>


            @elseif ((int) session('role_id') === 2)
                {{-- SIDEBAR KHUSUS ADMIN CABANG --}}
                <div class="nav-item">
                    <a href="/dashboard-admin-cabang"
                       class="nav-link {{ request()->is('dashboard-admin-cabang') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">Menu Utama</div>

<div class="nav-item">
    <a href="/pengelolaan-booking"
       class="nav-link {{ request()->is('pengelolaan-booking*') ? 'active' : '' }}"
       data-tooltip="Booking Servis">
        <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
        <span class="nav-text">Booking Servis</span>
    </a>
</div>

<div class="nav-item">
    <a href="/layanan"
       class="nav-link {{ request()->is('layanan*') ? 'active' : '' }}"
       data-tooltip="Layanan Servis">
        <span class="nav-icon"><i class="fas fa-tools"></i></span>
        <span class="nav-text">Layanan Servis</span>
    </a>
</div>

<div class="nav-item">
    <a href="/detailservis"
       class="nav-link {{ request()->is('detailservis*') ? 'active' : '' }}"
       data-tooltip="Detail Servis">
        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
        <span class="nav-text">Detail Servis</span>
    </a>
</div>

<div class="nav-item">
    <a href="/pelanggan"
       class="nav-link {{ request()->is('pelanggan*') ? 'active' : '' }}"
       data-tooltip="Data Pelanggan">
        <span class="nav-icon"><i class="fas fa-users"></i></span>
        <span class="nav-text">Data Pelanggan</span>
    </a>
</div>

<div class="nav-item">
    <a href="/kendaraan"
       class="nav-link {{ request()->is('kendaraan*') ? 'active' : '' }}"
       data-tooltip="Data Kendaraan">
        <span class="nav-icon"><i class="fas fa-car"></i></span>
        <span class="nav-text">Data Kendaraan</span>
    </a>
</div>

<div class="nav-item">
    <a href="/penugasan"
       class="nav-link {{ request()->is('penugasan*') ? 'active' : '' }}"
       data-tooltip="Penugasan Teknisi">
        <span class="nav-icon"><i class="fas fa-user-check"></i></span>
        <span class="nav-text">Penugasan Teknisi</span>
    </a>
</div>


            @elseif ((int) session('role_id') === 4)
                {{-- SIDEBAR KHUSUS TEKNISI --}}
                <div class="nav-item">
                    <a href="/dashboard-teknisi"
                       class="nav-link {{ request()->is('dashboard-teknisi') ? 'active' : '' }}"
                       data-tooltip="Dashboard Teknisi">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">Pekerjaan</div>

                <div class="nav-item">
                    <a href="/dashboard-teknisi"
                       class="nav-link {{ request()->is('dashboard-teknisi') ? 'active' : '' }}"
                       data-tooltip="Pekerjaan Saya">
                        <span class="nav-icon"><i class="fas fa-tools"></i></span>
                        <span class="nav-text">Pekerjaan Saya</span>
                    </a>
                </div>

                
                <div class="nav-item">
                    <a href="/teknisi/rating"
                       class="nav-link {{ request()->is('teknisi/rating') ? 'active' : '' }}"
                       data-tooltip="Rating & Ulasan">
                        <span class="nav-icon"><i class="fas fa-star"></i></span>
                        <span class="nav-text">Rating & Ulasan</span>
                    </a>
                </div>


            @elseif ((int) session('role_id') === 9)
                {{-- SIDEBAR KHUSUS PETUGAS SPARE PART --}}
                <div class="nav-section-title">Menu Utama</div>

                <div class="nav-item">
                    <a href="/dashboard-petugas-sparepart"
                       class="nav-link {{ request()->is('dashboard-petugas-sparepart') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">Inventaris</div>

                <div class="nav-item">
                    <a href="/sparepart"
                       class="nav-link {{ request()->is('sparepart*') ? 'active' : '' }}"
                       data-tooltip="Stok Spare Part">
                        <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                        <span class="nav-text">Stok Spare Part</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/kategori-sparepart"
                       class="nav-link {{ request()->is('kategori-sparepart*') ? 'active' : '' }}"
                       data-tooltip="Kategori Spare Part">
                        <span class="nav-icon"><i class="fas fa-tags"></i></span>
                        <span class="nav-text">Kategori Spare Part</span>
                    </a>
                </div>

                <div class="nav-section-title">Pesanan</div>

                <div class="nav-item">
                    <a href="/admin-pesanan-sparepart"
                       class="nav-link {{ request()->is('admin-pesanan-sparepart*') ? 'active' : '' }}"
                       data-tooltip="Kelola Pesanan">
                        <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                        <span class="nav-text">Kelola Pesanan</span>
                    </a>
                </div>

            @elseif ((int) session('role_id') === 10)
                {{-- SIDEBAR KHUSUS PETUGAS PENGIRIMAN --}}
                <div class="nav-section-title">Menu Utama</div>

                <div class="nav-item">
                    <a href="/dashboard-petugas-pengiriman"
                       class="nav-link {{ request()->is('dashboard-petugas-pengiriman') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">Logistik</div>

                <div class="nav-item">
                    <a href="/pengiriman"
                       class="nav-link {{ request()->is('pengiriman*') ? 'active' : '' }}"
                       data-tooltip="Pengiriman">
                        <span class="nav-icon"><i class="fas fa-truck"></i></span>
                        <span class="nav-text">Pengiriman</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/ekspedisi"
                       class="nav-link {{ request()->is('ekspedisi*') ? 'active' : '' }}"
                       data-tooltip="Ekspedisi">
                        <span class="nav-icon"><i class="fas fa-shipping-fast"></i></span>
                        <span class="nav-text">Ekspedisi</span>
                    </a>
                </div>


            @else



                        
<div class="nav-section-title">Menu Utama</div>
            <div class="nav-item">
                <a href="/dashboard"
                   class="nav-link {{ $pathAktif === 'dashboard' ? 'active' : '' }}"
                   data-tooltip="Dashboard">
                    <span class="nav-icon"><i class="fas fa-th-large"></i></span>
                    <span class="nav-text">Dashboard</span>
                </a>
            </div>
             <div class="nav-section-title">Manajemen Cabang</div>
            <div class="nav-item">
                <a href="/cabang"
                   class="nav-link {{ str_starts_with($pathAktif, 'cabang') ? 'active' : '' }}"
                   data-tooltip="Cabang">
                    <span class="nav-icon"><i class="fas fa-building"></i></span>
                    <span class="nav-text">Cabang</span>
                </a>
            </div>

            <div class="nav-section-title">Data Master</div>
            <div class="nav-item">
                <a href="/pelanggan"
                   class="nav-link {{ str_starts_with($pathAktif, 'pelanggan') ? 'active' : '' }}"
                   data-tooltip="Pelanggan">
                    <span class="nav-icon"><i class="fas fa-users"></i></span>
                    <span class="nav-text">Pelanggan</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="/kendaraan"
                   class="nav-link {{ str_starts_with($pathAktif, 'kendaraan') ? 'active' : '' }}"
                   data-tooltip="Kendaraan">
                    <span class="nav-icon"><i class="fas fa-car"></i></span>
                    <span class="nav-text">Kendaraan</span>
                </a>
            </div>

            <div class="nav-section-title">Manajemen Servis</div>
            <div class="nav-item">
                @php
                    $bookingMenuAktif = str_starts_with($pathAktif, 'booking')
                        || str_starts_with($pathAktif, 'pengelolaan-booking');
                @endphp
                <a href="#"
                   class="nav-link has-submenu {{ $bookingMenuAktif ? 'open' : '' }}"
                   data-tooltip="Booking">
                    <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                    <span class="nav-text">Booking Servis</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-submenu {{ $bookingMenuAktif ? 'open' : '' }}">
                    <div class="nav-item">
                        <a href="{{ route('pengelolaan-booking.index') }}"
                           class="nav-link {{ request()->is('pengelolaan-booking*') ? 'active' : '' }}">
                            <span class="nav-text">Daftar Booking</span>
                        </a>
                    </div>
                    {{--
                        Menu Booking Baru sengaja tidak ditampilkan dulu karena
                        route /booking/create belum terdaftar untuk Super Admin.
                        Tambahkan kembali setelah route dan controller admin tersedia.
                    --}}
                </div>
            </div>

            <div class="nav-item">
                <a href="#"
                   class="nav-link has-submenu {{ str_starts_with($pathAktif, 'layanan') || str_starts_with($pathAktif, 'detailservis') ? 'open' : '' }}"
                   data-tooltip="Layanan">
                    <span class="nav-icon"><i class="fas fa-tools"></i></span>
                    <span class="nav-text">Layanan Servis</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-submenu {{ str_starts_with($pathAktif, 'layanan') || str_starts_with($pathAktif, 'detailservis') ? 'open' : '' }}">
                    <div class="nav-item">
                        <a href="/layanan"
                           class="nav-link {{ str_starts_with($pathAktif, 'layanan') ? 'active' : '' }}">
                            <span class="nav-text">Daftar Layanan</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/detailservis"
                           class="nav-link {{ str_starts_with($pathAktif, 'detailservis') ? 'active' : '' }}">
                            <span class="nav-text">Detail Servis</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-item">
                <a href="/teknisi"
                   class="nav-link {{ str_starts_with($pathAktif, 'teknisi') ? 'active' : '' }}"
                   data-tooltip="Teknisi">
                    <span class="nav-icon"><i class="fas fa-hard-hat"></i></span>
                    <span class="nav-text">Teknisi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="/homeservice"
                   class="nav-link {{ str_starts_with($pathAktif, 'homeservice') ? 'active' : '' }}"
                   data-tooltip="Servis Panggilan">
                    <span class="nav-icon"><i class="fas fa-motorcycle"></i></span>
                    <span class="nav-text">Servis Panggilan</span>
                </a>
            </div>
             <div class="nav-section-title">Inventaris</div>
            <div class="nav-item">
                <a href="#"
                   class="nav-link has-submenu {{ str_starts_with($pathAktif, 'sparepart') || str_starts_with($pathAktif, 'kategori-sparepart') ? 'open' : '' }}"
                   data-tooltip="Spare Part">
                    <span class="nav-icon"><i class="fas fa-boxes"></i></span>
                    <span class="nav-text">Spare Part</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-submenu {{ str_starts_with($pathAktif, 'sparepart') || str_starts_with($pathAktif, 'kategori-sparepart') ? 'open' : '' }}">
                    <div class="nav-item">
                        <a href="/sparepart"
                           class="nav-link {{ str_starts_with($pathAktif, 'sparepart') ? 'active' : '' }}">
                            <span class="nav-text">Daftar Spare Part</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/kategori-sparepart"
                           class="nav-link {{ str_starts_with($pathAktif, 'kategori-sparepart') ? 'active' : '' }}">
                            <span class="nav-text">Kategori</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-item">
                <a href="#"
                   class="nav-link has-submenu {{ str_starts_with($pathAktif, 'order') ? 'open' : '' }}"
                   data-tooltip="Pesanan">
                    <span class="nav-icon"><i class="fas fa-clipboard-list"></i></span>
                    <span class="nav-text">Pesanan Spare Part</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-submenu {{ str_starts_with($pathAktif, 'order') ? 'open' : '' }}">
                    <div class="nav-item">
                        <a href="/order"
                           class="nav-link {{ $pathAktif === 'order' ? 'active' : '' }}">
                            <span class="nav-text">Daftar Pesanan</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/orderdetail"
                           class="nav-link {{ str_starts_with($pathAktif, 'orderdetail') ? 'active' : '' }}">
                            <span class="nav-text">Detail Pesanan</span>
                        </a>
                    </div>
                </div>
            </div>

            <div class="nav-section-title">Keuangan</div>
            <div class="nav-item">
                <a href="#"
                   class="nav-link has-submenu {{ str_starts_with($pathAktif, 'pembayaran') || str_starts_with($pathAktif, 'metodepembayaran') ? 'open' : '' }}"
                   data-tooltip="Pembayaran">
                    <span class="nav-icon"><i class="fas fa-credit-card"></i></span>
                    <span class="nav-text">Pembayaran</span>
                    <i class="fas fa-chevron-right nav-arrow"></i>
                </a>
                <div class="nav-submenu {{ str_starts_with($pathAktif, 'pembayaran') || str_starts_with($pathAktif, 'metodepembayaran') ? 'open' : '' }}">
                    <div class="nav-item">
                        <a href="/pembayaran"
                           class="nav-link {{ str_starts_with($pathAktif, 'pembayaran') ? 'active' : '' }}">
                            <span class="nav-text">Daftar Pembayaran</span>
                        </a>
                    </div>
                    <div class="nav-item">
                        <a href="/metodepembayaran"
                           class="nav-link {{ str_starts_with($pathAktif, 'metodepembayaran') ? 'active' : '' }}">
                            <span class="nav-text">Metode Pembayaran</span>
                        </a>
                    </div>
                </div>
            </div>

            
<div class="nav-item">
    <a href="/laporan-pembayaran"
       class="nav-link {{ request()->is('laporan-pembayaran*') ? 'active' : '' }}"
       data-tooltip="Laporan Pembayaran">
        <span class="nav-icon">
            <i class="fas fa-file-pdf"></i>
        </span>
        <span class="nav-text">Laporan Pembayaran</span>
    </a>
</div>

            <div class="nav-item">
                <a href="/promo"
                   class="nav-link {{ str_starts_with($pathAktif, 'promo') ? 'active' : '' }}"
                   data-tooltip="Promo">
                    <span class="nav-icon"><i class="fas fa-tags"></i></span>
                    <span class="nav-text">Promo</span>
                </a>
            </div>

            <div class="nav-section-title">Pengiriman</div>
            <div class="nav-item">
                <a href="/pengiriman"
                   class="nav-link {{ str_starts_with($pathAktif, 'pengiriman') ? 'active' : '' }}"
                   data-tooltip="Pengiriman">
                    <span class="nav-icon"><i class="fas fa-truck"></i></span>
                    <span class="nav-text">Pengiriman</span>
                </a>
            </div>

            <div class="nav-section-title">Ulasan</div>
            <div class="nav-item">
                <a href="/rating"
                   class="nav-link {{ str_starts_with($pathAktif, 'rating') ? 'active' : '' }}"
                   data-tooltip="Ulasan">
                    <span class="nav-icon"><i class="fas fa-star"></i></span>
                    <span class="nav-text">Rating & Ulasan</span>
                </a>
            </div>
             <div class="nav-section-title">Administrasi</div>
            <div class="nav-item">
                <a href="/user"
                   class="nav-link {{ str_starts_with($pathAktif, 'user') ? 'active' : '' }}"
                   data-tooltip="Pengguna">
                    <span class="nav-icon"><i class="fas fa-user-circle"></i></span>
                    <span class="nav-text">Pengguna</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="/role"
                   class="nav-link {{ str_starts_with($pathAktif, 'role') ? 'active' : '' }}"
                   data-tooltip="Role">
                    <span class="nav-icon"><i class="fas fa-shield-alt"></i></span>
                    <span class="nav-text">Hak Akses (Role)</span>
                </a>
            </div>

                    @endif
</nav>

        {{-- Logout --}}
        <div class="sidebar-footer">
            <form action="/logout" method="POST" style="margin:0">
                @csrf
                <button type="submit"
                        class="nav-link"
                        style="background:none;border:none;cursor:pointer;width:100%;text-align:left"
                        data-tooltip="Keluar">
                    <span class="nav-icon"><i class="fas fa-sign-out-alt"></i></span>
                    <span class="nav-text">Keluar</span>
                </button>
            </form>
        </div>

    </aside>

    <div class="mobile-overlay" id="mobileOverlay"></div>

    {{-- ============================================================
         KONTEN UTAMA
         ============================================================ --}}
    <div class="main-content" id="mainContent">

        {{-- Header --}}
        <header class="app-header">
            <div class="header-left">
                <button class="btn-toggle-sidebar" id="btnToggleSidebar" title="Toggle Sidebar">
                    <i class="fas fa-bars"></i>
                    </button>
                <nav class="page-breadcrumb">
                    <span>BengkelServis</span>
                    <i class="fas fa-chevron-right" style="font-size:0.65rem"></i>
                    @yield('breadcrumb', '<span class="breadcrumb-current">Dashboard</span>')
                </nav>
            </div>

            <div class="header-right">
                <button class="header-action-btn" title="Notifikasi" id="btnNotif">
                    <i class="fas fa-bell"></i>
                </button>

                <div class="header-divider"></div>

                <div class="dropdown">
                    <div class="user-menu" data-dropdown="userDropdown">
                        <div class="user-avatar">{{ $inisial ?? '?' }}</div>
                        <div class="user-info">
                            <span class="user-name">{{ $namaUser ?? 'Pengguna' }}</span>
                            <span class="user-role">{{ $namaRole ?? '' }}</span>
                        </div>
                        <i class="fas fa-chevron-down"
                           style="font-size:0.7rem;color:var(--color-text-muted);margin-left:4px"></i>
                    </div>
                    <div class="dropdown-menu" id="userDropdown">
                        <a class="dropdown-item" href="#">
                            <i class="fas fa-user-circle"></i> Profil Saya
                        </a>
                        <div class="dropdown-divider"></div>
                        <div class="dropdown-item danger" style="cursor:pointer"
                             onclick="document.getElementById('formLogoutHeader').submit()">
                            <i class="fas fa-sign-out-alt"></i> Keluar
                        </div>
                    </div>
                </div>

                <form id="formLogoutHeader" action="/logout" method="POST" style="display:none">
                    @csrf
                </form>
            </div>
        </header>

        {{-- Konten Halaman --}}
        <main class="page-content">
            @yield('content')
        </main>

    </div>
    {{-- /main-content --}}

    </div>
{{-- /app-wrapper --}}

<div class="toast-container" id="toastContainer"></div>

<script src="{{ asset('assets/js/data.js') }}"></script>
<script src="{{ asset('assets/js/app.js') }}"></script>
<script>
    var btnNotif = document.getElementById('btnNotif');
    if (btnNotif) {
        btnNotif.addEventListener('click', function() {
            if (typeof showToast === 'function') {
                showToast('Belum ada notifikasi baru.', 'info');
            }
        });
    }
</script>

@yield('scripts')

</body>
</html>

