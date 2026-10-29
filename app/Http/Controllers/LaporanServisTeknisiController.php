<?php

namespace App\Http\Controllers;

use App\Models\LaporanServis;
use App\Models\PenugasanTeknisi;
use App\Models\Teknisi;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class LaporanServisTeknisiController extends Controller
{
    /**
     * Resolve Teknisi milik user yang sedang login.
     */
    private function resolveTeknisi(): Teknisi
    {
        $teknisi = Teknisi::where('user_id', session('user_id'))->first();

        abort_if(!$teknisi, 403, 'Akun Anda belum terhubung dengan data Teknisi.');

        return $teknisi;
    }

    /**
     * Daftar laporan milik teknisi yang sedang login.
     */
    public function index()
    {
        $teknisi = $this->resolveTeknisi();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
        ])
            ->whereHas('penugasanTeknisi', function ($q) use ($teknisi) {
                $q->where('id_teknisi', $teknisi->id_teknisi);
            })
            ->orderByDesc('updated_at')
            ->orderByDesc('id')
            ->get();

        return view('teknisi.laporan.index', compact('laporan'));
    }

    /**
     * Form buat laporan baru untuk penugasan tertentu.
     */
    public function create($id_penugasan)
    {
        $teknisi = $this->resolveTeknisi();

        $penugasan = PenugasanTeknisi::with([
            'bookingServis.pelanggan',
            'bookingServis.kendaraan',
            'bookingServis.layananServis',
        ])
            ->where('id_penugasan', $id_penugasan)
            ->where('id_teknisi', $teknisi->id_teknisi)
            ->firstOrFail();

        // Hanya boleh buat laporan jika penugasan sudah Completed.
        abort_if(
            $penugasan->status !== 'Completed',
            403,
            'Laporan hanya dapat dibuat untuk penugasan yang berstatus Completed.'
        );

        // Jika sudah ada laporan, redirect ke edit.
        if ($penugasan->laporanServis) {
            return redirect()
                ->route('laporan-servis-teknisi.edit', $penugasan->laporanServis->id)
                ->with('info', 'Laporan untuk penugasan ini sudah ada. Silakan edit laporan yang ada.');
        }

        return view('teknisi.laporan.create', compact('penugasan'));
    }

    /**
     * Simpan laporan baru sebagai draft.
     */
    public function store(Request $request)
    {
        $teknisi = $this->resolveTeknisi();

        $data = $request->validate([
            'penugasan_id'             => 'required|integer|exists:PenugasanTeknisi,id_penugasan',
            'keluhan_awal'             => 'nullable|string',
            'hasil_diagnosis'          => 'nullable|string',
            'tindakan_perbaikan'       => 'nullable|string',
            // komponen_digunakan: field dihapus dari form, kolom database tetap ada
            'kondisi_motor_setelah'    => 'nullable|string',
            'kendala_pekerjaan'        => 'nullable|string',
            'catatan_rekomendasi'      => 'nullable|string',
            'tanggal_selesai_pekerjaan'=> 'nullable|date',
        ]);

        // Pastikan penugasan milik teknisi ini dan berstatus Completed.
        $penugasan = PenugasanTeknisi::where('id_penugasan', $data['penugasan_id'])
            ->where('id_teknisi', $teknisi->id_teknisi)
            ->firstOrFail();

        abort_if(
            $penugasan->status !== 'Completed',
            403,
            'Laporan hanya dapat dibuat untuk penugasan yang berstatus Completed.'
        );

        // Cegah duplikasi laporan.
        $sudahAda = LaporanServis::where('penugasan_id', $data['penugasan_id'])->exists();
        if ($sudahAda) {
            return back()
                ->withInput()
                ->withErrors(['penugasan_id' => 'Laporan untuk penugasan ini sudah ada.']);
        }

        // komponen_digunakan: tidak disentuh — field database tetap ada untuk data historis.

        DB::transaction(function () use ($data, $penugasan) {
            LaporanServis::create([
                'penugasan_id'             => $penugasan->id_penugasan,
                'booking_id'               => $penugasan->id_booking,
                'sumber'                   => 'bengkel',
                'keluhan_awal'             => $data['keluhan_awal'] ?? null,
                'hasil_diagnosis'          => $data['hasil_diagnosis'] ?? null,
                'tindakan_perbaikan'       => $data['tindakan_perbaikan'] ?? null,
                'kondisi_motor_setelah'    => $data['kondisi_motor_setelah'] ?? null,
                'kendala_pekerjaan'        => $data['kendala_pekerjaan'] ?? null,
                'catatan_rekomendasi'      => $data['catatan_rekomendasi'] ?? null,
                'tanggal_selesai_pekerjaan'=> $data['tanggal_selesai_pekerjaan'] ?? null,
                'status'                   => 'draft',
            ]);
        });

        return redirect()
            ->route('laporan-servis-teknisi.index')
            ->with('success', 'Laporan berhasil disimpan sebagai draft.');
    }

    /**
     * Form edit laporan (hanya draft atau perlu_revisi).
     */
    public function edit($id)
    {
        $teknisi = $this->resolveTeknisi();

        $laporan = LaporanServis::with([
            'penugasanTeknisi.bookingServis.pelanggan',
            'penugasanTeknisi.bookingServis.kendaraan',
            'penugasanTeknisi.bookingServis.layananServis',
        ])->findOrFail($id);

        // Pastikan laporan milik teknisi ini.
        abort_if(
            (int) optional($laporan->penugasanTeknisi)->id_teknisi !== (int) $teknisi->id_teknisi,
            403,
            'Laporan ini bukan milik Anda.'
        );

        // Hanya status draft dan perlu_revisi yang bisa diedit.
        abort_if(
            !in_array($laporan->status, ['draft', 'perlu_revisi'], true),
            403,
            'Laporan dengan status ini tidak dapat diedit.'
        );

        // Komponen digunakan: ubah array jadi teks per baris untuk form.
        $komponenTeks = is_array($laporan->komponen_digunakan)
            ? implode("\n", $laporan->komponen_digunakan)
            : '';

        return view('teknisi.laporan.edit', compact('laporan', 'komponenTeks'));
    }

    /**
     * Update laporan yang sedang diedit.
     */
    public function update(Request $request, $id)
    {
        $teknisi = $this->resolveTeknisi();

        $laporan = LaporanServis::findOrFail($id);

        $penugasan = PenugasanTeknisi::where('id_penugasan', $laporan->penugasan_id)
            ->where('id_teknisi', $teknisi->id_teknisi)
            ->firstOrFail();

        abort_if(
            !in_array($laporan->status, ['draft', 'perlu_revisi'], true),
            403,
            'Laporan dengan status ini tidak dapat diedit.'
        );

        $data = $request->validate([
            'keluhan_awal'             => 'nullable|string',
            'hasil_diagnosis'          => 'nullable|string',
            'tindakan_perbaikan'       => 'nullable|string',
            // komponen_digunakan: field dihapus dari form, kolom database tetap ada
            'kondisi_motor_setelah'    => 'nullable|string',
            'kendala_pekerjaan'        => 'nullable|string',
            'catatan_rekomendasi'      => 'nullable|string',
            'tanggal_selesai_pekerjaan'=> 'nullable|date',
        ]);

        // komponen_digunakan: tidak disentuh — field database tetap ada untuk data historis.

        DB::transaction(function () use ($laporan, $data) {
            $laporan->update([
                'keluhan_awal'             => $data['keluhan_awal'] ?? null,
                'hasil_diagnosis'          => $data['hasil_diagnosis'] ?? null,
                'tindakan_perbaikan'       => $data['tindakan_perbaikan'] ?? null,
                'kondisi_motor_setelah'    => $data['kondisi_motor_setelah'] ?? null,
                'kendala_pekerjaan'        => $data['kendala_pekerjaan'] ?? null,
                'catatan_rekomendasi'      => $data['catatan_rekomendasi'] ?? null,
                'tanggal_selesai_pekerjaan'=> $data['tanggal_selesai_pekerjaan'] ?? null,
            ]);
        });

        return redirect()
            ->route('laporan-servis-teknisi.index')
            ->with('success', 'Laporan berhasil diperbarui.');
    }

    /**
     * Ubah status laporan ke menunggu_verifikasi.
     */
    public function submit($id)
    {
        $teknisi = $this->resolveTeknisi();

        $laporan = LaporanServis::findOrFail($id);

        $penugasan = PenugasanTeknisi::where('id_penugasan', $laporan->penugasan_id)
            ->where('id_teknisi', $teknisi->id_teknisi)
            ->firstOrFail();

        abort_if(
            !in_array($laporan->status, ['draft', 'perlu_revisi'], true),
            403,
            'Laporan ini tidak dapat diajukan.'
        );

        DB::transaction(function () use ($laporan) {
            $laporan->update(['status' => 'menunggu_verifikasi']);
        });

        return redirect()
            ->route('laporan-servis-teknisi.index')
            ->with('success', 'Laporan berhasil diajukan untuk verifikasi.');
    }
}
