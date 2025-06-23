<?php

namespace App\Http\Controllers\Keuangan;

use App\Models\Kelas;
use App\Models\Keuangan\KategoriKeuangan;
use App\Models\Keuangan\NonSpp;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TagihanKeuanganController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Keuangan-Tagihan Keuangan';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['tahunajaran'] = TahunAjaran::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        $data['kelas'] = Kelas::whereHas('tahunajaran', function ($query) {
            return $query->where('status', 1);
        })->orderBy('kelas', 'asc')->get();
        $data['idtahunajaran'] = '';
        $data['idkelas'] = '';
        $data['rombel'] = [];
        return view('pages.keuangan.tagihankeuangan.index', $data);
    }

    public function show(Request $request)
    {
        try {
            $idkelas = Crypt::decrypt($request->idkelas);
            $idtahunajaran = Crypt::decrypt($request->idtahunajaran);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $data['tahunajaran'] = TahunAjaran::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        $data['kelas'] = Kelas::whereHas('tahunajaran', function ($query) {
            return $query->where('status', 1);
        })->orderBy('kelas', 'asc')->get();

        $data['idtahunajaran'] = $idtahunajaran;
        $data['idkelas'] = $idkelas;

        $rombel = Rombel::with(['siswa.spp', 'siswa.nonspp'])->where('idkelas', $idkelas)->get();
        $data['rombel'] = $rombel->map(function ($item) {

            $sppTotal = $item->siswa->spp->count();

            $nonsppTotal = $item->siswa->nonspp->flatMap(function ($nonspp) {
                return $nonspp->detailnonspp;
            })->sum('bayar');

            $jurusan = [$item->kelas->jurusan, 'semua'];
            $biayaKeuanganTotal = KategoriKeuangan::where('idtahunajaran', $item->siswa->idtahunajaran)->where('nama', '!=', 'SPP')->whereIn('jurusan', $jurusan)->sum('biaya');

            $start = new DateTime($item->siswa->diterima_tanggal);
            $end = new DateTime();

            $months = (($end->format('Y') - $start->format('Y')) * 12) + ($end->format('m') - $start->format('m'));

            if ($end->format('d') >= $start->format('d')) {
                $months += 1;
            }

            return (object)[
                'nisn' => $item->siswa->nisn,
                'nama' => $item->siswa->nama,
                'jmlBulan' => $months,
                'spp' => $sppTotal,
                'nonspp' => $nonsppTotal,
                'totalKeuangan' => (int) $biayaKeuanganTotal
            ];
        })->sortBy('nama');

        return view('pages.keuangan.tagihankeuangan.index', $data);
    }

    public function kelas(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return response(null, 400);
        }

        $kelas = Kelas::where('idtahunajaran', $id)->get();
        $kelas = $kelas->map(function ($item) {
            return (object)[
                'id' => Crypt::encrypt($item->id),
                'kelas' => $item->kelas,
            ];
        });
        return response()->json($kelas);
    }

    public function print(String $id)
    {
        try {
            $id = explode('*', Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $rombel = Rombel::where('idkelas', $id[1])->where('nisn', $id[0])->first();
        $jurusan = [$rombel->kelas->jurusan, 'semua'];
        $keuangan = KategoriKeuangan::where('idtahunajaran', $rombel->siswa->idtahunajaran)->whereIn('jurusan', $jurusan)->get();

        $nonspp = NonSpp::where('nisn', $rombel->siswa->nisn)->get();
        $keuangan_paid = [];
        foreach ($nonspp as $value) {
            $keuangan_paid[$value->idkategorikeuangan] = $value->detailnonspp->sum('bayar');
        }

        $data['siswa'] = $rombel;
        $data['keuangan'] = $keuangan->map(function ($item) use ($rombel, $keuangan_paid) {
            if ($item->nama == 'SPP') {
                $start = new DateTime($rombel->siswa->diterima_tanggal);
                $end = new DateTime();

                $months = (($end->format('Y') - $start->format('Y')) * 12) + ($end->format('m') - $start->format('m'));

                if ($end->format('d') >= $start->format('d')) {
                    $months += 1;
                }
                if ($rombel->siswa->spp->count() <= $months) {
                    $spp = $months - $rombel->siswa->spp->count();
                } else {
                    $spp = 0;
                }

                return (object)[
                    'keuangan' => 'SPP',
                    'biaya' => number_format($item->biaya, '0', ',', '.') . '/bulan',
                    'sisa_tagihan' => $spp . ' bulan',
                    'keterangan' => $spp == 0 ? 'Pembayaran Lunas' : 'Belum Bayar'
                ];
            } else {
                $tagihan = $item->biaya;
                if (isset($keuangan_paid[$item->id])) {
                    $tagihan = $item->biaya - $keuangan_paid[$item->id];
                    if ($tagihan == 0) {
                        $keterangan = "Sudah Bayar";
                    } elseif ($tagihan == $item->biaya) {
                        $keterangan = "Belum Bayar";
                    } elseif ($tagihan < $item->biaya) {
                        $keterangan = "Belum Lunas";
                    }
                } else {
                    $keterangan = "Belum Bayar";
                }

                return (object)[
                    'keuangan' => $item->nama,
                    'biaya' => number_format($item->biaya, '0', ',', '.'),
                    'sisa_tagihan' =>  number_format($tagihan, '0', ',', '.'),
                    'keterangan' => $keterangan
                ];
            }
        });

        return view('pages.keuangan.tagihankeuangan.cetak', $data);
    }
}
