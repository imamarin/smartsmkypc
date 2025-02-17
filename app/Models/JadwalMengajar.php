<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class JadwalMengajar extends Model
{
    //
    protected $guarded = [];
    use SoftDeletes;
    protected $dates = ['deleted_at'];

    public function matpel()
    {
        return $this->belongsTo(Matpel::class, 'kode_matpel', 'kode_matpel');
    }

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'nip', 'nip');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'idkelas');
    }

    public function jampel()
    {
        return $this->belongsTo(JamPelajaran::class, 'idjampel');
    }

    public function sistemblok()
    {
        return $this->belongsTo(SistemBlok::class, 'idsistemblok');
    }

    public function presensi()
    {
        return $this->hasMany(Presensi::class, 'idjadwalmengajar');
    }

    public function ajuanpresensimengajar()
    {
        return $this->hasMany(AjuanPresensiMengajar::class, 'idjawalmengajar');
    }
}
