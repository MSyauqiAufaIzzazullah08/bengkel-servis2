<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Pembayaran extends Model
{
    protected $table = 'Pembayaran';

    protected $primaryKey = 'id_pembayaran';

    public $timestamps = false;

    protected $fillable = [
        'id_booking',
        'id_home_service',
        'id_order',
        'id_promo',
        'metode_pembayaran_id',
        'jumlah',
        'status',
        'tanggal_bayar',
        'bukti_pembayaran',
    ];

    public function bookingServis()
    {
        return $this->belongsTo(
            BookingServis::class,
            'id_booking',
            'id_booking'
        );
    }

    public function homeServis()
    {
        return $this->belongsTo(
            HomeServis::class,
            'id_home_service',
            'id_home_service'
        );
    }

    public function orderSparePart()
    {
        return $this->belongsTo(
            OrderSparePart::class,
            'id_order',
            'id_order'
        );
    }

    public function promo()
    {
        return $this->belongsTo(
            Promo::class,
            'id_promo',
            'id_promo'
        );
    }

    public function metodePembayaran()
    {
        return $this->belongsTo(
            MetodePembayaran::class,
            'metode_pembayaran_id',
            'id_metode'
        );
    }
}
