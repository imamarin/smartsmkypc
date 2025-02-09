<?php

namespace App\Http\Controllers;

use App\Exports\RombelExport;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Maatwebsite\Excel\Facades\Excel;

class RombelController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->get();
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->get();
        $kelas = Kelas::with(['walikelas' => function ($query) {
            $query->limit(1);
        }])
            ->whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            })->orderBy('tingkat', 'asc')->get();

        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
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
            Rombel::upadteOrCreate([
                'idtahunajaran' => $request->idtahunajaran,
                'nisn' => $value
            ], [
                'idkelas' => $request->idkelas,
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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

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
        try {
            $id = Crypt::decrypt($id);
            Rombel::find($id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus');
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data gagal dihapus');
        }
    }

    public function showStudents(String $id)
    {
        //
        try {
            $id = explode('*', Crypt::decrypt($id));
            $idkelas = $id[0];
            $idtahunajaran = $id[1];
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $kelas = Kelas::find($idkelas);
        $idkelas = $kelas->id;
        $kdkelas = $kelas->kelas;
        $tingkat = $kelas->tingkat;
        $tahunajaran = TahunAjaran::orderBy('status', 'desc')->get();
        $kelas = Kelas::select('id', 'kelas', 'tingkat')->orderBy('tingkat', 'asc')->get();
        $rombel = Rombel::where(['idkelas' => $idkelas, 'idtahunajaran' => $idtahunajaran])->get();
        $idtahunajaran = $idtahunajaran;
        $siswa = Siswa::select('nisn', 'nama')->where('status', 1)->whereNotIn('nisn', Rombel::where('idtahunajaran', $idtahunajaran)->pluck('nisn'))->get();

        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        return view('pages.rombel.show', compact('rombel', 'siswa', 'kelas', 'tahunajaran', 'idkelas', 'kdkelas', 'idtahunajaran', 'tingkat'));
    }

    public function pindahTingkat(Request $request, String $id)
    {
        $request->validate([
            'nisn' => 'required'
        ]);

        try {
            $id = Crypt::decrypt($id);
            $idkelas = $id[0];
            $idtahunajaran = $id[1];
        } catch (DecryptException $e) {
        }

        foreach ($request->nisn as $key => $value) {
            Rombel::updateOrCreate([
                'idtahunajaran' => $idtahunajaran,
                'nisn' => $value
            ], [
                'idkelas' => $idkelas,
            ]);
        }
        return redirect()->back()->with('success', 'Data berhasil diproses');
    }

    public function updateRombel(Request $request, string $idkelas, string $idtahunajaran)
    {
        //
        $requestRombel = $request->validate([
            'idkelas' => 'required',
            'idtahunajaran' => 'required'
        ]);

        Rombel::find(["idkelas" => $idkelas, "idtahunajaran" => $idtahunajaran])->update($requestRombel);
        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    public function deleteSiswa(Request $request)
    {
        //
        $request->validate([
            "siswa" => 'required'
        ]);
        foreach ($request->siswa as $key => $value) {
            # code...
            Rombel::find($value)->delete();
        }
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function siswaRombel(Request $request)
    {
        $siswa = Rombel::where(['idkelas' => $request->idkelas, 'idtahunajaran' => $request->idtahunajaran])->get();
        $result = $siswa->map(function ($item) {
            return [
                'nisn' => $item->nisn,
                'nama' => $item->siswa->nama
            ];
        });

        return response()->json([
            "total" => $siswa->count(),
            "data" => $result
        ]);
    }

    public function export()
    {
        $kelas = Kelas::with(['rombel', 'jurusan'])->whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->orderBy('tingkat', 'asc')->get();
        return Excel::download(new RombelExport($kelas), 'Data Rombel.xlsx');
    }
}
