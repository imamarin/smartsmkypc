<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\Raport\KategoriSikap;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;

class KategoriSikapController extends Controller
{
    //
    public function __construct()
    {
        $title = 'Kategori Sikap';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }

    public function index()
    {
        $data['sikap'] = KategoriSikap::all();
        return view('pages.eraports.kategorisikap.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kategori' => 'required',
            'sikap' => 'required|string'
        ]);

        KategoriSikap::updateOrCreate($validate);

        return redirect()->back()->with('success', 'Kategori sikap berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        KategoriSikap::find($id)->delete();
        return redirect()->back()->with('success', 'Kategori sikap berhasil dihapus');
    }
}
