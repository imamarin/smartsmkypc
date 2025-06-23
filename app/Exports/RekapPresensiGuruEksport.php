<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class RekapPresensiGuruEksport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $data;
    protected $tahunajaran;

    public function __construct($data, $tahunajaran)
    {
        $this->data = $data;
    }

    public function view(): View
    {

        return view('exports.rekappresensiguru_export', $this->data);
    }
}
