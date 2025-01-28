<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::select('kdkelas')->orderBy('tingkat', 'asc')->get();
        $rombel = Rombel::with('siswa')->get();
        // dd($rombel->siswa);
        return view('pages.rombel.index', compact('rombel', 'siswa', 'kelas', 'tahunajaran'));
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
        //
        $request->validate([
            'idtahunajaran' => 'required',
            'kdkelas' => 'required',
            'nisn' => 'required'
        ]);

        foreach ($request->nisn as $key => $value) {
            # code...
            Rombel::firstOrCreate([
                'idtahunajaran' => $request->idtahunajaran,
                'kdkelas' => $request->kdkelas,
                'nisn' => $value
            ], [
                'idtahunajaran' => $request->idtahunajaran,
                'kdkelas' => $request->kdkelas,
                'nisn' => $value
            ]);
        }

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
        //
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
