@extends('layouts.app')

@section('title', 'Dashboard Teknisi')

@section('content')
<div class="container-fluid py-4">

    <div class="mb-4">
        <h2 class="fw-bold mb-1">Dashboard Teknisi</h2>
        <p class="text-muted mb-0">
            Selamat datang, {{ $teknisi->nama }}!
            Berikut daftar pekerjaan servis yang ditugaskan kepadamu.
        </p>
    </div>

    @if (session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul class="mb-0">
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- ============================================================
         Statistik Penugasan
         ============================================================ --}}
    <div class="row g-3 mb-4">
        @foreach ([
            ['Total Penugasan',   $totalPenugasan,   'secondary'],
            ['Pekerjaan Baru',    $penugasanBaru,    'primary'],
            ['Sedang Dikerjakan', $penugasanProses,  'warning'],
            ['Pekerjaan Selesai', $penugasanSelesai, 'success'],
        ] as [$label, $jumlah, $warna])
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">{{ $label }}</p>
                        <h2 class="fw-bold text-{{ $warna }} mb-0">{{ $jumlah }}</h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

    {{-- ============================================================
         Target Bulanan & Komisi (FEAT-003)
         ============================================================ --}}
    <div class="row g-3 mb-4">
        {{-- Target Bulanan --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Target Bulanan</p>
                    <h2 class="fw-bold text-info mb-0">{{ $targetBulanan }}</h2>
                    <small class="text-muted">servis / bulan</small>
                </div>
            </div>
        </div>

        {{-- Pencapaian Bulan Ini --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Pencapaian Bulan Ini</p>
                    <h2 class="fw-bold mb-1 {{ $persentasePencapaian >= 100 ? 'text-success' : 'text-warning' }}">
                        {{ $servisSelesaiBulanIni }} / {{ $targetBulanan }}
                    </h2>
                    <div class="progress" style="height:6px">
                        <div class="progress-bar {{ $persentasePencapaian >= 100 ? 'bg-success' : 'bg-warning' }}"
                             style="width:{{ $persentasePencapaian }}%"></div>
                    </div>
                    <small class="text-muted">{{ $persentasePencapaian }}%</small>
                </div>
            </div>
        </div>

        {{-- Estimasi Bonus --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Estimasi Bonus</p>
                    @if ($estimasiBonus !== null)
                        <h2 class="fw-bold text-success mb-0">
                            Rp {{ number_format($estimasiBonus, 0, ',', '.') }}
                        </h2>
                        <small class="text-success">Target tercapai! Menunggu pembayaran.</small>
                    @elseif ($persentasePencapaian >= 100)
                        <h2 class="fw-bold text-secondary mb-0">Sudah Dibayar</h2>
                        <small class="text-muted">Bonus bulan ini sudah dicairkan.</small>
                    @else
                        <h2 class="fw-bold text-muted mb-0">—</h2>
                        <small class="text-muted">
                            Kurang {{ max(0, $targetBulanan - $servisSelesaiBulanIni) }} servis lagi.
                        </small>
                    @endif
                </div>
            </div>
        </div>

        {{-- Komisi Belum Dibayar --}}
        <div class="col-md-3 col-sm-6">
            <div class="card border-0 shadow-sm h-100">
                <div class="card-body">
                    <p class="text-muted mb-2">Komisi Belum Dibayar</p>
                    <h2 class="fw-bold text-danger mb-0">
                        Rp {{ number_format($komisiTotalBelumDibayar, 0, ',', '.') }}
                    </h2>
                    <small class="text-muted">bulan {{ now()->isoFormat('MMMM YYYY') }}</small>
                </div>
            </div>
        </div>
    </div>

    {{-- ============================================================
         Riwayat Komisi Bulan Ini
         ============================================================ --}}
    @if ($riwayatKomisiBulanIni->isNotEmpty())
        <div class="card border-0 shadow-sm mb-4">
            <div class="card-header bg-white py-3">
                <h5 class="fw-bold mb-0">Riwayat Komisi Bulan Ini</h5>
            </div>
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table table-sm align-middle">
                        <thead>
                            <tr>
                                <th>No.</th>
                                <th>Tipe</th>
                                <th>Nominal</th>
                                <th>Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($riwayatKomisiBulanIni as $k)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>
                                        @if ($k->is_bonus_target)
                                            <span class="badge bg-warning text-dark">Bonus Target</span>
                                        @elseif ($k->tipe_servis === 'home_service')
                                            <span class="badge bg-info text-dark">Home Service</span>
                                        @else
                                            <span class="badge bg-secondary">Per Servis</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($k->nominal_komisi, 0, ',', '.') }}</td>
                                    <td>
                                        @if ($k->status_pembayaran === 'sudah_dibayar')
                                            <span class="badge bg-success">Sudah Dibayar</span>
                                        @else
                                            <span class="badge bg-danger">Belum Dibayar</span>
                                        @endif
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    @endif

    {{-- ============================================================
         Pekerjaan Saya
         ============================================================ --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">Pekerjaan Saya</h5>
        </div>

        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>No.</th>
                            <th>Booking</th>
                            <th>Pelanggan</th>
                            <th>Kendaraan</th>
                            <th>Layanan</th>
                            <th>Tanggal</th>
                            <th>Status</th>
                            <th>Catatan</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>

                    <tbody>
                        @forelse ($penugasan as $item)
                            @php
                                $warnaStatus = match($item->status) {
                                    'Assigned'    => 'warning',
                                    'In Progress' => 'primary',
                                    'Completed'   => 'success',
                                    default       => 'secondary',
                                };

                                $labelStatus = match($item->status) {
                                    'Assigned'    => 'Ditugaskan',
                                    'In Progress' => 'Sedang Dikerjakan',
                                    'Completed'   => 'Selesai',
                                    default       => $item->status,
                                };
                            @endphp

                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>#{{ $item->id_booking }}</td>
                                <td>
                                    {{ $item->bookingServis?->pelanggan?->nama ?? '-' }}
                                </td>
                                <td>
                                    {{ $item->bookingServis?->kendaraan?->merk ?? '-' }}
                                    {{ $item->bookingServis?->kendaraan?->model ?? '' }}
                                    <br>
                                    <small class="text-muted">
                                        {{ $item->bookingServis?->kendaraan?->nopol ?? '' }}
                                    </small>
                                </td>
                                <td>
                                    {{ $item->bookingServis?->layananServis?->nama_layanan ?? '-' }}
                                </td>
                                <td>{{ $item->tanggal }}</td>
                                <td>
                                    <span class="badge bg-{{ $warnaStatus }}">
                                        {{ $labelStatus }}
                                    </span>
                                </td>
                                <td>{{ $item->catatan ?: '-' }}</td>
                                <td>
                                    @if ($item->status === 'Assigned')
                                        <form
                                            action="{{ route('teknisi.penugasan.status', $item->id_penugasan) }}"
                                            method="POST"
                                        >
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="In Progress">
                                            <button type="submit" class="btn btn-primary btn-sm">
                                                Mulai Pengerjaan
                                            </button>
                                        </form>
                                    @elseif ($item->status === 'In Progress')
                                        <form
                                            action="{{ route('teknisi.penugasan.status', $item->id_penugasan) }}"
                                            method="POST"
                                            onsubmit="return confirm('Pastikan servis benar-benar sudah selesai. Lanjutkan?')"
                                        >
                                            @csrf
                                            @method('PUT')
                                            <input type="hidden" name="status" value="Completed">
                                            <button type="submit" class="btn btn-success btn-sm">
                                                Selesaikan Servis
                                            </button>
                                        </form>
                                    @else
                                        <span class="text-success">
                                            <i class="fas fa-check-circle"></i>
                                            Selesai
                                        </span>
                                    @endif
                                </td>
                            </tr>
                        @empty
                            <tr>
                                <td colspan="9" class="text-center text-muted py-4">
                                    Belum ada penugasan servis untuk kamu.
                                </td>
                            </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>

</div>
@endsection
