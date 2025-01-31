<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Siswa extends Model
{
    protected $guarded = [];
    protected $primaryKey = "nisn";

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
}
