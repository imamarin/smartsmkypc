<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Role extends Model
{
    protected $guarded = [];
    
    public function hak_akses()
    {
        return $this->hasMany(HakAkses::class, 'idrole');
    }
    public function user_role()
    {
        return $this->hasMany(UserRole::class, 'idrole');
    }
}
