<?php

namespace App\Http\Controllers\Raport\KurikulumMerdeka;

use App\Http\Controllers\Controller;
use App\Models\Raport\KategoriSikap;
use App\Models\Raport\NilaiSikap;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class NilaiSikapController extends Controller
{
    //
    protected $aktivasi;

    public function __construct()
    {
        $title = 'Ajuan Kehadiran Mengajar!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $this->aktivasi = Session::get('aktivasi');
    }

    public function index(Request $request)
    {
        $data['kelas'] = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        $data['sikap'] = KategoriSikap::where('kategori', 'sosial')->get();

        if ($request->hasAny(['idkelas', 'kategori'])) {
            try {
                $idkelas = Crypt::decrypt($request->idkelas);
                $kategori = Crypt::decrypt($request->kategori);
            } catch (DecryptException $e) {
                return redirect()->back()->with('warning', $e->getMessage());
            }
        } else {
            $idkelas = $data['kelas'][0]->idkelas;
            $kategori = $data['sikap'][0]->kategori;
        }

        $aktivasi = $this->aktivasi;

        $siswa = Siswa::with(['nilaisikap' => function ($query) use ($aktivasi, $kategori) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'semester' => $aktivasi->semester,
                'kategori' => $kategori
            ]);
        }])->whereHas('rombel', function ($query) use ($aktivasi, $idkelas) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $idkelas
            ]);
        })->orderBy('nama', 'asc')->get();


        $data['siswa'] = $siswa;
        $data['idkelas'] = $idkelas;
        $data['kategori'] = $kategori;

        return view('pages.eraports.kurikulummerdeka.nilaisikap.index', $data);
    }

    public function store(Request $request)
    {
        try {
            $kategori = Crypt::decrypt($request->kategori);
            $idkelas = Crypt::decrypt($request->idkelas);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $aktivasi = $this->aktivasi;
        $siswa = Siswa::select('nisn')->whereHas('rombel', function ($query) use ($aktivasi, $idkelas) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $idkelas
            ]);
        })->orderBy('nama', 'asc')->get();

        NilaiSikap::where([
            'idtahunajaran' => $aktivasi->idtahunajaran,
            'semester' => $aktivasi->semester,
            'kategori' => $kategori
        ])->whereIn('nisn', $siswa->pluck('nisn'))->delete();

        foreach ($request->sikap as $nisn => $sikap) {
            # code...
            foreach ($sikap as $key => $value) {
                # code...
                NilaiSikap::updateOrCreate([
                    'nisn' => $nisn,
                    'kategori' => $kategori,
                    'sikap' => $value,
                    'semester' => $aktivasi->semester,
                    'idtahunajaran' => $aktivasi->idtahunajaran
                ], [
                    'nilai' => 1
                ]);
            }
        }

        return redirect()->back()->with('success', 'Raport nilai sikap berhasil dismpan');
    }
}
