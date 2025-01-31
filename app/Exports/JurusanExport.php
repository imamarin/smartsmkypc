<?php

namespace App\Exports;

use App\Models\Jurusan;
use Illuminate\Contracts\View\View;
use Maatwebsite\Excel\Concerns\FromCollection;
use Maatwebsite\Excel\Concerns\FromView;

class JurusanExport implements FromView
{
    public function view(): View
    {
        return view('exports.jurusan_export', ['jurusan' => Jurusan::with('tahunajaran')->get()]);
    }
}
