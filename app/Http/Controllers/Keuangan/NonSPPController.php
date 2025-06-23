<?php

namespace App\Http\Controllers\Keuangan;

use App\Models\Kelas;
use App\Models\Keuangan\DetailNonSpp;
use App\Models\Keuangan\KategoriKeuangan;
use App\Models\Keuangan\NonSpp;
use App\Models\Rombel;
use App\Models\Siswa;
use DateInterval;
use DateTime;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Routing\Controller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class NonSPPController extends Controller
{
    //
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Keuangan-Pembayaran Lain';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            $title = 'Pembayaran Keuangan Siswa!';
            $text = "Yakin ingin membatalkan pembayaran ini?";
            confirmDelete($title, $text);

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $siswa = Siswa::where('status', 1)->orderBy('nama', 'asc')->get();
        $data['data_siswa'] = $siswa->map(function ($item) {
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
        $data['keuangan_paid'] = [];
        $data['kategori'] = [];
        $data['siswa'] = (object)[
            'nisn' => '',
            'nama' => '',
            'kelas' => '',
            'jurusan' => '',
            'idtahunajaran' => '',
        ];
        return view('pages.keuangan.nonspp.index', $data);
    }

    public function siswa(Request $request)
    {
        $nisn = decryptSmart($request->nisn);

        $siswa = Siswa::where('nisn', $nisn)->first();
        $kelas = Kelas::whereHas('rombel', function ($query) use ($nisn) {
            $query->where('nisn', $nisn)->whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            });
        })->first();

        $siswa = (object)[
            'nisn' => $siswa->nisn,
            'nama' => $siswa->nama,
            'kelas' => $kelas->kelas,
            'jurusan' => $kelas->jurusan->jurusan,
            'idtahunajaran' => $siswa->idtahunajaran,
        ];
        $data['siswa'] = $siswa;

        $nonspp = NonSpp::where('nisn', $nisn)->get();
        $keuangan_paid = [];
        foreach ($nonspp as $value) {
            $keuangan_paid[$value->nisn][$value->idkategorikeuangan] = [$value->id, $value->detailnonspp->sum('bayar')];
        }
        $data['keuangan_paid'] = $keuangan_paid;

        $siswa2 = Siswa::where('status', 1)->orderBy('nama', 'asc')->get();
        $data['data_siswa'] = $siswa2->map(function ($item) {
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

        $jurusan = [$siswa->jurusan, 'semua'];
        $data['kategori'] = KategoriKeuangan::where('idtahunajaran', $siswa->idtahunajaran)->where('nama', '!=', 'SPP')->whereIn('jurusan', $jurusan)->get();

        return view('pages.keuangan.nonspp.index', $data);
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

        $bayar = str_replace('Rp', '', $request->bayar);
        $bayar = str_replace('.', '', $bayar);
        $bayar = str_replace("\u{A0}", '', $bayar);
        $bayar = str_replace(" ", '', $bayar);

        $totalbayar = $id[2] - $id[3];

        if ($bayar >= $id[2] && $totalbayar == 0) {
            $nonspp = NonSpp::updateOrCreate([
                'nisn' => $id[0],
                'idkategorikeuangan' => $id[1]
            ], [
                'biaya' => $id[2],
                'metode' => 'tunai'
            ]);
            DetailNonSpp::create([
                'bayar' => $id[2],
                'idnonspp' => $nonspp->id,
                'iduser' => Auth::user()->id
            ]);
        } else {
            if ($bayar > $id[3]) {
                return redirect()->back()->with('warning', 'Uang bayar melebihi sisa tagihan');
            } else {
                $nonspp = NonSpp::updateOrCreate([
                    'nisn' => $id[0],
                    'idkategorikeuangan' => $id[1]
                ], [
                    'biaya' => $id[2],
                    'metode' => 'angsuran'
                ]);

                DetailNonSpp::create([
                    'bayar' => $bayar,
                    'idnonspp' => $nonspp->id,
                    'iduser' => Auth::user()->id
                ]);
            }
        }

        return redirect()->back()->with('success', 'Pembayaran ' . $id[4] . ' Berhasil Ditambahkan');
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
    public function update(Request $request, string $id)
    {
        // $validate = $request->validate([
        //     'nama' => 'required',
        //     'biaya' => 'required',
        //     'jurusan' => 'required',
        //     'idtahunajaran' => 'required',
        // ]);

        // KategoriKeuangan::find($id)->update($validate);
        // return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

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
            DetailNonSpp::where('idnonspp', $id[2])->delete();
            NonSpp::where([
                'nisn' => $id[0],
                'idkategorikeuangan' => $id[1]
            ])->delete();
            return redirect()->back()->with('success', 'Pembayaran ' . $id[3] . ' Berhasil dibatalkan');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Tidak dapat menghapus data karena masih memiliki relasi!');
        }
    }

    public function detailNonSPP(String $id)
    {
        try {
            $id = explode('*', Crypt::decrypt($id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $data['siswa'] = Rombel::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->where('nisn', $id[0])->first();


        $data['detailnonspp'] = DetailNonSpp::where('idnonspp', $id[2])->get();

        if ($data['detailnonspp']->count() < 1) {
            return redirect()->route('pembayaran-lain.siswa', ['nisn' => encryptSmart($id[0])]);
        }

        return view('pages.keuangan.detailnonspp.index', $data);
    }

    public function updateDetailNonSPP(Request $request, String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $bayar = str_replace('Rp', '', $request->bayar);
        $bayar = str_replace('.', '', $bayar);
        $bayar = str_replace("\u{A0}", '', $bayar);
        $bayar = str_replace(" ", '', $bayar);

        DetailNonSpp::find($id)->update([
            'bayar' => $bayar,
            'updated_at' => date('Y-m-d', strtotime($request->tanggal))
        ]);

        return redirect()->back()->with('success', 'Data uang masuk berhasil diubah');
    }

    public function deleteDetailNonSPP(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        DetailNonSpp::find($id)->delete();

        return redirect()->back()->with('success', 'Data uang masuk berhasil dihapus');
    }

    public function export()
    {

        // $matpel = KategoriKeuangan::get();
        // return Excel::download(new MatpelEksport($matpel), 'Data-Matpel.xlsx');
    }
}
