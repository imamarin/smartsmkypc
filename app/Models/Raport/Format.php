<?php

namespace App\Models\Raport;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class Format extends Model
{
    //
    protected $table = "rpt_formats";
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
