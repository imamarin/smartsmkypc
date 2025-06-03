<?php

namespace App\Http\Controllers;

use App\Exports\MatpelEksport;
use App\Models\Matpel;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;

class MataPelajaranController extends Controller
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

            $this->view = 'Kurikulum-Data Mata Pelajaran';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['matpel'] = Matpel::orderBy('matpels_kode', 'asc')->orderBy('kode_matpel', 'asc')->get();
        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.matapelajaran.index', $data);
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
            'kode_matpel' => 'required|unique:matpels,kode_matpel',
            'matpel' => 'required',
            'kelompok' => 'required',
            'matpels_kode' => 'nullable',
        ]);

        Matpel::create($validate);
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
            'matpel' => 'required',
            'kelompok' => 'required',
        ]);
        $validate['matpels_kode'] = $request->matpels_kode;

        Matpel::find($id)->update($validate);
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
            Matpel::where('matpels_kode', $id)->delete;
            Matpel::find($id)->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Tidak dapat menghapus data karena masih memiliki relasi!');
        }
    }

    public function export()
    {

        $matpel = Matpel::orderBy('matpels_kode', 'asc')->orderBy('kode_matpel', 'asc')->get();
        return Excel::download(new MatpelEksport($matpel), 'Data-Matpel.xlsx');
    }
}
