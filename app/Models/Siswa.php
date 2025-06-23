<?php

namespace App\Models;

use App\Models\Keuangan\NonSpp;
use App\Models\Keuangan\Spp;
use App\Models\Raport\AbsensiRaport;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\KenaikanKelas;
use App\Models\Raport\NilaiEkstrakurikuler;
use App\Models\Raport\NilaiPrakerin;
use App\Models\Raport\NilaiSikap;
use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];
    protected $primaryKey = "nisn";
    protected $keyType = "string";

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function rombel()
    {
        return $this->hasMany(Rombel::class, 'nisn', 'nisn');
    }

    public function detailpresensi()
    {
        return $this->hasMany(DetailPresensi::class, 'nisn', 'nisn');
    }

    public function presensihariansiswa()
    {
        return $this->hasMany(PresensiHarianSiswa::class, 'nisn', 'nisn');
    }

    public function detailnilaisiswa()
    {
        return $this->hasMany(DetailNilaiSiswa::class, 'nisn', 'nisn');
    }

    public function detailnilairaport()
    {
        return $this->hasMany(DetailNilaiRaport::class, 'nisn', 'nisn');
    }

    public function absensiraport()
    {
        return $this->hasMany(AbsensiRaport::class, 'nisn', 'nisn');
    }

    public function nilaisikap()
    {
        return $this->hasMany(NilaiSikap::class, 'nisn', 'nisn');
    }

    public function nilaiekstrakurikuler()
    {
        return $this->hasMany(NilaiEkstrakurikuler::class, 'nisn', 'nisn');
    }

    public function kenaikankelas()
    {
        return $this->hasMany(KenaikanKelas::class, 'nisn', 'nisn');
    }

    public function nilaiprakerin()
    {
        return $this->hasOne(NilaiPrakerin::class, 'nisn', 'nisn');
    }

    public function spp()
    {
        return $this->hasMany(Spp::class, 'nisn', 'nisn');
    }

    public function nonspp()
    {
        return $this->hasMany(NonSpp::class, 'nisn', 'nisn');
    }
}
