<?php

namespace App\Models\Raport;

use Illuminate\Database\Eloquent\Model;

class Ekstrakurikuler extends Model
{
    //
    protected $table = 'rpt_ekstrakurikulers';
    protected $guarded = [];

    public function nilaiekstrakurikuler(){
        return $this->hasMany(NilaiEkstrakurikuler::class, 'idekstrakurikuler');
    }
}
