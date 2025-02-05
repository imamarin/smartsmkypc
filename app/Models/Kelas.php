<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = [];

    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'idjurusan');
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'idkelas');
    }

    public function jadwalmengar()
    {
        return $this->hasMany(JadwalMengajar::class, 'idkelas');
    }

    public function presensi()
    {
        return $this->hasMay(Presensi::class, 'idkelas');
    }

    public function walikelas()
    {
        return $this->hasOne(Walikelas::class, 'idkelas');
    }
}
