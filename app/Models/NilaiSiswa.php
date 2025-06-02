<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class NilaiSiswa extends Model
{
    //
    protected $guarded = [];

    public function staf()
    {
        return $this->belongsTo(Staf::class, 'nip', 'nip');
    }

    public function matpel()
    {
        return $this->belongsTo(Matpel::class,  'kode_matpel', 'kode_matpel');
    }

    public function kelas()
    {
        return $this->belongsTo(Kelas::class, 'idkelas');
    }

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function detailnilaisiswa()
    {
        return $this->hasMany(DetailNilaiSiswa::class, 'idnilaisiswa');
    }

    public function kurmer()
    {
        return $this->hasOne(NilaiSiswaKurmer::class, 'idnilaisiswa');
    }
}
