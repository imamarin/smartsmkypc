<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SistemBlok extends Model
{
    //
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
