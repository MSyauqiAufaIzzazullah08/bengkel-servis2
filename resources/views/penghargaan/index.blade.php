@extends('layouts.app')
@section('title', 'Penghargaan Teknisi')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Penghargaan Teknisi</h2>
            <p class="text-muted mb-0">Employee of the Month / Year — leaderboard kinerja teknisi.</p>
        </div>
    </div>

    @if(session('success'))
        <div class="alert alert-success">{{ session('success') }}</div>
    @endif
    @if(session('error'))
        <div class="alert alert-danger">{{ session('error') }}</div>
    @endif

    {{-- Filter & Generate --}}
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('penghargaan.index') }}" class="row g-3 align-items-end mb-3">
                <div class="col-md-3">
                    <label class="form-label">Tipe Periode</label>
                    <select name="periode_tipe" class="form-select">
                        @foreach(['mingguan'=>'Mingguan','bulanan'=>'Bulanan','tahunan'=>'Tahunan'] as $val=>$lbl)
                        <option value="{{ $val }}" {{ $periodeTipe==$val?'selected':'' }}>{{ $lbl }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3">
                    <label class="form-label">Periode Mulai</label>
                    <input type="date" name="periode_mulai" class="form-control" value="{{ $periodeMulai }}">
                </div>
                @if((int)session('role_id')===1)
                <div class="col-md-3">
                    <label class="form-label">Cabang</label>
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($semuaCabang as $cab)
                        <option value="{{ $cab->id_cabang }}" {{ $cabangId==$cab->id_cabang?'selected':'' }}>{{ $cab->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <button type="submit" class="btn btn-primary w-100">Filter</button>
                </div>
            </form>

            {{-- Generate form --}}
            <hr>
            <form method="POST" action="{{ route('penghargaan.generate') }}" class="row g-3 align-items-end">
                @csrf
                <div class="col-md-2">
                    <label class="form-label">Tipe</label>
                    <select name="periode_tipe" class="form-select">
                        <option value="bulanan">Bulanan</option>
                        <option value="mingguan">Mingguan</option>
                        <option value="tahunan">Tahunan</option>
                    </select>
                </div>
                <div class="col-md-2">
                    <label class="form-label">Mulai</label>
                    <input type="date" name="periode_mulai" class="form-control" value="{{ now()->startOfMonth()->toDateString() }}">
                </div>
                <div class="col-md-2">
                    <label class="form-label">Selesai</label>
                    <input type="date" name="periode_selesai" class="form-control" value="{{ now()->endOfMonth()->toDateString() }}">
                </div>
                @if((int)session('role_id')===1)
                <div class="col-md-3">
                    <label class="form-label">Cabang (opsional)</label>
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach($semuaCabang as $cab)
                        <option value="{{ $cab->id_cabang }}">{{ $cab->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                @endif
                <div class="col-md-2">
                    <button type="submit" class="btn btn-success w-100" onclick="return confirm('Hitung ulang penghargaan untuk periode ini?')">
                        <i class="fas fa-calculator me-1"></i> Generate
                    </button>
                </div>
            </form>
        </div>
    </div>

    {{-- Leaderboard --}}
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3">
            <h5 class="fw-bold mb-0">
                Leaderboard — {{ ucfirst($periodeTipe) }}
                <span class="text-muted fw-normal fs-6 ms-2">mulai {{ $periodeMulai }}</span>
            </h5>
        </div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead>
                        <tr>
                            <th>Peringkat</th>
                            <th>Nama Teknisi</th>
                            <th>Cabang</th>
                            <th>Jumlah Servis</th>
                            <th>Rata-rata Rating</th>
                            <th>Skor Total</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse ($penghargaan as $item)
                        <tr class="{{ $item->peringkat<=3 ? 'table-warning' : '' }}">
                            <td>
                                @if($item->peringkat===1)
                                    <span class="badge bg-warning text-dark fs-6">🏆 1</span>
                                @elseif($item->peringkat===2)
                                    <span class="badge bg-secondary fs-6">🥈 2</span>
                                @elseif($item->peringkat===3)
                                    <span class="badge" style="background:#cd7f32;color:#fff;font-size:1rem">🥉 3</span>
                                @else
                                    {{ $item->peringkat }}
                                @endif
                            </td>
                            <td>{{ $item->teknisi?->nama ?? '—' }}</td>
                            <td>{{ $item->cabang?->nama_cabang ?? '—' }}</td>
                            <td>{{ $item->jumlah_servis }}</td>
                            <td>{{ $item->rata_rata_rating > 0 ? number_format($item->rata_rata_rating, 2) : '—' }}</td>
                            <td><strong>{{ number_format($item->skor_total, 2) }}</strong></td>
                        </tr>
                        @empty
                        <tr><td colspan="6" class="text-center text-muted py-4">Belum ada data penghargaan. Klik <strong>Generate</strong> untuk menghitung.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
