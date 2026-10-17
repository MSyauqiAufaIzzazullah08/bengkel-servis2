
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
        <div class="alert alert-success">
            {{ session('success') }}
        </div>
    @endif

    @if (session('error'))
        <div class="alert alert-danger">
            {{ session('error') }}
        </div>
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

    <div class="row g-3 mb-4">
        @foreach ([
            ['Total Penugasan', $totalPenugasan, 'secondary'],
            ['Pekerjaan Baru', $penugasanBaru, 'primary'],
            ['Sedang Dikerjakan', $penugasanProses, 'warning'],
            ['Pekerjaan Selesai', $penugasanSelesai, 'success'],
        ] as [$label, $jumlah, $warna])
            <div class="col-md-3 col-sm-6">
                <div class="card border-0 shadow-sm h-100">
                    <div class="card-body">
                        <p class="text-muted mb-2">{{ $label }}</p>
                        <h2 class="fw-bold text-{{ $warna }} mb-0">
                            {{ $jumlah }}
                        </h2>
                    </div>
                </div>
            </div>
        @endforeach
    </div>

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
                                    'Assigned' => 'warning',
                                    'In Progress' => 'primary',
                                    'Completed' => 'success',
                                    default => 'secondary',
                                };

                                $labelStatus = match($item->status) {
                                    'Assigned' => 'Ditugaskan',
                                    'In Progress' => 'Sedang Dikerjakan',
                                    'Completed' => 'Selesai',
                                    default => $item->status,
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
                                            <input
                                                type="hidden"
                                                name="status"
                                                value="In Progress"
                                            >
                                            <button
                                                type="submit"
                                                class="btn btn-primary btn-sm"
                                            >
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
                                            <input
                                                type="hidden"
                                                name="status"
                                                value="Completed"
                                            >
                                            <button
                                                type="submit"
                                                class="btn btn-success btn-sm"
                                            >
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
