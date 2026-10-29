<!DOCTYPE html>
<html lang="id">
<head>
<meta charset="UTF-8">
<title>Laporan Operasional Cabang</title>
<style>
    * { margin: 0; padding: 0; box-sizing: border-box; }
    body { font-family: Arial, sans-serif; font-size: 11px; color: #222; }
    h1 { font-size: 16px; margin-bottom: 4px; }
    h2 { font-size: 13px; margin: 14px 0 6px; color: #333; border-bottom: 1px solid #ccc; padding-bottom: 3px; }
    .header { text-align: center; margin-bottom: 16px; }
    .header p { font-size: 10px; color: #555; }
    .cards { display: flex; gap: 8px; margin-bottom: 14px; flex-wrap: wrap; }
    .card { border: 1px solid #ddd; border-radius: 4px; padding: 8px 12px; flex: 1; min-width: 130px; }
    .card-label { font-size: 9px; color: #888; }
    .card-value { font-size: 14px; font-weight: bold; }
    table { width: 100%; border-collapse: collapse; margin-bottom: 14px; }
    th { background: #f0f0f0; border: 1px solid #ccc; padding: 5px 7px; text-align: left; font-size: 10px; }
    td { border: 1px solid #ddd; padding: 5px 7px; font-size: 10px; }
    tr:nth-child(even) td { background: #fafafa; }
    .note { font-size: 9px; color: #888; margin-top: 10px; }
</style>
</head>
<body>

<div class="header">
    <h1>Laporan Operasional Cabang</h1>
    <p>{{ $cabang->nama_cabang ?? '' }} &mdash; Periode: {{ $dari }} s/d {{ $sampai }}</p>
    <p>Dicetak: {{ now()->isoFormat('D MMMM YYYY HH:mm') }}</p>
</div>

<div class="cards">
    <div class="card">
        <div class="card-label">Total Booking</div>
        <div class="card-value">{{ number_format($totalBooking) }}</div>
    </div>
    <div class="card">
        <div class="card-label">Servis Selesai</div>
        <div class="card-value">{{ number_format($servisSelesai) }}</div>
    </div>
    <div class="card">
        <div class="card-label">Servis Berjalan</div>
        <div class="card-value">{{ number_format($servisBerjalan) }}</div>
    </div>
    <div class="card">
        <div class="card-label">Pelanggan Unik</div>
        <div class="card-value">{{ number_format($jumlahPelanggan) }}</div>
    </div>
    <div class="card">
        <div class="card-label">Pendapatan Bengkel</div>
        <div class="card-value">Rp {{ number_format($pendapatanBengkel, 0, ',', '.') }}</div>
    </div>
    <div class="card">
        <div class="card-label">Home Service ({{ $jumlahHomeService }} layanan)</div>
        <div class="card-value">Rp {{ number_format($pendapatanHomeService, 0, ',', '.') }}</div>
    </div>
    <div class="card" style="background:#f0fff0">
        <div class="card-label">Total Pendapatan</div>
        <div class="card-value" style="color:#157347">Rp {{ number_format($totalPendapatan, 0, ',', '.') }}</div>
    </div>
</div>

<h2>Performa Teknisi</h2>
<table>
    <thead>
        <tr>
            <th>#</th>
            <th>Nama Teknisi</th>
            <th>Servis Selesai</th>
            <th>Rata-rata Rating</th>
            <th>Total Komisi Dibayar</th>
        </tr>
    </thead>
    <tbody>
        @forelse ($performaTeknisi as $i => $t)
            <tr>
                <td>{{ $i + 1 }}</td>
                <td>{{ $t->nama }}</td>
                <td>{{ $t->jumlah_servis_selesai }}</td>
                <td>{{ $t->avg_rating > 0 ? number_format($t->avg_rating, 1) : '—' }}</td>
                <td>Rp {{ number_format($t->total_komisi, 0, ',', '.') }}</td>
            </tr>
        @empty
            <tr><td colspan="5" style="text-align:center;color:#888">Belum ada data.</td></tr>
        @endforelse
    </tbody>
</table>

<p class="note">* Laba bersih tidak ditampilkan karena data biaya operasional belum lengkap.</p>
<p class="note">* Laporan ini digenerate otomatis dari sistem BengkelServis.</p>

</body>
</html>
