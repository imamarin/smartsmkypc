<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    //
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'kode_guru', 'kode_guru');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'idkelas');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
