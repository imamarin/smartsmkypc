<?php

namespace App\Http\Controllers;

use App\Models\Staf;
use App\Models\JadwalMengajar;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MatpelPengampu;
use App\Models\SistemBlok;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class JadwalMengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'data-jadwal-mengajar-guru' ||
                Route::currentRouteName() == 'data-jadwal-mengajar-guru.show' ||
                Route::currentRouteName() == 'kunci'

            ) {
                $this->view = 'Kurikulum-Jadwal Mengajar Guru';
            } else {
                $this->view = 'Administrasi Guru-Jadwal Mengajar';
            }
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        //
        $title = 'Hapus Jadwal Mengajar!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        $data['sistemblok'] = SistemBlok::where([
            'idtahunajaran' => $tahunajaran->id,
            'semester' => $tahunajaran->semester
        ])->get();

        $data['matpel'] = MatpelPengampu::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->where('nip', Auth::user()->staf->nip)->get();
        $data['jampel'] = JamPelajaran::where('idtahunajaran', $tahunajaran->id)->get();
        $data['kelas'] = Kelas::where('idtahunajaran', $tahunajaran->id)->get();
        $data['jadwal'] = JadwalMengajar::join('jam_pelajarans', 'jam_pelajarans.id', '=', 'jadwal_mengajars.idjampel')
            ->selectRaw('jadwal_mengajars.*, (jam_pelajarans.jam + jadwal_mengajars.jumlah_jam) - 1 as jam_keluar,
                (SELECT jp.akhir
                FROM jam_pelajarans as jp
                WHERE jp.hari = jam_pelajarans.hari
                AND jp.idtahunajaran = jam_pelajarans.idtahunajaran
                AND jp.jam = jam_keluar) as waktu_keluar')
            ->whereHas('sistemblok', function ($query) use ($tahunajaran) {
                $query->where([
                    'semester' => $tahunajaran->semester,
                    'idtahunajaran' => $tahunajaran->id,
                ]);
            })->where([
                'nip' => Auth::user()->staf->nip,
            ])->orderBy('jam_pelajarans.hari')
            ->orderBy('jam_pelajarans.jam')->get();

        $data['kunci_jadwal'] = $tahunajaran->kunci_jadwal;
        $data['staf'] = Staf::where('nip', Auth::user()->staf->nip)->first();
        return view('pages.jadwalmengajar.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $nip = Crypt::decrypt($request->nip);

        $validate = $request->validate([
            'kode_matpel' => 'required',
            'hari' => 'required',
            'idkelas' => 'required',
            'idjampel' => 'required',
            'idsistemblok' => 'required',
            'jumlah_jam' => 'required',
        ]);

        $jampel = JamPelajaran::where([
            'hari' => $request->hari,
            'jam' => $request->idjampel,
            'idtahunajaran' => $request->idtahunajaran,
        ])->first();

        if ($jampel) {
            unset($validate['hari']);
            $validate['idjampel'] = $jampel->id;
            $validate['nip'] = $nip;
            JadwalMengajar::create($validate);
            return redirect()->back()->with('success', 'Jadwal Mengajar berhasil disimpan!');
        } else {
            return redirect()->back()->with('warning', 'Jam pelajaran tidak tersedia!');
        }
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
        try {
            $id = explode('*', Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        if (count($id) == 3) {
            $title = 'Hapus Jadwal Mengajar!';
            $text = "Yakin ingin menghapus data ini?";
            confirmDelete($title, $text);

            // $tahunajaran = TahunAjaran::where('status', 1)->first();
            $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
            $data['sistemblok'] = SistemBlok::where([
                'idtahunajaran' => $id[2],
                'semester' => $id[1]
            ])->get();
            $data['matpel'] = MatpelPengampu::where('nip', $id[0])->get();
            $data['jampel'] = JamPelajaran::where('idtahunajaran', $id[2])->get();
            $data['kelas'] = Kelas::where('idtahunajaran', $id[2])->get();
            $data['jadwal'] = JadwalMengajar::join('jam_pelajarans', 'jam_pelajarans.id', '=', 'jadwal_mengajars.idjampel')
                ->selectRaw('jadwal_mengajars.*, (jam_pelajarans.jam + jadwal_mengajars.jumlah_jam) - 1 as jam_keluar,
                    (SELECT jp.akhir
                    FROM jam_pelajarans as jp
                    WHERE jp.hari = jam_pelajarans.hari
                    AND jp.idtahunajaran = jam_pelajarans.idtahunajaran
                    AND jp.jam = jam_keluar) as waktu_keluar')
                ->whereHas('sistemblok', function ($query) use ($id) {
                    $query->where([
                        'semester' => $id[1],
                        'idtahunajaran' => $id[2]
                    ]);
                })->where([
                    'nip' => $id[0],
                ])->orderBy('jam_pelajarans.hari')->orderBy('jam_pelajarans.jam')->get();
            $data['staf'] = Staf::where('nip', $id[0])->first();
            $data['kunci_jadwal'] = $tahunajaran->kunci_jadwal;

            return view('pages.jadwalmengajar.index', $data);
        }

        return redirect()->back()->with('warning', 'ID tidak tersedia');
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $id = Crypt::decrypt($id);
        $nip = Crypt::decrypt($request->nip);

        $validate = $request->validate([
            'kode_matpel' => 'required',
            'hari' => 'required',
            'idkelas' => 'required',
            'idjampel' => 'required',
            'idsistemblok' => 'required',
            'jumlah_jam' => 'required',
        ]);

        $jampel = JamPelajaran::where([
            'hari' => $request->hari,
            'jam' => $request->idjampel,
            'idtahunajaran' => $request->idtahunajaran,
        ])->first();

        if ($jampel) {
            unset($validate['hari']);
            $validate['idjampel'] = $jampel->id;
            $validate['nip'] = $nip;
            JadwalMengajar::find($id)->update($validate);
            return redirect()->back()->with('success', 'Jadwal Mengajar berhasil diubah!');
        } else {
            return redirect()->back()->with('warning', 'Jam pelajaran tidak tersedia!');
        }
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        try {
            //code...
            $jadwalmengajar = JadwalMengajar::find($id);
            if ($jadwalmengajar) {
                $jadwalmengajar->delete();
                return redirect()->back()->with('success', 'Jam pelajaran berhasil dihapus!');
            }

            return redirect()->back()->with('warning', 'Jam pelajaran gagal dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data tidak bisa dihapus');
        }
    }

    public function dataJadwalMengajarGuru()
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $teachers = Staf::with(['jadwalmengajar' => function ($query) use ($tahunajaran) {
            $query->with(['presensi' => function ($query) {
                $query->whereDate('created_at', date('Y-m-d'));
            }])->whereHas('sistemblok', function ($query) use ($tahunajaran) {
                $query->where([
                    'semester' => $tahunajaran->semester,
                    'idtahunajaran' => $tahunajaran->id,
                ]);
            })->whereHas('jampel', function ($query) use ($tahunajaran) {
                $query->where('hari', date('N'))->where('idtahunajaran', $tahunajaran->id);
            });
        }])->withSum(['jadwalmengajar as jadwal_mengajar_sum' => function ($query) use ($tahunajaran) {
            $query->whereHas('sistemblok', function ($query) use ($tahunajaran) {
                $query->where([
                    'semester' => $tahunajaran->semester,
                    'idtahunajaran' => $tahunajaran->id,
                ]);
            });
        }], 'jumlah_jam')->where('stafs.status', 1)->get();


        $data['staf'] = $teachers->sortByDesc(function ($teacher) {
            return $teacher->jadwalmengajar->count();
        })->values();
        $data['tahunajaran'] = $tahunajaran;

        $jadwalmengajar = JadwalMengajar::first();
        if ($jadwalmengajar->kunci == 0) {
            $data['kunci'] = "ditutup";
        } else {
            $data['kunci'] = "dibuka";
        }

        return view('pages.jadwalmengajar.guru', $data);
    }

    public function getJamPel() {}

    public function kunci(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        $tahunajaran = TahunAjaran::find($id);
        $tahunajaran->update([
            'kunci_jadwal' => DB::raw('1 - kunci_jadwal')
        ]);

        if ($tahunajaran->kunci_jadwal == '0') {
            $kunci = "ditutup";
        } else {
            $kunci = "dibuka";
        }

        return redirect()->back()->with('success', 'Jadwal mangajar berhasil di ' . $kunci);
    }
}
