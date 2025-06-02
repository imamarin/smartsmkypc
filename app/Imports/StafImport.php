<?php

namespace App\Imports;

use App\Models\Staf;
use App\Models\User;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class StafImport implements ToCollection
{
    /**
     * @param Collection $collection
     */
    public $successCount = 0;

    public function collection(Collection $collection)
    {
        //

        $rows = $collection->skip(1);
        foreach ($rows as $key => $row) {
            # code...
            $staf = Staf::where("nip", $row[1])->first();
            $user = User::where("username", $row[10])->first();
            if (!$staf && !$user) {
                $user = User::create([
                    'username' => $row[10],
                    'password' => bcrypt($row[11])
                ]);

                Staf::create([
                    "nip" => $row[1],
                    "nama" => $row[2],
                    "tempat_lahir" => $row[3],
                    "tanggal_lahir" => Date::excelToDateTimeObject($row[4])->format('Y-m-d'),
                    "jenis_kelamin" => $row[5],
                    "nuptk" => $row[6],
                    "no_hp" => $row[7],
                    "alamat" => $row[8],
                    "status" => $row[9],
                    "iduser" => $user->id,
                ]);

                $this->successCount++;
            }
        }
    }
}
