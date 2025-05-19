<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CapaianPembelajaran extends Model
{
    //
    protected $guarded = [];
    protected $primaryKey = "kode_cp";
    public $incrementing = false;
    protected $keyType = 'string';

    public function tp()
    {
        return $this->hasMany(TujuanPembelajaran::class, 'kode_cp', 'kode_cp');
    }

    public function matpel()
    {
        return $this->belongsTo(Matpel::class, "kode_matpel", "kode_matpel");
    }
}
