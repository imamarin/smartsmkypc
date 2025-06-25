<?php

namespace App\Models\Raport;

use App\Models\CapaianPembelajaran;
use Illuminate\Database\Eloquent\Model;

class NilaiCP extends Model
{
    //
    protected $table = 'rpt_nilai_cps';
    protected $guarded = [];

    public function nilairaport()
    {
        return $this->belongsTo(NilaiRaport::class, 'idnilairaport');
    }

    public function capaianpembelajaran()
    {
        return $this->belongsTo(CapaianPembelajaran::class, 'kode_cp', 'kode_cp');
    }
}
