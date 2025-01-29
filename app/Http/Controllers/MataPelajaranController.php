<?php

namespace App\Http\Controllers;

use App\Models\Matpel;
use Illuminate\Http\Request;

class MataPelajaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['matpel'] = Matpel::all();
        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.mata pelajaran.index', $data);
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
            'kelompok2' => 'required',
            'kategori' => 'nullable',
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
            'kelompok2' => 'required',
            'kategori' => 'nullable',
        ]);

        Matpel::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        Matpel::find($id)->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
