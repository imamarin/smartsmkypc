<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HakAkses extends Model
{
    protected $guarded = [];

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole');
    }

    public function fitur()
    {
        return $this->belongsTo(Fitur::class, 'idfitur');
    }
}
