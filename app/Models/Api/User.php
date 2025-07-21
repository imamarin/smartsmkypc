<?php

namespace App\Models\Api;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Foundation\Auth\User as Authenticatable;
use Laravel\Sanctum\HasApiTokens;

class User extends Authenticatable
{
    //
    use HasApiTokens;

    protected $table = 'users_api';
    protected $guarded = [];
}
