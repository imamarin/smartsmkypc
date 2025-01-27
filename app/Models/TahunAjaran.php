<?php

namespace App\Models;

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
}
