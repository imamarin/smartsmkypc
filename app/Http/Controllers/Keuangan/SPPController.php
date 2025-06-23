<?php

namespace App\Http\Controllers\Keuangan;

use App\Models\Kelas;
use App\Models\Keuangan\KategoriKeuangan;
use App\Models\Keuangan\Spp;
use App\Models\Rombel;
use App\Models\Siswa;
use DateInterval;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class SPPController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Keuangan-Pembayaran SPP';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            $title = 'Pembayaran SPP!';
            $text = "Yakin ingin membatalkan pembayaran ini?";
            confirmDelete($title, $text);

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $siswa = Siswa::where('status', 1)->orderBy('nama', 'asc')->get();
        $data['siswa'] = $siswa->map(function ($item) {
            $nisn = $item->nisn;
            $kelas = Kelas::select('kelas')->whereHas('rombel', function ($query) use ($nisn) {
                $query->where('nisn', $nisn)->whereHas('tahunajaran', function ($query) {
                    $query->where('status', 1);
                });
            })->first();
            return (object)[
                'nisn' => $item->nisn,
                'nama' => $item->nama,
                'kelas' => $kelas->kelas ?? '-',
            ];
        })->sortByDesc('kelas')->values();
        $data['nisn'] = '-';
        $data['spp'] = [];
        $data['bulan_spp'] = [];
        $data['biaya'] = [];
        return view('pages.keuangan.spp.index', $data);
    }

    public function siswa(Request $request)
    {
        $nisn = decryptSmart($request->nisn);

        $bulan = ['Januari', 'Februari', 'Maret', 'April', 'Mei', 'Juni', 'Juli', 'Agustus', 'September', 'Oktober', 'November', 'Desember'];

        $siswa = Siswa::where('nisn', $nisn)->first();


        $tanggal_awal = new DateTime($siswa->diterima_tanggal);
        $tanggal_akhir = clone $tanggal_awal;
        $tanggal_akhir->add(new DateInterval('P3Y'));
        $tanggal_akhir->modify('-1 month');

        $tanggal_awal->modify('first day of this month');
        $bulan_spp = [];
        while ($tanggal_awal < $tanggal_akhir) {
            $bulanNum = $tanggal_awal->format('n') - 1;
            $tahun = $tanggal_awal->format('Y');
            $bulan_spp[] = [
                'bulan' => $bulan[$bulanNum],
                'tahun' => $tahun
            ];

            $tanggal_awal->modify('+1 month');
        }
        $data['bulan_spp'] = $bulan_spp;

        $data['nisn'] = $nisn;
        $spp = Spp::where('nisn', $nisn)->orderBy('tahun', 'asc')->orderBy('id', 'asc')->get();
        $bulan_spp_paid = [];
        foreach ($spp as $value) {
            $bulan_spp_paid[$value->bulan . ' ' . $value->tahun] = $value->created_at;
        }
        $data['bulan_spp_paid'] = $bulan_spp_paid;

        $siswa2 = Siswa::where('status', 1)->orderBy('nama', 'asc')->get();
        $data['siswa'] = $siswa2->map(function ($item) {
            $nisn = $item->nisn;
            $kelas = Kelas::select('kelas')->whereHas('rombel', function ($query) use ($nisn) {
                $query->where('nisn', $nisn)->whereHas('tahunajaran', function ($query) {
                    $query->where('status', 1);
                });
            })->first();
            return (object)[
                'nisn' => $item->nisn,
                'nama' => $item->nama,
                'kelas' => $kelas?->kelas,
            ];
        });

        $jurusan = [$siswa->rombel[0]->kelas->jurusan, 'semua'];
        $data['biaya'] = KategoriKeuangan::where([
            'idtahunajaran' => $siswa->idtahunajaran,
            'nama' => 'SPP'
        ])->whereIn('jurusan', $jurusan)->get();

        return view('pages.keuangan.spp.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        try {
            $id = explode('*', Crypt::decrypt($request->id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        spp::create([
            'nisn' => $id[0],
            'bulan' => $id[1],
            'tahun' => $id[2],
            'biaya' => $request->biaya,
            'iduser' => Auth::user()->id,
        ]);

        return redirect()->back()->with('success', 'Pembayaran SPP Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    // public function update(Request $request, string $id)
    // {
    //     $validate = $request->validate([
    //         'nama' => 'required',
    //         'biaya' => 'required',
    //         'jurusan' => 'required',
    //         'idtahunajaran' => 'required',
    //     ]);

    //     KategoriKeuangan::find($id)->update($validate);
    //     return redirect()->back()->with('success', 'Data Berhasil Diubah');
    // }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {

        try {
            $id = explode('*', Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        try {
            Spp::where([
                'nisn' => $id[0],
                'bulan' => $id[1],
                'tahun' => $id[2]
            ])->delete();
            return redirect()->back()->with('success', 'Pembayaran SPP Berhasil dibatalkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus data karena masih memiliki relasi!');
        }
    }

    public function export()
    {

        // $matpel = KategoriKeuangan::get();
        // return Excel::download(new MatpelEksport($matpel), 'Data-Matpel.xlsx');
    }
}
