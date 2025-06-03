<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class WalikelasEksport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $walikelas;
    protected $tahunajaran;

    public function __construct($walikelas, $tahunajaran)
    {
        $this->walikelas = $walikelas;
        $this->tahunajaran = $tahunajaran;
    }

    public function view(): View
    {
        return view('exports.walikelas_export', ['walikelas' => $this->walikelas, 'tahunajaran' => $this->tahunajaran]);
    }
}
