<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class KelasController extends Controller
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

            if (!isset($this->fiturMenu['Data Kelas'])) {
                return redirect()->back();
            }

            $this->view = 'Data Kelas';
            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['kelas'] = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->get();
        $data['tahun_ajaran'] = TahunAjaran::all();
        $data['jurusan'] = Jurusan::all();
        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.kelas.index', $data);
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
            $idjurusan = decryptSmart($request->idjurusan);
            $idtahunajaran = decryptSmart($request->idtahunajaran);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $request->merge(['idjurusan' => $idjurusan, 'idtahunajaran' => $idtahunajaran]);

        $validate = $request->validate([
            'idtahunajaran' => ['required', 'exists:tahun_ajarans,id'],
            'kelas' => 'required',
            'tingkat' => 'required',
            'idjurusan' => ['required', 'exists:jurusans,id'],
        ]);

        Kelas::create($validate);
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
        try {
            $id = Crypt::decrypt($id);
            $idjurusan = decryptSmart($request->idjurusan);
            $idtahunajaran = decryptSmart($request->idtahunajaran);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $request->merge(['idjurusan' => $idjurusan, 'idtahunajaran' => $idtahunajaran]);

        $validate = $request->validate([
            'idtahunajaran' => ['required', 'exists:tahun_ajarans,id'],
            'kelas' => 'required',
            'tingkat' => 'required',
            'idjurusan' => ['required', 'exists:jurusans,id']
        ]);

        Kelas::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        try {
            //code...
            $kelas = Kelas::find($id);
            $kelas->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data Gagal Dihapus');
        }
    }

    public function export()
    {
        return Excel::download(new KelasExport, 'Data Kelas.xlsx');
    }

    public function getJsonByIdTahunAjaran(String $id)
    {
        try {
            $id = decryptSmart($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $kelas = Kelas::where('idtahunajaran', $id)->get();

        $kelas = $kelas->map(function ($item) {
            return [
                'id' => encryptSmart($item->id),
                'kelas' => $item->kelas
            ];
        });
        $tahunajaran = TahunAjaran::find($id);
        return response()->json([
            'idtahunajaran' => $tahunajaran->id,
            'semester' => $tahunajaran->semester,
            'data' => $kelas
        ]);
    }
}
