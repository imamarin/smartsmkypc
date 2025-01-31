<?php

namespace App\Http\Controllers;

use App\Models\DetailPresensi;
use App\Models\Presensi;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class PresensiController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $idjadwalmengajar = Crypt::decrypt($request->idjadwalmengajar);
        $idkelas = Crypt::decrypt($request->idkelas);
        $kode_matpel = Crypt::decrypt($request->kode_matpel);
        $kode_guru = Crypt::decrypt($request->kode_guru);

        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $presensi = Presensi::updateOrCreate([
            'idtahunajaran' => $tahunajaran->id,
            'semester' => $tahunajaran->semester,
            'idkelas' => $idkelas,
            'kode_matpel' => $kode_matpel,
            'kode_guru' => $kode_guru,
            'idjadwalmengajar' => $idjadwalmengajar
        ]);

        foreach ($request->presensi as $key => $value) {
            # code...
            DetailPresensi::updateOrCreate([
                'nisn' => $key,
                'keterangan' => $value,
                'idpresensi' => $presensi->id
            ]);
        }

        if ($presensi) {
            Presensi::find($presensi->id)->update(['pokok_bahasan' => $request->pokok_bahasan]);
        } else {
            Presensi::create(['pokok_bahasan' => $request->pokok_bahasan]);
        }

        return redirect()->back()->with('success', 'Presensi berhasil di proses');
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
    }
}
