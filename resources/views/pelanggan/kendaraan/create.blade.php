
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan | BengkelServis</title>

    <style>
        :root {
            --navy: #102B55;
            --navy-light: #173B75;
            --orange: #F5A623;
            --orange-light: #FFF2D9;
            --background: #F4F7FC;
            --text: #172033;
            --muted: #66758C;
            --border: #DCE4EF;
            --white: #FFFFFF;
        }

        * {
            box-sizing: border-box;
            margin: 0;
            padding: 0;
        }

        body {
            font-family: Arial, sans-serif;
            background: var(--background);
            color: var(--text);
            line-height: 1.6;
        }

        a {
            text-decoration: none;
        }

        header {
            min-height: 72px;
            background: white;
            border-bottom: 1px solid var(--border);
            display: flex;
            align-items: center;
            justify-content: space-between;
            padding: 16px 6%;
            gap: 15px;
        }

        .logo {
            font-size: 21px;
            font-weight: 800;
            color: var(--navy);
        }

        .logo span {
            color: var(--orange);
        }

        .top-link {
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .top-link:hover {
            color: #A45F00;
        }

        main {
            max-width: 1050px;
            margin: 35px auto;
            padding: 0 20px;
        }

        .breadcrumb {
            color: var(--muted);
            font-size: 12px;
            margin-bottom: 18px;
        }

        .page-heading {
            margin-bottom: 24px;
        }

        .page-heading h1 {
            color: var(--navy);
            font-size: 28px;
            margin-bottom: 8px;
        }

        .page-heading p {
            color: var(--muted);
            font-size: 14px;
        }

        .layout {
            display: grid;
            grid-template-columns: minmax(0, 1fr) 280px;
            gap: 22px;
            align-items: start;
        }

        .card {
            background: white;
            border: 1px solid var(--border);
            border-radius: 15px;
            padding: 28px;
            box-shadow: 0 5px 20px rgba(16,43,85,.04);
        }

        .card-heading {
            display: flex;
            align-items: center;
            gap: 13px;
            padding-bottom: 20px;
            margin-bottom: 22px;
            border-bottom: 1px solid var(--border);
        }

        .heading-icon {
            width: 47px;
            height: 47px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: var(--orange-light);
            border-radius: 12px;
            font-size: 23px;
        }

        .card-heading h2 {
            color: var(--navy);
            font-size: 18px;
        }

        .card-heading p {
            color: var(--muted);
            font-size: 12px;
            margin-top: 4px;
        }

        .form-group {
            margin-bottom: 19px;
        }

        label {
            display: block;
            margin-bottom: 7px;
            color: var(--navy);
            font-size: 13px;
            font-weight: bold;
        }

        .required {
            color: #C2410C;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 12px 13px;
            border: 1px solid #CBD5E1;
            border-radius: 8px;
            font-size: 13px;
            font-family: inherit;
            color: var(--text);
            background: white;
            transition: border-color .2s, box-shadow .2s;
        }

        input:focus {
            outline: none;
            border-color: var(--orange);
            box-shadow: 0 0 0 3px rgba(245,166,35,.18);
        }

        .field-help {
            margin-top: 6px;
            color: var(--muted);
            font-size: 11px;
        }

        .photo-box {
            border: 2px dashed #C4D0E0;
            border-radius: 12px;
            padding: 22px;
            text-align: center;
            background: #F8FAFD;
        }

        .photo-icon {
            width: 60px;
            height: 60px;
            margin: 0 auto 12px;
            display: grid;
            place-items: center;
            background: var(--orange-light);
            border-radius: 16px;
            font-size: 30px;
        }

        .photo-box strong {
            display: block;
            color: var(--navy);
            font-size: 14px;
        }

        .photo-help {
            font-size: 12px;
            color: var(--muted);
            margin: 7px 0 16px;
        }

        input[type="file"] {
            background: white;
            text-align: left;
        }

        input[type="file"]::file-selector-button {
            padding: 8px 12px;
            margin-right: 10px;
            border: 0;
            border-radius: 6px;
            background: var(--orange-light);
            color: var(--navy);
            font-weight: bold;
            cursor: pointer;
        }

        #preview {
            display: none;
            width: 100%;
            max-height: 300px;
            object-fit: contain;
            margin-top: 18px;
            border-radius: 10px;
            background: #E8EEF6;
            border: 1px solid var(--border);
        }

        .error {
            color: #B91C1C;
            font-size: 12px;
            margin-top: 6px;
        }

        .alert {
            padding: 13px 15px;
            border-radius: 9px;
            margin-bottom: 18px;
            background: #FEE2E2;
            color: #991B1B;
            border: 1px solid #FECACA;
            font-size: 13px;
        }

        .alert ul {
            padding-left: 20px;
            margin-top: 6px;
        }

        .form-actions {
            display: flex;
            gap: 12px;
            margin-top: 25px;
        }

        .btn {
            display: inline-flex;
            align-items: center;
            justify-content: center;
            padding: 12px 16px;
            border: 1px solid transparent;
            border-radius: 8px;
            font-family: inherit;
            font-size: 13px;
            font-weight: bold;
            cursor: pointer;
            transition: .2s;
        }

        .btn-primary {
            flex: 1;
            background: var(--navy);
            color: white;
        }

        .btn-primary:hover {
            background: var(--navy-light);
        }

        .btn-secondary {
            background: white;
            color: var(--navy);
            border-color: var(--border);
        }

        .btn-secondary:hover {
            background: var(--background);
        }

        .side-info {
            background: linear-gradient(145deg, var(--navy), var(--navy-light));
            color: white;
            border-radius: 14px;
            padding: 24px;
            margin-bottom: 17px;
        }

        .side-info .icon {
            font-size: 30px;
            margin-bottom: 12px;
        }

        .side-info h3 {
            font-size: 17px;
            margin-bottom: 9px;
        }

        .side-info p {
            color: #E1EAF8;
            font-size: 12px;
            line-height: 1.8;
        }

        .tips {
            background: white;
            border: 1px solid var(--border);
            border-radius: 14px;
            padding: 22px;
        }

        .tips h3 {
            color: var(--navy);
            font-size: 15px;
            margin-bottom: 15px;
        }

        .tip {
            display: flex;
            gap: 10px;
            margin-bottom: 14px;
            align-items: flex-start;
        }

        .tip:last-child {
            margin-bottom: 0;
        }

        .tip-icon {
            width: 29px;
            height: 29px;
            display: grid;
            place-items: center;
            flex-shrink: 0;
            background: var(--orange-light);
            border-radius: 8px;
        }

        .tip p {
            color: var(--muted);
            font-size: 12px;
            line-height: 1.7;
        }

        footer {
            text-align: center;
            padding: 28px 15px;
            color: var(--muted);
            font-size: 12px;
        }

        @media (max-width: 800px) {
            .layout {
                grid-template-columns: 1fr;
            }

            .side-panel {
                display: grid;
                grid-template-columns: repeat(2, minmax(0, 1fr));
                gap: 15px;
            }

            .side-info {
                margin-bottom: 0;
            }
        }

        @media (max-width: 560px) {
            header {
                padding: 15px 18px;
            }

            .logo {
                font-size: 18px;
            }

            main {
                margin: 25px auto;
                padding: 0 14px;
            }

            .page-heading h1 {
                font-size: 24px;
            }

            .card {
                padding: 20px;
            }

            .side-panel {
                grid-template-columns: 1fr;
            }

            .form-actions {
                flex-direction: column;
            }

            .btn {
                width: 100%;
            }
        }
    </style>
