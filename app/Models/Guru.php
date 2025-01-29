<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = [];
    protected $primaryKey = "kode_guru";

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function walikelas()
    {
    return $this->hasMany(Walikelas::class, 'kode_guru', 'kode_guru');
    }
}
