<?php

namespace App\Http\Controllers;

use App\Models\HomeServis;
use App\Models\Pelanggan;
use App\Models\Kendaraan;
use App\Models\MontirLapangan;
use App\Models\AdminCabang;
use App\Models\Cabang;
use Illuminate\Http\Request;
use Illuminate\Validation\Rule;

class HomeServisController extends Controller
{
    private function roleId(): int
    {
        return (int) session('role_id');
    }

    private function cabangAdmin(): ?int
    {
        $admin = AdminCabang::where(
            'user_id',
            session('user_id')
        )->first();

        return $admin ? (int) $admin->cabang_id : null;
    }

    private function montirSesuaiCabang()
    {
        $query = MontirLapangan::query()
            ->where('status', 'aktif')
            ->whereHas('user', function ($q) {
                $q->where('status', 'aktif');
            });

        if ($this->roleId() === 2) {
            $cabangId = $this->cabangAdmin();

            abort_if(!$cabangId, 403, 'Cabang admin tidak ditemukan.');

            $query->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId);
            });
        }

        return $query->orderBy('nama')->get();
    }

    private function queryHomeServis()
    {
        $query = HomeServis::with([
            'pelanggan',
            'kendaraan',
            'cabang',
            'montirLapangan.user',
        ]);

        if ($this->roleId() === 2) {
            $cabangId = $this->cabangAdmin();

            abort_if(!$cabangId, 403, 'Cabang admin tidak ditemukan.');

            $query->where('id_cabang', $cabangId);
        }

        return $query;
    }

    private function pastikanAksesData(HomeServis $homeServis): void
    {
        if ($this->roleId() !== 2) {
            return;
        }

        $cabangId = $this->cabangAdmin();

        abort_if(
            !$cabangId || (int) $homeServis->id_cabang !== $cabangId,
            403,
            'Kamu tidak memiliki akses ke data Home Service ini.'
        );
    }

    private function validasiMontirCabang(
        ?int $montirId,
        int $cabangId
    ): bool {
        if (!$montirId) {
            return false;
        }

        return MontirLapangan::where('id_montir', $montirId)
            ->where('status', 'aktif')
            ->whereHas('user', function ($q) use ($cabangId) {
                $q->where('cabang_id', $cabangId)
                    ->where('status', 'aktif');
            })
            ->exists();
    }

    public function index()
    {
        $homeServis = $this->queryHomeServis()
            ->orderByDesc('tanggal')
            ->get();

        return view('homeservice.index', compact('homeServis'));
    }

    public function create()
    {
        $pelanggan = Pelanggan::orderBy('nama')->get();
        $kendaraan = Kendaraan::with('pelanggan')->get();
        $montir = $this->montirSesuaiCabang();
        $cabang = Cabang::orderBy('nama_cabang')->get();

        return view('homeservice.create', compact(
            'pelanggan',
            'kendaraan',
            'montir',
            'cabang'
        ));
    }

    public function store(Request $request)
    {
        $data = $request->validate([
            'id_pelanggan' => [
                'required', 'integer',
                'exists:Pelanggan,id_pelanggan',
            ],
            'id_kendaraan' => [
                'required', 'integer',
                'exists:Kendaraan,id_kendaraan',
            ],
            'id_cabang' => [
                Rule::requiredIf($this->roleId() === 1),
                'nullable',
                'integer',
                'exists:cabang,id_cabang',
            ],
            'id_montir' => [
                'nullable',
                'integer',
                Rule::exists('MontirLapangan', 'id_montir')
                    ->where('status', 'aktif'),
            ],
            'alamat' => ['required', 'string'],
            'tanggal' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in([
                    'Requested',
                    'On The Way',
                    'Arrived',
                    'In Service',
                    'Completed',
                ]),
            ],
            'catatan' => ['nullable', 'string'],
        ]);

        $kendaraan = Kendaraan::findOrFail($data['id_kendaraan']);

        if ((int) $kendaraan->id_pelanggan !== (int) $data['id_pelanggan']) {
            return back()->withInput()->withErrors([
                'id_kendaraan' =>
                    'Kendaraan tidak sesuai dengan pelanggan yang dipilih.',
            ]);
        }

        if ($this->roleId() === 2) {
            $cabangId = $this->cabangAdmin();

            abort_if(!$cabangId, 403, 'Cabang admin tidak ditemukan.');

            $data['id_cabang'] = $cabangId;

            if (empty($data['id_montir'])) {
                return back()->withInput()->withErrors([
                    'id_montir' =>
                        'Pilih montir dari cabang kamu untuk menetapkan tugas.',
                ]);
            }

            if (!$this->validasiMontirCabang(
                (int) $data['id_montir'],
                $cabangId
            )) {
                return back()->withInput()->withErrors([
                    'id_montir' =>
                        'Montir harus berasal dari cabang kamu dan berstatus aktif.',
                ]);
            }
        } else {
            $data['id_cabang'] = (int) $data['id_cabang'];

            if (!empty($data['id_montir'])) {
                $montir = MontirLapangan::with('user')
                    ->findOrFail($data['id_montir']);

                if (
                    !$montir->user ||
                    (int) $montir->user->cabang_id !== (int) $data['id_cabang']
                ) {
                    return back()->withInput()->withErrors([
                        'id_montir' =>
                            'Cabang montir harus sama dengan cabang pesanan.',
                    ]);
                }
            }
        }

        HomeServis::create([
            'id_pelanggan' => $data['id_pelanggan'],
            'id_kendaraan' => $data['id_kendaraan'],
            'id_cabang' => $data['id_cabang'],
            'id_montir' => $data['id_montir'] ?? null,
            'alamat' => $data['alamat'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect('/homeservice')->with(
            'success',
            'Data Home Service berhasil disimpan.'
        );
    }

    public function edit($id)
    {
        $homeServis = $this->queryHomeServis()
            ->where('id_home_service', $id)
            ->firstOrFail();

        $this->pastikanAksesData($homeServis);

        $pelanggan = Pelanggan::orderBy('nama')->get();
        $kendaraan = Kendaraan::with('pelanggan')->get();
        $montir = $this->montirSesuaiCabang();
        $cabang = Cabang::orderBy('nama_cabang')->get();

        return view('homeservice.edit', compact(
            'homeServis',
            'pelanggan',
            'kendaraan',
            'montir',
            'cabang'
        ));
    }

    public function update(Request $request, $id)
    {
        $homeServis = HomeServis::findOrFail($id);

        $this->pastikanAksesData($homeServis);

        $data = $request->validate([
            'id_pelanggan' => [
                'required', 'integer',
                'exists:Pelanggan,id_pelanggan',
            ],
            'id_kendaraan' => [
                'required', 'integer',
                'exists:Kendaraan,id_kendaraan',
            ],
            'id_cabang' => [
                Rule::requiredIf($this->roleId() === 1),
                'nullable',
                'integer',
                'exists:cabang,id_cabang',
            ],
            'id_montir' => [
                'nullable',
                'integer',
                Rule::exists('MontirLapangan', 'id_montir')
                    ->where('status', 'aktif'),
            ],
            'alamat' => ['required', 'string'],
            'tanggal' => ['required', 'date'],
            'status' => [
                'required',
                Rule::in([
                    'Requested',
                    'On The Way',
                    'Arrived',
                    'In Service',
                    'Completed',
                ]),
            ],
            'catatan' => ['nullable', 'string'],
        ]);

        $kendaraan = Kendaraan::findOrFail($data['id_kendaraan']);

        if ((int) $kendaraan->id_pelanggan !== (int) $data['id_pelanggan']) {
            return back()->withInput()->withErrors([
                'id_kendaraan' =>
                    'Kendaraan tidak sesuai dengan pelanggan yang dipilih.',
            ]);
        }

        if ($this->roleId() === 2) {
            $cabangId = $this->cabangAdmin();

            abort_if(!$cabangId, 403, 'Cabang admin tidak ditemukan.');

            $data['id_cabang'] = $cabangId;

            if (!$this->validasiMontirCabang(
                isset($data['id_montir']) ? (int) $data['id_montir'] : null,
                $cabangId
            )) {
                return back()->withInput()->withErrors([
                    'id_montir' => 'Pilih montir aktif dari cabang kamu.',
                ]);
            }
        } else {
            $data['id_cabang'] = (int) $data['id_cabang'];

            if (!empty($data['id_montir'])) {
                $montir = MontirLapangan::with('user')
                    ->findOrFail($data['id_montir']);

                if (
                    !$montir->user ||
                    (int) $montir->user->cabang_id !== (int) $data['id_cabang']
                ) {
                    return back()->withInput()->withErrors([
                        'id_montir' =>
                            'Cabang montir harus sama dengan cabang pesanan.',
                    ]);
                }
            }
        }

        $homeServis->update([
            'id_pelanggan' => $data['id_pelanggan'],
            'id_kendaraan' => $data['id_kendaraan'],
            'id_cabang' => $data['id_cabang'],
            'id_montir' => $data['id_montir'] ?? null,
            'alamat' => $data['alamat'],
            'tanggal' => $data['tanggal'],
            'status' => $data['status'],
            'catatan' => $data['catatan'] ?? null,
        ]);

        return redirect('/homeservice')->with(
            'success',
            'Data Home Service berhasil diubah.'
        );
    }

    public function destroy($id)
    {
        $homeServis = HomeServis::findOrFail($id);

        $this->pastikanAksesData($homeServis);

        $homeServis->delete();

        return redirect('/homeservice')->with(
            'success',
            'Data Home Service berhasil dihapus.'
        );
    }
}
