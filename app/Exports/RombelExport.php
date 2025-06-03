<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RombelExport implements FromView
{
    protected $rombel;
    protected $kelas;


    public function __construct($rombel, $kelas)
    {
        $this->rombel = $rombel;
        $this->kelas = $kelas;
    }
    public function view(): View
    {
        return view('exports.rombel_export', ['rombel' => $this->rombel, 'kelas' => $this->kelas]);
    }
}
