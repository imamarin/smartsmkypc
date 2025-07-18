<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\KenaikanKelas;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class KenaikanKelasController extends Controller
{
    //
    protected $aktivasi;
    protected $idkelas;

    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');

        $title = 'Raport Absensi Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index()
    {
        $data['aktivasi'] = $this->aktivasi;

        $kelas = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();

        if ($kelas->count() > 0) {
            $aktivasi = $this->aktivasi;

            $siswa = Siswa::with([
                'kenaikankelas' => function ($query) use ($aktivasi) {
                    $query->where([
                        'idtahunajaran' => $aktivasi->idtahunajaran
                    ]);
                }
            ])->whereHas('rombel', function ($query) use ($aktivasi, $kelas) {
                $query->where([
                    'idtahunajaran' => $aktivasi->idtahunajaran,
                    'idkelas' => $this->idkelas ?? $kelas[0]->idkelas
                ]);
            })->orderBy('nama', 'asc')->get();
        } else {
            $siswa = [];
        }

        $data['idkelas'] = $this->idkelas ?? $kelas[0]->idkelas;
        $data['kelas'] = $kelas;
        $data['siswa'] = $siswa;

        return view('pages.eraports.kenaikankelas.index', $data);
    }

    public function show(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
            $this->idkelas = $id;
        } catch (DecryptException $e) {
            return redirect()->back();
        }

        return $this->index();
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'keterangan' => 'required|array',
        ]);

        foreach ($request->keterangan as $key => $value) {
            # code...
            KenaikanKelas::updateOrCreate([
                'nisn' => $key,
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
            ], [
                'keterangan' => $value,
            ]);
        }

        return redirect()->back()->with('success', 'Kenaikan kelas siswa berhasil di simpan');
    }
}
