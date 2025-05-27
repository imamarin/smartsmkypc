<?php

namespace App\Http\Controllers;

use App\Models\KalenderAkademik;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Validation\Rule;
use Illuminate\Routing\Controller;


class KalenderAkademikController extends Controller
{
    //
    protected $tahunajaran;

    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            $this->view = 'Kalender Akademik';


            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
        $this->tahunajaran = TahunAjaran::where('status', 1)->first();

        $title = 'Data Jurusan!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
    }


    public function index()
    {
        //
        $data['kalender'] = KalenderAkademik::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->orderBy('tanggal_mulai')->get();

        return view('pages.kalenderakademik.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kegiatan' => 'required|string',
            'tanggal_mulai' => ['required'],
            'tanggal_akhir' => ['required'],
            'status_kbm' => 'required'
        ]);

        $validate['idtahunajaran'] = $this->tahunajaran->id;

        KalenderAkademik::create($validate);

        return redirect()->back()->with('success', 'Data berhasi disimpan');
    }

    public function update(Request $request, String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        $validate = $request->validate([
            'kegiatan' => 'required|string',
            'tanggal_mulai' => ['required'],
            'tanggal_akhir' => ['required'],
            'status_kbm' => 'required'
        ]);

        $validate['idtahunajaran'] = $this->tahunajaran->id;

        KalenderAkademik::find($id)->update($validate);

        return redirect()->back()->with('success', 'Data berhasi diubah');
    }

    public function destroy(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('success', $e->getMessage());
        }

        KalenderAkademik::find($id)->delete();
        return redirect()->back()->with('success', 'Data berhasi dihapus');
    }
}
