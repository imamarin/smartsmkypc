<?php

namespace App\Http\Controllers;

use App\Exports\KelasExport;
use App\Models\Jurusan;
use Illuminate\Http\Request;
use App\Models\Kelas;
use App\Models\TahunAjaran;
use Maatwebsite\Excel\Facades\Excel;

class KelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['kelas'] = Kelas::with('tahunAjaran')->get();
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
        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'kelas' => 'required',
            'tingkat' => 'required',
            'idjurusan' => 'required',
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
        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'kelas' => 'required',
            'tingkat' => 'required',
            'idjurusan' => 'required',
        ]);

        Kelas::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $kelas = Kelas::find($id);
        $kelas->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function export()
    {
        return Excel::download(new KelasExport, 'Data Kelas.xlsx');
    }

    public function getJsonByIdTahunAjaran(String $id)
    {
        $kelas = Kelas::where('idtahunajaran', $id)->get();
        $tahunajaran = TahunAjaran::find($id);
        return response()->json([
            'idtahunajaran' => $tahunajaran->id,
            'semester' => $tahunajaran->semester,
            'data' => $kelas
        ]);
    }
}
