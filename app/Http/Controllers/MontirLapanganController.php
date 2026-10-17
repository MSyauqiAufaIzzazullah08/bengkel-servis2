<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\MontirLapangan;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class MontirLapanganController extends Controller
{
    /**
     * Mendapatkan cabang Admin Cabang yang sedang login.
     * Super Admin tidak dibatasi cabang.
     */
    private function cabangAdmin()
    {
        if ((int) session('role_id') !== 2) {
            return null;
        }

        $user = User::with('adminCabang')
            ->findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        return (int) $cabangId;
    }

    /**
     * Query montir sesuai hak akses cabang.
     */
    private function queryMontir()
    {
        $query = MontirLapangan::with([
            'user.role',
            'homeServis'
        ]);

        $cabangId = $this->cabangAdmin();

        if ($cabangId !== null) {
            $query->whereHas('user', function ($user) use ($cabangId) {
                $user->where('cabang_id', $cabangId)
                     ->where('role_id', 5);
            });
        }

        return $query;
    }

    /**
     * Daftar akun yang boleh dipilih untuk profil montir.
     */
    private function queryUserMontir()
    {
        $query = User::where('role_id', 5);

        $cabangId = $this->cabangAdmin();

        if ($cabangId !== null) {
            $query->where('cabang_id', $cabangId);
        }

        return $query->whereNotIn(
            'id_user',
            MontirLapangan::select('user_id')
        )->orderBy('nama');
    }

    public function index()
    {
        $montir = $this->queryMontir()->get();

        return view('montirlapangan.index', compact('montir'));
    }

    public function create()
    {
        $users = $this->queryUserMontir()->get();

        $cabang = collect();

        if ((int) session('role_id') === 1) {
            $cabang = Cabang::orderBy('nama_cabang')->get();
        }

        return view('montirlapangan.create', compact('users', 'cabang'));
    }

    public function store(Request $request)
    {
        $isSuperAdmin = (int) session('role_id') === 1;

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:User,id_user',
                Rule::unique('MontirLapangan', 'user_id'),
            ],
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:25',
            'area_tugas' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $userQuery = User::where('id_user', $validated['user_id'])
            ->where('role_id', 5);

        if (!$isSuperAdmin) {
            $cabangId = $this->cabangAdmin();

            abort_if($cabangId === null, 403);

            $userQuery->where('cabang_id', $cabangId);
        }

        $user = $userQuery->firstOrFail();

        MontirLapangan::create([
            'user_id' => $user->id_user,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'area_tugas' => $validated['area_tugas'],
            'status' => $validated['status'],
        ]);

        return redirect('/montirlapangan')
            ->with('success', 'Data berhasil disimpan.');
    }

    public function edit($id)
    {
        $montir = $this->queryMontir()->findOrFail($id);

        $users = User::where('role_id', 5)
            ->where(function ($query) use ($montir) {
                $query->whereNotIn(
                    'id_user',
                    MontirLapangan::where(
                        'id_montir',
                        '!=',
                        $montir->id_montir
                    )->select('user_id')
                )->orWhere('id_user', $montir->user_id);
            });

        $cabangId = $this->cabangAdmin();

        if ($cabangId !== null) {
            $users->where('cabang_id', $cabangId);
        }

        $users = $users->orderBy('nama')->get();

        $cabang = collect();

        if ((int) session('role_id') === 1) {
            $cabang = Cabang::orderBy('nama_cabang')->get();
        }

        return view(
            'montirlapangan.edit',
            compact('montir', 'users', 'cabang')
        );
    }

    public function update(Request $request, $id)
    {
        $montir = $this->queryMontir()->findOrFail($id);

        $validated = $request->validate([
            'user_id' => [
                'required',
                'exists:User,id_user',
                Rule::unique('MontirLapangan', 'user_id')
                    ->ignore($montir->id_montir, 'id_montir'),
            ],
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:25',
            'area_tugas' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ]);

        $isSuperAdmin = (int) session('role_id') === 1;

        $userQuery = User::where('id_user', $validated['user_id'])
            ->where('role_id', 5);

        if (!$isSuperAdmin) {
            $cabangId = $this->cabangAdmin();

            abort_if($cabangId === null, 403);

            $userQuery->where('cabang_id', $cabangId);
        }

        $user = $userQuery->firstOrFail();

        $montir->update([
            'user_id' => $user->id_user,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'area_tugas' => $validated['area_tugas'],
            'status' => $validated['status'],
        ]);

        return redirect('/montirlapangan')
            ->with('success', 'Data berhasil diubah.');
    }

    public function destroy($id)
    {
        $montir = $this->queryMontir()->findOrFail($id);

        $montir->delete();

        return redirect('/montirlapangan')
            ->with('success', 'Data berhasil dihapus.');
    }
}
