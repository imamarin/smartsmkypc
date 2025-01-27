<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use App\Models\Jurusan;

class JurusanController extends Controller
{
    /**
     * Display a listing of the resource.
     */
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
            'idtahunajaran' => 'required',
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
        $validate = $request->validate([
            'idtahunajaran' => 'required',
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
        $jurusan = Jurusan::find($id);
        $jurusan->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
