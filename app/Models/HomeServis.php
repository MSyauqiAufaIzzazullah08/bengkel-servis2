<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HomeServis extends Model
{
    protected $table = 'homeservice';
    protected $primaryKey = 'id_home_service';
    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'id_kendaraan',
        'id_cabang',
        'id_layanan_servis',
        'id_montir',
        'alamat',
        'tanggal',
        'status',
        'catatan',
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

    public function montirLapangan()
    {
        return $this->belongsTo(
            MontirLapangan::class,
            'id_montir',
            'id_montir'
        );
    }

    
public function layananServis()
{
    return $this->belongsTo(
        LayananServis::class,
        'id_layanan_servis',
        'id_layanan'
    );
}


    public function pembayaran()
    {
        return $this->hasMany(
            Pembayaran::class,
            'id_home_service',
            'id_home_service'
        );
    }
}
