<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KomisiTeknisi extends Model
{
    protected $table = 'komisi_teknisi';
    protected $primaryKey = 'id_komisi';

    public $timestamps = true;

    protected $fillable = [
        'id_teknisi',
        'id_penugasan',
        'id_booking',
        'id_home_service',
        'nominal_komisi',
        'tipe_servis',
        'is_bonus_target',
        'periode_bulan',
        'status_pembayaran',
        'tanggal_dibayar',
    ];

    protected $casts = [
        'periode_bulan'    => 'date',
        'tanggal_dibayar'  => 'datetime',
        'is_bonus_target'  => 'boolean',
        'nominal_komisi'   => 'decimal:2',
    ];

    // ----------------------------------------------------------------
    // Relations
    // ----------------------------------------------------------------

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'id_teknisi', 'id_teknisi');
    }

    public function penugasanTeknisi()
    {
        return $this->belongsTo(PenugasanTeknisi::class, 'id_penugasan', 'id_penugasan');
    }

    public function bookingServis()
    {
        return $this->belongsTo(BookingServis::class, 'id_booking', 'id_booking');
    }

    public function homeServis()
    {
        return $this->belongsTo(HomeServis::class, 'id_home_service', 'id_home_service');
    }
}
