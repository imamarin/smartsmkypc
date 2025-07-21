<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Rombel;
use Illuminate\Http\Request;
use App\Models\Siswa;


class SiswaController extends Controller
{
    //
    public function index()
    {
        $siswa = Siswa::with('user')->get();
        return response()->json($siswa);
    }

    public function getSiswaByClass($class)
    {
        $rombel = Rombel::whereHas('kelas', function ($query) use ($class) {
            $query->where('tingkat', $class)->whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            });
        })->get();

        $result = $rombel->map(function ($item) {
            return [
                'user' => [
                    'id' => $item->siswa->user->id,
                    'username' => $item->siswa->user->username,
                    'password' => $item->siswa->user->password
                ],
                'siswa' => [
                    'id' => $item->siswa->nisn,
                    'nisn' => $item->siswa->nisn_dapodik,
                    'jk' => $item->siswa->jenis_kelamin,
                    'nama' => $item->siswa->nama,
                    'kelas' => $item->kelas->kelas,
                    'no_hp' => $item->siswa->no_hp_siswa,
                    'alamat' => $item->siswa->alamat_siswa,
                    'jurusan' => $item->kelas->jurusan->kompetensi
                ]
            ];
        });
        return response()->json($result);
    }

    public function getById(Request $request)
    {
        $siswa = Siswa::find($request->id);
        return response()->json($siswa);
    }

    public function getSiswaByIdTahunAjaran(Request $request)
    {
        $idtahunajaran = $request->idtahunajaran;
        $kdkelas = $request->kdkelas;
        $siswa = Siswa::with(['siswakelas' => function ($query) use ($idtahunajaran) {
            $query->where('siswakelas.idtahunajaran', $idtahunajaran);
            // $query->limit(1);
        }])
            ->whereHas('siswakelas', function ($query) use ($idtahunajaran) {
                $query->where('siswakelas.idtahunajaran', $idtahunajaran);
            })->get();

        $result = $siswa->map(function ($item) {
            if ($item->siswakelas->count() > 0) {
                $kelas = $item->siswakelas->first();
                $kdkelas = $kelas->kdkelas;
            } else {
                $kdkelas = "-";
            }
            return [
                'id' => $item->nisn,
                'nisn' => $item->nisn3,
                'nama' => $item->nama,
                'kelas' => $kdkelas,
                'no_hp' => $item->hp_siswa,
                'alamat' => $item->alamat_siswa
            ];
        });


        return response()->json($result);
    }
}
