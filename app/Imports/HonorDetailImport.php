<?php

namespace App\Imports;

use App\Models\Honor;
use App\Models\HonorDetail;
use App\Models\Staf;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class HonorDetailImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public $successCount = 0;
    protected $id;

    public function __construct($id)
    {
        $this->id = $id;
    }

    public function collection(Collection $collection)
    {
        //
        $rows = $collection->skip(1);
        foreach ($rows as $row) {
            # code...
            $staf = Staf::where("nip", $row[1])->first();
            if ($staf) {
                HonorDetail::updateOrCreate(
                    [
                        "nip" => $row[1],
                        "idhonor" => $this->id,
                    ],
                    [
                        "nip" => $row[1],
                        "jml_jam" => $row[2],
                        "bonus_hdr" => $row[3],
                        "yayasan" => $row[4],
                        "tun_jab_bak" => $row[5],
                        "tunjab" => $row[6],
                        "honor" => $row[7],
                        "sub_non_ser" => $row[8],
                        "jml" => $row[9],
                        "tabungan" => $row[10],
                        "arisan" => $row[11],
                        "qurban" => $row[12],
                        "kas_1" => $row[13],
                        "kas_2" => $row[14],
                        "lainnya" => $row[15],
                        "jum_tal" => $row[16],
                    ]
                );

                $this->successCount++;
            }
        }
    }
}
