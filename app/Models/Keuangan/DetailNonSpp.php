<?php

namespace App\Models\Keuangan;

use Illuminate\Database\Eloquent\Model;

class DetailNonSpp extends Model
{
    //
    protected $table = 'fnc_detail_non_spps';
    protected $guarded = [];

    public function nonspp()
    {
        return $this->belongsTo(NonSpp::class, 'idnonspp');
    }
}
