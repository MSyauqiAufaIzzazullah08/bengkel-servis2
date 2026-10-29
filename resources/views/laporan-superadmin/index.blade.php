@extends('layouts.app')
@section('title', 'Laporan Eksekutif')
@section('content')
<div class="container-fluid py-4">
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h2 class="fw-bold mb-1">Laporan Eksekutif</h2>
            <p class="text-muted mb-0">Konsolidasi seluruh cabang &mdash; {{ $dari }} s/d {{ $sampai }}</p>
        </div>
        <a href="{{ route('laporan-superadmin.pdf', ['dari'=>$dari,'sampai'=>$sampai,'cabang_id'=>$filterCabangId]) }}"
           class="btn btn-outline-danger"><i class="fas fa-file-pdf me-1"></i> Export PDF</a>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-body">
            <form method="GET" action="{{ route('laporan-superadmin.index') }}" class="row g-3 align-items-end">
                <div class="col-md-3">
                    <label class="form-label">Cabang</label>
                    <select name="cabang_id" class="form-select">
                        <option value="">Semua Cabang</option>
                        @foreach ($semuaCabangList as $cab)
                        <option value="{{ $cab->id_cabang }}" {{ $filterCabangId==$cab->id_cabang?'selected':'' }}>{{ $cab->nama_cabang }}</option>
                        @endforeach
                    </select>
                </div>
                <div class="col-md-3"><label class="form-label">Dari</label><input type="date" name="dari" class="form-control" value="{{ $dari }}"></div>
                <div class="col-md-3"><label class="form-label">Sampai</label><input type="date" name="sampai" class="form-control" value="{{ $sampai }}"></div>
                <div class="col-md-2"><button type="submit" class="btn btn-primary w-100">Filter</button></div>
            </form>
        </div>
    </div>
    <div class="row g-3 mb-4">
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Total Booking Nasional</p><h2 class="fw-bold text-primary">{{ number_format($grandTotalBooking) }}</h2></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Total Pendapatan Nasional</p><h2 class="fw-bold text-success">Rp {{ number_format($grandTotalPendapatan,0,',','.') }}</h2><small class="text-muted">Bengkel + Home Service</small></div></div></div>
        <div class="col-md-4"><div class="card border-0 shadow-sm h-100"><div class="card-body"><p class="text-muted mb-1">Pelanggan (Booking)</p><h2 class="fw-bold text-info">{{ number_format($grandTotalPelanggan) }}</h2></div></div></div>
    </div>
    <div class="alert alert-warning"><i class="fas fa-info-circle me-2"></i><strong>Catatan:</strong> Laba bersih tidak ditampilkan karena data biaya operasional belum lengkap. Angka yang ditampilkan adalah pendapatan kotor (gross revenue).</div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Tren Booking 12 Bulan Terakhir</h5></div>
        <div class="card-body"><canvas id="chartTren" height="70"></canvas></div>
    </div>
    <div class="card border-0 shadow-sm mb-4">
        <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Perbandingan Per Cabang</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>#</th><th>Cabang</th><th>Booking</th><th>Selesai</th><th>Pelanggan</th><th>Pendapatan Bengkel</th><th>HS Jml</th><th>HS Pendapatan</th><th>Total Pendapatan</th><th>Avg Rating</th></tr></thead>
                    <tbody>
                        @foreach ($perCabang as $i => $row)
                        <tr>
                            <td>{{ $i+1 }}</td>
                            <td>{{ $row['cabang']->nama_cabang }}</td>
                            <td>{{ $row['total_booking'] }}</td>
                            <td>{{ $row['servis_selesai'] }}</td>
                            <td>{{ $row['pelanggan_unik'] }}</td>
                            <td>Rp {{ number_format($row['pendapatan_bengkel'],0,',','.') }}</td>
                            <td>{{ $row['hs_jumlah'] }}</td>
                            <td>Rp {{ number_format($row['hs_pendapatan'],0,',','.') }}</td>
                            <td><strong>Rp {{ number_format($row['total_pendapatan'],0,',','.') }}</strong></td>
                            <td>{{ $row['avg_rating']>0 ? number_format($row['avg_rating'],1) : '—' }}</td>
                        </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
    <div class="card border-0 shadow-sm">
        <div class="card-header bg-white py-3"><h5 class="fw-bold mb-0">Top 10 Teknisi</h5></div>
        <div class="card-body">
            <div class="table-responsive">
                <table class="table table-hover align-middle">
                    <thead><tr><th>#</th><th>Nama Teknisi</th><th>Cabang</th><th>Servis Selesai</th><th>Avg Rating</th></tr></thead>
                    <tbody>
                        @forelse ($topTeknisi as $i => $t)
                        <tr><td>{{ $i+1 }}</td><td>{{ $t->nama }}</td><td>{{ $t->nama_cabang??'—' }}</td><td>{{ $t->jumlah_servis }}</td><td>{{ $t->avg_rating>0?number_format($t->avg_rating,1):'—' }}</td></tr>
                        @empty
                        <tr><td colspan="5" class="text-center text-muted py-3">Belum ada data.</td></tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
        </div>
    </div>
</div>
@endsection
@section('scripts')
<script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.0/dist/chart.umd.min.js"></script>
<script>
(function(){
    var labels=@json(array_keys($trenBulanan));
    var values=@json(array_values($trenBulanan));
    var ctx=document.getElementById('chartTren');
    if(!ctx)return;
    new Chart(ctx,{type:'bar',data:{labels:labels,datasets:[{label:'Booking',data:values,backgroundColor:'rgba(13,110,253,0.6)',borderColor:'#0d6efd',borderWidth:1}]},options:{responsive:true,plugins:{legend:{display:false}},scales:{y:{beginAtZero:true,ticks:{stepSize:1}}}}});
})();
</script>
@endsection
