<?php

namespace App\Http\Controllers\Raport\KurikulumMerdeka;

use App\Http\Controllers\Controller;
use App\Http\Controllers\Raport\KurikulumMerdeka\VersionCetak\V1Controller;
use App\Http\Controllers\Raport\Kurtilas\VersionCetak\V1Controller as VersionCetakV1Controller;
use App\Models\Kelas;
use App\Models\Raport\DetailNilaiRaport;
use App\Models\Raport\Ekstrakurikuler;
use App\Models\Raport\Format;
use App\Models\Raport\MatpelKelas;
use App\Models\Siswa;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Session;

class CetakController extends Controller
{
    protected $aktivasi;


    public function __construct()
    {
        $this->aktivasi = Session::get('aktivasi');

        $title = 'Raport Absensi Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    //
    public function index()
    {
        $data['aktivasi'] = $this->aktivasi;

        $data['kelas'] = Walikelas::select('walikelas.*', 'kelas.kelas as kls', 'kelas.tingkat as tingkat', DB::raw('(SELECT versi FROM rpt_formats WHERE idtahunajaran = walikelas.idtahunajaran AND tingkat = kelas.tingkat) as versi'))
            ->join('kelas', 'kelas.id', '=', 'walikelas.idkelas')
            ->where([
                'walikelas.idtahunajaran' => $this->aktivasi->idtahunajaran,
                'nip' => Auth::user()->staf->nip
            ])->get();

        return view('pages.eraports.kurikulummerdeka.cetak.index', $data);
    }

    public function page(string $page, string $id, string $start, string $end)
    {
        try {
            $id = explode("*", Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $versi = Format::where('tingkat', $id[2])->first();
        if ($versi->kurikulum == 'kurikulummerdeka') {
            if ($versi->versi == '1') {
                $v = new V1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        } else if ($versi->kurikulum == 'kurtilas') {
            if ($versi->versi == '1') {
                $v = new VersionCetakV1Controller;
                return $v->index($this->aktivasi, $page, $id[1], $start, $end);
            }
        }
    }
}
