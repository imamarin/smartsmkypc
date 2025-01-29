<?php

namespace App\Http\Controllers;

use App\Models\Guru;
use App\Models\Rombel;
use App\Models\Walikelas;
use Illuminate\Http\Request;

class WalikelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
        $data['guru'] = Guru::all();
        $data['walikelas'] = Rombel::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->get();

        $title = 'Data Walikelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        // dd($data['walikelas']);
        return view('pages.walikelas.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'idrombel' => 'required',
            'kode_guru' => 'required'
        ]);

        Walikelas::create($validate);
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        //
        $validate = $request->validate([
            'kode_guru' => 'required'
        ]);

        Walikelas::findOrFail($id)->update($validate);
        return redirect()->back()->with('Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
        Walikelas::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
