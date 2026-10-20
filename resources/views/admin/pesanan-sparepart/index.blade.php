
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kelola Pesanan Spare Part | BengkelServis</title>

    <style>
        * { box-sizing: border-box; }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            color: #102b55;
            background: #f3f6fc;
            font-size: 14px;
        }

        header {
            padding: 18px 6%;
            background: white;
            border-bottom: 1px solid #d9e3f2;
            display: flex;
            justify-content: space-between;
            align-items: center;
            gap: 15px;
        }

        a { color: #102b55; text-decoration: none; }

        .logo { font-size: 20px; font-weight: bold; }
        .logo span { color: #f5a623; }

        main {
            width: min(1250px, 94%);
            margin: 30px auto;
        }

        h1 { font-size: 25px; margin-bottom: 8px; }
        h2 { font-size: 16px; }

        .muted { color: #71829d; line-height: 1.6; }

        .stats {
            display: grid;
            grid-template-columns: repeat(4, 1fr);
            gap: 15px;
            margin: 22px 0;
        }

        .stat, .card {
            background: white;
            border: 1px solid #d9e3f2;
            border-radius: 12px;
            padding: 20px;
        }

        .stat strong {
            display: block;
            font-size: 25px;
            margin-top: 10px;
        }

        .message {
            padding: 13px;
            border-radius: 8px;
            margin: 15px 0;
        }

        .success { background: #dcfce7; color: #166534; }
        .error { background: #fee2e2; color: #991b1b; }

        .table-wrap {
            overflow-x: auto;
            margin-top: 18px;
        }

        table {
            width: 100%;
            border-collapse: collapse;
            min-width: 900px;
        }

        th, td {
            padding: 13px 12px;
            border-bottom: 1px solid #e6edf6;
            text-align: left;
            vertical-align: top;
        }

        th {
            background: #f8fafc;
            font-size: 12px;
        }

        .items { line-height: 1.8; }

        .badge {
            display: inline-block;
            padding: 6px 9px;
            border-radius: 20px;
            font-size: 11px;
            font-weight: bold;
            background: #fff1d6;
            color: #8a5700;
        }

        .badge.selesai {
            background: #dcfce7;
            color: #166534;
        }

        .badge.dibatalkan {
            background: #fee2e2;
            color: #991b1b;
        }

        select {
            width: 145px;
            padding: 9px;
            border: 1px solid #cbd5e1;
            border-radius: 7px;
            margin-bottom: 8px;
            background: white;
        }

        button, .btn {
            display: inline-block;
            padding: 10px 13px;
            border: 0;
            border-radius: 7px;
            background: #102b55;
            color: white;
            font-weight: bold;
            font-size: 12px;
            cursor: pointer;
        }

        .empty {
            padding: 40px 10px;
            text-align: center;
            color: #71829d;
        }

        footer {
            text-align: center;
            color: #71829d;
            padding: 25px;
            font-size: 12px;
        }

        @media (max-width: 750px) {
            .stats { grid-template-columns: repeat(2, 1fr); }
            header { padding: 16px 4%; }
            h1 { font-size: 22px; }
        }
    </style>
</head>
<body>

<header>
    <a href="/dashboard-super-admin" class="logo">
        🔧 Bengkel<span>Servis</span>
    </a>

    <a href="/dashboard">← Dashboard</a>
</header>

<main>
    <h1>Kelola Pesanan Spare Part</h1>
    <p class="muted">
        Pantau pesanan pelanggan, periksa rincian barang, dan perbarui status pesanan.
    </p>

    @if (session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif

    @if ($errors->any())
        <div class="message error">
            @foreach ($errors->all() as $error)
                <div>{{ $error }}</div>
            @endforeach
        </div>
    @endif

    <section class="stats">
        <div class="stat">
            <span class="muted">Total Pesanan</span>
            <strong>{{ $totalPesanan }}</strong>
        </div>

        <div class="stat">
            <span class="muted">Menunggu</span>
            <strong>{{ $pesananPending }}</strong>
        </div>

        <div class="stat">
            <span class="muted">Diproses</span>
            <strong>{{ $pesananDiproses }}</strong>
        </div>

        <div class="stat">
            <span class="muted">Selesai</span>
            <strong>{{ $pesananSelesai }}</strong>
        </div>
    </section>

    <section class="card">
        <h2>Daftar Pesanan Pelanggan</h2>

        @if ($orders->isEmpty())
            <div class="empty">
                <div style="font-size:38px">📦</div>
                <h3>Belum Ada Pesanan</h3>
                <p>Pesanan spare part pelanggan akan muncul di halaman ini.</p>
            </div>
        @else
            <div class="table-wrap">
                <table>
                    <thead>
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Pelanggan</th>
                            <th>Tanggal</th>
                            <th>Rincian Barang</th>
                            <th>Total</th>
                            <th>Alamat Pengiriman</th>
                            <th>Status</th>
                            <th>Perbarui Status</th>
                        </tr>
                    </thead>

                    <tbody>
                        @foreach ($orders as $order)
                            <tr>
                                <td>
                                    <strong>#{{ $order->id_order }}</strong>
                                </td>

                                <td>
                                    {{ $order->pelanggan->nama ?? 'Pelanggan' }}
                                    <br>
                                    <small class="muted">
                                        {{ $order->pelanggan->email ?? '-' }}
                                    </small>
                                </td>

                                <td>
                                    {{ $order->tanggal_order
                                        ? \Carbon\Carbon::parse($order->tanggal_order)->format('d-m-Y H:i')
                                        : '-' }}
                                </td>

                                <td class="items">
                                    @forelse ($order->orderDetail as $detail)
                                        <div>
                                            {{ $detail->sparePart->nama ?? 'Spare part dihapus' }}
                                            <br>
                                            <small class="muted">
                                                {{ $detail->jumlah }} ×
                                                Rp {{ number_format($detail->harga, 0, ',', '.') }}
                                            </small>
                                        </div>
                                    @empty
                                        <span class="muted">Tidak ada rincian barang</span>
                                    @endforelse
                                </td>

                                <td>
                                    <strong>
                                        Rp {{ number_format($order->total_harga, 0, ',', '.') }}
                                    </strong>
                                </td>

                                <td>{{ $order->alamat_pengiriman ?: '-' }}</td>

                                <td>
                                    <span class="badge
                                        {{ $order->status === 'Selesai' ? 'selesai' : '' }}
                                        {{ $order->status === 'Dibatalkan' ? 'dibatalkan' : '' }}">
                                        {{ $order->status }}
                                    </span>
                                </td>

                                <td>
                                    <form
                                        action="/admin-pesanan-sparepart/{{ $order->id_order }}/status"
                                        method="POST">

                                        @csrf
                                        @method('PUT')                       
@php
    $statusOptions = match ($order->status) {
        'Pending' => ['Pending', 'Diproses', 'Dibatalkan'],
        'Diproses' => ['Diproses', 'Dibatalkan'],
        default => [$order->status],
    };
@endphp

<select name="status" required>
    @foreach ($statusOptions as $status)
        <option
            value="{{ $status }}"
            {{ $order->status === $status ? 'selected' : '' }}
        >
            {{ $status }}
        </option>
    @endforeach
</select>


                                        <button type="submit">Simpan Status</button>
                                    </form>
                                </td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        @endif
    </section>
</main>

<footer>
    &copy; {{ date('Y') }} BengkelServis — Admin
</footer>

</body>
</html>
