<?php

namespace App\Exports;

use App\Models\Kelas;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class KelasExport implements FromView
{
    public function view(): View
    {
        return view('exports.kelas_export', ['kelas' => Kelas::with('tahunajaran')->get()]);
    }
}
