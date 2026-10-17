@extends('layouts.app')

@section('content')
<div style="padding: 24px;">
    <div style="margin-bottom: 28px;">
        <h2 style="font-weight: 700; margin-bottom: 8px;">
            Dashboard Petugas Service
        </h2>
        <p style="color: #64748b; margin: 0;">
            Selamat datang, {{ session('user_nama', 'Petugas Service') }}!
            Silakan pilih menu untuk mulai bekerja.
        </p>
    </div>

    <div style="
        display: grid;
        grid-template-columns: repeat(auto-fit, minmax(230px, 1fr));
        gap: 20px;
    ">
        <a href="/pengelolaan-booking" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #f59e0b;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#f59e0b;margin-bottom:14px;">
                    <i class="fas fa-calendar-check"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Booking Servis</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Lihat dan kelola booking servis pelanggan.</p>
            </div>
        </a>

        <a href="/layanan" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #2563eb;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#2563eb;margin-bottom:14px;">
                    <i class="fas fa-tools"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Layanan Servis</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Lihat dan kelola daftar layanan bengkel.</p>
            </div>
        </a>

        <a href="/detailservis" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #7c3aed;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#7c3aed;margin-bottom:14px;">
                    <i class="fas fa-clipboard-list"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Detail Servis</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Buka data detail pekerjaan servis.</p>
            </div>
        </a>

        <a href="/pelanggan" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #0891b2;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#0891b2;margin-bottom:14px;">
                    <i class="fas fa-users"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Data Pelanggan</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Lihat dan kelola data pelanggan.</p>
            </div>
        </a>

        <a href="/kendaraan" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #16a34a;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#16a34a;margin-bottom:14px;">
                    <i class="fas fa-car"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Data Kendaraan</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Lihat dan kelola data kendaraan pelanggan.</p>
            </div>
        </a>

        <a href="/penugasan" style="text-decoration:none;color:inherit;">
            <div style="background:white;padding:24px;border-radius:14px;border:1px solid #e2e8f0;border-left:5px solid #ea580c;box-shadow:0 4px 12px #0f172a0a;height:100%;box-sizing:border-box;">
                <div style="font-size:28px;color:#ea580c;margin-bottom:14px;">
                    <i class="fas fa-user-check"></i>
                </div>
                <h3 style="font-size:18px;font-weight:700;margin:0 0 8px;">Penugasan Teknisi</h3>
                <p style="color:#64748b;font-size:14px;margin:0;">Buka menu penugasan teknisi servis.</p>
            </div>
        </a>
    </div>
</div>
@endsection
