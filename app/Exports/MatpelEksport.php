<?php

namespace App\Exports;

use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;
use Illuminate\Contracts\View\View;

class MatpelEksport implements FromView
{
    /**
     * @return \Illuminate\Support\Collection
     */
    protected $matpel;
    public function __construct($matpel)
    {
        $this->matpel = $matpel;
    }

    public function view(): View
    {
        return view('exports.matpel_export', ['matpel' => $this->matpel]);
    }
}
