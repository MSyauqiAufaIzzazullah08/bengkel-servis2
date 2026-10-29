<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class BookingServis extends Model
{
    protected $table = 'bookingservis';
    protected $primaryKey = 'id_booking';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'id_kendaraan',
        'id_cabang',
        'layanan_id',
        'tanggal_booking',
        'waktu_booking',
        'status',
        'catatan',
        'sumber_pendaftaran',
        'didaftarkan_oleh',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'id_pelanggan',
            'id_pelanggan'
        );
    }

    public function kendaraan()
    {
        return $this->belongsTo(
            Kendaraan::class,
            'id_kendaraan',
            'id_kendaraan'
        );
    }

    public function cabang()
    {
        return $this->belongsTo(
            Cabang::class,
            'id_cabang',
            'id_cabang'
        );
    }

    public function layananServis()
    {
        return $this->belongsTo(
            LayananServis::class,
            'layanan_id',
            'id_layanan'
        );
    }

    public function detailServis()
    {
        return $this->hasMany(
            DetailServis::class,
            'booking_id',
            'id_booking'
        );
    }

    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_booking',
            'id_booking'
        );
    }

    public function rating()
    {
        return $this->hasOne(
            Rating::class,
            'id_booking',
            'id_booking'
        );
    }

    public function penugasanTeknisi()
    {
        return $this->hasOne(
            PenugasanTeknisi::class,
            'id_booking',
            'id_booking'
        );
    }

    public function laporanServis()
    {
        return $this->hasOne(LaporanServis::class, 'booking_id', 'id_booking');
    }
}