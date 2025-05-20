<?php

namespace App\Http\Controllers\Raport\KurikulumMerdeka;

use App\Http\Controllers\Controller;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\NilaiRaport;
use App\Models\Rombel;
use App\Models\Siswa;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

class DetailNilaiRaportController extends Controller
{
    //
    protected $aktivasi;

    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');
    }

    public function index()
    {
        //
    }

    public function input($nilairaport, $id)
    {
        try {
            $data['id'] = Crypt::encrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $siswa = Siswa::whereHas('rombel', function ($query) use ($nilairaport) {
            $query->where([
                'idtahunajaran' => $nilairaport->idtahunajaran,
                'idkelas' => $nilairaport->idkelas
            ]);
        })->orderBy('nama', 'asc')->get();

        $detailnilairaport = DetailNilaiRaport::where([
            'idnilairaport' => $id
        ])->get();

        $nilai_pengetahuan = [];
        foreach ($detailnilairaport as $key => $value) {
            $nilai_pengetahuan[$value->nisn] = $value->nilai_1;
        }

        $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        $data['nilairaport'] = $nilairaport;
        $data['siswa'] = $siswa;

        return view('pages.eraports.kurikulummerdeka.detailnilairaport.index', $data);
    }

    public function store(Request $request, String $id)
    {
        //
        $request->validate([
            'nilai_pengetahuan' => 'required|array',
            'nilai_pengetahuan.*' => 'integer|min:0|max:100',
        ]);

        foreach ($request->nilai_pengetahuan as $key => $value) {
            # code...
            DetailNilaiRaport::updateOrCreate([
                'nisn' => $key,
                'idnilairaport' => $id,
            ], [
                'nilai_1' => $value,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai raport berhasil di simpan');
    }
}
