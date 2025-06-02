<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Raport\KurikulumMerdeka\DetailNilaiRaportController as KurikulumMerdekaDetailNilaiRaportController;
use App\Http\Controllers\Raport\Kurtilas\DetailNilaiRaportController as KurtilasDetailNilaiRaportController;
use App\Models\DetailNilaiSiswa;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\Format;
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
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $nilairaport = NilaiRaport::find($id);

        $versi = Format::where('tingkat', $nilairaport->kelas->tingkat)->first();
        if ($versi) {
            if ($versi->kurikulum == 'kurikulummerdeka') {
                $v = new KurikulumMerdekaDetailNilaiRaportController;
                return $v->input($nilairaport, $id);
            } else if ($versi->kurikulum == 'kurtilas') {
                $v = new KurtilasDetailNilaiRaportController;
                return $v->input($nilairaport, $id);
            }
        }

        return redirect()->back();
    }

    public function store(Request $request, String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->route('nilai-raport.index')->with('warning', $e->getMessage());
        }

        $nilairaport = NilaiRaport::find($id);

        $versi = Format::where('tingkat', $nilairaport->kelas->tingkat)->first();
        if ($versi) {
            if ($versi->kurikulum == 'kurikulummerdeka') {
                $v = new KurikulumMerdekaDetailNilaiRaportController;
                return $v->store($request, $id);
            } else if ($versi->kurikulum == 'kurtilas') {
                $v = new KurtilasDetailNilaiRaportController;
                return $v->store($request, $id);
            }
        }

        return redirect()->back();
    }

    public function export(String $id){
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $nilairaport = NilaiRaport::find($id);

        $versi = Format::where('tingkat', $nilairaport->kelas->tingkat)->first();
        if ($versi) {
            if ($versi->kurikulum == 'kurikulummerdeka') {
                $v = new KurikulumMerdekaDetailNilaiRaportController;
                return $v->export($nilairaport, $id);
            }
        }

        return redirect()->back();
    }

    
}
