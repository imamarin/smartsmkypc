<?php

namespace App\Exports;

use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class RombelExport implements FromView
{
    protected $kelas;


    public function __construct($kelas)
    {
        $this->kelas = $kelas;
    }
    public function view(): View
    {
        // dd($this->kelas);
        return view('exports.rombel_export', ['kelas' => $this->kelas]);
    }
}
