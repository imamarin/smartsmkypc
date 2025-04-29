<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\AbsensiRaport;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class AbsensiRaportController extends Controller
{
    //
    //
    protected $aktivasi;

    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');

        $title = 'Raport Absensi Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index()
    {
        //
        $data['aktivasi'] = $this->aktivasi;
        $kelas = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        if ($kelas->count() > 0) {
            $aktivasi = $this->aktivasi;

            $siswa = Siswa::with([
                'absensiraport' => function ($query) use ($aktivasi) {
                    $query->where([
                        'idtahunajaran' => $aktivasi->idtahunajaran,
                        'semester' => $aktivasi->semester,
                    ]);
                },
                'presensihariansiswa' => function ($query) use ($aktivasi) {
                    $query->selectRaw("
                        nisn,
                        SUM(keterangan = 'h') as hadir,
                        SUM(keterangan = 's') as sakit,
                        SUM(keterangan = 'i') as izin,
                        SUM(keterangan = 'a') as alfa
                    ")->where([
                        'semester' => $aktivasi->semester,
                        'idtahunajaran' => $aktivasi->idtahunajaran
                    ])->groupBy('nisn');
                }
            ])->whereHas('rombel', function ($query) use ($kelas) {
                $query->where([
                    'idtahunajaran' => $kelas[0]->idtahunajaran,
                    'idkelas' => $kelas[0]->idkelas
                ]);
            })->orderBy('nama', 'asc')->get();
            // dd($siswa);
        } else {
            $siswa = [];
        }

        $data['idkelas'] = $kelas[0]->idkelas;
        $data['kelas'] = $kelas;
        $data['siswa'] = $siswa;
        
        return view('pages.eraports.absensiraport.index', $data);
    }

    public function show(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $data['aktivasi'] = $this->aktivasi;
        $kelas = Walikelas::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        if ($kelas->count() > 0) {
            $aktivasi = $this->aktivasi;

            $siswa = Siswa::with([
                'absensiraport' => function ($query) use ($aktivasi) {
                    $query->where([
                        'idtahunajaran' => $aktivasi->idtahunajaran,
                        'semester' => $aktivasi->semester,
                    ]);
                },
                'presensihariansiswa' => function ($query) use ($aktivasi) {
                    $query->selectRaw("
                        nisn,
                        SUM(keterangan = 'h') as hadir,
                        SUM(keterangan = 's') as sakit,
                        SUM(keterangan = 'i') as izin,
                        SUM(keterangan = 'a') as alfa
                    ")->where([
                        'semester' => $aktivasi->semester,
                        'idtahunajaran' => $aktivasi->idtahunajaran
                    ])->groupBy('nisn');
                }
            ])->whereHas('rombel', function ($query) use ($aktivasi, $id) {
                $query->where([
                    'idtahunajaran' => $aktivasi->idtahunajaran,
                    'idkelas' => $id
                ]);
            })->orderBy('nama', 'asc')->get();
            // dd($siswa);
        } else {
            $siswa = [];
        }

        $data['idkelas'] = $id;
        $data['kelas'] = $kelas;
        $data['siswa'] = $siswa;
        return view('pages.eraports.absensiraport.index', $data);
    }

    public function store(Request $request)
    {
        //
        $request->validate([
            'izin' => 'required|array',
            'sakit' => 'required|array',
            'alfa' => 'required|array',
            'izin.*' => 'integer|min:0|max:100',
            'sakit.*' => 'integer|min:0|max:100',
            'alfa.*' => 'integer|min:0|max:100',
        ]);

        foreach ($request->izin as $key => $value) {
            # code...
            AbsensiRaport::updateOrCreate([
                'nisn' => $key,
                'semester' => $this->aktivasi->semester,
                'idtahunajaran' => $this->aktivasi->idtahunajaran,
            ], [
                'izin' => $value,
                'sakit' => $request->sakit[$key],
                'alfa' => $request->alfa[$key]
            ]);
        }

        return redirect()->back()->with('success', 'Raport absensi siswa berhasil di simpan');
    }
}
