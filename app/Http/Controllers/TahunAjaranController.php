<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Support\Facades\Crypt;

class TahunAjaranController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['tahun_ajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        $title = 'Data Tahun Ajaran!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.tahun ajaran.index', $data);
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
            'awal_tahun_ajaran' => 'required',
            'akhir_tahun_ajaran' => 'required',
            'semester' => 'required',
            'tgl_mulai' => 'required',
        ]);

        tahunAjaran::create($validate);
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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'awal_tahun_ajaran' => 'required',
            'akhir_tahun_ajaran' => 'required',
            'semester' => 'required',
            'tgl_mulai' => 'required',
        ]);

        tahunAjaran::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $tahun_ajaran = TahunAjaran::find($id);
        try {
            if ($tahun_ajaran->status != 1) {
                $tahun_ajaran->delete();
                return redirect()->back()->with('success', 'Data Berhasil Dihapus');
            }
            return redirect()->back()->with('errors', 'Data Tidak Bisa Dihapus');
        } catch (\Exception $e) {
            if ($e->getCode() == 23000) {
                // Error code 23000 biasanya terkait dengan constraint violation
                return redirect()->route('tahun-ajaran.index')->with('error', 'Data tidak bisa dihapus karena masih digunakan.');
            }
        }
    }

    public function updateStatus(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $tahun_ajaran = TahunAjaran::find($id);
        if ($request->status == 1) {
            $activeTahunAjaran = TahunAjaran::where('status', 1)->first();
            if ($activeTahunAjaran && $activeTahunAjaran->id !== $tahun_ajaran->id) {
                return redirect()->back()->with('error', 'Hanya boleh ada satu tahun ajaran aktif.');
            }
        }
        $tahun_ajaran->update([
            'status' => $request->status
        ]);

        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }
}
