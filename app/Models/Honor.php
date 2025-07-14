<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Honor extends Model
{
    //
    protected $guarded = [];

    public function honordetail()
    {
        return $this->hasMany(HonorDetail::class, 'idhonor');
    }
}
