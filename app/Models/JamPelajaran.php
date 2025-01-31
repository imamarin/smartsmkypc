<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JamPelajaran extends Model
{
    //
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function jadwalmengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'idjampel');
    }
}
