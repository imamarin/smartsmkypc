<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class TokenMengajar extends Model
{
    //
    protected $guarded = [];

    public function jadwalmengajar()
    {
        return $this->belongsTo(JadwalMengajar::class, 'idjadwalmengajar');
    }
}
