<?php

namespace App\Exports;

use App\Models\Siswa;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class NilaiSiswaExport implements FromView
{
    protected $data;

    public function __construct($data)
    {
        $this->data = $data;
    }
    public function view(): View
    {
        return view('exports.kurmer.nilai_siswa_export', $this->data);
    }
}
