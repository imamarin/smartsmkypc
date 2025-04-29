<?php

namespace App\Models\Raport;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;

class KenaikanKelas extends Model
{
    //
    protected $guarded = [];
    protected $table = 'rpt_kenaikan_kelas';

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
