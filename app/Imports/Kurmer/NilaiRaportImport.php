<?php

namespace App\Imports\Kurmer;

use App\Models\Raport\DetailNilaiRaport;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class NilaiRaportImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    protected $nilairaport;
    public $successCount = 0;

    public function __construct($nilairaport)
    {
        $this->nilairaport = $nilairaport;
    }


    public function collection(Collection $collection)
    {
        //
        $rows = $collection->skip(3);
        foreach ($rows as $key => $row) {
            # code...
            if ($row[3] >= 0 && $row[3] <= 100) {
                DetailNilaiRaport::updateOrCreate([
                    'nisn' => $row[1],
                    'idnilairaport' => $this->nilairaport->id,
                ], [
                    'nilai_1' => $row[3],
                ]);
                $this->successCount++;
            }
        }
    }
}
