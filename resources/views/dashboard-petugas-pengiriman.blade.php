
@extends('layouts.app')

@section('title', 'Dashboard Petugas Pengiriman')
@section('breadcrumb', 'Dashboard Petugas Pengiriman')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2>Dashboard Petugas Pengiriman</h2>
        <p>Selamat datang! Kelola pengiriman pesanan spare part di sini.</p>
    </div>

    <div class="row g-3">
        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-truck fa-2x mb-3"></i>
                    <h5>Data Pengiriman</h5>
                    <p>Kelola data pengiriman, nomor resi, kurir, dan status paket.</p>
                    <a href="/pengiriman" class="btn btn-primary">Kelola Pengiriman</a>
                </div>
            </div>
        </div>

        <div class="col-md-6 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-shipping-fast fa-2x mb-3"></i>
                    <h5>Data Ekspedisi</h5>
                    <p>Kelola data ekspedisi yang digunakan untuk pengiriman.</p>
                    <a href="/ekspedisi" class="btn btn-primary">Kelola Ekspedisi</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
