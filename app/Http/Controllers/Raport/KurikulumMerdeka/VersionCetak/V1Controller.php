<?php

namespace App\Http\Controllers\Raport\KurikulumMerdeka\VersionCetak;

use App\Http\Controllers\Controller;
use App\Models\DetailNilaiSiswa;
use App\Models\Kelas;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\Ekstrakurikuler;
use App\Models\Raport\Format;
use App\Models\Raport\MatpelKelas;
use App\Models\Raport\NilaiCP;
use App\Models\Siswa;
use App\Models\TahunAjaran;
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
        } else if ($page == "transkrip") {
            return $this->transkrip($aktivasi, $kelas, $start, $end);
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

        return view("pages.eraports.kurikulummerdeka.cetak.v1.cover", $data);
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
        }, 'nilaiprakerin' => function ($query) use ($aktivasi) {
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
            ->orderBy('nama', 'asc')
            ->skip($start - 1)->take($end - $start + 1)
            ->get();

        $matpelkelas = MatpelKelas::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'semester' => $aktivasi->semester,
            'idkelas' => $id
        ])
            ->orderBy('kelompok_matpel', 'asc')
            ->orderBy('id', 'asc')
            ->get();

        $data['kelompok_matpel_A'] = $matpelkelas->filter(function ($item) {
            return $item['kelompok_matpel'] == 'A';
        })
            ->sortBy('id_matpelkelas')
            ->values();

        $data['kelompok_matpel_B'] = $matpelkelas->filter(function ($item) {
            return $item['kelompok_matpel'] == 'B';
        })
            ->sortBy('id_matpelkelas')
            ->values();


        $detailnilairaport = DetailNilaiRaport::whereHas('nilairaport', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id,
                'semester' => $aktivasi->semester
            ]);
        })->get();

        $pengetahuan = [];
        foreach ($detailnilairaport as $item) {
            # code...
            $pengetahuan[$item->nilairaport->kode_matpel][$item->nisn][$item->nilairaport->nip] = $item->nilai_1;
        }

        $nilaicp = NilaiCP::whereHas('nilairaport', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id,
                'semester' => $aktivasi->semester
            ]);
        })->get();
        $nilai_cp = [];
        foreach ($nilaicp as $item) {
            $nilai_cp[$item->nisn][$item->nilairaport->kode_matpel][$item->nilairaport->nip][$item->kode_cp] = [
                'textCapaian' => $item->capaianpembelajaran->capaian,
                'nilai' => $item->nilai
            ];
        }

        // dd($nilai_cp);
        $data['nilai_cp'] = $nilai_cp;

        $data['pengetahuan'] = $pengetahuan;
        return view("pages.eraports.kurikulummerdeka.cetak.v1.raport1", $data);
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
        return view('pages.eraports.kurikulummerdeka.cetak.v1.ranking', $data);
    }

    public function transkrip($aktivasi, $id, $start, $end)
    {
        $data['aktivasi'] = $aktivasi;

        $data['walikelas'] = Walikelas::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'idkelas' => $id
        ])->first();

        $siswa = Siswa::with(['rombel' => function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        }])->whereHas('rombel', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        })
            ->orderBy('nama', 'asc')
            ->skip($start - 1)->take($end - $start + 1)
            ->get();

        $siswa = $siswa->map(function ($siswa) use ($aktivasi) {
            return (object)[
                'nisn' => $siswa->nisn,
                'nama' => $siswa->nama,
                'masuk_tahun' => $siswa->tahunajaran->awal_tahun_ajaran,
                'rombel' => $siswa->rombel,
                'kenaikankelas' => $siswa->kenaikankelas->where('idtahunajaran', $aktivasi->idtahunajaran)->first(),
                'nilaiprakerin' => $siswa->nilaiprakerin?->where('idtahunajaran', $aktivasi->idtahunajaran)->where('semester', $aktivasi->semester)->first(),
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
        return view('pages.eraports.kurikulummerdeka.cetak.v1.transkrip', $data);
    }
}
