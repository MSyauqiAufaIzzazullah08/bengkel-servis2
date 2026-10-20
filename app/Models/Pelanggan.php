<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pelanggan extends Model
{
    protected $table = 'pelanggan';
    protected $primaryKey = 'id_pelanggan';
    public $timestamps = false;

    protected $fillable = [
        'nama',
        'email',
        'no_hp',
        'alamat',
        'tanggal_daftar',
    ];

    public function kendaraan()
    {
        return $this->hasMany(Kendaraan::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function bookingServis()
    {
        return $this->hasMany(BookingServis::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function orderSparePart()
    {
        return $this->hasMany(OrderSparePart::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function homeServis()
    {
        return $this->hasMany(HomeServis::class, 'id_pelanggan', 'id_pelanggan');
    }

    public function rating()
    {
        return $this->hasMany(Rating::class, 'id_pelanggan', 'id_pelanggan');
    }
}
