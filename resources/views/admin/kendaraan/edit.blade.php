
@extends('layouts.app')

@section('title', 'Edit Kendaraan')

@section('content')
<div class="page-header">
    <div>
        <h1 class="page-title">Edit Kendaraan</h1>
        <p class="page-subtitle">Ubah data kendaraan pelanggan.</p>
    </div>
</div>

<a href="/kendaraan" class="btn btn-secondary" style="margin-bottom:20px">
    <i class="fas fa-arrow-left"></i> Kembali
</a>

<div class="card">
    <div class="card-header">
        <h3 class="card-title">
            <i class="fas fa-edit"></i> Form Edit Kendaraan
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

        <form action="/kendaraan/{{ $kendaraan->id_kendaraan }}" method="POST">
            @csrf
            @method('PUT')

            <div class="form-group">
                <label for="id_pelanggan">Pemilik / Pelanggan <span style="color:red">*</span></label>
                <select name="id_pelanggan" id="id_pelanggan" class="form-control" required>
                    <option value="">-- Pilih Pelanggan --</option>
                    @foreach ($pelanggan as $p)
                        <option value="{{ $p->id_pelanggan }}"
                            {{ old('id_pelanggan', $kendaraan->id_pelanggan) == $p->id_pelanggan ? 'selected' : '' }}>
                            {{ $p->nama }} - {{ $p->no_hp }}
                        </option>
                    @endforeach
                </select>
            </div>

            <div class="form-group">
                <label for="merk">Merek Kendaraan <span style="color:red">*</span></label>
                <input type="text" name="merk" id="merk" class="form-control"
                    value="{{ old('merk', $kendaraan->merk) }}" required>
            </div>

            <div class="form-group">
                <label for="model">Model Kendaraan <span style="color:red">*</span></label>
                <input type="text" name="model" id="model" class="form-control"
                    value="{{ old('model', $kendaraan->model) }}" required>
            </div>

            <div class="form-group">
                <label for="tahun">Tahun <span style="color:red">*</span></label>
                <input type="number" name="tahun" id="tahun" class="form-control"
                    value="{{ old('tahun', $kendaraan->tahun) }}" min="1900" max="2100" required>
            </div>

            <div class="form-group">
                <label for="nopol">Nomor Polisi <span style="color:red">*</span></label>
                <input type="text" name="nopol" id="nopol" class="form-control"
                    value="{{ old('nopol', $kendaraan->nopol) }}" required>
            </div>

            <button type="submit" class="btn btn-primary">
                <i class="fas fa-save"></i> Update Kendaraan
            </button>

            <a href="/kendaraan" class="btn btn-secondary">Batal</a>
        </form>
    </div>
</div>
@endsection
