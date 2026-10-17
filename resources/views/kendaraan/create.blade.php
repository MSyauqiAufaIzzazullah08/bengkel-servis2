
<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Tambah Kendaraan | BengkelServis</title>

    <style>
        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            font-family: Arial, sans-serif;
            background: #f3f7fb;
            color: #172b4d;
        }

        .navbar {
            background: white;
            padding: 18px 7%;
            border-bottom: 1px solid #e2e8f0;
            font-weight: bold;
            color: #087f72;
        }

        .container {
            max-width: 650px;
            margin: 35px auto;
            padding: 0 18px;
        }

        .card {
            background: white;
            padding: 28px;
            border: 1px solid #e0e7ef;
            border-radius: 14px;
            box-shadow: 0 8px 25px rgba(20, 40, 70, 0.05);
        }

        h2 {
            margin: 0 0 8px;
            font-size: 23px;
        }

        .subtitle {
            margin-bottom: 24px;
            color: #718096;
            font-size: 13px;
            line-height: 1.6;
        }

        .form-group {
            margin-bottom: 17px;
        }

        label {
            display: block;
            margin-bottom: 8px;
            font-size: 13px;
            font-weight: bold;
        }

        input[type="text"],
        input[type="number"],
        input[type="file"] {
            width: 100%;
            padding: 12px;
            border: 1px solid #cbd5e1;
            border-radius: 8px;
            font-size: 14px;
            background: white;
        }

        input:focus {
            outline: 2px solid #b7eee4;
            border-color: #087f72;
        }

        .photo-box {
            border: 2px dashed #b8c9d8;
            border-radius: 12px;
            padding: 18px;
            text-align: center;
            background: #f8fbfd;
        }

        .photo-icon {
            font-size: 38px;
            margin-bottom: 8px;
        }

        .photo-help {
            font-size: 12px;
            color: #718096;
            margin: 8px 0 14px;
        }

        #preview {
            display: none;
            width: 100%;
            max-height: 260px;
            object-fit: contain;
            margin-top: 16px;
            border-radius: 10px;
            background: #eaf0f5;
        }

        .error {
            color: #dc2626;
            font-size: 12px;
            margin-top: 6px;
        }

        .alert {
            padding: 12px;
            border-radius: 8px;
            margin-bottom: 18px;
            background: #fff1f2;
            color: #be123c;
            font-size: 13px;
        }

        .btn {
            display: block;
            width: 100%;
            padding: 13px;
            border: none;
            border-radius: 8px;
            background: #087f72;
            color: white;
            font-size: 14px;
            font-weight: bold;
            cursor: pointer;
            text-align: center;
            text-decoration: none;
        }

        .btn:hover {
            background: #06665d;
        }

        .back {
            display: block;
            margin-top: 18px;
            text-align: center;
            text-decoration: none;
            color: #087f72;
            font-size: 13px;
        }

        @media (max-width: 480px) {
            .card {
                padding: 20px;
            }
        }
    </style>
</head>
<body>

    <header class="navbar">
        🔧 BengkelServis
    </header>

    <main class="container">
        <section class="card">
            <h2>Tambah Kendaraan</h2>
            <p class="subtitle">
                Lengkapi identitas kendaraan dan tambahkan foto agar
                kendaraanmu mudah dikenali saat melakukan booking servis.
            </p>

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

            <form action="{{ url('/kendaraan-pelanggan') }}"
                  method="POST"
                  enctype="multipart/form-data">

                @csrf

                <div class="form-group">
                    <label for="merk">Merek Kendaraan</label>
                    <input
                        type="text"
                        id="merk"
                        name="merk"
                        value="{{ old('merk') }}"
                        placeholder="Contoh: Toyota"
                        maxlength="100"
                        required>
                    @error('merk')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="model">Model Kendaraan</label>
                    <input
                        type="text"
                        id="model"
                        name="model"
                        value="{{ old('model') }}"
                        placeholder="Contoh: Avanza"
                        maxlength="100"
                        required>
                    @error('model')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="tahun">Tahun Kendaraan</label>
                    <input
                        type="number"
                        id="tahun"
                        name="tahun"
                        value="{{ old('tahun') }}"
                        placeholder="Contoh: 2022"
                        min="1900"
                        max="{{ date('Y') + 1 }}"
                        required>
                    @error('tahun')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="nopol">Nomor Polisi</label>
                    <input
                        type="text"
                        id="nopol"
                        name="nopol"
                        value="{{ old('nopol') }}"
                        placeholder="Contoh: BL 1234 AA"
                        maxlength="20"
                        required>
                    @error('nopol')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <div class="form-group">
                    <label for="foto">Foto Kendaraan (Opsional)</label>

                    <div class="photo-box">
                        <div class="photo-icon">🚘</div>
                        <strong>Pilih foto kendaraanmu</strong>
                        <p class="photo-help">
                            Format JPG, JPEG, PNG, atau WEBP. Maksimal 2 MB.
                        </p>

                        <input
                            type="file"
                            id="foto"
                            name="foto"
                            accept=".jpg,.jpeg,.png,.webp,image/jpeg,image/png,image/webp">

                        <img id="preview" alt="Preview foto kendaraan">
                    </div>

                    @error('foto')
                        <div class="error">{{ $message }}</div>
                    @enderror
                </div>

                <button type="submit" class="btn">
                    Simpan Kendaraan
                </button>

                <a href="{{ url('/kendaraan-pelanggan') }}" class="back">
                    ← Kembali ke Kendaraan Saya
                </a>
            </form>
        </section>
    </main>

    <script>
        const inputFoto = document.getElementById('foto');
        const preview = document.getElementById('preview');

        inputFoto.addEventListener('change', function () {
            const file = this.files[0];

            if (!file) {
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

            preview.src = URL.createObjectURL(file);
            preview.style.display = 'block';
        });
    </script>

</body>
</html>
