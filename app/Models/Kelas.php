<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Kelas extends Model
{
    protected $guarded = [];
    public function tahunAjaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function jurusan()
    {
        return $this->belongsTo(Jurusan::class, 'idjurusan');
    }
}
