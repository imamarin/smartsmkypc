<?php

namespace App\Http\Controllers\Keuangan;

use App\Models\Jurusan;
use App\Models\Keuangan\KategoriKeuangan;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class KategoriKeuanganController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Keuangan-Kategori Keuangan';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['kategorikeuangan'] = KategoriKeuangan::orderBy('id', 'desc')->get();
        $title = 'Data Kategori Keuangan!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['jurusan'] = Jurusan::orderBy('jurusan', 'asc')->get();
        $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        return view('pages.keuangan.kategori.index', $data);
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
            'nama' => 'required',
            'biaya' => 'required',
            'jurusan' => 'required',
            'idtahunajaran' => 'required',
        ]);

        KategoriKeuangan::create($validate);
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
        $validate = $request->validate([
            'nama' => 'required',
            'biaya' => 'required',
            'jurusan' => 'required',
            'idtahunajaran' => 'required',
        ]);

        KategoriKeuangan::find($id)->update($validate);
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
            return redirect()->back()->with('error', $e->getMessage());
        }

        try {
            //code...
            KategoriKeuangan::find($id)->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Tidak dapat menghapus data karena masih memiliki relasi!');
        }
    }

    public function export()
    {

        // $matpel = KategoriKeuangan::get();
        // return Excel::download(new MatpelEksport($matpel), 'Data-Matpel.xlsx');
    }
}
