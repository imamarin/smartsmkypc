<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetailNilaiSiswa extends Model
{
    //
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function nilaisiswa()
    {
        return $this->belongsTo(NilaiSiswa::class, 'idnilaisiswa', 'idnilaisiswa');
    }
}
