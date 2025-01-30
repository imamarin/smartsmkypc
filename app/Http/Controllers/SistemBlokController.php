<?php

namespace App\Http\Controllers;

use App\Models\SistemBlok;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class SistemBlokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['sistemblok'] = SistemBlok::orderBy('idtahunajaran', 'desc')->get();
        $data['tahunajaran'] = TahunAjaran::all();
        return view('pages.sistemblok.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'nama_sesi' => 'required',
            'semester' => 'required',
            'idtahunajaran' => 'required'
        ]);

        SistemBlok::create($validate);
        return redirect()->back()->with('success', 'Data Berhasil Disimpan!');
    }

    /**
     * Display the specified resource.
     */
    public function show(string $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        //
        $validate = $request->validate([
            'nama_sesi' => 'required',
            'semester' => 'required',
            'idtahunajaran' => 'required'
        ]);

        SistemBlok::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        SistemBlok::find($id)->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
