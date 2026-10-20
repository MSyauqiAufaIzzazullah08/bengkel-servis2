@extends('layouts.app')

@section('title', 'Dashboard Admin Cabang')

@section('breadcrumb')
    <span class="breadcrumb-current">Dashboard Admin Cabang</span>
@endsection

@section('content')
@php
    $nama = session('user_nama', 'Admin Cabang');
@endphp

<style>
    .admin-welcome {
        background: linear-gradient(135deg, #172554, #1e40af);
        color: white;
        border-radius: 16px;
        padding: 28px;
        margin-bottom: 24px;
    }

    .admin-welcome h2 {
        margin: 0 0 8px;
        font-size: 25px;
        font-weight: 800;
    }

    .admin-welcome p {
        margin: 0;
        opacity: .9;
    }

    .admin-menu-grid {
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(220px, 1fr));
        gap: 18px;
    }

    .admin-menu-card {
        display: block;
        padding: 22px;
        background: white;
        border: 1px solid #e5e7eb;
        border-radius: 14px;
        text-decoration: none;
        color: #1f2937;
        transition: .2s ease;
    }

    .admin-menu-card:hover {
        transform: translateY(-3px);
        border-color: #f97316;
        box-shadow: 0 8px 24px rgba(15, 23, 42, .08);
    }

    .admin-menu-icon {
        width: 48px;
        height: 48px;
        border-radius: 12px;
        display: flex;
        align-items: center;
        justify-content: center;
        background: #fff7ed;
        color: #ea580c;
        font-size: 21px;
        margin-bottom: 16px;
    }

    .admin-menu-card h3 {
        font-size: 16px;
        margin: 0 0 8px;
        font-weight: 700;
    }

    .admin-menu-card p {
        font-size: 13px;
        line-height: 1.6;
        color: #6b7280;
        margin: 0;
    }

    .admin-section-title {
        font-size: 19px;
        font-weight: 800;
        margin: 28px 0 16px;
        color: #172554;
    }
</style>

<div class="admin-welcome">
    <h2>Selamat Datang, {{ $nama }}!</h2>
    <p>Dashboard Admin Cabang — kelola aktivitas dan layanan cabang Anda.</p>
</div>

<h3 class="admin-section-title">Menu Pengelolaan Cabang</h3>

<div class="admin-menu-grid">

    <a href="/pengelolaan-booking" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-calendar-check"></i></div>
        <h3>Booking Servis</h3>
        <p>Lihat dan kelola booking servis pelanggan.</p>
    </a>

    <a href="/layanan" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-tools"></i></div>
        <h3>Layanan Servis</h3>
        <p>Lihat dan kelola daftar layanan servis.</p>
    </a>

    <a href="/detailservis" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-clipboard-list"></i></div>
        <h3>Detail Servis</h3>
        <p>Kelola detail pekerjaan servis kendaraan.</p>
    </a>

    <a href="/pelanggan" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-users"></i></div>
        <h3>Data Pelanggan</h3>
        <p>Lihat data pelanggan yang menggunakan layanan bengkel.</p>
    </a>

    <a href="/kendaraan" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-car"></i></div>
        <h3>Data Kendaraan</h3>
        <p>Lihat data kendaraan pelanggan.</p>
    </a>

    <a href="/penugasan" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-user-check"></i></div>
        <h3>Penugasan Teknisi</h3>
        <p>Kelola penugasan teknisi untuk pekerjaan servis.</p>
    </a>

    <a href="/teknisi" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-hard-hat"></i></div>
        <h3>Data Teknisi</h3>
        <p>Lihat dan kelola data teknisi.</p>
    </a>

    <a href="/sparepart" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-boxes"></i></div>
        <h3>Spare Part</h3>
        <p>Kelola data dan stok suku cadang.</p>
    </a>

    <a href="/operasional" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-chart-line"></i></div>
        <h3>Operasional Cabang</h3>
        <p>Informasi operasional dan laporan cabang.</p>
    </a>

    <a href="/admin-pesanan-sparepart" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-shopping-cart"></i></div>
        <h3>Pesanan Spare Part</h3>
        <p>Lihat dan proses pesanan suku cadang.</p>
    </a>

    <a href="/homeservice" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-motorcycle"></i></div>
        <h3>Home Service</h3>
        <p>Kelola layanan servis panggilan.</p>
    </a>

    {{-- MENU BARU: PEMBAYARAN HOME SERVICE --}}
    <a href="{{ route('pembayaran-home-service.index') }}"
       class="admin-menu-card">
        <div class="admin-menu-icon">
            <i class="fas fa-money-bill-wave"></i>
        </div>
        <h3>Pembayaran Home Service</h3>
        <p>
            Lihat dan kelola pembayaran layanan Home Service
            pelanggan setelah servis selesai.
        </p>
    </a>

    <a href="/pengiriman" class="admin-menu-card">
        <div class="admin-menu-icon"><i class="fas fa-truck"></i></div>
        <h3>Pengiriman</h3>
        <p>Pantau pengiriman pesanan suku cadang.</p>
    </a>

</div>
@endsection
