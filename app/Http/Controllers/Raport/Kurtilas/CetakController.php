<?php

namespace App\Http\Controllers\Raport\Kurtilas;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Raport\KurikulumMerdeka\VersionCetak\V1Controller;
use App\Http\Controllers\Raport\KurikulumMerdeka\VersionCetak\V2Controller;
use App\Http\Controllers\Raport\Kurtilas\VersionCetak\V1Controller as VersionCetakV1Controller;
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

class CetakController extends Controller
{
    protected $aktivasi;


    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');

        $title = 'Raport Absensi Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    //
    public function index()
    {
        $data['aktivasi'] = $this->aktivasi;

        $data['kelas'] = Walikelas::select('walikelas.*', 'kelas.kelas as kls', 'kelas.tingkat as tingkat', DB::raw('(SELECT versi FROM rpt_formats WHERE idtahunajaran = walikelas.idtahunajaran AND tingkat = kelas.tingkat) as versi'))
            ->join('kelas', 'kelas.id', '=', 'walikelas.idkelas')
            ->where([
                'walikelas.idtahunajaran' => $this->aktivasi->idtahunajaran,
                'nip' => Auth::user()->staf->nip
            ])->get();

        return view('pages.eraports.cetak.index', $data);
    }

    public function page(string $page, string $id, string $start, string $end)
    {
        try {
            $id = explode("*", Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $versi = Format::where('tingkat', $id[2])->first();
        if($versi->kurikulum == 'kurikulummerdeka'){
            if ($versi->versi == '1') {
                $v = new V1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        }else if($versi->kurikulum == 'kurtilas'){
            if ($versi->versi == '1') {
                $v = new VersionCetakV1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        }
    }

    public function cover($id, $start, $end, $versi)
    {
        $aktivasi = $this->aktivasi;
        $data['aktivasi'] = $aktivasi;
        $data['siswa'] = Siswa::whereHas('rombel', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        })
            ->offset($start - 1)->limit($end)
            ->get();

        return view("pages.eraports.cetak.v{$versi}.cover", $data);
    }

    public function raport1($id, $start, $end, $versi)
    {
        $aktivasi = $this->aktivasi;
        $data['aktivasi'] = $aktivasi;
        $data['siswa'] = Siswa::whereHas('rombel', function ($query) use ($aktivasi, $id) {
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

        return view("pages.eraports.cetak.v{$versi}.raport1", $data);
    }

    public function raport2($id, $start, $end, $versi)
    {
        //
        $aktivasi = $this->aktivasi;
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

        return view("pages.eraports.cetak.v{$versi}.raport2", $data);
    }
}
