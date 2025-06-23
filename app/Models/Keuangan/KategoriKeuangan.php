<?php

namespace App\Models\Keuangan;

use App\Models\TahunAjaran;
use Illuminate\Database\Eloquent\Model;

class KategoriKeuangan extends Model
{
    //
    protected $table = "fnc_kategori_keuangans";
    protected $guarded = [];

    public function tahunajaran()
    {
        return $this->belongsTo(TahunAjaran::class, 'idtahunajaran');
    }

    public function nonspp()
    {
        return $this->hasMany(NonSpp::class, 'idkategorikeuangan');
    }
}
