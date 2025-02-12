<?php

namespace App\Http\Controllers;

use App\Models\JadwalMengajar;
use App\Models\MatpelPengampu;
use App\Models\NilaiSiswa;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class NilaiSiswaController extends Controller
{
    //
    protected $tahunajaran;
    public function __construct()
    {
        $this->tahunajaran = TahunAjaran::where('status', 1)->first();
    }
    public function index(Request $request)
    {
        $title = 'Data Pengolah Nilai!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $tahunajaran = $this->tahunajaran;

        $kelas = JadwalMengajar::with('kelas')->whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where([
                'idtahunajaran' => $tahunajaran->id,
                'semester' => $tahunajaran->semester,
            ]);
        })->where('nip', Auth::user()->staf->nip)->groupBy('idkelas')->get();

        $matpel = MatpelPengampu::with('matpel')->where([
            'idtahunajaran' => $tahunajaran->id
        ])->where('nip', Auth::user()->staf->nip)->groupBy('kode_matpel')->get();

        $nilaisiswa = NilaiSiswa::where([
            'semester' => $tahunajaran->semester,
            'idtahunajaran' => $tahunajaran->id,
            'nip' => Auth::user()->staf->nip
        ])->get();

        $data['kelas'] = $kelas;
        $data['matpel'] = $matpel;
        $data['nilaisiswa'] = $nilaisiswa;
        $data['tugas'] = $nilaisiswa->where('kategori', 'tugas');
        $data['sumatif'] = $nilaisiswa->where('kategori', 'sumatif');
        $data['uts'] = $nilaisiswa->where('kategori', 'uts');
        $data['uas'] = $nilaisiswa->where('kategori', 'uas');
        $data['kategori'] = $request->kategori ?? 'tugas';
        // dd($request->kategori);
        return view('pages.nilaisiswa.index', $data);
    }

    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'kategori' => 'required',
            'idkelas' => 'required',
            'kode_matpel' => 'required',
            'tanggal_pelaksanaan' => 'required',
            'keterangan' => 'required'
        ]);

        $tahunajaran = $this->tahunajaran;
        $validate['semester'] = $tahunajaran->semester;
        $validate['idtahunajaran'] = $tahunajaran->id;
        $validate['nip'] = Auth::user()->staf->nip;

        NilaiSiswa::create($validate);

        return redirect()->route('nilai-siswa', [
            'kategori' => $request->kategori,
        ])->with('success', 'Data berhasil disimpan');
    }

    public function update(Request $request, String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $validate = $request->validate([
            'kategori' => 'required',
            'kode_matpel' => 'required',
            'tanggal_pelaksanaan' => 'required',
            'keterangan' => 'required'
        ]);

        $tahunajaran = $this->tahunajaran;
        $validate['semester'] = $tahunajaran->semester;
        $validate['idtahunajaran'] = $tahunajaran->id;
        $validate['nip'] = Auth::user()->staf->nip;

        NilaiSiswa::find($id)->update($validate);

        return redirect()->route('nilai-siswa', [
            'kategori' => $request->kategori,
        ])->with('success', 'Data berhasil diubah');
    }

    public function destroy(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $nilaisiswa = NilaiSiswa::find($id)->delete();
        return redirect()->route('nilai-siswa', [
            'kategori' => $nilaisiswa->kategori,
        ])->with('success', 'Data berhasil dihapus');
    }
}
