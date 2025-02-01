<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\JamPelajaran;
use App\Models\Kelas;
use App\Models\MatpelPengampu;
use App\Models\SistemBlok;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\DB;

class JadwalMengajarController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
        $data['matpel'] = MatpelPengampu::where('kode_guru', Auth::user()->guru->kode_guru)->get();
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
                    'idtahunajaran' => $tahunajaran->id
                ]);
            })->where([
                'kode_guru' => Auth::user()->guru->kode_guru,
            ])->get();

        return view('pages.jadwalmengajar.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
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
            $validate['kode_guru'] = Auth::user()->guru->kode_guru;
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
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
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
            $validate['kode_guru'] = Auth::user()->guru->kode_guru;
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
        JadwalMengajar::find($id)->delete();
        return redirect()->back()->with('success', 'Jam pelajaran berhasil dihapus!');
    }
}
