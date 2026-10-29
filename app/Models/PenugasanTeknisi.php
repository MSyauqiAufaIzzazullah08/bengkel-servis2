<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenugasanTeknisi extends Model
{
    protected $table = 'PenugasanTeknisi';
    protected $primaryKey = 'id_penugasan';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'id_teknisi',
        'tanggal',
        'status',
        'catatan',
        'metode_pemilihan',
        'ditetapkan_oleh',
        'tanggal_selesai',
    ];

    public function bookingServis()
    {
        return $this->belongsTo(
            BookingServis::class,
            'id_booking',
            'id_booking'
        );
    }

    public function teknisi()
    {
        return $this->belongsTo(
            Teknisi::class,
            'id_teknisi',
            'id_teknisi'
        );
    }

    public function laporanServis()
    {
        return $this->hasOne(LaporanServis::class, 'penugasan_id', 'id_penugasan');
    }
}