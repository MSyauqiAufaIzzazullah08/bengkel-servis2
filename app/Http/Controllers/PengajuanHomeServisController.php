<?php

namespace App\Http\Controllers;

use App\Models\Pelanggan;
use App\Models\User;
use App\Models\Kendaraan;
use App\Models\Cabang;
use App\Models\LayananServis;
use App\Models\HomeServis;
use Illuminate\Http\Request;

class PengajuanHomeServisController extends Controller
{
    
private function pelangganLogin(): Pelanggan
{
    abort_if((int) session('role_id') !== 6, 403);

    $email = session('user_email');

    if (!$email && session('user_id')) {
        $user = User::find(session('user_id'));
        $email = $user?->email;
    }

    $pelanggan = null;

    if ($email) {
        $pelanggan = (new Pelanggan)
            ->newQuery()
            ->where('email', $email)
            ->first();
    }

    if (!$pelanggan && session('user_nama')) {
        $pelanggan = (new Pelanggan)
            ->newQuery()
            ->where('nama', session('user_nama'))
            ->first();
    }

    abort_if(
        !$pelanggan,
        403,
        'Data pelanggan tidak ditemukan. Silakan login kembali.'
    );

    return $pelanggan;
}


    public function create()
    {
        $pelanggan = $this->pelangganLogin();

        $kendaraan = Kendaraan::where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )->get();

        $cabang = Cabang::orderBy('nama_cabang')->get();

        $layanan = LayananServis::orderBy('nama_layanan')->get();

        return view('pelanggan.home-service.create', compact(
            'pelanggan',
            'kendaraan',
            'cabang',
            'layanan'
        ));
    }

    public function store(Request $request)
    {
        $pelanggan = $this->pelangganLogin();

        $data = $request->validate([
            'id_kendaraan' => ['required', 'integer'],
            'id_cabang' => ['required', 'integer', 'exists:cabang,id_cabang'],
            'id_layanan_servis' => [
                'nullable',
                'integer',
                'exists:LayananServis,id_layanan',
            ],
            'alamat' => ['required', 'string', 'max:2000'],
            'tanggal' => [
                'required',
                'date',
                'after_or_equal:today',
            ],
            'catatan' => ['nullable', 'string', 'max:2000'],
        ], [
            'id_kendaraan.required' => 'Pilih kendaraan yang akan diservis.',
            'id_cabang.required' => 'Pilih cabang bengkel.',
            'alamat.required' => 'Alamat Home Service wajib diisi.',
            'tanggal.required' => 'Tanggal servis wajib dipilih.',
            'tanggal.after_or_equal' => 'Tanggal servis tidak boleh sebelum hari ini.',
        ]);

        $kendaraan = Kendaraan::where(
            'id_kendaraan',
            $data['id_kendaraan']
        )->where(
            'id_pelanggan',
            $pelanggan->id_pelanggan
        )->first();

        if (!$kendaraan) {
            return back()
                ->withInput()
                ->withErrors([
                    'id_kendaraan' =>
                        'Kendaraan yang dipilih tidak terdaftar pada akunmu.',
                ]);
        }

        HomeServis::create([
            'id_pelanggan' => $pelanggan->id_pelanggan,
            'id_kendaraan' => $kendaraan->id_kendaraan,
            'id_layanan_servis' => $data['id_layanan_servis'] ?? null,
            'id_cabang' => $data['id_cabang'],
            'id_montir' => null,
            'alamat' => $data['alamat'],
            'tanggal' => $data['tanggal'],
            'status' => 'Requested',
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect('/dashboard-pelanggan')->with(
            'success',
            'Pengajuan Home Service berhasil dikirim! Tunggu konfirmasi dari petugas.'
        );
    }
}
