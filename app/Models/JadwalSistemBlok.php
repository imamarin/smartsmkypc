<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalSistemBlok extends Model
{
    //
    protected $guarded = [];

    public function sistemblok()
    {
        return $this->belongsTo(SistemBlok::class, 'idsistemblok');
    }
}
