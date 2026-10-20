<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Cabang extends Model
{
    protected $table = 'cabang';
    protected $primaryKey = 'id_cabang';
    public $timestamps = false;

    protected $fillable = [
        'nama_cabang',
        'alamat',
        'no_telepon',
        'status',
    ];

    public function operasionalCabang()
    {
        return $this->hasMany(OperasionalCabang::class, 'id_cabang', 'id_cabang');
    }

    public function adminCabang()
    {
        return $this->hasMany(AdminCabang::class, 'cabang_id', 'id_cabang');
    }
}
