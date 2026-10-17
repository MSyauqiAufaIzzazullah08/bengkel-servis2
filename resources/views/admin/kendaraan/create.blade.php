
@extends('layouts.app')

@section('title', 'Tambah Kendaraan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Tambah Kendaraan</h1>
        <p class="page-subtitle">Daftarkan kendaraan milik pelanggan.</p>
    </div>
</div>

<a href="/kendaraan" class="btn btn-secondary" style="margin-bottom:20px">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-car"></i> Form Tambah Kendaraan
        </h3>
    </div>

    <div style="padding:24px">
        @if ($errors->any())
            <div class="alert alert-danger">
                <ul>
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
            </div>
        @endif

        <form action="/kendaraan" method="POST">
            @csrf

            <div class="form-group">
                <label for="id_pelanggan">Pemilik / Pelanggan <span style="color:red">*</span></label>
                <select name="id_pelanggan" id="id_pelanggan" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id_pelanggan }}"
                            {{ old('id_pelanggan') == $p->id_pelanggan ? 'selected' : '' }}>
                            {{ $p->nama }} - {{ $p->no_hp }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="merk">Merek Kendaraan <span style="color:red">*</span></label>
                <input type="text" name="merk" id="merk" class="form-control"
                    value="{{ old('merk') }}" placeholder="Contoh: Toyota" required>
            </div>

            <div class="form-group">
                <label for="model">Model Kendaraan <span style="color:red">*</span></label>
                <input type="text" name="model" id="model" class="form-control"
                    value="{{ old('model') }}" placeholder="Contoh: Avanza" required>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun <span style="color:red">*</span></label>
                <input type="number" name="tahun" id="tahun" class="form-control"
                    value="{{ old('tahun') }}" min="1900" max="2100" required>
            </div>

            <div class="form-group">
                <label for="nopol">Nomor Polisi <span style="color:red">*</span></label>
                <input type="text" name="nopol" id="nopol" class="form-control"
                    value="{{ old('nopol') }}" placeholder="Contoh: BL1234AA" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Simpan Kendaraan
            </button>

            <a href="/kendaraan" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
