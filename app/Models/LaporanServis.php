<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LaporanServis extends Model
{
    protected $table = 'laporan_servis';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'penugasan_id',
        'booking_id',
        'sumber',
        'keluhan_awal',
        'hasil_diagnosis',
        'tindakan_perbaikan',
        'komponen_digunakan',
        'kondisi_motor_setelah',
        'kendala_pekerjaan',
        'catatan_rekomendasi',
        'tanggal_selesai_pekerjaan',
        'status',
    ];

    protected $casts = [
        'komponen_digunakan' => 'array',
    ];

    public function penugasanTeknisi()
    {
        return $this->belongsTo(PenugasanTeknisi::class, 'penugasan_id', 'id_penugasan');
    }

    public function bookingServis()
    {
        return $this->belongsTo(BookingServis::class, 'booking_id', 'id_booking');
    }

    public function riwayatVerifikasi()
    {
        return $this->hasMany(RiwayatVerifikasiLaporan::class, 'laporan_id', 'id');
    }

    /**
     * Relasi ke HomeServis.
     * CATATAN: Kolom booking_id digunakan secara dual-purpose:
     * - Ketika sumber='booking': booking_id merujuk ke bookingservis.id_booking
     * - Ketika sumber='home_service': booking_id merujuk ke homeservice.id_home_service
     * Ini untuk menghindari penambahan kolom baru.
     */
    public function homeServis()
    {
        return $this->belongsTo(HomeServis::class, 'booking_id', 'id_home_service');
    }
}
