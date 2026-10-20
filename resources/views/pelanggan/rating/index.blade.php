<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Rating & Ulasan | BengkelServis</title>
    <style>
        * { box-sizing: border-box; }
        :root { --navy:#102b55; --orange:#f5a623; --bg:#f3f6fc; --border:#d9e3f2; --muted:#71829d; }
        body { margin:0; font-family:Arial,sans-serif; color:var(--navy); background:var(--bg); font-size:14px; }
        header { background:white; border-bottom:1px solid var(--border); padding:18px 7%; display:flex; justify-content:space-between; align-items:center; }
        .logo { text-decoration:none; font-weight:bold; color:var(--navy); font-size:20px; }
        .logo span { color:var(--orange); }
        .back { color:var(--navy); text-decoration:none; font-size:12px; font-weight:bold; }
        .container { width:min(1000px,92%); margin:32px auto; }
        .breadcrumb { color:var(--muted); font-size:12px; margin-bottom:10px; }
        h1 { font-size:26px; margin:0 0 8px; }
        h2 { font-size:17px; margin:0 0 18px; }
        .subtitle { color:var(--muted); line-height:1.6; margin:0 0 24px; }
        .hero { background:linear-gradient(110deg,#102b55,#244b83); color:white; padding:25px; border-radius:13px; margin-bottom:25px; }
        .hero h2 { margin-bottom:8px; }
        .hero p { margin:0; color:#e1e9f6; line-height:1.7; }
        .layout { display:grid; grid-template-columns:1fr 1fr; gap:22px; align-items:start; }
        .card { background:white; border:1px solid var(--border); border-radius:12px; padding:23px; box-shadow:0 4px 14px #102b5508; margin-bottom:20px; }
        label { display:block; font-weight:bold; margin:16px 0 7px; font-size:13px; }
        input,select,textarea { width:100%; padding:12px; border:1px solid #cbd7e8; border-radius:7px; background:white; color:var(--navy); font:inherit; }
        textarea { min-height:110px; resize:vertical; }
        input:focus,select:focus,textarea:focus { outline:2px solid #f5a62355; border-color:var(--orange); }
        .stars { display:flex; gap:8px; margin:8px 0; }
        .stars label { margin:0; cursor:pointer; }
        .stars input { position:absolute; opacity:0; width:1px; }
        .stars span { font-size:32px; color:#d4dbe6; cursor:pointer; }
        .stars label:has(input:checked) span { color:var(--orange); }
        .btn { display:inline-block; border:0; border-radius:7px; padding:12px 16px; font-weight:bold; cursor:pointer; text-decoration:none; font-size:13px; }
        .btn-primary { background:var(--navy); color:white; width:100%; margin-top:18px; }
        .btn-delete { background:#fff1f2; color:#be123c; border:1px solid #fecdd3; padding:8px 10px; }
        .message { padding:13px; border-radius:8px; margin-bottom:18px; line-height:1.5; }
        .success { background:#dcfce7; color:#166534; }
        .error { background:#fee2e2; color:#991b1b; }
        .review { border:1px solid var(--border); padding:17px; border-radius:10px; margin-bottom:14px; background:white; }
        .review-top { display:flex; justify-content:space-between; gap:10px; align-items:start; }
        .review h3 { margin:0 0 7px; font-size:15px; }
        .rating-stars { color:var(--orange); font-size:19px; letter-spacing:2px; }
        .date { color:var(--muted); font-size:11px; margin-top:9px; }
        .empty { text-align:center; padding:30px 12px; border:1px dashed #cbd7e8; border-radius:10px; color:var(--muted); line-height:1.7; }
        .hint { color:var(--muted); font-size:12px; line-height:1.6; }
        footer { text-align:center; padding:25px; color:var(--muted); font-size:12px; }
        @media (max-width:720px) { .layout { grid-template-columns:1fr; } header { padding:16px 5%; } .container { margin-top:24px; } .card { padding:18px; } h1 { font-size:23px; } }
    </style>
</head>
<body>
<header>
    <a href="/dashboard-pelanggan" class="logo">🔧 Bengkel<span>Servis</span></a>
    <a href="/dashboard-pelanggan" class="back">← Dashboard Pelanggan</a>
</header>

<main class="container">
    <div class="breadcrumb">Beranda / Rating & Ulasan</div>
    <h1>Rating & Ulasan</h1>
    <p class="subtitle">Bagikan pengalamanmu setelah servis kendaraan atau menerima pesanan spare part.</p>

    <section class="hero">
        <h2>⭐ Pendapatmu Berarti bagi Kami</h2>
        <p>Ulasanmu membantu BengkelServis meningkatkan kualitas pelayanan. Kamu bisa memberikan penilaian setelah transaksi selesai.</p>
    </section>

    @if (session('success'))
        <div class="message success">{{ session('success') }}</div>
    @endif
    @if (session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif
    @if ($errors->any())
        <div class="message error">
            <strong>Periksa kembali data berikut:</strong>
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    <div class="layout">
        <section class="card">
            <h2>✍️ Tulis Ulasan</h2>

            @if ($booking->isEmpty() && $homeServices->isEmpty() && $orders->isEmpty())
                <div class="empty">
                    <div style="font-size:32px">⭐</div>
                    <strong>Belum Ada Transaksi untuk Dinilai</strong>
                    <p class="hint">Transaksi yang sudah selesai dan belum diberi rating akan muncul di sini.</p>
                </div>
            @else
                <form action="/rating-pelanggan" method="POST">
                    @csrf
                    <label for="jenis_transaksi">Jenis Transaksi</label>
                    <select name="jenis_transaksi" id="jenis_transaksi" required>
                        <option value="">-- Pilih jenis transaksi --</option>
                        @if ($booking->isNotEmpty())
                            <option value="booking" {{ old('jenis_transaksi') === 'booking' ? 'selected' : '' }}>Booking Servis</option>
                        @endif
                        @if ($homeServices->isNotEmpty())
                            <option value="home_service" {{ old('jenis_transaksi') === 'home_service' ? 'selected' : '' }}>Home Service</option>
                        @endif
                        @if ($orders->isNotEmpty())
                            <option value="order" {{ old('jenis_transaksi') === 'order' ? 'selected' : '' }}>Pesanan Spare Part</option>
                        @endif
                    </select>

                    <label for="id_transaksi">Transaksi</label>
                    <select name="id_transaksi" id="id_transaksi" required>
                        <option value="">-- Pilih transaksi --</option>
                        @foreach ($booking as $item)
                            <option data-jenis="booking" value="{{ $item->id_booking }}" {{ (string) old('id_transaksi') === (string) $item->id_booking && old('jenis_transaksi') === 'booking' ? 'selected' : '' }}>
                                Booking #{{ $item->id_booking }} — {{ $item->tanggal_booking }}
                            </option>
                        @endforeach
                        @foreach ($homeServices as $item)
                            <option data-jenis="home_service" value="{{ $item->id_home_service }}" {{ (string) old('id_transaksi') === (string) $item->id_home_service && old('jenis_transaksi') === 'home_service' ? 'selected' : '' }}>
                                Home Service #{{ $item->id_home_service }} — {{ $item->tanggal }}
                            </option>
                        @endforeach
                        @foreach ($orders as $item)
                            <option data-jenis="order" value="{{ $item->id_order }}" {{ (string) old('id_transaksi') === (string) $item->id_order && old('jenis_transaksi') === 'order' ? 'selected' : '' }}>
                                Pesanan Spare Part #{{ $item->id_order }} — {{ $item->tanggal_order }}
                            </option>
                        @endforeach
                    </select>

                    <label>Rating</label>
                    <div class="stars" role="radiogroup" aria-label="Pilih rating">
                        @for ($i = 5; $i >= 1; $i--)
                            <label title="{{ $i }} bintang">
                                <input type="radio" name="nilai" value="{{ $i }}" {{ (int) old('nilai', 5) === $i ? 'checked' : '' }} required>
                                <span>★</span>
                            </label>
                        @endfor
                    </div>
                    <p class="hint">Pilih 1 sampai 5 bintang sesuai pengalamanmu.</p>

                    <label for="ulasan">Ulasan (Opsional)</label>
                    <textarea name="ulasan" id="ulasan" maxlength="1000" placeholder="Ceritakan pengalamanmu...">{{ old('ulasan') }}</textarea>

                    <button type="submit" class="btn btn-primary">Kirim Rating & Ulasan →</button>
                </form>
            @endif
        </section>

        <section>
            <h2>Riwayat Ulasan Saya</h2>
            @forelse ($rating as $item)
                <article class="review">
                    <div class="review-top">
                        <div>
                            <h3>
                                @if ($item->id_booking)
                                    Booking Servis #{{ $item->id_booking }}
                                @elseif ($item->id_home_service)
                                    Home Service #{{ $item->id_home_service }}
                                @elseif ($item->id_order)
                                    Pesanan Spare Part #{{ $item->id_order }}
                                @else
                                    Ulasan Pelanggan
                                @endif
                            </h3>
                            <div class="rating-stars">{{ str_repeat('★', (int) $item->nilai) }}<span style="color:#d4dbe6">{{ str_repeat('★', 5 - (int) $item->nilai) }}</span></div>
                        </div>
                        <form action="/rating-pelanggan/{{ $item->id_rating }}" method="POST" onsubmit="return confirm('Yakin ingin menghapus ulasan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="btn btn-delete" type="submit">Hapus</button>
                        </form>
                    </div>
                    <p>{{ $item->ulasan ?: 'Tidak ada komentar tertulis.' }}</p>
                    <div class="date">{{ $item->tanggal ? \Carbon\Carbon::parse($item->tanggal)->format('d M Y, H:i') : '-' }}</div>
                </article>
            @empty
                <div class="empty">
                    <div style="font-size:32px">💬</div>
                    <strong>Belum Ada Ulasan</strong>
                    <p>Ulasan yang sudah kamu kirim akan ditampilkan di sini.</p>
                </div>
            @endforelse
        </section>
    </div>
</main>

<footer>&copy; {{ date('Y') }} BengkelServis — Portal Pelanggan</footer>

<script>
    const jenis = document.getElementById('jenis_transaksi');
    const transaksi = document.getElementById('id_transaksi');

    function filterTransaksi() {
        if (!jenis || !transaksi) return;
        const pilihan = jenis.value;

        Array.from(transaksi.options).forEach(option => {
            if (!option.value) {
                option.hidden = false;
                return;
            }
            option.hidden = pilihan !== '' && option.dataset.jenis !== pilihan;
        });

        if (transaksi.selectedOptions.length && transaksi.selectedOptions[0].hidden) {
            transaksi.value = '';
        }
    }

    jenis?.addEventListener('change', filterTransaksi);
    filterTransaksi();
</script>
</body>
</html>
