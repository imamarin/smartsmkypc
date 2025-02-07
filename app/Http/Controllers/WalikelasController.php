<?php

namespace App\Http\Controllers;

use App\Models\Staf;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Walikelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;

class WalikelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index(Request $request)
    {
        //
        $data['staf'] = Staf::all();
        $data['tahunajaran'] = TahunAjaran::orderBy('status', 'desc')->get();
        if ($request->isMethod('post')) {
            $data['walikelas'] = Kelas::whereHas('tahunajaran', function ($query) use ($request) {
                $query->where('id', $request->idtahunajaran);
            })->get();
            $data['idtahunajaran'] = $request->idtahunajaran;
        } else {
            $data['walikelas'] = Kelas::whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            })->get();
            $data['idtahunajaran'] = '';
        }

        $title = 'Data Walikelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        // dd($data['walikelas']);
        return view('pages.walikelas.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'idkelas' => 'required',
            'nip' => 'required'
        ]);

        Walikelas::create($validate);
        return redirect()->back()->with('success', 'Data berhasil disimpan');
    }

    /**
     * Display the specified resource.
     */
    public function show(String $id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, String $id)
    {
        //
        $validate = $request->validate([
            'nip' => 'required'
        ]);

        Walikelas::findOrFail($id)->update($validate);
        return redirect()->back()->with('Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
        Walikelas::findOrFail($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function siswa(Request $request)
    {

        if ($request->idkelas) {
            $where = [
                'nip' => Auth::user()->staf->nip,
                'idkelas' => $request->idkelas
            ];
        } else {
            $where = [
                'nip' => Auth::user()->staf->nip
            ];
        }

        $data['kelas'] = Walikelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->where($where)->get();

        if ($data['kelas']->count() < 1) {
            return redirect()->back()->with('warning', 'Walikelas tidak tersedia!');
        }

        $data['walikelas'] = Walikelas::with(['kelas' =>  function ($query) {
            $query->withCount([
                'rombel as laki_count' => function ($query) {
                    $query->whereHas('siswa', function ($query) {
                        $query->where('jenis_kelamin', 'L');
                    });
                },
                'rombel as perempuan_count' => function ($query) {
                    $query->whereHas('siswa', function ($query) {
                        $query->where('jenis_kelamin', 'P');
                    });
                },
            ]);
        }])->where($where)->whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->first();

        return view('pages.walikelas.siswa', $data);
    }

    public function petugasPresensi(Request $request, String $id)
    {
        $id = Crypt::decrypt($id);

        $walikelas = Walikelas::find($id);
        $walikelas->update([
            'petugas_presensi' => $request->nisn
        ]);

        return redirect()->route('walikelas.tahunajaran', ['idkelas' => $walikelas->idkelas])->with('success', 'Petugas presensi harian berhasil disimpan');
    }
}
