<?php

namespace App\Models\Keuangan;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;

class Spp extends Model
{
    //
    protected $table = "fnc_spps";
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
