<?php

namespace App\Imports;

use App\Models\Siswa;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Collection;
use Maatwebsite\Excel\Concerns\ToCollection;
use PhpOffice\PhpSpreadsheet\Shared\Date;

class SiswaImport implements ToCollection
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
            $siswa = Siswa::where("nisn", $row[1])->first();
            $user = User::where("username", $row[28])->first();
            if (!$siswa && !$user) {
                $user = User::create([
                    'username' => $row[28],
                    'password' => bcrypt($row[29])
                ]);

                Siswa::create([
                    "nisn" => $row[1],
                    "nis" => $row[2],
                    "nama" => $row[3],
                    "tempat_lahir" => $row[4],
                    "tanggal_lahir" => Date::excelToDateTimeObject($row[5])->format('Y-m-d'),
                    "jenis_kelamin" => $row[6],
                    "nik" => $row[7],
                    "asal_sekolah" => $row[8],
                    "no_hp_siswa" => $row[9],
                    "nama_ayah" => $row[10],
                    "nama_ibu" => $row[11],
                    "pekerjaan_ayah" => $row[12],
                    "pekerjaan_ibu" => $row[13],
                    "no_hp_ortu" => $row[14],
                    "alamat_ortu" => $row[15],
                    "alamat_siswa" => $row[16],
                    "agama" => $row[17],
                    "status_keluarga" => $row[18],
                    "anak_ke" => $row[19],
                    "walisiswa" => $row[20],
                    "alamat_wali" => $row[21],
                    "no_hp_wali" => $row[22],
                    "pekerjaan_wali" => $row[23],
                    "diterima_tanggal" => Date::excelToDateTimeObject($row[24])->format('Y-m-d'),
                    "status" => $row[25],
                    "kelas" => $row[26],
                    "idtahunajaran" => $row[27],
                    "iduser" => $user->id,
                ]);

                $this->successCount++;
            }
        }
    }
}
