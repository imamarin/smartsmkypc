<?php

namespace App\Models\Raport;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class IdentitasRaport extends Model
{
    //
    protected $table = 'rpt_identitasi_raports';
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
