
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Pembayaran - BengkelServis</title>
    <style>
        * { box-sizing: border-box; }
        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f4f6f9;
            color: #263445;
        }
        .container {
            max-width: 1200px;
            margin: 35px auto;
            padding: 0 20px;
        }
        .header {
            background: #173b63;
            color: white;
            padding: 25px;
            border-radius: 12px;
            margin-bottom: 24px;
        }
        .header h1 { margin: 0 0 8px; }
        .header p { margin: 0; color: #dbeafe; }
        .card {
            background: white;
            padding: 24px;
            border-radius: 12px;
            margin-bottom: 22px;
            box-shadow: 0 3px 12px rgba(0,0,0,.05);
        }
        .filter {
            display: flex;
            gap: 16px;
            align-items: end;
            flex-wrap: wrap;
        }
        .field {
            display: flex;
            flex-direction: column;
            gap: 7px;
        }
        input {
            padding: 11px;
            border: 1px solid #ccd5df;
            border-radius: 7px;
            font-size: 14px;
        }
        .btn {
            display: inline-block;
            padding: 11px 16px;
            border: 0;
            border-radius: 7px;
            text-decoration: none;
            cursor: pointer;
            font-weight: bold;
            font-size: 14px;
        }
        .btn-primary { background: #1d4ed8; color: white; }
        .btn-success { background: #15803d; color: white; }
        .btn-light { background: #e5e7eb; color: #263445; }
        .summary {
            display: grid;
            grid-template-columns: repeat(2, minmax(0, 1fr));
            gap: 18px;
        }
        .summary-item {
            padding: 20px;
            border-radius: 10px;
            background: #eff6ff;
        }
        .summary-item span { color: #526579; }
        .summary-item h2 { margin: 10px 0 0; color: #173b63; }
        .table-wrap { overflow-x: auto; }
        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 13px;
        }
        th, td {
            text-align: left;
            padding: 13px 10px;
            border-bottom: 1px solid #e5e7eb;
            white-space: nowrap;
        }
        th { background: #f1f5f9; }
        .status {
            display: inline-block;
            padding: 5px 9px;
            border-radius: 20px;
            background: #fef3c7;
            color: #92400e;
            font-size: 12px;
        }
        .status.lunas { background: #dcfce7; color: #166534; }
        .status.gagal { background: #fee2e2; color: #991b1b; }
        .empty { text-align: center; padding: 28px; color: #64748b; }
        .error { color: #b91c1c; font-size: 13px; }
        @media (max-width: 600px) {
            .summary { grid-template-columns: 1fr; }
            .container { margin-top: 18px; padding: 0 12px; }
            .card { padding: 16px; }
        }
    </style>
</head>
<body>
<div class="container">
    <div class="header">
        <h1>Laporan Pembayaran</h1>
        <p>BengkelServis — Ringkasan dan riwayat transaksi pembayaran.</p>
    </div>

    @if (session('success'))
        <div class="card">{{ session('success') }}</div>
    @endif

    <div class="card">
        <h3>Filter Periode Laporan</h3>

        <form action="{{ url('/laporan-pembayaran') }}" method="GET" class="filter">
            <div class="field">
                <label for="tanggal_mulai">Tanggal Mulai</label>
                <input
                    type="date"
                    id="tanggal_mulai"
                    name="tanggal_mulai"
                    value="{{ $tanggalMulai }}"
                >
                @error('tanggal_mulai')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <div class="field">
                <label for="tanggal_selesai">Tanggal Selesai</label>
                <input
                    type="date"
                    id="tanggal_selesai"
                    name="tanggal_selesai"
                    value="{{ $tanggalSelesai }}"
                >
                @error('tanggal_selesai')
                    <span class="error">{{ $message }}</span>
                @enderror
            </div>

            <button type="submit" class="btn btn-primary">Tampilkan</button>

            <a href="{{ url('/laporan-pembayaran') }}" class="btn btn-light">
                Reset
            </a>

            <a
                href="{{ url('/laporan-pembayaran/pdf') . '?' . http_build_query(request()->only('tanggal_mulai', 'tanggal_selesai')) }}"
                class="btn btn-success"
            >
                Download PDF
            </a>
        </form>
    </div>

    <div class="summary">
        <div class="summary-item">
            <span>Total Transaksi</span>
            <h2>{{ number_format($totalTransaksi, 0, ',', '.') }}</h2>
        </div>

        <div class="summary-item">
            <span>Total Pembayaran Lunas</span>
            <h2>Rp {{ number_format($totalLunas, 0, ',', '.') }}</h2>
        </div>
    </div>

    <div class="card" style="margin-top: 22px;">
        <h3>Rincian Transaksi</h3>

        <div class="table-wrap">
            <table>
                <thead>
                <tr>
                    <th>No.</th>
                    <th>ID Pembayaran</th>
                    <th>Pelanggan</th>
                    <th>Jenis Transaksi</th>
                    <th>Metode</th>
                    <th>Promo</th>
                    <th>Jumlah</th>
                    <th>Status</th>
                    <th>Tanggal Bayar</th>
                </tr>
                </thead>
                <tbody>
                @forelse ($pembayaran as $item)
                    @php
                        $pelanggan = $item->bookingServis?->pelanggan
                            ?? $item->orderSparePart?->pelanggan;

                        $jenis = $item->id_booking
                            ? 'Servis Bengkel'
                            : ($item->id_order ? 'Spare Part' : 'Lainnya');

                        $status = strtolower(trim((string) $item->status));
                    @endphp
                    <tr>
                        <td>{{ $loop->iteration }}</td>
                        <td>#{{ $item->id_pembayaran }}</td>
                        <td>{{ $pelanggan->nama ?? $pelanggan->nama_pelanggan ?? '-' }}</td>
                        <td>{{ $jenis }}</td>
                        <td>{{ $item->metodePembayaran->nama_metode ?? '-' }}</td>
                        <td>{{ $item->promo->kode_promo ?? '-' }}</td>
                        <td>Rp {{ number_format((float) $item->jumlah, 0, ',', '.') }}</td>
                        <td>
                            <span class="status {{ $status === 'lunas' ? 'lunas' : ($status === 'gagal' ? 'gagal' : '') }}">
                                {{ ucfirst($item->status ?? 'Belum tersedia') }}
                            </span>
                        </td>
                        <td>
                            {{ $item->tanggal_bayar
                                ? \Illuminate\Support\Carbon::parse($item->tanggal_bayar)->format('d-m-Y')
                                : '-' }}
                        </td>
                    </tr>
                @empty
                    <tr>
                        <td colspan="9" class="empty">
                            Tidak ada transaksi pada periode yang dipilih.
                        </td>
                    </tr>
                @endforelse
                </tbody>
            </table>
        </div>
    </div>
</div>
</body>
</html>
