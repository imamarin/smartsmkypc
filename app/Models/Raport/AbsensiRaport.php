<?php

namespace App\Models\Raport;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class AbsensiRaport extends Model
{
    //
    protected $table = 'rpt_absensi_raports';
    protected $guarded = [];

    public function siswas()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