</head>

<body>

<header>
    <a href="/dashboard-pelanggan" class="logo">
        🔧 <span>Bengkel</span>Servis
    </a>

    <a class="top-link" href="/kendaraan-pelanggan">
        ← Kendaraan Saya
    </a>
</header>

<main>
    <div class="breadcrumb">Beranda / Kendaraan / Tambah Kendaraan</div>

    <div class="page-heading">
        <h1>Tambah Kendaraan</h1>
        <p>
            Daftarkan kendaraanmu agar lebih mudah digunakan saat booking servis.
        </p>
    </div>

    <div class="layout">
        <section class="card">
            <div class="card-heading">
                <div class="heading-icon">🚘</div>
                <div>
                    <h2>Identitas Kendaraan</h2>
                    <p>Isi data kendaraan sesuai informasi sebenarnya.</p>
                </div>
            </div>

            @if ($errors->any())
                <div class="alert">
                    <strong>Periksa kembali data kamu:</strong>
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            @if (session('error'))
                <div class="alert">{{ session('error') }}</div>
            @endif

            <form
                action="{{ url('/kendaraan-pelanggan') }}"
                method="POST"
                enctype="multipart/form-data"
            >
                @csrf

                <div class="form-group">
                    <label for="merk">
                        Merek Kendaraan <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="merk"
                        name="merk"
                        value="{{ old('merk') }}"
                        placeholder="Contoh: Toyota"
                        maxlength="100"
                        required
                    >
                    @error('merk')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="model">
                        Model Kendaraan <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="model"
                        name="model"
                        value="{{ old('model') }}"
                        placeholder="Contoh: Avanza"
                        maxlength="100"
                        required
                    >
                    @error('model')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tahun">
                        Tahun Kendaraan <span class="required">*</span>
                    </label>
                    <input
                        type="number"
                        id="tahun"
                        name="tahun"
                        value="{{ old('tahun') }}"
                        placeholder="Contoh: 2022"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        required
                    >
                    @error('tahun')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nopol">
                        Nomor Polisi <span class="required">*</span>
                    </label>
                    <input
                        type="text"
                        id="nopol"
                        name="nopol"
                        value="{{ old('nopol') }}"
                        placeholder="Contoh: BL 1234 AA"
                        maxlength="20"
                        required
                    >
                    @error('nopol')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto">Foto Kendaraan (Opsional)</label>

                    <div class="photo-box">
                        <div class="photo-icon">📷</div>
                        <strong>Pilih foto kendaraanmu</strong>

                        <p class="photo-help">
                            JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>

                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp"
                        >

                        <img id="preview" alt="Pratinjau foto kendaraan">
                    </div>

                    @error('foto')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-actions">
                    <a href="{{ url('/kendaraan-pelanggan') }}" class="btn btn-secondary">
                        ← Kembali
                    </a>

                    <button type="submit" class="btn btn-primary">
                        Simpan Kendaraan →
                    </button>
                </div>
            </form>
        </section>

        <aside class="side-panel">
            <div class="side-info">
                <div class="icon">🛠️</div>
                <h3>Kelola Kendaraanmu</h3>
                <p>
                    Simpan identitas kendaraan supaya kamu lebih mudah memilihnya
                    ketika membuat booking servis di BengkelServis.
                </p>
            </div>

            <div class="tips">
                <h3>Tips Mengisi Data</h3>

                <div class="tip">
                    <div class="tip-icon">✓</div>
                    <p>Masukkan merek dan model sesuai kendaraan yang kamu miliki.</p>
                </div>

                <div class="tip">
                    <div class="tip-icon">✓</div>
                    <p>Periksa kembali nomor polisi agar tidak salah saat booking.</p>
                </div>

                <div class="tip">
                    <div class="tip-icon">📷</div>
                    <p>Foto kendaraan bersifat opsional. Gunakan foto yang jelas jika tersedia.</p>
                </div>
            </div>
        </aside>
    </div>
