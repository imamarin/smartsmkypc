<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\Ekstrakurikuler;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class EkstrakurikulerController extends Controller
{
    //
    public function __construct()
    {
        $title = 'Ekstrakurikuler';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index()
    {
        $data['ekstrakurikuler'] = Ekstrakurikuler::all();
        return view('pages.eraports.ekstrakurikuler.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'nama' => 'required|string'
        ]);

        Ekstrakurikuler::updateOrCreate($validate);
        return redirect()->back()->with('success', 'Data Ekstrakurikuler berhasil disimpan');
    }

    public function update(Request $request, String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'nama' => 'required|string'
        ]);

        Ekstrakurikuler::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data Ekstrakurikuler berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        Ekstrakurikuler::find($id)->delete();
        return redirect()->back()->with('success', 'Data Ekstrakurikuler berhasil dihapus');
    }
}
