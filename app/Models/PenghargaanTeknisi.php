<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class PenghargaanTeknisi extends Model
{
    protected $table = 'penghargaan_teknisi';
    protected $primaryKey = 'id';

    public $timestamps = true;

    protected $fillable = [
        'teknisi_id',
        'cabang_id',
        'periode_tipe',
        'periode_mulai',
        'periode_selesai',
        'jumlah_servis',
        'rata_rata_rating',
        'skor_total',
        'peringkat',
    ];

    public function teknisi()
    {
        return $this->belongsTo(Teknisi::class, 'teknisi_id', 'id_teknisi');
    }

    public function cabang()
    {
        return $this->belongsTo(Cabang::class, 'cabang_id', 'id_cabang');
    }
}
