<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\Format;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class FormatController extends Controller
{
    //
    public function index()
    {
        //
        $title = 'Data Format Raport!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['format'] = Format::orderBy('id', 'desc')->get();
        $data['tahunajaran'] = TahunAjaran::orderBy('id', 'desc')->get();
        return view('pages.eraports.format.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kurikulum' => 'required',
            'versi' => 'required',
            'tingkat' => 'required',
            'idtahunajaran' => 'required'
        ]);

        Format::updateOrCreate([
            'kurikulum' => $request->kurikulum,
            'versi' => $request->versi,
            'tingkat' => $request->tingkat,
            'idtahunajaran' => $request->idtahunajaran
        ]);

        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        Format::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }
}
