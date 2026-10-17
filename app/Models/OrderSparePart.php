<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class OrderSparePart extends Model
{
    protected $table = 'ordersparepart';

    protected $primaryKey = 'id_order';

    public $timestamps = false;

    protected $fillable = [
        'id_pelanggan',
        'tanggal_order',
        'status',
        'total_harga',
        'alamat_pengiriman',
    ];

    public function pelanggan()
    {
        return $this->belongsTo(
            Pelanggan::class,
            'id_pelanggan',
            'id_pelanggan'
        );
    }

    public function orderDetail()
    {
        return $this->hasMany(
            OrderDetail::class,
            'id_order',
            'id_order'
        );
    }

    public function pengiriman()
    {
        return $this->hasOne(
            Pengiriman::class,
            'id_order',
            'id_order'
        );
    }

    public function pembayaran()
    {
        return $this->hasOne(
            Pembayaran::class,
            'id_order',
            'id_order'
        );
    }

    public function rating()
    {
        return $this->hasOne(
            Rating::class,
            'id_order',
            'id_order'
        );
    }
}
