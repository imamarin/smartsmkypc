<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Presensi extends Model
{
    //
    protected $guarded = [];

    public function guru()
    {
        return $this->belongsTo(Guru::class, 'kode_guru', 'kode_guru');
    }

    public function matpel()
    {
        return $this->belongsTo(Matpel::class, 'kode_matpel', 'kode_matpel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'idkelas');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function jadwalmengajar()
    {
        return $this->belongsTo(JadwalMengajar::class, 'dijadwalmengajar');
    }
}
