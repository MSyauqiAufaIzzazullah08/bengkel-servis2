<?php

namespace App\Services;

use App\Models\KonfigurasiKomisi;
use App\Models\PenghargaanTeknisi;
use Illuminate\Support\Facades\DB;

class PenghargaanService
{
    /**
     * Hitung skor semua teknisi untuk periode tertentu dan simpan ke tabel penghargaan_teknisi.
     *
     * @param string   $periode_tipe   'mingguan' | 'bulanan' | 'tahunan'
     * @param string   $periode_mulai  Format: 'Y-m-d'
     * @param string   $periode_selesai Format: 'Y-m-d'
     * @param int|null $cabang_id      null = semua cabang
     */
    public function hitungDanSimpan(
        string $periode_tipe,
        string $periode_mulai,
        string $periode_selesai,
        ?int $cabang_id = null
    ): void {
        $config       = KonfigurasiKomisi::aktif()->first();
        $targetBulanan = $config ? (int) $config->target_bulanan : 20;

        // Sesuaikan target dengan tipe periode
        switch ($periode_tipe) {
            case 'mingguan':
                $targetPeriode = max(1, (int) round($targetBulanan / 4));
                break;
            case 'tahunan':
                $targetPeriode = $targetBulanan * 12;
                break;
            default: // bulanan
                $targetPeriode = $targetBulanan;
        }

        // Query teknisi yang relevan
        $teknisiQuery = DB::table('Teknisi as t')
            ->select('t.id_teknisi', 't.nama', 't.user_id');

        if ($cabang_id) {
            // Hanya teknisi yang pernah bertugas di cabang ini
            $teknisiQuery->whereExists(function ($q) use ($cabang_id, $periode_mulai, $periode_selesai) {
                $q->select(DB::raw(1))
                    ->from('PenugasanTeknisi as pt')
                    ->join('bookingservis as b', 'pt.id_booking', '=', 'b.id_booking')
                    ->whereRaw('pt.id_teknisi = t.id_teknisi')
                    ->where('b.id_cabang', $cabang_id)
                    ->whereBetween('pt.tanggal', [$periode_mulai, $periode_selesai]);
            });
        }

        $semuaTeknisi = $teknisiQuery->get();

        $hasilSkor = [];

        foreach ($semuaTeknisi as $teknisi) {
            $tid = $teknisi->id_teknisi;

            // Jumlah servis bengkel yang laporannya disetujui dalam periode
            $jumlahServisQuery = DB::table('PenugasanTeknisi as pt')
                ->join('laporan_servis as ls', 'ls.penugasan_id', '=', 'pt.id_penugasan')
                ->where('pt.id_teknisi', $tid)
                ->where('ls.status', 'disetujui')
                ->whereBetween('pt.tanggal', [$periode_mulai, $periode_selesai]);

            if ($cabang_id) {
                $jumlahServisQuery->join('bookingservis as b', 'pt.id_booking', '=', 'b.id_booking')
                    ->where('b.id_cabang', $cabang_id);
            }

            $jumlahServis = $jumlahServisQuery->count('pt.id_penugasan');

            // Tambahkan Home Service jika teknisi juga punya akun MontirLapangan (user_id sama)
            $montir = DB::table('MontirLapangan')
                ->where('user_id', $teknisi->user_id)
                ->first();

            if ($montir) {
                $hsQuery = DB::table('homeservice')
                    ->where('id_montir', $montir->id_montir)
                    ->where('status', 'Completed')
                    ->whereBetween('tanggal', [$periode_mulai, $periode_selesai]);
                if ($cabang_id) {
                    $hsQuery->where('id_cabang', $cabang_id);
                }
                $jumlahHS = $hsQuery->count();
                $jumlahServis += $jumlahHS;
            }

            // Rata-rata rating dari booking servis teknisi ini di periode
            $ratingQuery = DB::table('Rating as r')
                ->join('bookingservis as b', 'r.id_booking', '=', 'b.id_booking')
                ->join('PenugasanTeknisi as pt', 'pt.id_booking', '=', 'b.id_booking')
                ->where('pt.id_teknisi', $tid)
                ->whereBetween('b.tanggal_booking', [$periode_mulai, $periode_selesai]);

            if ($cabang_id) {
                $ratingQuery->where('b.id_cabang', $cabang_id);
            }

            $rataRating = (float) $ratingQuery->avg('r.nilai');

            // Persentase konsistensi
            $persenKonsistensi = min(($jumlahServis / $targetPeriode) * 100, 100);

            // Skor total: servis 50%, rating 30%, konsistensi 20%
            $skorTotal = ($jumlahServis * 0.50)
                + ($rataRating * 20 * 0.30)
                + ($persenKonsistensi * 0.20);

            // Resolve cabang_id untuk baris penghargaan
            $resolvedCabangId = $cabang_id;
            if (!$resolvedCabangId) {
                $resolvedCabangId = DB::table('PenugasanTeknisi as pt')
                    ->join('bookingservis as b', 'pt.id_booking', '=', 'b.id_booking')
                    ->where('pt.id_teknisi', $tid)
                    ->whereBetween('pt.tanggal', [$periode_mulai, $periode_selesai])
                    ->value('b.id_cabang');
            }

            if (!$resolvedCabangId) {
                // Teknisi belum punya penugasan, skip
                continue;
            }

            PenghargaanTeknisi::updateOrCreate(
                [
                    'teknisi_id'  => $tid,
                    'periode_tipe' => $periode_tipe,
                    'periode_mulai' => $periode_mulai,
                ],
                [
                    'cabang_id'       => $resolvedCabangId,
                    'periode_selesai' => $periode_selesai,
                    'jumlah_servis'   => $jumlahServis,
                    'rata_rata_rating' => round($rataRating, 2),
                    'skor_total'      => round($skorTotal, 4),
                    'peringkat'       => 0, // assigned below
                ]
            );

            $hasilSkor[] = [
                'teknisi_id'   => $tid,
                'skor_total'   => $skorTotal,
                'periode_mulai' => $periode_mulai,
                'periode_tipe' => $periode_tipe,
            ];
        }

        // Assign peringkat: sort by skor_total DESC
        usort($hasilSkor, fn($a, $b) => $b['skor_total'] <=> $a['skor_total']);

        foreach ($hasilSkor as $i => $item) {
            PenghargaanTeknisi::where('teknisi_id', $item['teknisi_id'])
                ->where('periode_tipe', $item['periode_tipe'])
                ->where('periode_mulai', $item['periode_mulai'])
                ->update(['peringkat' => $i + 1]);
        }
    }
}
