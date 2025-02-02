<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class MasukMengajarController extends Controller
{
    //
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
            })->where([
                'kode_guru' => Auth::user()->guru->kode_guru,
            ])->get();

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
                'kode_guru' => Auth::user()->guru->kode_guru,
                'kode_matpel' => $jadwal->kode_matpel,
                'idkelas' => $jadwal->idkelas
            ])->orderBy('updated_at', 'desc')->get();
        } else {
            $data['jadwal'] = $jadwal;
            $data['presensi'] = $presensi;
            $data['siswa'] = $presensi->detailpresensi;
            $data['catatan'] = Presensi::select('catatan_pembelajaran', 'updated_at')->where([
                'kode_guru' => Auth::user()->guru->kode_guru,
                'kode_matpel' => $presensi->kode_matpel,
                'idkelas' => $presensi->idkelas
            ])->orderBy('updated_at', 'desc')->get();
        }
        return view('pages.masukmengajar.show', $data);
    }

    public function updateCatatan(Request $request, String $id)
    {
        $idjadwalmengajar = Crypt::decrypt($id);
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $presensi = Presensi::whereDate('created_at', date("Y-m-d"))
            ->where('idjadwalmengajar', $idjadwalmengajar)
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
