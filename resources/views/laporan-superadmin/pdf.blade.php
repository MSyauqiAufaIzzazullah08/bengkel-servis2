<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Eksekutif</title>
<style>
    *{margin:0;padding:0;box-sizing:border-box;}
    body{font-family:Arial,sans-serif;font-size:10px;color:#222;}
    h1{font-size:15px;margin-bottom:3px;}
    h2{font-size:12px;margin:12px 0 5px;color:#333;border-bottom:1px solid #ccc;padding-bottom:2px;}
    .header{text-align:center;margin-bottom:14px;}
    .header p{font-size:9px;color:#555;}
    .cards{display:flex;gap:6px;margin-bottom:12px;flex-wrap:wrap;}
    .card{border:1px solid #ddd;border-radius:3px;padding:6px 10px;flex:1;min-width:110px;}
    .card-label{font-size:8px;color:#888;}
    .card-value{font-size:13px;font-weight:bold;}
    table{width:100%;border-collapse:collapse;margin-bottom:12px;}
    th{background:#f0f0f0;border:1px solid #ccc;padding:4px 6px;text-align:left;font-size:9px;}
    td{border:1px solid #ddd;padding:4px 6px;font-size:9px;}
    tr:nth-child(even) td{background:#fafafa;}
    .note{font-size:8px;color:#888;margin-top:8px;}
    .warn{background:#fff3cd;border:1px solid #ffc107;padding:5px 8px;font-size:9px;margin-bottom:10px;border-radius:3px;}
</style>
</head>
<body>
<div class="header">
    <h1>Laporan Eksekutif — BengkelServis</h1>
    <p>Periode: {{ $dari }} s/d {{ $sampai }} | Dicetak: {{ now()->isoFormat('D MMMM YYYY HH:mm') }}</p>
</div>
<div class="warn">Laba bersih tidak ditampilkan karena data biaya operasional belum lengkap. Angka di bawah adalah pendapatan kotor.</div>
<div class="cards">
    <div class="card"><div class="card-label">Total Booking</div><div class="card-value">{{ number_format($grandTotalBooking) }}</div></div>
    <div class="card"><div class="card-label">Total Pendapatan</div><div class="card-value">Rp {{ number_format($grandTotalPendapatan,0,',','.') }}</div></div>
    <div class="card"><div class="card-label">Pelanggan</div><div class="card-value">{{ number_format($grandTotalPelanggan) }}</div></div>
</div>
<h2>Perbandingan Per Cabang</h2>
<table>
    <thead><tr><th>#</th><th>Cabang</th><th>Booking</th><th>Selesai</th><th>Pelanggan</th><th>Pend. Bengkel</th><th>HS Jml</th><th>HS Pend.</th><th>Total Pend.</th><th>Rating</th></tr></thead>
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
            <td>Rp {{ number_format($row['total_pendapatan'],0,',','.') }}</td>
            <td>{{ $row['avg_rating']>0?number_format($row['avg_rating'],1):'—' }}</td>
        </tr>
        @endforeach
    </tbody>
</table>
<h2>Top 10 Teknisi</h2>
<table>
    <thead><tr><th>#</th><th>Nama</th><th>Cabang</th><th>Servis Selesai</th><th>Avg Rating</th></tr></thead>
    <tbody>
        @forelse ($topTeknisi as $i => $t)
        <tr><td>{{ $i+1 }}</td><td>{{ $t->nama }}</td><td>{{ $t->nama_cabang??'—' }}</td><td>{{ $t->jumlah_servis }}</td><td>{{ $t->avg_rating>0?number_format($t->avg_rating,1):'—' }}</td></tr>
        @empty
        <tr><td colspan="5" style="text-align:center">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>
<p class="note">* Laporan ini digenerate otomatis dari sistem BengkelServis.</p>
</body>
</html>
