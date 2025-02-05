<?php

namespace App\Http\Controllers;

use App\Models\SistemBlok;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class SistemBlokController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $title = 'Hapus Sesi!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['sistemblok'] = SistemBlok::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->orderBy('idtahunajaran', 'desc')->get();
        $data['tahunajaran'] = TahunAjaran::where('status', 1)->first();
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

    public function updateStatus(Request $request, $id)
    {
        $id = Crypt::decrypt($id);
        SistemBlok::query()->update([
            'status' => 0
        ]);
        SistemBlok::find($id)->update([
            'status' => 1
        ]);

        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }
}
