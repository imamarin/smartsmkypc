<?php

namespace App\Models\Keuangan;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;

class NonSpp extends Model
{
    //
    protected $table = 'fnc_non_spps';
    protected $guarded = [];

    public function detailnonspp()
    {
        return $this->hasMany(DetailNonSpp::class, 'idnonspp');
    }

    public function kategorikeuangan()
    {
        return $this->belongsTo(KategoriKeuangan::class, 'idkategorikeuangan');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
