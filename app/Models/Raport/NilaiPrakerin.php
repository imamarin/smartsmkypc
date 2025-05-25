<?php

namespace App\Models\Raport;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class NilaiPrakerin extends Model
{
    //
    protected $table = 'rpt_nilai_prakerins';
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
