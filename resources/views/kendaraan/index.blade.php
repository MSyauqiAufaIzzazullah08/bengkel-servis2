
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Kendaraan Saya | BengkelServis</title>

    <style>
        *{box-sizing:border-box;margin:0;padding:0}
        body{font-family:Arial,sans-serif;background:#f4f7fb;color:#172b4d}
        header{height:70px;background:#fff;display:flex;justify-content:space-between;align-items:center;padding:0 5%;border-bottom:1px solid #e2e8f0}
        .logo{font-size:20px;font-weight:bold;color:#087f73}
        a{text-decoration:none}
        .top-link{color:#087f73;font-size:13px;font-weight:bold}
        main{max-width:1200px;margin:35px auto;padding:0 22px}
        .heading{display:flex;justify-content:space-between;align-items:center;gap:16px;margin-bottom:25px}
        h1{font-size:27px;margin-bottom:8px}
        .subtitle{font-size:14px;color:#718096;line-height:1.6}
        .btn{display:inline-block;background:#087f73;color:white;padding:12px 17px;border-radius:8px;font-size:13px;font-weight:bold}
        .message{padding:14px 16px;background:#dff7e8;color:#166534;border-radius:9px;margin-bottom:18px;font-size:14px}
        .error{background:#fff0f0;color:#b42318}
        .grid{display:grid;grid-template-columns:repeat(3,minmax(0,1fr));gap:20px}
        .card{background:white;border:1px solid #e2e8f0;border-radius:14px;overflow:hidden;box-shadow:0 4px 15px #172b4d08}
        .vehicle-photo{width:100%;height:210px;object-fit:cover;display:block;background:#e2e8f0}
        .photo-placeholder{height:210px;display:flex;align-items:center;justify-content:center;background:#e0f7f3;font-size:65px}
        .content{padding:22px}
        .plate{display:inline-block;background:#f1f5f9;padding:9px 12px;border-radius:7px;font-weight:bold;font-size:15px;margin:12px 0}
        .detail{font-size:13px;color:#718096;line-height:1.8}
        .actions{display:flex;justify-content:space-between;align-items:center;gap:10px;margin-top:18px;border-top:1px solid #edf1f5;padding-top:15px}
        .delete{border:0;background:#fff0f0;color:#b42318;padding:9px 11px;border-radius:7px;cursor:pointer;font-size:12px}
        .empty{background:#fff;border:1px dashed #cbd5e1;border-radius:14px;text-align:center;padding:55px 20px;grid-column:1/-1}
        .empty .emoji{font-size:40px;margin-bottom:15px}
        .empty p{color:#718096;font-size:14px;margin:10px 0 20px}
        footer{text-align:center;padding:30px;color:#94a3b8;font-size:12px}
        @media(max-width:850px){.grid{grid-template-columns:repeat(2,minmax(0,1fr))}}
        @media(max-width:560px){.grid{grid-template-columns:1fr}.heading{align-items:flex-start;flex-direction:column}h1{font-size:23px}}
    </style>
</head>
<body>
<header>
    <div class="logo">🔧 BengkelServis</div>
    <a class="top-link" href="/dashboard-pelanggan">← Dashboard</a>
</header>

<main>
    <div class="heading">
        <div>
            <h1>Kendaraan Saya</h1>
            <p class="subtitle">Lihat foto dan kelola kendaraan yang kamu daftarkan.</p>
        </div>
        <a class="btn" href="/kendaraan-pelanggan/create">+ Tambah Kendaraan</a>
    </div>

    @if(session('success'))
        <div class="message">{{ session('success') }}</div>
    @endif

    @if(session('error'))
        <div class="message error">{{ session('error') }}</div>
    @endif

    <div class="grid">
        @forelse($kendaraans as $kendaraan)
            <article class="card">
                @if($kendaraan->foto && \Illuminate\Support\Facades\Storage::disk('public')->exists($kendaraan->foto))
                    <img class="vehicle-photo"
                         src="{{ asset('storage/' . $kendaraan->foto) }}"
                         alt="Foto {{ $kendaraan->merk }} {{ $kendaraan->model }}">
                @else
                    <div class="photo-placeholder" aria-label="Foto belum tersedia">🚘</div>
                @endif

                <div class="content">
                    <h2>{{ $kendaraan->merk }} {{ $kendaraan->model }}</h2>
                    <div class="plate">{{ $kendaraan->nopol }}</div>

                    <p class="detail">
                        Tahun kendaraan: <strong>{{ $kendaraan->tahun }}</strong><br>
                        Pemilik: {{ $pelanggan->nama }}
                    </p>

                    <div class="actions">
                        <a class="top-link" href="/booking-pelanggan/create">Booking Servis →</a>

                        <form action="/kendaraan-pelanggan/{{ $kendaraan->id_kendaraan }}"
                              method="POST"
                              onsubmit="return confirm('Yakin ingin menghapus kendaraan ini?')">
                            @csrf
                            @method('DELETE')
                            <button class="delete" type="submit">Hapus</button>
                        </form>
                    </div>
                </div>
            </article>
        @empty
            <div class="empty">
                <div class="emoji">🚗</div>
                <h2>Belum Ada Kendaraan</h2>
                <p>Tambahkan kendaraan beserta fotonya agar mudah dikenali.</p>
                <a class="btn" href="/kendaraan-pelanggan/create">Tambah Kendaraan Pertama</a>
            </div>
        @endforelse
    </div>
</main>

<footer>© {{ date('Y') }} BengkelServis — Portal Pelanggan</footer>
</body>
</html>
