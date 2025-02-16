<?php

namespace App\Http\Controllers;

use App\Models\JadwalSistemBlok;
use App\Models\SistemBlok;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
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
        })->orderBy('idtahunajaran', 'desc')->orderBy('status', 'desc')->get();
        $data['tahunajaran'] = TahunAjaran::where('status', 1)->first();
        return view('pages.sistemblok.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $request->merge(['idtahunajaran' => $idtahunajaran]);

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
        try {
            $id = Crypt::decrypt($id);
            $idtahunajaran = decryptSmart($request->idtahunajaran);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $request->merge(['idtahunajaran' => $idtahunajaran]);
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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        SistemBlok::find($id)->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        SistemBlok::query()->update([
            'status' => '0'
        ]);
        SistemBlok::find($id)->update([
            'status' => '1'
        ]);

        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }

    public function jadwal(Request $request)
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $data['sistemblok'] = JadwalSistemBlok::whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where([
                'semester' => $tahunajaran->semester,
                'idtahunajaran' => $tahunajaran->id
            ]);
        })->get();
        $data['sesi'] = SistemBlok::where([
            'semester' => $tahunajaran->semester,
            'idtahunajaran' => $tahunajaran->id
        ])->get();

        return view('pages.sistemblok.jadwal', $data);
    }

    public function simpanJadwal(Request $request)
    {
        $validate = $request->validate([
            'idsistemblok' => 'required',
            'tanggal_mulai' => 'required',
            'tanggal_akhir' => 'required'
        ]);

        JadwalSistemBlok::create($validate);
        return redirect()->back()->with('success', 'Jadwal sesi berhasil disimpan');
    }

    public function hapusJadwal($id)
    {
        try {
            $id = Crypt::decrypt($id);
            JadwalSistemBlok::find($id)->delete();
            return redirect()->back()->with('success', 'Jadwal sesi berhasil dihapus');
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }
}
