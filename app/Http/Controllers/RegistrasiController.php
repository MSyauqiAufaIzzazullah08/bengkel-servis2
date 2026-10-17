<?php
namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Schema;

class RegistrasiController extends Controller
{
    public function showRegister()
    {
        return view('auth.register');
    }

    public function register(Request $request)
    {
        $validator = Validator::make($request->all(), [
            'nama' => 'required|string|max:100',
            'email' => 'required|email|max:100|unique:User,email',
            'no_hp' => 'required|string|max:20',
            'alamat' => 'required|string|max:255',
            'password' => 'required|string|min:8|confirmed',
        ], [
            'nama.required' => 'Nama lengkap wajib diisi.',
            'email.required' => 'Email wajib diisi.',
            'email.email' => 'Format email tidak valid.',
            'email.unique' => 'Email sudah digunakan.',
            'no_hp.required' => 'Nomor HP wajib diisi.',
            'alamat.required' => 'Alamat wajib diisi.',
            'password.required' => 'Password wajib diisi.',
            'password.min' => 'Password minimal 8 karakter.',
            'password.confirmed' => 'Konfirmasi password tidak cocok.',
        ]);

        if ($validator->fails()) {
            return back()
                ->withErrors($validator)
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]));
        }

        if (!DB::table('Role')->where('id_role', 6)->exists()) {
            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->with('error', 'Role Pelanggan belum tersedia.');
        }

        if (DB::table('Pelanggan')
            ->where('email', $request->email)
            ->exists()) {
            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->with('error', 'Email sudah terdaftar sebagai pelanggan.');
        }

        try {
            DB::transaction(function () use ($request) {
                DB::table('User')->insert([
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'password' => Hash::make($request->password),
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                    'role_id' => 6,
                    'status' => 'aktif',
                ]);

                $dataPelanggan = [
                    'nama' => $request->nama,
                    'email' => $request->email,
                    'no_hp' => $request->no_hp,
                    'alamat' => $request->alamat,
                ];

                if (Schema::hasColumn('Pelanggan', 'tanggal_daftar')) {
                    $dataPelanggan['tanggal_daftar'] = now()->toDateString();
                }

                DB::table('Pelanggan')->insert($dataPelanggan);
            });

            return redirect('/login')
                ->with('success', 'Registrasi berhasil! Silakan login dengan akun baru Anda.');

        } catch (\Throwable $e) {
            report($e);

            return back()
                ->withInput($request->except([
                    'password',
                    'password_confirmation',
                ]))
                ->with('error', 'Registrasi gagal disimpan. Periksa struktur tabel database dan coba kembali.');
        }
    }
}
