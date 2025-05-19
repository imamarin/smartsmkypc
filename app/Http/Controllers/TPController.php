<?php

namespace App\Http\Controllers;

use App\Models\CapaianPembelajaran;
use App\Models\TujuanPembelajaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class TPController extends Controller
{
    //
    public function index(String $id)
    {
        $title = 'Data Tujuan Pembelajaran!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect('/capaian-pembelajaran');
        }

        $data['cp'] = CapaianPembelajaran::where('kode_cp', $id)->first();
        return view('pages.tp.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kode_cp' => 'required',
            'tujuan' => 'required',
            'bt1' => 'numeric|min:0|max:100|required',
            'bt2' => 'numeric|min:0|max:100|required',
            't1' => 'numeric|min:0|max:100|required',
            't2' => 'numeric|min:0|max:100|required',
        ]);

        TujuanPembelajaran::create($validate);
        return redirect()->back()->with('success', 'Data Berhasil Ditambahkan');
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        }

        $validate = $request->validate([
            'kode_cp' => 'required',
            'tujuan' => 'required',
            'bt1' => 'numeric|min:0|max:100|required',
            'bt2' => 'numeric|min:0|max:100|required',
            't1' => 'numeric|min:0|max:100|required',
            't2' => 'numeric|min:0|max:100|required',
        ]);

        TujuanPembelajaran::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Berhasil Diubah');
    }

    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        }

        $tp = TujuanPembelajaran::find($id);
        $tp->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
