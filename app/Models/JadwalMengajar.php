<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class JadwalMengajar extends Model
{
    //
    protected $guarded = [];

    public function matpel()
    {
        return $this->belongsTo(Matpel::class, 'kode_matpel', 'kode_matpel');
    }

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'kode_guru', 'kode_guru');
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
}
