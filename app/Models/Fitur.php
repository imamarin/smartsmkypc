<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Fitur extends Model
{
    protected $guarded = [];

    public function menu()
    {
        return $this->belongsTo(Menu::class, 'idmenu');
    }

    public function hakakses()
    {
        return $this->hasMany(HakAkses::class, 'idfitur');
    }
}
