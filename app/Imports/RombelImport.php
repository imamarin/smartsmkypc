<?php

namespace App\Imports;

use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;

class RombelImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    protected $idkelas;
    protected $idtahunajaran;
    public $successCount = 0;

    public function __construct($id)
    {
        $this->idkelas = $id[0];
        $this->idtahunajaran = $id[1];
    }


    public function collection(Collection $collection)
    {
        //
        $rows = $collection->skip(1);
        foreach ($rows as $key => $row) {
            $siswa = Siswa::where('nisn', $row[1])->first();
            if ($siswa) {
                Rombel::updateOrCreate([
                    "nisn" => $row[1],
                    "idtahunajaran" => $this->idtahunajaran,
                ], [
                    "idkelas" => $this->idkelas
                ]);

                $this->successCount++;
            }
        }
    }
}
