<?php

namespace App\Http\Controllers;

use App\Exports\RombelExport;
use App\Imports\RombelImport;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    protected $view;
    protected $fiturMenu;
    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Data Master-Data Rombel';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            $title = 'Data Rombel!';
            $text = "Yakin ingin menghapus data ini?";
            confirmDelete($title, $text);

            return $next($request);
        });
    }

    public function index()
    {
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::with(['walikelas' => function ($query) {
            $query->limit(1);
        }, 'rombel'])
            ->whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            })->orderBy('tingkat', 'asc')->get();

        // $kelas = $kelas->sortByDesc(function ($kls) {
        //     return $kls->rombel->count();
        // })->values();


        return view('pages.rombel.index', compact('siswa', 'kelas', 'tahunajaran'));
    }

    public function tahunajaran(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::with(['walikelas' => function ($query) {
            $query->limit(1);
        }, 'rombel'])
            ->whereHas('tahunajaran', function ($query) use ($id) {
                $query->where('idtahunajaran', $id);
            })->orderBy('tingkat', 'asc')->get();


        $idtahunajaran = $id;

        return view('pages.rombel.index', compact('siswa', 'kelas', 'tahunajaran', 'idtahunajaran'));
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
        //
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $idkelas = decryptSmart($request->idkelas);

        $request->merge(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran]);

        $request->validate([
            'idtahunajaran' => 'required',
            'idkelas' => 'required',
            'nisn' => 'required'
        ]);

        foreach ($request->nisn as $key => $value) {
            # code...
            Rombel::upadteOrCreate([
                'idtahunajaran' => $request->idtahunajaran,
                'nisn' => $value
            ], [
                'idkelas' => $request->idkelas,
            ]);
        }

        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
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
        //
        try {
            $id = Crypt::decrypt($id);
            $idtahunajaran = decryptSmart($request->idtahunajaran);
            $idkelas = decryptSmart($request->idkelas);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $request->merge(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran]);

        $request->validate([
            'nisn' => 'required',
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);


        Rombel::find($id)->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
            Rombel::find($id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }

    public function showStudents(String $id)
    {
        //
        if (!in_array('Tampil Siswa', $this->fiturMenu['Data Master-Data Rombel'])) {
            return redirect()->back();
        }

        try {
            $id = explode('*', Crypt::decrypt($id));
            $idkelas = $id[0];
            $idtahunajaran = $id[1];
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $kelas = Kelas::find($idkelas);
        $idkelas = $kelas->id;
        $kdkelas = $kelas->kelas;
        $tingkat = $kelas->tingkat;
        $tahunajaran = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        $kelas = Kelas::select('id', 'kelas', 'tingkat', 'idtahunajaran')->orderBy('tingkat', 'asc')->get();
        $rombel = Rombel::with('kelas')->where(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran])->get()->sortBy(function ($rombel) {
            return $rombel->siswa->nama;
        })->values();
        $idtahunajaran = $idtahunajaran;
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->whereNotIn('nisn', Rombel::where('idtahunajaran', $idtahunajaran)->pluck('nisn'))->get();

        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        return view('pages.rombel.show', compact('rombel', 'siswa', 'kelas', 'tahunajaran', 'idkelas', 'kdkelas', 'idtahunajaran', 'tingkat'));
    }

    public function pindahTingkat(Request $request, String $id)
    {
        $request->validate([
            'nisn' => 'required'
        ]);

        try {
            $id = explode('*', Crypt::decrypt($id));
            $idkelas = $id[0];
            $idtahunajaran = $id[1];
        } catch (DecryptException $e) {
        }

        foreach ($request->nisn as $key => $value) {
            Rombel::updateOrCreate([
                'idtahunajaran' => $idtahunajaran,
                'nisn' => $value
            ], [
                'idkelas' => $idkelas,
            ]);
        }
        return redirect()->back()->with('success', 'Data berhasil diproses');
    }

    public function updateRombel(Request $request, string $idkelas, string $idtahunajaran)
    {
        //
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $idkelas = decryptSmart($request->idkelas);

        $request->merge(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran]);

        $requestRombel = $request->validate([
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);

        Rombel::find(["idkelas" => $idkelas, "idtahunajaran" => $idtahunajaran])->update($requestRombel);
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function deleteSiswa(Request $request)
    {
        //
        $request->validate([
            "siswa" => 'required'
        ]);
        foreach ($request->siswa as $key => $value) {
            # code...
            Rombel::find($value)->delete();
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function siswaRombel(Request $request)
    {
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $kelas = decryptSmart($request->idkelas);
        $siswa = Rombel::whereHas('kelas', function ($query) use ($idtahunajaran, $kelas) {
            $query->where(['idkelas' => $kelas, 'idtahunajaran' => $idtahunajaran]);
        })->get();
        // $siswa = Rombel::where(['idkelas' => decryptSmart($request->idkelas), 'idtahunajaran' => decryptSmart($request->idtahunajaran)])->get();
        // $siswa = Rombel::where(['idkelas' => $request->idkelas, 'idtahunajaran' => $request->idtahunajaran])->get();
        $result = $siswa->map(function ($item) {
            return [
                'nisn' => $item->nisn,
                'nama' => $item->siswa->nama
            ];
        });

        return response()->json([
            "total" => $siswa->count(),
            "data" => $result
        ]);
    }

    public function export($id)
    {

        try {
            $id = explode('*', Crypt::decrypt($id));
            $idkelas = $id[0];
            $idtahunajaran = $id[1];
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $rombel = Rombel::with('kelas')->where(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran])->get()->sortBy(function ($rombel) {
            return $rombel->siswa->nama;
        })->values();
        $kelas = Kelas::find($idkelas);

        return Excel::download(new RombelExport($rombel, $kelas->kelas), 'Data-Rombel-' . $kelas->kelas . '.xlsx');
    }

    public function import(Request $request)
    {

        $request->validate([
            'file' => 'required|mimes:xlsx',
            'id' => 'required'
        ]);

        try {
            $id = explode('*', Crypt::decrypt($request->id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $import = new RombelImport($id);
        Excel::import($import, $request->file('file'));

        return back()->with('success', "Berhasil mengimpor {$import->successCount} data.");
    }
}
