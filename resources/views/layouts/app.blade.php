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
    <a href="{{ route('montir-lapangan.tugas.index') }}"
       class="nav-link {{ request()->is('montir-lapangan/tugas*') ? 'active' : '' }}"
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
    <a href="{{ route('booking-offline.index') }}"
       class="nav-link {{ request()->is('booking-offline*') ? 'active' : '' }}"
       data-tooltip="Booking Offline">
        <span class="nav-icon"><i class="fas fa-calendar-plus"></i></span>
        <span class="nav-text">Booking Offline</span>
    </a>
</div>

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

<div class="nav-item">
    <a href="{{ route('verifikasi-laporan.index') }}"
       class="nav-link {{ request()->is('verifikasi-laporan*') ? 'active' : '' }}"
       data-tooltip="Verifikasi Laporan">
        <span class="nav-icon"><i class="fas fa-clipboard-check"></i></span>
        <span class="nav-text">Verifikasi Laporan</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('admin.laporan-servis.index') }}"
       class="nav-link {{ request()->is('admin/laporan-servis*') ? 'active' : '' }}"
       data-tooltip="Laporan Servis">
        <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
        <span class="nav-text">Laporan Servis</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('komisi.index') }}"
       class="nav-link {{ request()->is('komisi') ? 'active' : '' }}"
       data-tooltip="Komisi Teknisi">
        <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
        <span class="nav-text">Komisi Teknisi</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('laporan.cabang') }}"
       class="nav-link {{ request()->is('laporan-cabang*') ? 'active' : '' }}"
       data-tooltip="Laporan Cabang">
        <span class="nav-icon"><i class="fas fa-chart-bar"></i></span>
        <span class="nav-text">Laporan Cabang</span>
    </a>
</div>

<div class="nav-item">
    <a href="{{ route('penghargaan.index') }}"
       class="nav-link {{ request()->is('penghargaan*') ? 'active' : '' }}"
       data-tooltip="Penghargaan Teknisi">
        <span class="nav-icon"><i class="fas fa-trophy"></i></span>
        <span class="nav-text">Penghargaan Teknisi</span>
    </a>
</div>

<div class="nav-section-title">Keuangan</div>

