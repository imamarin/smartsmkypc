<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Staf extends Model
{
    protected $guarded = [];
    protected $primaryKey = "nip";
    public $incrementing = false;
    protected $keyType = "string";

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function walikelas()
    {
        return $this->hasMany(Walikelas::class, 'nip', 'nip');
    }

    public function jadwalmengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'nip', 'nip');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'nip', 'nip');
    }

    protected $casts = [
        'id' => 'string',
    ];
}
