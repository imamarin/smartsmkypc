<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Matpel extends Model
{
    protected $guarded = [];
    protected $primaryKey = "kode_matpel";
    public $incrementing = false;
    protected $keyType = 'string';

    public function matpelpengampu()
    {
        return $this->hasMany(MatpelPengampu::class, 'kode_matpel', 'kode_matpel');
    }

    public function jadwalmengajar()
    {
        return $this->hasMany(JadwalMengajar::class, 'kode_matpel', 'kode_matpel');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'kode_guru', 'kode_guru');
    }

    public function parent()
    {
        return $this->belongsTo(Matpel::class, 'parent_id');
    }

    public function children()
    {
        return $this->hasMany(Matpel::class, 'parent_id');
    }
}
