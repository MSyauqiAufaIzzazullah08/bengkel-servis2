<?php

namespace App\Http\Controllers;

use App\Models\Cabang;
use App\Models\Teknisi;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class TeknisiController extends Controller
{
    private function cabangAdmin()
    {
        if ((int) session('role_id') !== 2) {
            return null;
        }

        $user = User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        return (int) $cabangId;
    }

    private function queryTeknisi()
    {
        $query = Teknisi::with([
            'user',
            'penugasanTeknisi.bookingServis',
        ]);

        $cabangId = $this->cabangAdmin();

        if ($cabangId !== null) {
            $query->whereHas('user', function ($user) use ($cabangId) {
                $user->where('cabang_id', $cabangId);
            });
        }

        return $query;
    }

    private function queryUserTeknisi()
    {
        $query = User::where('role_id', 4);

        $cabangId = $this->cabangAdmin();

        if ($cabangId !== null) {
            $query->where('cabang_id', $cabangId);
        }

        return $query->orderBy('nama');
    }

    public function index()
    {
        $teknisi = $this->queryTeknisi()->get();

        return view('teknisi.index', compact('teknisi'));
    }

    public function create()
    {
        $users = $this->queryUserTeknisi()->get();

        $cabang = collect();

        if ((int) session('role_id') === 1) {
            $cabang = Cabang::orderBy('nama_cabang')->get();
        }

        return view('teknisi.create', compact('users', 'cabang'));
    }

    public function store(Request $request)
    {
        $isSuperAdmin = (int) session('role_id') === 1;

        $rules = [
            'user_id' => [
                'required',
                'exists:User,id_user',
                'unique:Teknisi,user_id',
            ],
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:25',
            'keahlian' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ];

        if ($isSuperAdmin) {
            $rules['cabang_id'] = [
                'required',
                'exists:cabang,id_cabang',
            ];
        }

        $validated = $request->validate($rules);

        $userQuery = User::where('id_user', $validated['user_id'])
            ->where('role_id', 4);

        if (!$isSuperAdmin) {
            $cabangId = $this->cabangAdmin();

            abort_if($cabangId === null, 403);

            $userQuery->where('cabang_id', $cabangId);
        }

        $user = $userQuery->firstOrFail();

        if ($isSuperAdmin) {
            $user->cabang_id = $validated['cabang_id'];
            $user->save();
        }

        Teknisi::create([
            'user_id' => $user->id_user,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'keahlian' => $validated['keahlian'],
            'status' => $validated['status'],
        ]);

        return redirect('/teknisi')
            ->with('success', 'Teknisi berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $teknisi = $this->queryTeknisi()->findOrFail($id);
        $users = $this->queryUserTeknisi()->get();

        $cabang = collect();

        if ((int) session('role_id') === 1) {
            $cabang = Cabang::orderBy('nama_cabang')->get();
        }

        return view('teknisi.edit', compact(
            'teknisi',
            'users',
            'cabang'
        ));
    }

    public function update(Request $request, $id)
    {
        $teknisi = $this->queryTeknisi()->findOrFail($id);
        $isSuperAdmin = (int) session('role_id') === 1;

        $rules = [
            'user_id' => [
                'required',
                'exists:User,id_user',
                Rule::unique('Teknisi', 'user_id')
                    ->ignore($id, 'id_teknisi'),
            ],
            'nama' => 'required|string|max:100',
            'no_hp' => 'required|string|max:25',
            'keahlian' => 'required|string|max:255',
            'status' => 'required|string|max:50',
        ];

        if ($isSuperAdmin) {
            $rules['cabang_id'] = [
                'required',
                'exists:cabang,id_cabang',
            ];
        }

        $validated = $request->validate($rules);

        $userQuery = User::where('id_user', $validated['user_id'])
            ->where('role_id', 4);

        if (!$isSuperAdmin) {
            $cabangId = $this->cabangAdmin();

            abort_if($cabangId === null, 403);

            $userQuery->where('cabang_id', $cabangId);
        }

        $user = $userQuery->firstOrFail();

        if ($isSuperAdmin) {
            $user->cabang_id = $validated['cabang_id'];
            $user->save();
        }

        $teknisi->update([
            'user_id' => $user->id_user,
            'nama' => $validated['nama'],
            'no_hp' => $validated['no_hp'],
            'keahlian' => $validated['keahlian'],
            'status' => $validated['status'],
        ]);

        return redirect('/teknisi')
            ->with('success', 'Data teknisi berhasil diubah.');
    }

    public function destroy($id)
    {
        $teknisi = $this->queryTeknisi()->findOrFail($id);

        if ($teknisi->penugasanTeknisi()->exists()) {
            return redirect('/teknisi')
                ->with(
                    'error',
                    'Teknisi tidak dapat dihapus karena masih memiliki penugasan.'
                );
        }

        $teknisi->delete();

        return redirect('/teknisi')
            ->with('success', 'Teknisi berhasil dihapus.');
    }
}
