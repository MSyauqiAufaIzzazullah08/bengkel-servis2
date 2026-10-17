<?php

namespace App\Http\Controllers;

use App\Models\OperasionalCabang;
use App\Models\Cabang;
use App\Models\User;
use Illuminate\Http\Request;

class OperasionalCabangController extends Controller
{
    private function cabangIdAdmin()
    {
        $user = User::findOrFail(session('user_id'));

        $cabangId = $user->cabang_id
            ?? $user->adminCabang?->cabang_id;

        abort_if(
            !$cabangId,
            403,
            'Akun Admin Cabang belum terhubung dengan cabang.'
        );

        return $cabangId;
    }

    private function queryOperasional()
    {
        $query = OperasionalCabang::with('cabang');

        if ((int) session('role_id') === 2) {
            $query->where('cabang_id', $this->cabangIdAdmin());
        }

        return $query;
    }

    public function index()
    {
        $operasional = $this->queryOperasional()->get();

        return view('operasional.index', compact('operasional'));
    }

    public function create()
    {
        if ((int) session('role_id') === 2) {
            $cabangId = $this->cabangIdAdmin();

            $sudahAda = OperasionalCabang::where(
                'cabang_id',
                $cabangId
            )->exists();

            if ($sudahAda) {
                return redirect('/operasional')
                    ->with('error', 'Data operasional cabang ini sudah tersedia.');
            }

            $cabang = Cabang::where('id_cabang', $cabangId)->get();
        } else {
            $cabang = Cabang::whereDoesntHave('operasionalCabang')
                ->orderBy('nama_cabang')
                ->get();
        }

        return view('operasional.create', compact('cabang'));
    }

    public function store(Request $request)
    {
        $isAdminCabang = (int) session('role_id') === 2;

        $rules = [
            'total_booking' => 'required|integer|min:0',
            'total_transaksi' => 'required|integer|min:0',
            'pendapatan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'laporan' => 'nullable|string|max:255',
        ];

        if (!$isAdminCabang) {
            $rules['cabang_id'] =
                'required|exists:cabang,id_cabang|unique:OperasionalCabang,cabang_id';
        }

        $validated = $request->validate($rules);

        $cabangId = $isAdminCabang
            ? $this->cabangIdAdmin()
            : $validated['cabang_id'];

        if (OperasionalCabang::where('cabang_id', $cabangId)->exists()) {
            return redirect('/operasional')
                ->with('error', 'Data operasional untuk cabang ini sudah tersedia.');
        }

        OperasionalCabang::create([
            'cabang_id' => $cabangId,
            'total_booking' => $validated['total_booking'],
            'total_transaksi' => $validated['total_transaksi'],
            'pendapatan' => $validated['pendapatan'],
            'stok' => $validated['stok'],
            'laporan' => $validated['laporan'] ?? null,
        ]);

        return redirect('/operasional')
            ->with('success', 'Data operasional berhasil ditambahkan.');
    }

    public function edit($id)
    {
        $operasional = $this->queryOperasional()->findOrFail($id);

        if ((int) session('role_id') === 2) {
            $cabang = Cabang::where(
                'id_cabang',
                $this->cabangIdAdmin()
            )->get();
        } else {
            $cabang = Cabang::where(function ($query) use ($operasional) {
                $query->whereDoesntHave('operasionalCabang')
                    ->orWhere('id_cabang', $operasional->cabang_id);
            })
            ->orderBy('nama_cabang')
            ->get();
        }

        return view('operasional.edit', compact('operasional', 'cabang'));
    }

    public function update(Request $request, $id)
    {
        $isAdminCabang = (int) session('role_id') === 2;

        $operasional = $this->queryOperasional()->findOrFail($id);

        $rules = [
            'total_booking' => 'required|integer|min:0',
            'total_transaksi' => 'required|integer|min:0',
            'pendapatan' => 'required|numeric|min:0',
            'stok' => 'required|integer|min:0',
            'laporan' => 'nullable|string|max:255',
        ];

        if (!$isAdminCabang) {
            $rules['cabang_id'] =
                'required|exists:cabang,id_cabang|unique:OperasionalCabang,cabang_id,'
                . $id . ',id_operasional';
        }

        $validated = $request->validate($rules);

        $operasional->update([
            'cabang_id' => $isAdminCabang
                ? $this->cabangIdAdmin()
                : $validated['cabang_id'],
            'total_booking' => $validated['total_booking'],
            'total_transaksi' => $validated['total_transaksi'],
            'pendapatan' => $validated['pendapatan'],
            'stok' => $validated['stok'],
            'laporan' => $validated['laporan'] ?? null,
        ]);

        return redirect('/operasional')
            ->with('success', 'Data operasional berhasil diubah.');
    }

    public function destroy($id)
    {
        $operasional = $this->queryOperasional()->findOrFail($id);

        $operasional->delete();

        return redirect('/operasional')
            ->with('success', 'Data operasional berhasil dihapus.');
    }
}
