<?php

namespace App\Services;

use App\Models\KomisiTeknisi;
use App\Models\KonfigurasiKomisi;
use App\Models\PenugasanTeknisi;
use App\Models\HomeServis;
use App\Models\Teknisi;
use Illuminate\Support\Collection;
use Illuminate\Support\Facades\DB;

/**
 * KomisiService mengelola komisi untuk Teknisi (role 4) melalui PenugasanTeknisi.
 * Scope: hanya penugasanteknisi (booking workshop).
 * Home Service (homeservice + montirlapangan) adalah alur terpisah dan
 * TIDAK dimasukkan ke dalam komisi_teknisi melalui class ini.
 * (hitungKomisiHomeService() adalah utility method opsional — lihat komentar method tersebut)
 */
class KomisiService
{
    /**
     * Hitung dan simpan komisi per servis bengkel ketika laporan disetujui.
     * Guard: tidak insert duplikat untuk penugasan yang sama.
     */
    public function hitungKomisiPenugasan(PenugasanTeknisi $penugasan): void
    {
        // Pastikan laporan sudah disetujui
        $laporan = $penugasan->laporanServis;
        if (!$laporan || $laporan->status !== 'disetujui') {
            return;
        }

        // Cek duplikat: sudah ada komisi per_servis untuk penugasan ini?
        $sudahAda = KomisiTeknisi::where('id_penugasan', $penugasan->id_penugasan)
            ->where('tipe_servis', 'bengkel')
            ->where('is_bonus_target', 0)
            ->exists();

        if ($sudahAda) {
            return;
        }

        // Ambil konfigurasi komisi aktif
        $config = KonfigurasiKomisi::aktif()->first();
        if (!$config) {
            return; // Tidak ada konfigurasi — tidak bisa hitung
        }

        // Tentukan periode: awal bulan dari tanggal penugasan
        $periodeDate = date('Y-m-01', strtotime($penugasan->tanggal ?? now()->toDateString()));

        DB::transaction(function () use ($penugasan, $config, $periodeDate) {
            KomisiTeknisi::create([
                'id_teknisi'       => $penugasan->id_teknisi,
                'id_penugasan'     => $penugasan->id_penugasan,
                'id_booking'       => $penugasan->id_booking,
                'id_home_service'  => null,
                'nominal_komisi'   => $config->komisi_per_servis,
                'tipe_servis'      => 'bengkel',
                'is_bonus_target'  => 0,
                'periode_bulan'    => $periodeDate,
                'status_pembayaran' => 'belum_dibayar',
            ]);
        });

        // Setelah insert komisi per servis, cek apakah target bulan ini sudah tercapai
        $this->hitungBonusTarget($penugasan->id_teknisi, $periodeDate);
    }

    /**
     * Hitung dan simpan komisi untuk home service.
     * Guard: tidak insert duplikat untuk id_home_service yang sama.
     */
    public function hitungKomisiHomeService(HomeServis $homeServis, Teknisi $teknisi): void
    {
        // Cek duplikat
        $sudahAda = KomisiTeknisi::where('id_home_service', $homeServis->id_home_service)
            ->where('is_bonus_target', 0)
            ->exists();

        if ($sudahAda) {
            return;
        }

        $config = KonfigurasiKomisi::aktif()->first();
        if (!$config) {
            return;
        }

        $periodeDate = date('Y-m-01', strtotime($homeServis->tanggal ?? now()->toDateString()));

        DB::transaction(function () use ($homeServis, $teknisi, $config, $periodeDate) {
            KomisiTeknisi::create([
                'id_teknisi'       => $teknisi->id_teknisi,
                'id_penugasan'     => null,
                'id_booking'       => null,
                'id_home_service'  => $homeServis->id_home_service,
                'nominal_komisi'   => $config->komisi_per_servis,
                'tipe_servis'      => 'home_service',
                'is_bonus_target'  => 0,
                'periode_bulan'    => $periodeDate,
                'status_pembayaran' => 'belum_dibayar',
            ]);
        });

        // Cek bonus target bulan ini
        $this->hitungBonusTarget($teknisi->id_teknisi, $periodeDate);
    }

    /**
     * Hitung dan simpan bonus target bulanan jika target tercapai.
     *
     * @param int    $id_teknisi
     * @param string $periode_bulan  Format: 'Y-m-d' (tanggal 1 bulan tersebut)
     */
    public function hitungBonusTarget(int $id_teknisi, string $periode_bulan): void
    {
        $config = KonfigurasiKomisi::aktif()->first();
        if (!$config) {
            return;
        }

        // Hitung total servis valid bulan ini (bengkel + home_service, bukan bonus)
        $totalServis = KomisiTeknisi::where('id_teknisi', $id_teknisi)
            ->where('is_bonus_target', 0)
            ->whereDate('periode_bulan', $periode_bulan)
            ->count();

        if ($totalServis < $config->target_bulanan) {
            return; // Belum mencapai target
        }

        // Cek apakah bonus bulan ini sudah ada
        $bonusSudahAda = KomisiTeknisi::where('id_teknisi', $id_teknisi)
            ->where('is_bonus_target', 1)
            ->whereDate('periode_bulan', $periode_bulan)
            ->exists();

        if ($bonusSudahAda) {
            return;
        }

        DB::transaction(function () use ($id_teknisi, $config, $periode_bulan) {
            KomisiTeknisi::create([
                'id_teknisi'       => $id_teknisi,
                'id_penugasan'     => null,
                'id_booking'       => null,
                'id_home_service'  => null,
                'nominal_komisi'   => $config->bonus_target_bulanan,
                'tipe_servis'      => 'bengkel',
                'is_bonus_target'  => 1,
                'periode_bulan'    => $periode_bulan,
                'status_pembayaran' => 'belum_dibayar',
            ]);
        });
    }

    /**
     * Ambil daftar komisi seorang teknisi dalam rentang periode.
     *
     * @param int    $id_teknisi
     * @param string $dari    Format: 'Y-m-d'
     * @param string $sampai  Format: 'Y-m-d'
     */
    public function getKomisiTeknisiPeriode(int $id_teknisi, string $dari, string $sampai): Collection
    {
        return KomisiTeknisi::where('id_teknisi', $id_teknisi)
            ->whereBetween('periode_bulan', [$dari, $sampai])
            ->with(['penugasanTeknisi.bookingServis', 'homeServis'])
            ->orderByDesc('periode_bulan')
            ->orderByDesc('id_komisi')
            ->get();
    }
}
