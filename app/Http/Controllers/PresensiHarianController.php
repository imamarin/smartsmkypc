<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use App\Models\Kelas;
use App\Models\PresensiHarianSiswa;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class PresensiHarianController extends Controller
{
    //
    public function index()
    {
        $title = 'Data Presensi!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function siswa(Request $request)
    {

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get();

        if ($request->idkelas) {
            try {
                $idkelas = Crypt::decrypt($request->idkelas);
            } catch (DecryptException $e) {
                return "Error: " . $e->getMessage();
            }
        } else {
            $idkelas = $kelas[0]->id ?? null;
        }

        $presensiHarian = PresensiHarianSiswa::select('*')->selectRaw("
            COUNT(*) as total_siswa,
            SUM(keterangan = 'h') as total_hadir,
            SUM(keterangan = 's') as total_sakit,
            SUM(keterangan = 'i') as total_izin,
            SUM(keterangan = 'a') as total_alfa
        ")->whereHas('siswa.rombel', function ($query) use ($idkelas) {
            $query->where('idkelas', $idkelas)->whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            });
        })->groupBy('idtahunajaran')->groupBy('semester')->groupBy('created_at')->get();

        $presensi = [];
        foreach ($presensiHarian as $value) {
            # code...
            $presensi[date('Y-m-d', strtotime($value->created_at))] = $value;
        }

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

        $first = strtotime($tahunajaran->tgl_mulai);
        $end = strtotime(date('Y-m-d'));

        $tanggal = [];
        $data_presensi = [];
        while ($end >= $first) {
            # code...
            if (!in_array(date('Y-m-d', $end), $tanggal_akademik)) {
                if (isset($presensi[date('Y-m-d', $end)])) {
                    array_push($data_presensi, $presensi[date('Y-m-d', $end)]);
                    array_push($tanggal, date('Y-m-d', $end));
                } else {
                    array_push($data_presensi, null);
                    array_push($tanggal, date('Y-m-d', $end));
                }
            }

            $end = strtotime('-1 day', $end);
        }

        $data['presensi'] = $data_presensi;
        $data['tanggal'] = $tanggal;
        $data['kelas'] = $kelas;
        $data['kelas_selected'] = $idkelas;
        $data['tahunajaran'] = $tahunajaran;

        return view('pages.presensi.harian-siswa', $data);
    }

    public function create(String $id = null)
    {
        //

        try {
            $id = explode("*", Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $tahunajaran = TahunAjaran::find($id[1]);

        $rombel = Rombel::where('idtahunajaran', $id[1])->where('idkelas', $id[0])->get();
        if ($rombel->count() > 0) {

            $tanggal = date('Y-m-d', strtotime($id[2]));
            $presensihariansiswa = PresensiHarianSiswa::where([
                'idtahunajaran' => $tahunajaran->id,
                'semester' => $tahunajaran->semester
            ])->whereDate('created_at', $tanggal)->get();

            $presensi = [];
            foreach ($presensihariansiswa as $value) {
                # code...
                $presensi[$value->nisn] = $value->keterangan;
            }

            $data['rombel'] = $rombel;
            $data['tahunajaran'] = $tahunajaran;
            $data['tanggal'] = $tanggal;
            $data['presensi'] = $presensi;
            $data['idkelas'] = $rombel[0]->idkelas ?? 0;

            return view('pages.presensi.harian-siswa-create', $data);
        }

        return redirect()->route('presensi-harian-siswa', [
            'idkelas' => Crypt::encrypt($id[0])
        ])->with('warning', 'Rombel tidak tersedia');
    }

    public function store(Request $request, String $id)
    {
        //
        $request->validate([
            'presensi' => 'required'
        ]);

        try {
            $id = explode("*", Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        foreach ($request->presensi as $key => $value) {
            # code...
            $presensi = PresensiHarianSiswa::where([
                'nisn' => $key,
                'semester' => $id[2],
                'idtahunajaran' => $id[1],
            ])->whereDate('created_at', $id[3])->first();

            if (!$presensi) {
                $presensi = new PresensiHarianSiswa();
                $presensi->nisn = $key;
                $presensi->semester = $id[2];
                $presensi->idtahunajaran = $id[1];
                $presensi->created_at = $id[3];
            }

            $presensi->keterangan = $value;
            $presensi->save();
        }

        return redirect()->route('presensi-harian-siswa', [
            'idkelas' => Crypt::encrypt($id[0])
        ])->with('success', 'Presensi harian berhasil disimpan');
    }

    public function rekapSiswa(Request $request)
    {
        $tahunajaran = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();

        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get();

        if ($request->isMethod('post')) {
            try {
                $idkelas = Crypt::decrypt($request->idkelas);
                $idtahunajaran = Crypt::decrypt($request->idtahunajaran);
                $semester = $request->semester;
            } catch (DecryptException $e) {
                return "Error: " . $e->getMessage();
            }
        } else {
            $idkelas = $kelas[0]->id;
            $idtahunajaran = $tahunajaran[0]->id;
            $semester = $tahunajaran[0]->semester;
        }

        $presensiHarian = PresensiHarianSiswa::select('*')->selectRaw("
            SUM(keterangan = 'h') as total_hadir,
            SUM(keterangan = 's') as total_sakit,
            SUM(keterangan = 'i') as total_izin,
            SUM(keterangan = 'a') as total_alfa
        ")->whereHas('siswa.rombel', function ($query) use ($idkelas, $idtahunajaran) {
            $query->where('idkelas', $idkelas)->whereHas('tahunajaran', function ($query) use ($idtahunajaran) {
                $query->where('id', $idtahunajaran);
            });
        })->where([
            'semester' => $semester,
            'idtahunajaran' => $idtahunajaran
        ])->groupBy('nisn')->get();

        $data['presensi'] = $presensiHarian;
        $data['kelas'] = $kelas;
        $data['idkelas'] = $idkelas;
        $data['tahunajaran'] = $tahunajaran;
        $data['idtahunajaran'] = $idtahunajaran;
        $data['semester'] = $semester;

        return view('pages.presensi.rekap-harian-siswa', $data);
    }
}
