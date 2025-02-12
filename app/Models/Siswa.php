<?php

namespace App\Models;

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
}
