<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Kelas;
use App\Models\Matpel;
use App\Models\MatpelPengampu;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\MatpelKelas;
use App\Models\Staf;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class MatpelKelasController extends Controller
{
    //
    protected $aktivasi;
    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');
        $title = 'Data Matpel Kelas Raport!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index()
    {
        $data['kelas'] = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        $data['matpel'] = MatpelPengampu::where('idtahunajaran', $this->aktivasi->idtahunajaran)->orderBy('kode_matpel', 'asc')->groupBy('kode_matpel')->get();
        $data['staf'] = MatpelPengampu::where('idtahunajaran', $this->aktivasi->idtahunajaran)->groupBy('nip')->get();

        $data['matpelkelas'] = MatpelKelas::whereIn('idkelas', $data['kelas']->pluck('idkelas'))
            ->where([
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
                'semester' => $this->aktivasi->semester
            ])->get();
        return view('pages.eraports.matpelkelas.index', $data);
    }

    public function show(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
            $data['idkelas'] = $id;
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $data['kelas'] = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        $data['matpel'] = MatpelPengampu::where('idtahunajaran', $this->aktivasi->idtahunajaran)->orderBy('kode_matpel', 'asc')->groupBy('kode_matpel')->get();
        $data['staf'] = MatpelPengampu::where('idtahunajaran', $this->aktivasi->idtahunajaran)->groupBy('nip')->get();;
        $data['matpelkelas'] = MatpelKelas::where('idkelas', $id)
            ->where([
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
                'semester' => $this->aktivasi->semester
            ])->get();

        return view('pages.eraports.matpelkelas.index', $data);
    }

    public function store(Request $request)
    {
        try {
            $idkelas = Crypt::decrypt($request->idkelas);
            $kode_matpel = Crypt::decrypt($request->kode_matpel);
            $nip = Crypt::decrypt($request->nip);
            $request->merge([
                'idkelas' => $idkelas,
                'kode_matpel' => $kode_matpel,
                'nip' => $nip,
            ]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $request->validate([
            'idkelas' => 'required',
            'nip' => 'required',
            'kode_matpel' => 'required'
        ]);

        MatpelKelas::updateOrCreate([
            'idkelas' => $request->idkelas,
            'kode_matpel' => $request->kode_matpel,
            'semester' => $this->aktivasi->semester,
            'idtahunajaran' => $this->aktivasi->idtahunajaran
        ], [
            'nip' => $request->nip,
            'kelompok_matpel' => $request->kelompok_matpel,
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        MatpelKelas::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
