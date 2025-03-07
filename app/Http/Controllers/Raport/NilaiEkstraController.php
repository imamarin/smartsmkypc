<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\Ekstrakurikuler;
use App\Models\Raport\NilaiEkstrakurikuler;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class NilaiEkstraController extends Controller
{
    //
    protected $aktivasi;

    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');

        $title = 'Nilai Ekstrakurikuler Raport!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index(Request $request)
    {
        $data['kelas'] = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        $data['ekstrakurikuler'] = Ekstrakurikuler::all();
        $aktivasi = $this->aktivasi;

        if ($request->hasAny(['idkelas'])) {
            try {
                $idkelas = Crypt::decrypt($request->idkelas);
            } catch (DecryptException $e) {
                return redirect()->back()->with('warning', $e->getMessage());
            }
        } else {
            $idkelas = $data['kelas'][0]->idkelas;
        }

        $siswa = Siswa::whereHas('rombel', function ($query) use ($aktivasi, $idkelas) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $idkelas
            ]);
        })->orderBy('nama', 'asc')->get();

        $data['nilaiekstrakurikuler'] = NilaiEkstrakurikuler::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'semester' => $aktivasi->semester
        ])->whereIn('nisn', $siswa->pluck('nisn'))->get();

        $data['siswa'] = $siswa;
        $data['idkelas'] = $idkelas;

        return view('pages.eraports.ekstrakurikuler.nilai', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'idekstrakurikuler' => 'required',
            'nisn' => 'required',
        ]);

        try {
            $idekstrakurikuler = Crypt::decrypt($request->idekstrakurikuler);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        foreach ($request->nisn as $key => $value) {
            # code...
            NilaiEkstrakurikuler::updateOrCreate([
                'idekstrakurikuler' => $idekstrakurikuler,
                'nisn' => $value,
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
                'semester' => $this->aktivasi->semester,
            ], [
                'nilai' => $request->nilai
            ]);
        }

        return redirect()->back()->with('success', 'Nilai Ekstrakurikuler berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        NilaiEkstrakurikuler::find($id)->delete();

        return redirect()->back()->with('success', 'Nilai Ekstrakurikuler berhasil dihapus');
    }
}
