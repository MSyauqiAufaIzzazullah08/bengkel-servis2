<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class RiwayatVerifikasiLaporan extends Model
{
    protected $table = 'riwayat_verifikasi_laporan';
    protected $primaryKey = 'id';

    public $timestamps = false;

    const UPDATED_AT = null;

    protected $fillable = [
        'laporan_id',
        'admin_id',
        'aksi',
        'catatan',
    ];

    public function laporan()
    {
        return $this->belongsTo(LaporanServis::class, 'laporan_id', 'id');
    }

    public function admin()
    {
        return $this->belongsTo(User::class, 'admin_id', 'id_user');
    }
}
