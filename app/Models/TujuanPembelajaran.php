<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TujuanPembelajaran extends Model
{
    //
    protected $guarded = [];

    public function cp()
    {
        return $this->belongsTo(CapaianPembelajaran::class, 'kode_cp', 'kode_cp');
    }

    public function nilaisiswakurmer()
    {
        return $this->hasMany(NilaiSiswaKurmer::class, 'idtujuanpembelajaran');
    }
}
