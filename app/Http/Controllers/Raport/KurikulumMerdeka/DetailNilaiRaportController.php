<?php

namespace App\Http\Controllers\Raport\KurikulumMerdeka;

use App\Exports\NilaiSiswaExport;
use App\Http\Controllers\Controller;
use App\Imports\NilaiRaportImpor;
use App\Imports\Kurmer\NilaiRaportImport;
use App\Models\CapaianPembelajaran;
use App\Models\DetailNilaiSiswa;
use App\Models\Matpel;
use App\Models\PersentaseNilaiSiswa;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\NilaiCP;
use App\Models\Raport\NilaiRaport;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Maatwebsite\Excel\Facades\Excel;

class DetailNilaiRaportController extends Controller
{
    //
    protected $aktivasi;

    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');
    }

    public function index()
    {
        //
    }

    public function input($nilairaport, $id)
    {
        try {
            $data['id'] = Crypt::encrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $arr_matpel = [];
        $matpel = Matpel::where('matpels_kode', $nilairaport->kode_matpel)->get();
        if ($matpel->count() > 0) {
            foreach ($matpel as $key => $item) {
                $arr_matpel[] = $item->kode_matpel;
            }
            $data['cp'] = CapaianPembelajaran::whereIn('kode_matpel', $arr_matpel)->get();
        } else {
            $data['cp'] = CapaianPembelajaran::where('kode_matpel', $nilairaport->kode_matpel)->get();
        }

        $siswa = Siswa::whereHas('rombel', function ($query) use ($nilairaport) {
            $query->where([
                'idtahunajaran' => $nilairaport->idtahunajaran,
                'idkelas' => $nilairaport->idkelas
            ]);
        })->orderBy('nama', 'asc')->get();

        $detailnilairaport = DetailNilaiRaport::where([
            'idnilairaport' => $id
        ])->get();

        $nilai_pengetahuan = [];
        if ($detailnilairaport->count() > 0) {
            foreach ($detailnilairaport as $key => $value) {
                $nilai_pengetahuan[$value->nisn] = $value->nilai_1;
            }
            $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        } else {
            $nilaiakhir = $this->nilaiakhir($nilairaport);
            foreach ($nilaiakhir['nilaisiswa'] as $value) {
                # code...
                $nilai_pengetahuan[$value->nisn] = round(($value->nilai_tugas * $nilaiakhir['persen_tugas']) + ($value->nilai_sumatif * $nilaiakhir['persen_sumatif']) + ($value->nilai_uts * $nilaiakhir['persen_uts']) + ($value->nilai_uas * $nilaiakhir['persen_uas']));
            }
            $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        }

        $nilai_cp = [];
        $nilaicp = NilaiCP::where('idnilairaport', $id)->get();
        if ($nilaicp->count() > 0) {
            foreach ($nilaicp as $key => $value) {
                $nilai_cp[$value->nisn][$value->kode_cp] = $value->nilai;
            }
        }

        $data['nilai_cp'] = $nilai_cp;

        $data['nilairaport'] = $nilairaport;
        $data['siswa'] = $siswa;

        return view('pages.eraports.kurikulummerdeka.detailnilairaport.index', $data);
    }

    public function store(Request $request, String $id)
    {
        //
        $request->validate([
            'nilai_pengetahuan' => 'required|array',
            'nilai_pengetahuan.*' => 'integer|min:0|max:100',
        ]);

        foreach ($request->nilai_pengetahuan as $key => $value) {
            # code...
            DetailNilaiRaport::updateOrCreate([
                'nisn' => $key,
                'idnilairaport' => $id,
            ], [
                'nilai_1' => $value,
            ]);
        }

        foreach ($request->capaian as $key => $value) {
            # code...
            foreach ($value as $key2 => $value2) {
                # code...
                if ($value2 < 2) {
                    NilaiCP::updateOrCreate([
                        'nisn' => $key,
                        'idnilairaport' => $id,
                        'kode_cp' => $key2
                    ], [
                        'nilai' => $value2
                    ]);
                } else {
                    NilaiCP::where([
                        'nisn' => $key,
                        'idnilairaport' => $id,
                        'kode_cp' => $key2
                    ])->delete();
                }
            }
        }

        return redirect()->back()->with('success', 'Nilai raport berhasil di simpan');
    }

    public function nilaiakhir($nilairaport)
    {
        $data['nilaisiswa'] = DetailNilaiSiswa::selectRaw("
            siswas.nisn,
            siswas.nama,
            AVG(IF(nilai_siswas.kategori = 'tugas', detail_nilai_siswas.nilai, NULL)) as nilai_tugas,
            AVG(IF(nilai_siswas.kategori = 'sumatif', detail_nilai_siswas.nilai, NULL)) as nilai_sumatif,
            AVG(IF(nilai_siswas.kategori = 'uts', detail_nilai_siswas.nilai, NULL)) as nilai_uts,
            AVG(IF(nilai_siswas.kategori = 'uas', detail_nilai_siswas.nilai, NULL)) as nilai_uas
        ")
            ->join('siswas', 'siswas.nisn', '=', 'detail_nilai_siswas.nisn')
            ->join('nilai_siswas', 'nilai_siswas.id', '=', 'detail_nilai_siswas.idnilaisiswa')
            ->whereHas('nilaisiswa', function ($query) use ($nilairaport) {
                $query->where([
                    'idtahunajaran' => $nilairaport->idtahunajaran,
                    'semester' => $nilairaport->semester,
                    'kode_matpel' => $nilairaport->kode_matpel,
                    'idkelas' => $nilairaport->idkelas,
                    'nip' => $nilairaport->nip
                ]);
            })->groupBy('detail_nilai_siswas.nisn')->orderBy('siswas.nama')->get();

        $persen = PersentaseNilaiSiswa::where([
            'idtahunajaran' => $nilairaport->idtahunajaran,
            'semester' => $nilairaport->semester,
            'kode_matpel' => $nilairaport->kode_matpel,
            'idkelas' => $nilairaport->idkelas,
            'nip' => $nilairaport->nip
        ])->first();

        $data['persen_tugas'] = ($persen->tugas ?? 25) / 100;
        $data['persen_sumatif'] = ($persen->sumatif ?? 25) / 100;
        $data['persen_uts'] = ($persen->uts ?? 25) / 100;
        $data['persen_uas'] = ($persen->uas ?? 25) / 100;

        return $data;
    }

    public function export($nilairaport, $id)
    {
        try {
            $data['id'] = Crypt::encrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $siswa = Siswa::whereHas('rombel', function ($query) use ($nilairaport) {
            $query->where([
                'idtahunajaran' => $nilairaport->idtahunajaran,
                'idkelas' => $nilairaport->idkelas
            ]);
        })->orderBy('nama', 'asc')->get();

        $detailnilairaport = DetailNilaiRaport::where([
            'idnilairaport' => $id
        ])->get();

        $nilai_pengetahuan = [];
        if ($detailnilairaport->count() > 0) {
            foreach ($detailnilairaport as $key => $value) {
                $nilai_pengetahuan[$value->nisn] = $value->nilai_1;
            }
            $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        } else {
            $nilaiakhir = $this->nilaiakhir($nilairaport);
            foreach ($nilaiakhir['nilaisiswa'] as $value) {
                # code...
                $nilai_pengetahuan[$value->nisn] = round(($value->nilai_tugas * $nilaiakhir['persen_tugas']) + ($value->nilai_sumatif * $nilaiakhir['persen_sumatif']) + ($value->nilai_uts * $nilaiakhir['persen_uts']) + ($value->nilai_uas * $nilaiakhir['persen_uas']));
            }
            $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        }

        $data['nilairaport'] = $nilairaport;
        $data['siswa'] = $siswa;

        return Excel::download(new NilaiSiswaExport($data), 'Nilai_Siswa_' . $nilairaport->kelas->kelas . '.xlsx');
    }

    public function import($nilairaport, Request $request)
    {

        $import = new NilaiRaportImport($nilairaport);
        Excel::import($import, $request->file('file'));

        return back()->with('success', "Berhasil mengimpor {$import->successCount} data.");
    }
}
