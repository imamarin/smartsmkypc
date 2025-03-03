<?php

namespace App\Models\Raport;

use App\Models\Kelas;
use App\Models\Matpel;
use App\Models\Staf;
use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class NilaiRaport extends Model
{
    //
    protected $table = 'rpt_nilai_raports';
    protected $guarded = [];

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

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }
}
