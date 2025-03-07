<?php

namespace App\Models\Raport;

use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class NilaiEkstrakurikuler extends Model
{
    //
    protected $table = 'rpt_nilai_ekstrakurikulers';
    protected $guarded = [];

    public function ekstrakurikuler()
    {
        return $this->belongsTo(Ekstrakurikuler::class, 'idekstrakurikuler');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn');
    }
}
