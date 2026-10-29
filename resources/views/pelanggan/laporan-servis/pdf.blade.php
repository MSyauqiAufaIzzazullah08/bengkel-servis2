<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <title>Laporan Servis</title>
    <style>
        /* Inline CSS only — DomPDF tidak dapat memuat CDN external */
        * { box-sizing: border-box; margin: 0; padding: 0; }
        body { font-family: Arial, Helvetica, sans-serif; font-size: 12px; color: #222; padding: 20px; }
        h1 { font-size: 18px; text-align: center; margin-bottom: 4px; }
        .subtitle { text-align: center; font-size: 11px; color: #555; margin-bottom: 20px; }
        hr { border: none; border-top: 2px solid #333; margin-bottom: 16px; }
        .section { margin-bottom: 16px; }
        .section-title { font-size: 13px; font-weight: bold; background: #f0f0f0; padding: 4px 8px; border-left: 4px solid #333; margin-bottom: 8px; }
        table.dl { width: 100%; border-collapse: collapse; }
        table.dl td { padding: 4px 6px; vertical-align: top; }
        table.dl td.label { width: 35%; font-weight: bold; color: #444; }
        table.dl td.value { width: 65%; }
        ul.komponen { margin: 0; padding-left: 18px; }
        .footer { margin-top: 30px; text-align: right; font-size: 10px; color: #888; }
        .badge { display: inline-block; padding: 2px 8px; border-radius: 4px; font-size: 11px; background: #28a745; color: #fff; }
    </style>
</head>
<body>

    <h1>Laporan Servis Kendaraan</h1>
    <p class="subtitle">BengkelServis Multi-Cabang</p>
    <hr>

    @php
        $penugasan = optional($laporan->penugasanTeknisi);
        $booking   = optional($penugasan->bookingServis);
        $kendaraan = optional($booking->kendaraan);
        $layanan   = optional($booking->layananServis);
        $cabang    = optional($booking->cabang);
        $teknisi   = optional($penugasan->teknisi);
    @endphp

    <div class="section">
        <div class="section-title">Informasi Pelanggan &amp; Kendaraan</div>
        <table class="dl">
            <tr>
                <td class="label">Nama Pelanggan</td>
                <td class="value">{{ optional($pelanggan)->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kendaraan</td>
                <td class="value">{{ $kendaraan->merk ?? '' }} {{ $kendaraan->model ?? '' }} — {{ $kendaraan->nopol ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Cabang</td>
                <td class="value">{{ $cabang->nama_cabang ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Layanan</td>
                <td class="value">{{ $layanan->nama_layanan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Teknisi</td>
                <td class="value">{{ $teknisi->nama ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tanggal Selesai</td>
                <td class="value">{{ $laporan->tanggal_selesai_pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Status</td>
                <td class="value"><span class="badge">Disetujui</span></td>
            </tr>
        </table>
    </div>

    <div class="section">
        <div class="section-title">Laporan Teknis</div>
        <table class="dl">
            <tr>
                <td class="label">Keluhan Awal</td>
                <td class="value">{{ $laporan->keluhan_awal ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Hasil Diagnosis</td>
                <td class="value">{{ $laporan->hasil_diagnosis ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Tindakan Perbaikan</td>
                <td class="value">{{ $laporan->tindakan_perbaikan ?? '-' }}</td>
            </tr>
            {{-- [DISABLED - Komponen Digunakan dihapus karena fitur sparepart penjualan online dihapus] --}}
            <tr>
                <td class="label">Kondisi Motor Setelah</td>
                <td class="value">{{ $laporan->kondisi_motor_setelah ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Kendala Pekerjaan</td>
                <td class="value">{{ $laporan->kendala_pekerjaan ?? '-' }}</td>
            </tr>
            <tr>
                <td class="label">Catatan &amp; Rekomendasi</td>
                <td class="value">{{ $laporan->catatan_rekomendasi ?? '-' }}</td>
            </tr>
        </table>
    </div>

    <div class="footer">
        Dicetak: {{ now()->format('d/m/Y H:i') }} | BengkelServis
    </div>

</body>
</html>
