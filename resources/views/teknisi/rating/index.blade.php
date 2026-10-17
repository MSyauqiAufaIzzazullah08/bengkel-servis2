
@extends('layouts.app')

@section('title', 'Rating & Ulasan Teknisi')

@section('content')
<div class="page-header">
    <div>
        <h1>Rating & Ulasan</h1>
        <p>Lihat penilaian pelanggan untuk servis yang kamu kerjakan.</p>
    </div>
</div>

<div class="stats-grid" style="margin-bottom: 24px;">
    <div class="stat-card">
        <div class="stat-card-label">Total Ulasan</div>
        <div class="stat-card-value">{{ $totalRating }}</div>
    </div>

    <div class="stat-card">
        <div class="stat-card-label">Rata-rata Rating</div>
        <div class="stat-card-value">
            {{ number_format($rataRata, 1, ',', '.') }}
            <span style="color:#E8A317;">★</span>
        </div>
    </div>
</div>

<div class="card">
    <div class="card-header">
        <h3>Ulasan Servis Saya</h3>
    </div>

    <div class="card-body">
        @if (session('success'))
            <div class="alert alert-success">
                {{ session('success') }}
            </div>
        @endif

        @forelse ($rating as $item)
            <div style="padding:18px 0; border-bottom:1px solid #E5E7EB;">
                <div style="display:flex; justify-content:space-between; gap:16px; flex-wrap:wrap;">
                    <div>
                        <strong>
                            Booking #{{ $item->id_booking }}
                        </strong>

                        <div style="margin-top:5px; color:#64748B;">
                            Pelanggan:
                            {{ $item->pelanggan->nama ?? 'Pelanggan' }}
                        </div>

                        <div style="margin-top:4px; color:#64748B;">
                            Layanan:
                            {{ $item->bookingServis->layananServis->nama_layanan
                                ?? 'Servis kendaraan' }}
                        </div>
                    </div>

                    <div style="text-align:right;">
                        <div style="color:#E8A317; font-size:20px; letter-spacing:2px;">
                            @for ($i = 1; $i <= 5; $i++)
                                {{ $i <= (int) $item->nilai ? '★' : '☆' }}
                            @endfor
                        </div>

                        <small style="color:#64748B;">
                            {{ $item->tanggal
                                ? \Carbon\Carbon::parse($item->tanggal)->format('d-m-Y H:i')
                                : '-' }}
                        </small>
                    </div>
                </div>

                <div style="margin-top:14px;">
                    <strong>Ulasan pelanggan</strong>

                    <p style="margin-top:6px; color:#475569;">
                        {{ $item->ulasan ?: 'Pelanggan tidak menuliskan komentar.' }}
                    </p>
                </div>
            </div>
        @empty
            <div style="text-align:center; padding:40px 16px;">
                <div style="font-size:42px; color:#CBD5E1;">★</div>
                <h3>Belum Ada Ulasan</h3>
                <p style="color:#64748B;">
                    Ulasan pelanggan akan muncul di sini setelah servis yang
                    kamu kerjakan dinilai.
                </p>
            </div>
        @endforelse
    </div>
</div>
@endsection
