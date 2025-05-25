<?php

namespace App\Http\Controllers\Raport\Kurtilas\VersionCetak;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\Ekstrakurikuler;
use App\Models\Raport\Format;
use App\Models\Raport\MatpelKelas;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class V1Controller extends Controller
{
    //
    public function index($aktivasi, $page, $kelas, $start, $end)
    {
        //
        if ($page == "cover") {
            return $this->cover($aktivasi, $kelas, $start, $end);
        } else if ($page == "raport1") {
            return $this->raport1($aktivasi, $kelas, $start, $end);
        } else if ($page == "peringkat") {
            return $this->ranking($aktivasi, $kelas);
        } else {
            return redirect()->back();
        }
    }

    public function cover($aktivasi, $id, $start, $end)
    {
        $data['aktivasi'] = $aktivasi;
        $data['siswa'] = Siswa::whereHas('rombel', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        })
            ->offset($start - 1)->limit($end)
            ->get();

        return view("pages.eraports.kurtilas.cetak.v1.cover", $data);
    }

    public function raport1($aktivasi, $id, $start, $end)
    {
        $data['aktivasi'] = $aktivasi;
        $data['walikelas'] = Walikelas::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'idkelas' => $id
        ])->first();

        $data['siswa'] = Siswa::with(['nilaiekstrakurikuler' => function ($query) use ($aktivasi) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'semester' => $aktivasi->semester
            ]);
        }, 'absensiraport' => function ($query) use ($aktivasi) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'semester' => $aktivasi->semester
            ]);
        }, 'kenaikankelas' => function ($query) use ($aktivasi) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
            ]);
        }])->whereHas('rombel', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        })
            ->offset($start - 1)->limit($end)
            ->get();

        $matpelkelas = MatpelKelas::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'semester' => $aktivasi->semester,
            'idkelas' => $id
        ])
            ->orderBy('kelompok_matpel', 'asc')
            ->get();

        $data['kelompok_matpel_A'] = $matpelkelas->filter(function ($item) {
            return $item['kelompok_matpel'] == 'A';
        });

        $data['kelompok_matpel_B'] = $matpelkelas->filter(function ($item) {
            return $item['kelompok_matpel'] == 'B';
        });

        $data['kelompok_matpel_C'] = $matpelkelas->filter(function ($item) {
            return $item['kelompok_matpel'] == 'C';
        });

        $detailnilairaport = DetailNilaiRaport::whereHas('nilairaport', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id,
                'semester' => $aktivasi->semester
            ]);
        })->get();
        $pengetahuan = [];
        $keterampilan = [];
        $kkm = [];
        $bobot_pengetahuan = [];
        $bobot_keterampilan = [];
        $kkm = [];
        foreach ($detailnilairaport as $item) {
            # code...
            $pengetahuan[$item->nilairaport->kode_matpel][$item->nisn][$item->nilairaport->nip] = $item->nilai_1;
            $keterampilan[$item->nilairaport->kode_matpel][$item->nisn][$item->nilairaport->nip] = $item->nilai_2;
            $kkm[$item->nilairaport->kode_matpel][$item->nilairaport->nip] = $item->nilairaport->kkm;
            $bobot_pengetahuan[$item->nilairaport->kode_matpel][$item->nilairaport->nip] = $item->nilairaport->bobot_pengetahuan;
            $bobot_keterampilan[$item->nilairaport->kode_matpel][$item->nilairaport->nip] = $item->nilairaport->bobot_keterampilan;
        }

        $data['pengetahuan'] = $pengetahuan;
        $data['keterampilan'] = $keterampilan;
        $data['kkm'] = $kkm;
        $data['bobot_pengetahuan'] = $bobot_pengetahuan;
        $data['bobot_keterampilan'] = $bobot_keterampilan;

        return view("pages.eraports.kurtilas.cetak.v1.raport1", $data);
    }

    public function ranking($aktivasi, $id)
    {

        $matpelkelas = MatpelKelas::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'semester' => $aktivasi->semester,
            'idkelas' => $id
        ])
            ->orderBy('kelompok_matpel', 'asc')
            ->get();

        $data['detailnilairaport'] = DetailNilaiRaport::select('rpt_detail_nilai_raports.*')->selectRaw('AVG(rpt_detail_nilai_raports.nilai_1) AS rata')
            ->whereHas('nilairaport', function ($query) use ($aktivasi, $id, $matpelkelas) {
                $query->where([
                    'idtahunajaran' => $aktivasi->idtahunajaran,
                    'idkelas' => $id,
                    'semester' => $aktivasi->semester
                ])
                    ->whereIn('kode_matpel', $matpelkelas->pluck('kode_matpel'))
                    ->whereIn('nip', $matpelkelas->pluck('nip'));
            })->groupBy('rpt_detail_nilai_raports.nisn')->orderBy('rata', 'desc')->get();

        $data['aktivasi'] = $aktivasi;
        $data['kelas'] = Kelas::find($id);
        return view('pages.eraports.kurtilas.cetak.v1.ranking', $data);
    }
}
