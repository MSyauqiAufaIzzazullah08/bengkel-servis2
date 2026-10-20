<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class LayananServis extends Model
{
    protected $table = 'layananservis';
    protected $primaryKey = 'id_layanan';

    public $timestamps = false;

    protected $fillable = [
        'nama_layanan',
        'kategori',
        'harga',
        'estimasi_waktu',
        'deskripsi',
    ];

    public function bookingServis()
    {
        return $this->hasMany(
            BookingServis::class,
            'layanan_id',
            'id_layanan'
        );
    }

    public function detailServis()
    {
        return $this->hasMany(
            DetailServis::class,
            'layanan_id',
            'id_layanan'
        );
    }
}
