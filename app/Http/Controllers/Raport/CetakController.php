<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
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

        $data['kelas'] = Walikelas::select('walikelas.*', 'kelas.kelas as kls', DB::raw('(SELECT versi FROM rpt_formats WHERE idtahunajaran = walikelas.idtahunajaran AND tingkat = kelas.tingkat) as versi'))
            ->join('kelas', 'kelas.id', '=', 'walikelas.idkelas')
            ->where([
                'walikelas.idtahunajaran' => $this->aktivasi->idtahunajaran,
                'nip' => Auth::user()->staf->nip
            ])->get();

        return view('pages.eraports.cetak.index', $data);
    }

    public function page(string $page, string $id, string $start, string $end)
    {
        try {
            $id = explode("*", Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        if ($page == "cover") {
            return $this->cover($id[1], $start, $end);
        } else if ($page == "raport1") {
            return $this->raport1($id[1], $start, $end);
        }
    }

    public function cover($id, $start, $end)
    {
        $aktivasi = $this->aktivasi;
        $data['siswa'] = Siswa::whereHas('rombel', function ($query) use ($aktivasi, $id) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'idkelas' => $id
            ]);
        })
            ->offset($start - 1)->limit($end)
            ->get();
        // dd($data['siswa']);
        return view('pages.eraports.cetak.v1.cover', $data);
    }

    public function raport1()
    {
        $aktivasi = $this->aktivasi;
        $where = [
            
        ]
    }
}
