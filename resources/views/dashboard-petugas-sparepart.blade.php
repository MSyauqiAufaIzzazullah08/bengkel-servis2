
@extends('layouts.app')

@section('title', 'Dashboard Petugas Spare Part')
@section('breadcrumb', 'Dashboard Petugas Spare Part')

@section('content')
<div class="container-fluid">
    <div class="mb-4">
        <h2>Dashboard Petugas Spare Part</h2>
        <p>Selamat datang! Kelola stok dan pesanan spare part di sini.</p>
    </div>

    <div class="row g-3">
        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-boxes fa-2x mb-3"></i>
                    <h5>Stok Spare Part</h5>
                    <p>Tambah, ubah, hapus, dan periksa stok spare part.</p>
                    <a href="/sparepart" class="btn btn-primary">Kelola Stok</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-tags fa-2x mb-3"></i>
                    <h5>Kategori Spare Part</h5>
                    <p>Kelola kategori barang spare part.</p>
                    <a href="/kategori-sparepart" class="btn btn-primary">Kelola Kategori</a>
                </div>
            </div>
        </div>

        <div class="col-md-4 mb-3">
            <div class="card shadow-sm h-100">
                <div class="card-body">
                    <i class="fas fa-clipboard-list fa-2x mb-3"></i>
                    <h5>Pesanan Spare Part</h5>
                    <p>Periksa pesanan pelanggan dan proses status pesanan.</p>
                    <a href="/admin-pesanan-sparepart" class="btn btn-primary">Kelola Pesanan</a>
                </div>
            </div>
        </div>
    </div>
</div>
@endsection
