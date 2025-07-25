<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class KasusSiswa extends Model
{
    //
    protected $guarded = [];

    public function siswa()
    {
        return $this->belongsTo(Siswa::class, 'nisn', 'nisn');
    }
}