<div class="nav-item">
    <a href="{{ route('konfirmasi-pembayaran.index') }}"
       class="nav-link {{ request()->is('konfirmasi-pembayaran*') ? 'active' : '' }}"
       data-tooltip="Konfirmasi Pembayaran">
        <span class="nav-icon"><i class="fas fa-money-check-alt"></i></span>
        <span class="nav-text">Konfirmasi Pembayaran</span>
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

                <div class="nav-item">
                    <a href="{{ route('laporan-servis-teknisi.index') }}"
                       class="nav-link {{ request()->is('laporan-servis-teknisi*') ? 'active' : '' }}"
                       data-tooltip="Laporan Servis Saya">
                        <span class="nav-icon"><i class="fas fa-file-medical-alt"></i></span>
                        <span class="nav-text">Laporan Servis Saya</span>
                    </a>
                </div>


            {{-- [DISABLED - Sidebar role 9 Petugas Spare Part dihapus karena fitur penjualan sparepart online dihapus] --}}

            {{-- [DISABLED - Sidebar role 10 Petugas Pengiriman dihapus karena fitur penjualan sparepart online dihapus] --}}

            @elseif ((int) session('role_id') === 6)
                {{-- SIDEBAR KHUSUS PELANGGAN --}}
                <div class="nav-item">
                    <a href="/dashboard-pelanggan"
                       class="nav-link {{ request()->is('dashboard-pelanggan') ? 'active' : '' }}"
                       data-tooltip="Dashboard">
                        <span class="nav-icon"><i class="fas fa-home"></i></span>
                        <span class="nav-text">Dashboard</span>
                    </a>
                </div>

                <div class="nav-section-title">Servis Kendaraan</div>

                <div class="nav-item">
                    <a href="/booking-pelanggan"
                       class="nav-link {{ request()->is('booking-pelanggan*') ? 'active' : '' }}"
                       data-tooltip="Booking Servis">
                        <span class="nav-icon"><i class="fas fa-calendar-check"></i></span>
                        <span class="nav-text">Booking Servis</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="{{ route('laporan-servis-pelanggan.index') }}"
                       class="nav-link {{ request()->is('laporan-servis-pelanggan*') ? 'active' : '' }}"
                       data-tooltip="Laporan Servis">
                        <span class="nav-icon"><i class="fas fa-file-alt"></i></span>
                        <span class="nav-text">Laporan Servis</span>
                    </a>
                </div>

                <div class="nav-section-title">Kendaraan & Lainnya</div>

                <div class="nav-item">
                    <a href="/kendaraan-pelanggan"
                       class="nav-link {{ request()->is('kendaraan-pelanggan*') ? 'active' : '' }}"
                       data-tooltip="Kendaraan Saya">
                        <span class="nav-icon"><i class="fas fa-car"></i></span>
                        <span class="nav-text">Kendaraan Saya</span>
                    </a>
                </div>

                <div class="nav-item">
                    <a href="/rating-pelanggan"
                       class="nav-link {{ request()->is('rating-pelanggan*') ? 'active' : '' }}"
                       data-tooltip="Rating & Ulasan">
                        <span class="nav-icon"><i class="fas fa-star"></i></span>
                        <span class="nav-text">Rating & Ulasan</span>
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
            {{-- [DISABLED - Menu Inventaris Spare Part dihapus karena fitur penjualan sparepart online dihapus] --}}

            {{-- [DISABLED - Menu Pesanan Spare Part dihapus karena fitur penjualan sparepart online dihapus] --}}

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

            {{-- [DISABLED - Menu Pengiriman dihapus karena fitur penjualan sparepart online dihapus] --}}

            <div class="nav-section-title">Ulasan</div>
            <div class="nav-item">
                <a href="/rating"
                   class="nav-link {{ str_starts_with($pathAktif, 'rating') ? 'active' : '' }}"
                   data-tooltip="Ulasan">
                    <span class="nav-icon"><i class="fas fa-star"></i></span>
                    <span class="nav-text">Rating & Ulasan</span>
                </a>
            </div>

            <div class="nav-section-title">Komisi Teknisi</div>
            <div class="nav-item">
                <a href="{{ route('komisi.konfigurasi.index') }}"
                   class="nav-link {{ request()->is('komisi/konfigurasi*') ? 'active' : '' }}"
                   data-tooltip="Konfigurasi Komisi">
                    <span class="nav-icon"><i class="fas fa-cogs"></i></span>
                    <span class="nav-text">Konfigurasi Komisi</span>
                </a>
            </div>
            <div class="nav-item">
                <a href="{{ route('komisi.index') }}"
                   class="nav-link {{ request()->is('komisi') ? 'active' : '' }}"
                   data-tooltip="Data Komisi">
                    <span class="nav-icon"><i class="fas fa-money-bill-wave"></i></span>
                    <span class="nav-text">Data Komisi</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('laporan.superadmin') }}"
                   class="nav-link {{ request()->is('laporan-superadmin*') ? 'active' : '' }}"
                   data-tooltip="Laporan Eksekutif">
                    <span class="nav-icon"><i class="fas fa-chart-line"></i></span>
                    <span class="nav-text">Laporan Eksekutif</span>
                </a>
            </div>

            <div class="nav-item">
                <a href="{{ route('penghargaan.index') }}"
                   class="nav-link {{ request()->is('penghargaan*') ? 'active' : '' }}"
                   data-tooltip="Penghargaan Teknisi">
                    <span class="nav-icon"><i class="fas fa-trophy"></i></span>
                    <span class="nav-text">Penghargaan Teknisi</span>
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

