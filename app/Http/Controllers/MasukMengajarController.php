<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\JadwalSistemBlok;
use App\Models\KalenderAkademik;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class MasukMengajarController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'masuk-mengajar.index' ||
                Route::currentRouteName() == 'masuk-mengajar.show' ||
                Route::currentRouteName() == 'masuk-mengajar.updateCatatan'
            ) {
                $this->view = 'Administrasi Guru-Masuk Mengajar';
            } else if (Route::currentRouteName() == 'show-presensi.tanggal') {
                $this->view = 'Administrasi Guru-Rekap Presensi Siswa';
            } else if (Route::currentRouteName() == 'ajuan-kehadiran-mengajar.presensi') {
                $this->view = 'Administrasi Guru-Rekap Presensi Mengajar';
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
        $title = 'Hapus Jadwal Mengajar!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $data['jadwal'] = JadwalMengajar::join('jam_pelajarans', 'jam_pelajarans.id', '=', 'jadwal_mengajars.idjampel')
            ->selectRaw('jadwal_mengajars.*, (jam_pelajarans.jam + jadwal_mengajars.jumlah_jam) - 1 as jam_keluar,
                (SELECT jp.akhir
                FROM jam_pelajarans as jp
                WHERE jp.hari = jam_pelajarans.hari
                AND jp.idtahunajaran = jam_pelajarans.idtahunajaran
                AND jp.jam = jam_keluar) as waktu_keluar')
            ->whereHas('jampel', function ($query) use ($tahunajaran) {
                $query->where('hari', date('N'))->where('idtahunajaran', $tahunajaran->id);
            })
            ->whereHas('sistemblok', function ($query) {
                $query->whereHas('jadwalsistemblok', function ($query) {
                    $query->where('tanggal_mulai', '<=', date('Y-m-d'))->where('tanggal_akhir', '>=', date('Y-m-d'));
                });
            })
            ->where([
                'nip' => Auth::user()->staf->nip,
            ])->get();

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

        $data['tanggal_akademik'] = $tanggal_akademik;

        return view('pages.masukmengajar.index', $data);
    }

    public function show(String $id, String $tgl = null)
    {
        $id = Crypt::decrypt($id);
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        if ($tgl == null) {
            $date = date('Y-m-d');
            $data['tanggal'] = date('d-m-Y H:i:s');
        } else {
            $date = date('Y-m-d', strtotime($tgl));
            $data['tanggal'] = date('d-m-Y H:i:s', strtotime($tgl));
        }
        $presensi = Presensi::whereDate('created_at', $date)
            ->where('idjadwalmengajar', $id)
            ->where('semester', $tahunajaran->semester)->first();

        $jadwal = JadwalMengajar::join('jam_pelajarans', 'jam_pelajarans.id', '=', 'jadwal_mengajars.idjampel')
            ->selectRaw('jadwal_mengajars.*, (jam_pelajarans.jam + jadwal_mengajars.jumlah_jam) - 1 as jam_keluar,
                (SELECT jp.akhir
                FROM jam_pelajarans as jp
                WHERE jp.hari = jam_pelajarans.hari
                AND jp.idtahunajaran = jam_pelajarans.idtahunajaran
                AND jp.jam = jam_keluar) as waktu_keluar')
            ->where([
                'jadwal_mengajars.id' => $id,
            ])->first();

        if (!$presensi) {
            if (!$jadwal) {
                return redirect()->back()->with('warning', 'Mohon maaf jadwal tidak tersedia!');
            }
            $data['jadwal'] = $jadwal;
            $data['siswa'] = $jadwal->kelas->rombel;
            $data['catatan'] = Presensi::select('catatan_pembelajaran', 'updated_at')->where([
                'nip' => Auth::user()->staf->nip,
                'kode_matpel' => $jadwal->kode_matpel,
                'idkelas' => $jadwal->idkelas
            ])->orderBy('updated_at', 'desc')->get();
        } else {
            $data['jadwal'] = $jadwal;
            $data['presensi'] = $presensi;
            $data['siswa'] = $presensi->detailpresensi;
            $data['catatan'] = Presensi::select('catatan_pembelajaran', 'updated_at')->where([
                'nip' => Auth::user()->staf->nip,
                'kode_matpel' => $presensi->kode_matpel,
                'idkelas' => $presensi->idkelas
            ])->orderBy('updated_at', 'desc')->get();
        }
        return view('pages.masukmengajar.show', $data);
    }

    public function updateCatatan(Request $request, String $id)
    {
        try {
            $id = explode('*', Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $presensi = Presensi::whereDate('created_at', date("Y-m-d", $id[1]))
            ->where('idjadwalmengajar', $id[0])
            ->where('semester', $tahunajaran->semester);
        if ($presensi->first()) {
            $presensi->update([
                'catatan_pembelajaran' => $request->catatan
            ]);

            return redirect()->back()->with('success', 'Catatan Pembelajaran berhasil disimpan!');
        }
        return redirect()->back()->with('info', 'Silakan lakukan presensi siswa terlebih dahulu!');
    }
}
