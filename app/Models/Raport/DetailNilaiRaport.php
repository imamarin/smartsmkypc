<?php

namespace App\Models\Raport;

use App\Models\Siswa;
use Illuminate\Database\Eloquent\Model;

class DetailNilaiRaport extends Model
{
    //
    protected $table = 'rpt_detail_nilai_raports';
    protected $guarded = [];

    public function nilairaport()
    {
        return $this->belongsTo(NilaiRaport::class, 'idnilairaport');
    }

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
