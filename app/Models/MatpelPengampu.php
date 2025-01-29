<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class MatpelPengampu extends Model
{
    //
    protected $guarded = [];

    public function matpel()
    {
        return $this->belongsTo(Matpel::class, 'kode_matpel', 'kode_matpel');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
