<?php

namespace App\Models;

use App\Models\Raport\IdentitasRaport;
use Illuminate\Database\Eloquent\Model;

class TahunAjaran extends Model
{
    protected $guarded  = [];

    public function kelas()
    {
        return $this->hasMany(Kelas::class, 'idtahunajaran');
    }

    public function jurusan()
    {
        return $this->hasMany(Jurusan::class, 'idtahunajaran');
    }

    public function siswa()
    {
        return $this->hasMany(Siswa::class, 'idtahunajaran');
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'idtahunajaran');
    }

    public function walikelas()
    {
        return $this->hasMany(Walikelas::class, 'idtahunajaran');
    }

    public function matpelpengampu()
    {
        return $this->hasMany(MatpelPengampu::class, 'idtahunajaran');
    }

    public function jampelajaran()
    {
        return $this->hasMany(JamPelajaran::class, 'idtahunajaran');
    }

    public function sistemblok()
    {
        return $this->hasMany(SistemBlok::class, 'idtahunajaran');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'idtahunajaran');
    }

    public function presensihariansiswa()
    {
        return $this->hasMany(PresensiHarianSiswa::class, 'idtahunajaran');
    }

    public function nilaisiswa()
    {
        return $this->hasMany(NilaiSiswa::class, 'idtahunajaran');
    }

    public function kalenderakademik()
    {
        return $this->hasMany(KalenderAkademik::class, 'idtahunajaran');
    }

    public function identitasraport()
    {
        return $this->hasMany(IdentitasRaport::class, 'idtahunajaran');
    }
}
