<?php

namespace App\Models;

use App\Models\Raport\NilaiRaport;
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
        return $this->hasMany(Walikelas::class, 'idkelas');
    }

    public function nilaisiswa()
    {
        return $this->hasMany(NilaiSiswa::class, 'idkelas');
    }

    public function nilairaport()
    {
        return $this->hasMany(NilaiRaport::class, 'idkelas');
    }
}
