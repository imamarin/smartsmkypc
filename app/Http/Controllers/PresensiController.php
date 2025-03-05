<?php

namespace App\Http\Controllers;

use App\Models\AjuanPresensiMengajar;
use App\Models\DetailPresensi;
use App\Models\Staf;
use App\Models\JadwalMengajar;
use App\Models\JadwalSistemBlok;
use App\Models\KalenderAkademik;
use App\Models\Kelas;
use App\Models\MatpelPengampu;
use App\Models\Presensi;
use App\Models\PresensiHarianSiswa;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Nette\Utils\ArrayList;
use Nette\Utils\Arrays;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //

    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $idjadwalmengajar = Crypt::decrypt($request->idjadwalmengajar);
        $idkelas = Crypt::decrypt($request->idkelas);
        $kode_matpel = Crypt::decrypt($request->kode_matpel);
        $nip = Crypt::decrypt($request->nip);
        $tanggal = Crypt::decrypt($request->tanggal);

        $date = date('Y-m-d', strtotime($tanggal));

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $presensi = Presensi::whereDate('created_at', $date)
            ->where('idjadwalmengajar', $idjadwalmengajar)
            ->where('semester', $tahunajaran->semester)->first();
        if ($presensi) {
            $presensi->update([
                'idtahunajaran' => $tahunajaran->id,
                'semester' => $tahunajaran->semester,
                'idkelas' => $idkelas,
                'kode_matpel' => $kode_matpel,
                'nip' => $nip,
                'pokok_bahasan' => $request->pokok_bahasan
            ]);
        } else {
            $presensi = Presensi::create([
                'idtahunajaran' => $tahunajaran->id,
                'semester' => $tahunajaran->semester,
                'idkelas' => $idkelas,
                'kode_matpel' => $kode_matpel,
                'nip' => $nip,
                'idjadwalmengajar' => $idjadwalmengajar,
                'pokok_bahasan' => $request->pokok_bahasan,
                'created_at' => date('Y-m-d H:i:s', strtotime($tanggal)) ?? date('Y-m-d H:i:s')
            ]);
        }

        foreach ($request->presensi as $key => $value) {
            # code...
            $detailPresensi = DetailPresensi::where('idpresensi', $presensi->id)->where('nisn', $key);
            if ($detailPresensi->first()) {
                $detailPresensi->update([
                    'nisn' => $key,
                    'keterangan' => $value,
                    'idpresensi' => $presensi->id
                ]);
            } else {
                DetailPresensi::Create([
                    'nisn' => $key,
                    'keterangan' => $value,
                    'idpresensi' => $presensi->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Presensi berhasil di proses');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }

    public function rekapSiswa()
    {
        //
        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $data['rekap'] = JadwalMengajar::select('jadwal_mengajars.*')
            ->selectRaw("
                (SELECT COUNT(*) FROM detail_presensis as dp 
                JOIN presensis as p ON dp.idpresensi = p.id
                WHERE dp.keterangan = 'h' 
                AND p.nip = jadwal_mengajars.nip
                AND p.kode_matpel = jadwal_mengajars.kode_matpel
                AND p.idtahunajaran = '$tahunajaran->id'
                AND p.semester = '$tahunajaran->semester') as hadir_count")
            ->selectRaw("
                (SELECT COUNT(*) FROM detail_presensis as dp 
                JOIN presensis as p ON dp.idpresensi = p.id 
                WHERE p.nip = jadwal_mengajars.nip
                AND p.kode_matpel = jadwal_mengajars.kode_matpel
                AND p.idtahunajaran = '$tahunajaran->id'
                AND p.semester = '$tahunajaran->semester') as presensi_count")
            ->whereHas('sistemblok', function ($query) use ($tahunajaran) {
                $query->where([
                    'idtahunajaran' => $tahunajaran->id,
                    'semester' => $tahunajaran->semester
                ]);
            })->where([
                'nip' => Auth::user()->staf->nip,
            ])->groupBy('kode_matpel', 'idkelas')->get();

        return view('pages.presensi.siswa', $data);
    }

    public function rekapSiswaDetail($id)
    {
        //
        $id = explode("-", Crypt::decrypt($id));
        if (count($id) == 2) {
            $tahunajaran = TahunAjaran::where('status', 1)->first();

            $data['siswa'] = Rombel::whereHas(
                'kelas',
                function ($query) use ($tahunajaran, $id) {
                    $query->where([
                        'idkelas' => $id[1],
                        'idtahunajaran' => $tahunajaran->id
                    ]);
                }
            )->get();

            $query = Presensi::where([
                'kode_matpel' => $id[0],
                'nip' => Auth::user()->staf->nip,
                'idkelas' => $id[1],
                'semester' => $tahunajaran->semester,
                'idtahunajaran' => $tahunajaran->id
            ]);

            $presensi = $query->get();

            if ($presensi->count() > 0) {
                $presensi_siswa = [];
                $tanggal_presensi = [];
                foreach ($presensi as $value) {
                    # code...
                    $tanggal = $value->created_at->format('Y-m-d H:i:s');
                    array_push($tanggal_presensi, $tanggal);
                    foreach ($value->kelas->rombel as $siswa) {
                        # code...
                        $detailpresensi = $value->detailpresensi()->select('keterangan')->where('nisn', $siswa->nisn)->first();
                        if ($detailpresensi) {
                            $presensi_siswa[$tanggal][$siswa->nisn] = $detailpresensi->keterangan;
                        } else {
                            $presensi_siswa[$tanggal][$siswa->nisn] = '-';
                        }
                    }
                }

                $data['presensi_siswa'] = $presensi_siswa;
                $data['tanggal_presensi'] = $tanggal_presensi;
                $data['kelas'] = $query->first()->kelas->kelas;
                $data['matpel'] = $query->first()->matpel->matpel;
                return view('pages.presensi.siswa-detail', $data);
            }
        }

        return redirect()->back()->with('warning', 'Presensi tidak tersedia');
    }

    public function historyPresensi(String $id)
    {
        $id = explode("-", Crypt::decrypt($id));
        if (count($id) == 2) {

            $tahunajaran = TahunAjaran::where('status', 1)->first();

            $presensi = Presensi::select('idjadwalmengajar', 'created_at')->where([
                'nip' => Auth::user()->staf->nip,
                'kode_matpel' => $id[0],
                'idkelas' => $id[1],
                'semester' => $tahunajaran->semester,
                'idtahunajaran' => $tahunajaran->id
            ])->orderBy('created_at', 'desc')->get();

            $mappedPresensi = $presensi->map(function ($item) {
                return [
                    'id' => Crypt::encrypt($item->idjadwalmengajar),
                    'created_at' => $item->created_at->format('d-m-Y H:i:s')
                ];
            });

            return response()->json([
                'data_count' => $presensi->count(),
                'data' => $mappedPresensi
            ]);
        }

        return response()->json([
            'data_count' => 0,
            'data' => null
        ]);
    }

    public function rekapPresensiSiswa(Request $request)
    {
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $idkelas = decryptSmart($request->idkelas);
        $request->merge([
            'idtahunajaran' => $idtahunajaran,
            'idkelas' => $idkelas
        ]);

        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();

        if ($request->segment(2) == 'walikelas') {
            $data['kelas'] = Kelas::select('kelas.id', 'kelas.kelas')->join('walikelas', 'walikelas.idkelas', '=', 'kelas.id')
                ->where('walikelas.idtahunajaran', $tahunajaran->id)
                ->where('walikelas.nip', Auth::user()->staf->nip)->get();
            $data['route_kbm'] = 'walikelas.rekap-presensi-siswa.kbm';
            $data['route_harian'] = 'walikelas.rekap-presensi-siswa.harian';
        } else {
            $data['kelas'] = Kelas::where('idtahunajaran', $tahunajaran->id)->get();
            $data['route_kbm'] = 'data-rekap-presensi-siswa.kbm';
            $data['route_harian'] = 'data-rekap-presensi-siswa.harian';
        }


        $data['kelas_selected'] = $request->idkelas;
        $data['tahunajaran_selected'] = $request->idtahunajaran ?? $tahunajaran->id;
        $data['semester_selected'] = $request->semester ?? $tahunajaran->semester;

        if ($request->isMethod('post')) {
            if ($request->kbm) {
                $kbm = $this->kbmSiswa($request);
                $data = array_merge($data, $kbm);
            }

            if ($request->harian) {
                $harian = $this->harianSiswa($request, $tahunajaran);
                $data = array_merge($data, $harian);
            }
        } else {
            $kelasList = $data['kelas']->pluck('id')->toArray();
            $semuakelas = $this->semuaKelas($tahunajaran, $kelasList);
            $data = array_merge($data, $semuakelas);
        }

        return view('pages.presensi.rekap-presensi-siswa', $data);
    }


    public function semuaKelas(TahunAjaran $tahunajaran, array $kelasList)
    {
        $presensi = PresensiHarianSiswa::selectRaw("
                rombels.idkelas as idkelas,
                kelas.kelas as kelas,
                COUNT(keterangan) as total_pertemuan,
                SUM(keterangan = 'h') as total_hadir,
                SUM(keterangan = 's') as total_sakit,
                SUM(keterangan = 'i') as total_izin,
                SUM(keterangan = 'a') as total_alfa
            ")
            ->join('rombels', 'rombels.nisn', '=', 'presensi_harian_siswas.nisn')
            ->join('kelas', 'kelas.id', '=', 'rombels.idkelas')
            ->where([
                'presensi_harian_siswas.semester' => $tahunajaran->semester,
                'presensi_harian_siswas.idtahunajaran' => $tahunajaran->id
            ])
            ->whereIn('rombels.idkelas', $kelasList)
            ->groupBy('rombels.idkelas')
            ->get();


        $kelas = [];
        $presensi_kelas = [];
        foreach ($presensi as $key => $value) {
            # code...
            if (isset($presensi_kelas['Hadir'])) {
                array_push($presensi_kelas['Hadir'], floor($value->total_hadir / $value->total_pertemuan * 100));
            } else {
                $presensi_kelas['Hadir'][0] = floor($value->total_hadir / $value->total_pertemuan * 100);
            }

            if (isset($presensi_kelas['Sakit'])) {
                array_push($presensi_kelas['Sakit'], floor($value->total_sakit / $value->total_pertemuan * 100));
            } else {
                $presensi_kelas['Sakit'][0] = floor($value->total_sakit / $value->total_pertemuan * 100);
            }

            if (isset($presensi_kelas['Izin'])) {
                array_push($presensi_kelas['Izin'], floor($value->total_izin / $value->total_pertemuan * 100));
            } else {
                $presensi_kelas['Izin'][0] = floor($value->total_izin / $value->total_pertemuan * 100);
            }

            if (isset($presensi_kelas['Tanpa Keterangan'])) {
                array_push($presensi_kelas['Tanpa Keterangan'], floor($value->total_alfa / $value->total_pertemuan * 100));
            } else {
                $presensi_kelas['Tanpa Keterangan'][0] = floor($value->total_alfa / $value->total_pertemuan * 100);
            }
        }

        $mapPresensi  = [];
        $warna = ['#4BC0C0', '#FFCD56',  '#36A2EB', '#FF9F40'];
        $index = 0;
        foreach ($presensi_kelas as $key => $value) {
            # code...
            $data = [
                'label' => $key,
                'data' => $value,
                'backgroundColor' => $warna[$index],
                'stack' => $index
            ];
            $mapPresensi[$index] = $data;
            $index++;
        }

        $kelas = $presensi->pluck('kelas')->toArray();

        $data['presensi_kelas'] = $kelas;
        $data['presensi_kelas_siswa'] = $mapPresensi;

        return $data;
    }

    public function kbmSiswa(Request $request)
    {
        $data['siswa'] = Rombel::whereHas(
            'kelas',
            function ($query) use ($request) {
                $query->where([
                    'idkelas' => $request->idkelas,
                    'idtahunajaran' => $request->idtahunajaran
                ]);
            }
        )->get();

        $query = Presensi::where([
            'idkelas' => $request->idkelas,
            'semester' => $request->semester,
            'idtahunajaran' => $request->idtahunajaran
        ]);

        $presensi = $query->get();
        $presensi_siswa = [];
        $matpel_presensi = [];
        if ($presensi->count() > 0) {
            $total_hadir = [];
            $total_sakit = [];
            $total_izin = [];
            $total_alfa = [];
            foreach ($presensi as $value) {
                # code...
                $matpel = $value->matpel->matpel;

                //mengumpulka data mata pelajaran
                if (!in_array($matpel, $matpel_presensi)) {
                    array_push($matpel_presensi, $matpel);
                }

                foreach ($value->kelas->rombel as $siswa) {
                    # code...
                    $detailpresensi = $value->detailpresensi()->select('keterangan')->where('nisn', $siswa->nisn)->first();
                    if ($detailpresensi) {
                        if ($detailpresensi->keterangan == 'h') {
                            if (isset($presensi_siswa[$matpel][$siswa->nisn]['h'])) {
                                $presensi_siswa[$matpel][$siswa->nisn]['h']++;
                                $total_hadir[$siswa->nisn]++;
                            } else {
                                $presensi_siswa[$matpel][$siswa->nisn]['h'] = 1;
                                isset($total_hadir[$siswa->nisn]) ? $total_hadir[$siswa->nisn]++ : $total_hadir[$siswa->nisn] = 1;
                            }
                        } elseif ($detailpresensi->keterangan == 's') {
                            if (isset($presensi_siswa[$matpel][$siswa->nisn]['s'])) {
                                $presensi_siswa[$matpel][$siswa->nisn]['s']++;
                                $total_sakit[$siswa->nisn]++;
                            } else {
                                $presensi_siswa[$matpel][$siswa->nisn]['s'] = 1;
                                isset($total_sakit[$siswa->nisn]) ? $total_sakit[$siswa->nisn]++ : $total_sakit[$siswa->nisn] = 1;
                            }
                        } elseif ($detailpresensi->keterangan == 'i') {
                            if (isset($presensi_siswa[$matpel][$siswa->nisn]['i'])) {
                                $presensi_siswa[$matpel][$siswa->nisn]['i']++;
                                $total_izin[$siswa->nisn]++;
                            } else {
                                $presensi_siswa[$matpel][$siswa->nisn]['i'] = 1;
                                isset($total_izin[$siswa->nisn]) ? $total_izin[$siswa->nisn]++ : $total_izin[$siswa->nisn] = 1;
                            }
                        } elseif ($detailpresensi->keterangan == 'a') {
                            if (isset($presensi_siswa[$matpel][$siswa->nisn]['a'])) {
                                $presensi_siswa[$matpel][$siswa->nisn]['a']++;
                                $total_alfa[$siswa->nisn]++;
                            } else {
                                $presensi_siswa[$matpel][$siswa->nisn]['a'] = 1;
                                isset($total_alfa[$siswa->nisn]) ? $total_alfa[$siswa->nisn]++ : $total_alfa[$siswa->nisn] = 1;
                            }
                        }
                    }
                }
            }

            // dd($total_hadir);    

            $data['presensi_kbm_siswa'] = $presensi_siswa;
            $data['matpel_presensi'] = $matpel_presensi;
            $data['total_hadir'] = $total_hadir;
            $data['total_izin'] = $total_izin;
            $data['total_sakit'] = $total_sakit;
            $data['total_alfa'] = $total_alfa;

            return $data;
        } else {
            return redirect()->back()->with('warning', 'Data Presensi tidak tersedia');
        }
    }

    public function harianSiswa(Request $request, TahunAjaran $tahunajaran)
    {
        $presensiHarian = PresensiHarianSiswa::select('*')->selectRaw("
            SUM(keterangan = 'h') as total_hadir,
            SUM(keterangan = 's') as total_sakit,
            SUM(keterangan = 'i') as total_izin,
            SUM(keterangan = 'a') as total_alfa
        ")->whereHas('siswa.rombel', function ($query) use ($request) {
            $query->where('idkelas', $request->idkelas)->whereHas('tahunajaran', function ($query) use ($request) {
                $query->where('id', $request->idtahunajaran);
            });
        })->where([
            'semester' => $request->semester,
            'idtahunajaran' => $request->idtahunajaran
        ])->groupBy('nisn')->get();

        $data['presensi_harian_siswa'] = $presensiHarian->sortBy('siswa.nama');
        $data['idkelas'] = $request->idkelas;
        $data['idtahunajaran'] = $request->idtahunajaran;
        $data['semester'] = $request->semester;

        return $data;
    }

    public function rekapGuru(String $id)
    {
        $title = 'Ajuan Kehadiran Mengajar!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        if ($id) {
            try {
                $id = explode("*", Crypt::decrypt($id));
            } catch (DecryptException $e) {
                return redirect()->back()->with('warning', $e->getMessage());
            }

            $tahunajaran = TahunAjaran::where([
                'id' => $id[1],
            ])->first();

            $tahunajaran = [
                'id' => $id[1],
                'semester' => $id[2]
            ];

            $tahunajaran = json_decode(json_encode($tahunajaran));

            $data['staf'] = Staf::select('nip', 'nama')->where('nip', $id[0])->first();
            $nip = $id[0];
        } else {
            $tahunajaran = TahunAjaran::where('status', 1)->first();
            $nip = Auth::user()->staf->nip;
        }


        $jadwalmengajar = JadwalMengajar::with('presensi')->whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where([
                'semester' => $tahunajaran->semester,
                'idtahunajaran' => $tahunajaran->id
            ]);
        })->where('nip', $nip)->get();

        $jadwalsistemblok = JadwalSistemBlok::whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where([
                'semester' => $tahunajaran->semester,
                'idtahunajaran' => $tahunajaran->id
            ]);
        })->get();

        $kalenderakademik = KalenderAkademik::where('idtahunajaran', $tahunajaran->id)->get();

        $tanggal_akademik = [];
        foreach ($kalenderakademik as $value) {
            # code...
            $first = strtotime($value->tanggal_mulai);
            $end = strtotime($value->tanggal_akhir);

            while ($first <= $end) {
                array_push($tanggal_akademik, date('Y-m-d', $first));
                $first = strtotime('+1 day', $first);
            }
        }

        // dd($tanggal_akademik);

        $tanggal = [];
        foreach ($jadwalsistemblok as $value) {
            # code...
            $first = strtotime($value->tanggal_mulai);
            $end = strtotime($value->tanggal_akhir);
            while ($first <= $end) {
                if (!in_array(date('Y-m-d', $first), $tanggal_akademik)) {
                    if (isset($tanggal[$value->idsistemblok])) {
                        if (!in_array(date('Y-m-d', $first), $tanggal[$value->idsistemblok])) {
                            array_push($tanggal[$value->idsistemblok], date('Y-m-d', $first));
                        }
                    } else {
                        $tanggal[$value->idsistemblok][0] = date('Y-m-d', $first);
                    }
                }

                $first = strtotime('+1 day', $first);
            }
        }

        foreach ($tanggal as $key => $value) {
            # code...
            $max_tanggal = $tanggal[$key];
            $filtered = array_filter($value, function ($date) use ($max_tanggal) {
                if (date('Y-m-d') > $max_tanggal) {
                    $t = max($date);
                } else {
                    $t = date('Y-m-d');
                }

                return date('Y-m-d', strtotime($date)) <= $t;
            });

            $tanggal[$key] = $filtered;
        }

        $presensi = [];
        foreach ($jadwalmengajar as $item) {
            foreach ($tanggal[$item->idsistemblok] as $item_tanggal) {
                if (date('N', strtotime($item_tanggal)) == $item->jampel->hari) {
                    if ($item->presensi->count() > 0) {
                        $result = false;
                        foreach ($item->presensi as  $value) {
                            # code...
                            if (date('Y-m-d', strtotime($item_tanggal)) == date('Y-m-d', strtotime($value->created_at))) {
                                $result = true;
                            }
                        }

                        $ajuan = [];
                        if ($result == false) {
                            $ajuan = AjuanPresensiMengajar::where('idjadwalmengajar', $item->id)->whereDate('tanggal_mengajar', date('Y-m-d', strtotime($item_tanggal)))->first();
                        }


                        $ket =  [
                            'jadwal' => $item->id,
                            'matpel' => $item->matpel->matpel,
                            'kelas' => $item->kelas->kelas,
                            'tanggal' => date('Y-m-d', strtotime($item_tanggal)),
                            'hari' => date('N', strtotime($item_tanggal)),
                            'jam' => $item->jampel->jam,
                            'keterangan' => $result ? 'Hadir' : 'Tidak Hadir',
                            'ajuan' => $ajuan
                        ];

                        array_push($presensi, $ket);
                    } else {
                        $ket =  [
                            'jadwal' => $item->id,
                            'matpel' => $item->matpel->matpel,
                            'kelas' => $item->kelas->kelas,
                            'tanggal' => date('Y-m-d', strtotime($item_tanggal)),
                            'hari' => date('N', strtotime($item_tanggal)),
                            'jam' => $item->jampel->jam,
                            'keterangan' => 'Tidak Hadir',
                            'ajuan' => AjuanPresensiMengajar::where('idjadwalmengajar', $item->id)->whereDate('tanggal_mengajar', date('Y-m-d', strtotime($item_tanggal)))->first(),
                        ];
                        array_push($presensi, $ket);
                    }
                }
            }
        }

        usort($presensi, function ($a, $b) {
            if ($a['tanggal'] == $b['tanggal']) {
                return $b['jam'] <=> $a['jam'];
            }

            return $b['tanggal'] <=> $a['tanggal'];
        });

        $data['presensi'] = json_decode(json_encode($presensi));

        return view('pages.presensi.guru', $data);
    }

    public function rekapPresensiGuru(Request $request)
    {
        //
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $idtahunajaran = $request->idtahunajaran ? decryptSmart($request->idtahunajaran) : $tahunajaran->id;
        $semester = $request->semester ?? $tahunajaran->semester;

        $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        $data['kelas'] = Kelas::where('idtahunajaran', $idtahunajaran)->get();

        $data['staf'] = Staf::where('status', 1)->whereHas('jadwalmengajar.sistemblok', function ($query) use ($idtahunajaran) {
            $query->where('idtahunajaran', $idtahunajaran);
        })->get();

        $jadwalsistemblok = JadwalSistemBlok::whereHas('sistemblok', function ($query) use ($idtahunajaran, $semester) {
            $query->where([
                'semester' => $semester,
                'idtahunajaran' => $idtahunajaran
            ]);
        })->get();

        $kalenderakademik = KalenderAkademik::where('idtahunajaran', $idtahunajaran)->get();
        $tanggal_akademik = [];
        foreach ($kalenderakademik as $value) {
            # code...
            $first = strtotime($value->tanggal_mulai);
            $end = strtotime($value->tanggal_akhir);

            while ($first <= $end) {
                array_push($tanggal_akademik, date('Y-m-d', $first));
                $first = strtotime('+1 day', $first);
            }
        }

        $tanggal = [];
        foreach ($jadwalsistemblok as $value) {
            # code...
            $first = strtotime($value->tanggal_mulai);
            $end = strtotime($value->tanggal_akhir);
            while ($first <= $end) {
                if (!in_array(date('Y-m-d', $first), $tanggal_akademik)) {
                    if (isset($tanggal[$value->idsistemblok])) {
                        if (!in_array(date('Y-m-d', $first), $tanggal[$value->idsistemblok])) {
                            array_push($tanggal[$value->idsistemblok], date('Y-m-d', $first));
                        }
                    } else {
                        $tanggal[$value->idsistemblok][0] = date('Y-m-d', $first);
                    }
                }

                $first = strtotime('+1 day', $first);
            }
        }

        foreach ($tanggal as $key => $value) {
            # code...
            $max_tanggal = $tanggal[$key];
            $filtered = array_filter($value, function ($date) use ($max_tanggal) {
                if (date('Y-m-d') > $max_tanggal) {
                    $t = max($date);
                } else {
                    $t = date('Y-m-d');
                }

                return date('Y-m-d', strtotime($date)) <= $t;
            });

            $tanggal[$key] = $filtered;
        }

        $jadwalmengajar = JadwalMengajar::with(['presensi'])->whereHas('sistemblok', function ($query) use ($idtahunajaran, $semester) {
            $query->where([
                'idtahunajaran' => $idtahunajaran,
                'semester' => $semester
            ]);
        })->get();

        $jumlahJamPerHari = [];
        $jumlahPertemuan = [];
        $t = [];
        foreach ($jadwalmengajar as $jadwal) {
            # code...
            if (isset($jumlahJamPerHari[$jadwal->nip][$jadwal->idsistemblok][$jadwal->jampel->hari])) {
                $jumlahJamPerHari[$jadwal->nip][$jadwal->idsistemblok][$jadwal->jampel->hari]++;
            } else {
                $jumlahJamPerHari[$jadwal->nip][$jadwal->idsistemblok][$jadwal->jampel->hari] = 1;
            }

            $tanggal_awal = min($tanggal[$jadwal->idsistemblok]);
            foreach ($jadwal->presensi as $key => $value) {
                # code...
                if (date('Y-m-d', strtotime($value->created_at)) >= $tanggal_awal) {
                    if (isset($jumlahPertemuan[$jadwal->nip])) {
                        $jumlahPertemuan[$jadwal->nip]++;
                    } else {
                        $jumlahPertemuan[$jadwal->nip] = 1;
                    }
                }
            }
        }

        $totalPertemuan = [];
        foreach ($jumlahJamPerHari as $key_staf => $staf) {
            $totalPertemuan[$key_staf] = 0;
            foreach ($staf as $key_sistemblok => $sesi) {
                # code...
                foreach ($tanggal[$key_sistemblok] as $tgl) {
                    if (isset($sesi[date('N', strtotime($tgl))])) {
                        $totalPertemuan[$key_staf] += $sesi[date('N', strtotime($tgl))];
                    }
                }
            }
        }


        $data['jumlahPertemuan'] = $jumlahPertemuan;
        $data['totalPertemuan'] = $totalPertemuan;

        $data['tahunajaran_selected'] = $idtahunajaran;
        $data['semester_selected'] = $semester;

        return view('pages.presensi.rekap-presensi-guru', $data);
    }
}
