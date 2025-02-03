<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Guru extends Model
{
    protected $guarded = [];
    protected $primaryKey = "kode_guru";
    public $incrementing = false;
    protected $keyType = "string";

    public function user()
    {
        return $this->belongsTo(User::class, 'iduser');
    }

    public function walikelas()
    {
        return $this->hasMany(Walikelas::class, 'kode_guru', 'kode_guru');
    }

    public function jadwalmengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'kode_guru', 'kode_guru');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_guru', 'kode_guru');
    }

    protected $casts = [
        'id' => 'string',
    ];
}
