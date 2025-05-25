<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\NilaiPrakerin;
use App\Models\Raport\NilaiRaport;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class NilaiPrakerinController extends Controller
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
                'nilaiprakerin' => function ($query) use ($aktivasi) {
                    $query->where([
                        'semester' => $aktivasi->semester,
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

        return view('pages.eraports.nilaiprakerin.index', $data);
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
            'dudi' => 'required|array',
            'alamat' => 'required|array',
            'nilai.*' => 'required|integer|min:0|max:100',
            'waktu.*' => 'required|integer|min:0',
        ]);

        foreach ($request->dudi as $key => $value) {
            # code...
            NilaiPrakerin::updateOrCreate([
                'nisn' => $key,
                'semester' => $this->aktivasi->semester,
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
            ], [
                'dudi' => $value,
                'alamat' => $request->alamat[$key],
                'nilai' => $request->nilai[$key],
                'waktu' => $request->waktu[$key],
            ]);
        }

        return redirect()->back()->with('success', 'Nilai prakerin siswa berhasil di simpan');
    }
}
