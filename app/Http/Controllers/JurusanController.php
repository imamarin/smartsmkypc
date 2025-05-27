<?php

namespace App\Http\Controllers;

use App\Exports\JurusanExport;
use App\Exports\SiswaExport;
use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Jurusan;
use App\Models\Siswa;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class JurusanController extends Controller
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

            $this->view = 'Data Master-Data Jurusan';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['tahun_ajaran'] = TahunAjaran::all();
        $data['jurusan'] = Jurusan::with('tahunajaran')->get();
        $title = 'Data Jurusan!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.jurusan.index', $data);
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
        $validate = $request->validate([
            // 'idtahunajaran' => 'required',
            'jurusan' => 'required',
            'kompetensi' => 'required',
            'program_keahlian' => 'required',
            'bidang_keahlian' => 'required',
        ]);

        Jurusan::create($validate);
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
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        }

        $validate = $request->validate([
            // 'idtahunajaran' => 'required',
            'jurusan' => 'required',
            'kompetensi' => 'required',
            'program_keahlian' => 'required',
            'bidang_keahlian' => 'required',
        ]);

        Jurusan::find($id)->update($validate);
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
            $jurusan = Jurusan::find($id);
            $jurusan->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data tidak bisa dihapus');
        }
    }

    public function export()
    {
        if (!in_array('Eksport', $this->fiturMenu['Data Jurusan'])) {
            return redirect()->back();
        }
        return Excel::download(new JurusanExport, 'Data Jurusan.xlsx');
    }
}
