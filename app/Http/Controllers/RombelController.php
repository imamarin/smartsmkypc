<?php

namespace App\Http\Controllers;

use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->orderBy('tingkat', 'asc')->get();
        // $rombel = Rombel::select('rombels.*', DB::raw('COUNT(*) as total'))->whereHas('tahunajaran', function ($query) {
        //     $query->where('status', 1);
        // })->groupBy('idtahunajaran', 'kdkelas')->get();
        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        // dd($rombel);
        return view('pages.rombel.index', compact('siswa', 'kelas', 'tahunajaran'));
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
            'idkelas' => 'required',
            'nisn' => 'required'
        ]);

        foreach ($request->nisn as $key => $value) {
            # code...
            Rombel::firstOrCreate([
                'idtahunajaran' => $request->idtahunajaran,
                'idkelas' => $request->idkelas,
                'nisn' => $value
            ], [
                'idtahunajaran' => $request->idtahunajaran,
                'idkelas' => $request->idkelas,
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
        $request->validate([
            'nisn' => 'required',
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);
        Rombel::find($id)->update($request->all());
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        echo $id;
        Rombel::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function showStudents(String $idkelas, String $idtahunajaran)
    {
        //
        $kelas = Kelas::find($idkelas);
        $idkelas = $kelas->id;
        $kdkelas = $kelas->kelas;
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::select('id', 'kelas')->orderBy('tingkat', 'asc')->get();
        $rombel = Rombel::where(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran])->get();
        $idtahunajaran = $idtahunajaran;
        return view('pages.rombel.show', compact('rombel', 'siswa', 'kelas', 'tahunajaran', 'idkelas', 'kdkelas', 'idtahunajaran'));
    }

    public function levelUpClass(Request $request, String $id)
    {
        $request->validate([
            'nisn' => 'required',
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);

        Rombel::firstOrCreate([
            'idtahunajaran' => $request->idtahunajaran,
            'idkelas' => $request->kdkelas,
            'nisn' => $request->nisn
        ], [
            'idtahunajaran' => $request->idtahunajaran,
            'kdkelas' => $request->kdkelas,
            'nisn' => $request->nisn
        ]);
        redirect()->back()->with('success', 'Data berhasil diproses');
    }

    public function updateRombel(Request $request, string $kdkelas, string $idtahunajaran)
    {
        //
        $requestRombel = $request->validate([
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);

        Rombel::where(["idkelas" => $kdkelas, "idtahunajaran" => $idtahunajaran])->update($requestRombel);
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }
}
