<?php

namespace App\Models;

use App\Models\Keuangan\KategoriKeuangan;
use App\Models\Raport\AbsensiRaport;
use App\Models\Raport\Format;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\MatpelKelas;
use App\Models\Raport\NilaiEkstrakurikuler;
use App\Models\Raport\NilaiPrakerin;
use App\Models\Raport\NilaiRaport;
use App\Models\Raport\NilaiSikap;
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

    public function nilairaport()
    {
        return $this->hasMany(NilaiRaport::class, 'idtahunajaran');
    }

    public function matpelkelas()
    {
        return $this->hasMany(MatpelKelas::class, 'idtahunajaran');
    }

    public function absensiraport()
    {
        return $this->hasMany(AbsensiRaport::class, 'idtahunajaran');
    }

    public function nilaisikap()
    {
        return $this->hasMany(NilaiSikap::class, 'idtahunajaran');
    }

    public function nilaiekstrakurikuler()
    {
        return $this->hasMany(NilaiEkstrakurikuler::class, 'idtahunajaran');
    }

    public function format()
    {
        return $this->hasMany(Format::class, 'idtahunjaran');
    }

    public function nilaiprakerin()
    {
        return $this->hasMany(NilaiPrakerin::class, 'idtahunajaran');
    }

    public function kategorikeuangan()
    {
        return $this->hasMany(KategoriKeuangan::class, 'idtahunajaran');
    }
}
