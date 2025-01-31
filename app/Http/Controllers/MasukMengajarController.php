<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
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

    public function show(String $id)
    {
        $id = Crypt::decrypt($id);

        $data['jadwal'] = JadwalMengajar::join('jam_pelajarans', 'jam_pelajarans.id', '=', 'jadwal_mengajars.idjampel')
            ->selectRaw('jadwal_mengajars.*, (jam_pelajarans.jam + jadwal_mengajars.jumlah_jam) - 1 as jam_keluar,
                (SELECT jp.akhir
                FROM jam_pelajarans as jp
                WHERE jp.hari = jam_pelajarans.hari
                AND jp.idtahunajaran = jam_pelajarans.idtahunajaran
                AND jp.jam = jam_keluar) as waktu_keluar')
            ->where([
                'jadwal_mengajars.id' => $id,
            ])->first();

        if ($data['jadwal']) {
            return view('pages.masukmengajar.show', $data);
        }

        return redirect()->back()->with('warning', 'Mohon maaf jadwal tidak tersedia!');
    }
}
