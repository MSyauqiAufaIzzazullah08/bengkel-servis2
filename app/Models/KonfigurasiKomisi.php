<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KonfigurasiKomisi extends Model
{
    protected $table = 'konfigurasi_komisi';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'target_bulanan',
        'komisi_per_servis',
        'bonus_target_bulanan',
        'berlaku_mulai',
        'created_by',
    ];

    /**
     * Scope konfigurasi yang sedang berlaku (tanggal berlaku_mulai <= hari ini,
     * ambil yang paling baru).
     */
    public function scopeAktif($query)
    {
        return $query
            ->where('berlaku_mulai', '<=', now()->toDateString())
            ->orderByDesc('berlaku_mulai')
            ->limit(1);
    }
}
