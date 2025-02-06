<?php

namespace App\Http\Controllers;

use App\Models\DetailPresensi;
use App\Models\Staf;
use App\Models\JadwalMengajar;
use App\Models\Kelas;
use App\Models\MatpelPengampu;
use App\Models\Presensi;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;

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
                'pokok_bahasan' => $request->pokok_bahasan
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

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $data['tahunajaran'] = TahunAjaran::all();
        $data['kelas'] = Kelas::where('idtahunajaran', $tahunajaran->id)->get();

        if ($request->isMethod('post')) {
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

                $data['presensi_siswa'] = $presensi_siswa;
                $data['matpel_presensi'] = $matpel_presensi;
                $data['total_hadir'] = $total_hadir;
                $data['total_izin'] = $total_izin;
                $data['total_sakit'] = $total_sakit;
                $data['total_alfa'] = $total_alfa;
            } else {
                return redirect()->back()->with('warning', 'Data Presensi tidak tersedia');
            }
        }

        $data['kelas_selected'] = $request->idkelas;
        $data['tahunajaran_selected'] = $request->idtahunajaran;
        $data['semester_selected'] = $request->semester;

        return view('pages.presensi.rekap-presensi-siswa', $data);
    }

    public function rekapGuru(String $id = null)
    {
        if ($id) {
            $id = explode("*", Crypt::decrypt($id));
            if (count($id) != 3) {
                return redirect()->back()->with('warning', 'Data presensi tidak tersedia!');
            }

            $tahunajaran = TahunAjaran::where([
                'id' => $id[1],
                'semester' => $id[2]
            ])->first();

            if (!$tahunajaran) {
                return redirect()->back()->with('warning', 'Data presensi tidak tersedia!');
            }

            $data['staf'] = Staf::select('nip', 'nama')->where('nip', $id[0])->first();
            $nip = $id[0];
        } else {
            $tahunajaran = TahunAjaran::where('status', 1)->first();
            $nip = Auth::user()->staf->nip;
        }


        $jadwalmengajar = JadwalMengajar::whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where([
                'idtahunajaran' => $tahunajaran->id,
                'semester' => $tahunajaran->semester
            ]);
        })->where('nip', $nip)->get();
        $mappingPresensi = $jadwalmengajar->map(function ($item) use ($tahunajaran) {
            $first = strtotime($tahunajaran->tgl_mulai);
            $end = strtotime(date('Y-m-d'));
            $presensi = [];
            while ($first <= $end) {
                if (date('N', $first) == $item->jampel->hari) {
                    foreach ($item->presensi as  $value) {
                        # code...
                        if (date('Y-m-d', $first) == date('Y-m-d', strtotime($value->created_at))) {
                            return [
                                'jadwal' => $item->id,
                                'matpel' => $item->matpel->matpel,
                                'kelas' => $item->kelas->kelas,
                                'tanggal' => date('Y-m-d', $first),
                                'hari' => date('N', $first),
                                'jam' => $item->jampel->jam,
                                'keterangan' => 'hadir'
                            ];
                        }
                    }

                    return [
                        'jadwal' => $item->id,
                        'matpel' => $item->matpel->matpel,
                        'kelas' => $item->kelas->kelas,
                        'tanggal' => date('Y-m-d', $first),
                        'hari' => date('N', $first),
                        'jam' => $item->jampel->jam,
                        'keterangan' => 'Tidak Hadir'
                    ];
                }
                $first = strtotime('+1 day', $first);
            }
        })->sortBy('tanggal')->sortBy('jam');

        $data['presensi'] = json_decode(json_encode($mappingPresensi));

        return view('pages.presensi.guru', $data);
    }

    public function rekapPresensiGuru(Request $request)
    {
        //
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $idtahunajaran = $request->idtahunajaran ?? $tahunajaran->id;
        $semester = $request->semester ?? $tahunajaran->semester;

        $data['tahunajaran'] = TahunAjaran::all();
        $data['kelas'] = Kelas::where('idtahunajaran', $idtahunajaran)->get();
        $data['staf'] = Staf::where('status', 1)->get();
        $jadwalmengajar = JadwalMengajar::whereHas('sistemblok', function ($query) use ($idtahunajaran, $semester) {
            $query->where([
                'idtahunajaran' => $idtahunajaran,
                'semester' => $semester
            ]);
        })->get();

        $jumlahJamPerHari = [];
        $jumlahPertemuan = [];
        foreach ($jadwalmengajar as $jadwal) {
            # code...

            if (isset($jumlahJamPerHari[$jadwal->nip][$jadwal->jampel->hari])) {
                $jumlahJamPerHari[$jadwal->nip][$jadwal->jampel->hari]++;
            } else {
                $jumlahJamPerHari[$jadwal->nip][$jadwal->jampel->hari] = 1;
            }

            if ($jadwal->presensi->count() > 0) {
                if (isset($jumlahPertemuan[$jadwal->nip])) {
                    $jumlahPertemuan[$jadwal->nip] += $jadwal->presensi->count();
                } else {
                    $jumlahPertemuan[$jadwal->nip] = $jadwal->presensi->count();
                }
            }
        }

        $totalPertemuan = [];
        $first = strtotime($tahunajaran->tgl_mulai);
        $end = strtotime(date('Y-m-d'));

        foreach ($jumlahJamPerHari as $key_staf => $staf) {
            # code...
            while ($first <= $end) {
                if (isset($staf[date('N', $first)])) {
                    if (isset($totalPertemuan[$key_staf])) {
                        $totalPertemuan[$key_staf] += $staf[date('N', $first)];
                    } else {
                        $totalPertemuan[$key_staf] = $staf[date('N', $first)];
                    }
                }
                $first = strtotime('+1 day', $first);
            }
        }

        $data['jumlahPertemuan'] = $jumlahPertemuan;
        $data['totalPertemuan'] = $totalPertemuan;

        $data['tahunajaran_selected'] = $idtahunajaran;
        $data['semester_selected'] = $semester;

        return view('pages.presensi.rekap-presensi-guru', $data);
    }

    public function showPresensi(String $id) {}
}
