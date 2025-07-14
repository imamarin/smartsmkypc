<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HonorDetail extends Model
{
    //
    protected $guarded = [];

    public function honor()
    {
        return $this->belongsTo(Honor::class, 'idhonor');
    }

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'nip', 'nip');
    }
}
