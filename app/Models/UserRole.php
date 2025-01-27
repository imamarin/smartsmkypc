<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserRole extends Model
{
    protected $guarded = [];

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function role()
    {
        return $this->belongsTo(Role::class, 'idrole');
    }
}
