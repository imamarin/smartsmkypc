<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Walikelas extends Model
{
    //
    protected $guarded = [];

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'nip', 'nip');
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
