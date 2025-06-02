<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSiswaKurmer extends Model
{
    //
    protected $guarded = [];

    public function nilaisiswa()
    {
        return $this->belongsTo(NilaiSiswa::class, 'idnilaisiswa');
    }

    public function tp()
    {
        return $this->belongsTo(TujuanPembelajaran::class, 'idtujuanpembelajaran');
    }
}
