<?php

namespace App\Http\Controllers\Raport;

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

    public function input(String $id)
    {
        try {
            $data['id'] = $id;
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $nilairaport = NilaiRaport::find($id);
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
        $nilai_keterampilan = [];
        foreach ($detailnilairaport as $key => $value) {
            $nilai_pengetahuan[$value->nisn] = $value->pengetahuan;
            $nilai_keterampilan[$value->nisn] = $value->keterampilan;
        }

        $data['nilai_pengetahuan'] = $nilai_pengetahuan;
        $data['nilai_keterampilan'] = $nilai_keterampilan;
        $data['nilairaport'] = $nilairaport;
        $data['siswa'] = $siswa;

        return view('pages.eraports.detailnilairaport.index', $data);
    }

    public function store(Request $request, String $id)
    {
        //
        $request->validate([
            'nilai_pengetahuan' => 'required|array',
            'nilai_keterampilan' => 'required|array',
            'nilai_pengetahuan.*' => 'integer|min:0|max:100',
            'nilai_keterampilan.*' => 'integer|min:0|max:100',
        ]);

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('nilai-raport.index')->with('warning', $e->getMessage());
        }

        foreach ($request->nilai_pengetahuan as $key => $value) {
            # code...
            DetailNilaiRaport::updateOrCreate([
                'nisn' => $key,
                'idnilairaport' => $id,
            ], [
                'pengetahuan' => $value,
                'keterampilan' => $request->nilai_keterampilan[$key] ?? 0,
            ]);
        }

        return redirect()->back()->with('success', 'Nilai raport berhasil di simpan');
    }
}