</main>

<footer>
    © {{ date('Y') }} BengkelServis — Portal Pelanggan
</footer>

<script>
    const inputFoto = document.getElementById('foto');
    const preview = document.getElementById('preview');

    inputFoto.addEventListener('change', function () {
        const file = this.files[0];

        if (!file) {
            if (preview.dataset.url) {
                URL.revokeObjectURL(preview.dataset.url);
            }

            preview.removeAttribute('src');
            preview.style.display = 'none';
            return;
        }

        const tipeDiizinkan = [
            'image/jpeg',
            'image/png',
            'image/webp'
        ];

        if (!tipeDiizinkan.includes(file.type)) {
            alert('Pilih foto dengan format JPG, PNG, atau WEBP.');
            this.value = '';
            preview.removeAttribute('src');
            preview.style.display = 'none';
            return;
        }

        if (file.size > 2 * 1024 * 1024) {
            alert('Ukuran foto maksimal 2 MB.');
            this.value = '';
            preview.removeAttribute('src');
            preview.style.display = 'none';
            return;
        }

        if (preview.dataset.url) {
            URL.revokeObjectURL(preview.dataset.url);
        }

        const url = URL.createObjectURL(file);
        preview.dataset.url = url;
        preview.src = url;
        preview.style.display = 'block';
    });
</script>

</body>
</html>
