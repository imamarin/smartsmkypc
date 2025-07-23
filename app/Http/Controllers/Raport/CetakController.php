<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Raport\KurikulumMerdeka\VersionCetak\V1Controller as VersionCetakV1Controller;
use App\Http\Controllers\Raport\Kurtilas\VersionCetak\V1Controller as KurtilasVersionCetakV1Controller;
use App\Http\Middleware\AktivasiRaport;
use App\Models\DetailNilaiSiswa;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\Format;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\MatpelKelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
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

        if ($versi->kurikulum == 'kurikulummerdeka') {
            if ($versi->versi == '1') {
                $v = new VersionCetakV1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        } else if ($versi->kurikulum == 'kurtilas') {
            if ($versi->versi == '1') {
                $v = new KurtilasVersionCetakV1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        }
    }

    public function infoSiswa()
    {
        // $aktivasi = IdentitasRaport::whereHas('tahunajaran', function ($query) {
        //     $query->where('status', 1);
        // })->first();

        $aktivasi = TahunAjaran::where('status', 1)->first();

        $idkelas = Rombel::where('nisn', Auth::user()->siswa->nisn)->where('idtahunajaran', $aktivasi->idtahunajaran)->value('idkelas');

        $siswa = Siswa::with(['rombel' => function ($query) use ($aktivasi, $idkelas) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $idkelas
            ]);
        }])->whereHas('rombel', function ($query) use ($aktivasi, $idkelas) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $idkelas
            ]);
        })
            ->where('nisn', Auth::user()->siswa->nisn)
            ->get();

        $siswa = $siswa->map(function ($siswa) use ($aktivasi) {
            return (object)[
                'nisn' => $siswa->nisn,
                'nama' => $siswa->nama,
                'masuk_tahun' => $siswa->tahunajaran->awal_tahun_ajaran,
                'rombel' => $siswa->rombel,
                'kenaikankelas' => $siswa->kenaikankelas->where('idtahunajaran', $aktivasi->idtahunajaran)->first() ?? null,
                'nilaiprakerin' => $siswa->nilaiprakerin
                    ? $siswa->nilaiprakerin->where('idtahunajaran', $aktivasi->idtahunajaran)->where('semester', $aktivasi->semester)->where('nisn', $siswa->nisn)->first()
                    : null,
                'absensi' => $siswa->absensiraport->where('idtahunajaran', $aktivasi->idtahunajaran)
                    ->map(function ($absensi) {
                        return (object)[
                            'tahun_ajaran' => $absensi->tahunajaran->awal_tahun_ajaran,
                            'semester' => $absensi->tahunajaran->semester,
                            'sakit' => $absensi->sakit,
                            'izin' => $absensi->izin,
                            'alfa' => $absensi->alfa,
                        ];
                    })->sortBy([
                        ['idtahunajaran', 'asc'],
                        ['semester', 'asc'],
                    ])->values(),
                'matpel' => MatpelKelas::select('rpt_matpel_kelas.*', DB::raw('GROUP_CONCAT(nip) as daftar_nip'))->with('matpel')->whereHas(
                    'kelas',
                    function ($query) use ($siswa) {
                        $query->whereHas('rombel', function ($query) use ($siswa) {
                            $query->where('nisn', $siswa->nisn);
                        });
                    }
                )->orderBy('kelompok_matpel', 'asc')->orderBy('id', 'asc')->groupBy('kode_matpel')->get()->map(function ($matpel) use ($siswa) {
                    $nip = explode(',', $matpel->daftar_nip);
                    return (object)[
                        'daftar_nip' => $nip,
                        'kode_matpel' => $matpel->kode_matpel,
                        'matpel' => $matpel->matpel->matpel,
                        'us' => DetailNilaiSiswa::select('nilai')->whereHas('nilaisiswa', function ($query) use ($matpel) {
                            $query->where('kode_matpel', $matpel->kode_matpel)
                                ->where('nip', $matpel->nip)
                                ->where('kategori', 'uas');
                        })->where('nisn', $siswa->nisn)->first(),
                        'hasil' => DetailNilaiRaport::whereHas('nilairaport', function ($query) use ($matpel, $nip) {
                            $query->where('kode_matpel', $matpel->kode_matpel)->whereIn('nip', $nip);
                        })
                            ->where('nisn', $siswa->nisn)
                            ->get()->map(function ($hasil) {
                                return (object)[
                                    'nip' => $hasil->nilairaport->nip,
                                    'tahun_ajaran' => $hasil->nilairaport->tahunajaran->awal_tahun_ajaran,
                                    'semester' => $hasil->nilairaport->semester,
                                    'nilai' => $hasil->nilai_1
                                ];
                            })->sortBy([
                                ['idtahunajaran', 'asc'],
                                ['semester', 'asc'],
                            ])->values(),
                        ''
                    ];
                })->values(),
            ];
        })->values();

        $data['siswa'] = $siswa;
        return view('pages.siswa.hasilstudi', $data);
    }
}
