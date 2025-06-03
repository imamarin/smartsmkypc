<?php

namespace App\Http\Controllers;

use App\Exports\WalikelasEksport;
use App\Models\Staf;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use League\Flysystem\DecoratedAdapter;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;
use Maatwebsite\Excel\Facades\Excel;

class WalikelasController extends Controller
{
    /**
     * Display a listing of the resource.
     */

    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'walikelas' ||
                Route::currentRouteName() == 'walikelas.tahunajaran' ||
                Route::currentRouteName() == 'walikelas.petugaspresensi'
            ) {
                $this->view = 'Walikelas-Data Siswa';
            } else {
                $this->view = 'Kurikulum-Data Walikelas';
            }
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            $title = 'Data Walikelas!';
            $text = "Yakin ingin menghapus data ini?";
            confirmDelete($title, $text);

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index(Request $request)
    {
        //
        $data['staf'] = Staf::all();
        $data['tahunajaran'] = TahunAjaran::orderBy('status', 'desc')->orderBy('id', 'desc')->get();
        if ($request->isMethod('post')) {
            $data['walikelas'] = Kelas::whereHas('tahunajaran', function ($query) use ($request) {
                $query->where('id', decryptSmart($request->idtahunajaran));
            })->get();
            $data['idtahunajaran'] = decryptSmart($request->idtahunajaran);
        } else {
            $data['walikelas'] = Kelas::whereHas('tahunajaran', function ($query) {
                $query->where('status', 1);
            })->get();
            $data['idtahunajaran'] = $data['tahunajaran'][0]->id;
        }


        return view('pages.walikelas.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $idtahunajaran = decryptSmart($request->idtahunajaran);
        $idkelas = decryptSmart($request->idkelas);
        $request->merge(['idtahunajaran' => $idtahunajaran, 'idkelas' => $idkelas]);
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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $validate = $request->validate([
            'nip' => 'required'
        ]);

        Walikelas::find($id)->update($validate);

        return redirect()->back()->with('success', 'Data berhasil diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        Walikelas::find($id)->delete();

        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function siswa(Request $request)
    {

        if ($request->idkelas) {
            try {
                $idkelas = Crypt::decrypt($request->idkelas);
            } catch (DecryptException $e) {
                return redirect()->back()->with('error', $e->getMessage());
            }
            $where = [
                'nip' => Auth::user()->staf->nip,
                'idkelas' => $idkelas
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

        return redirect()->route('walikelas.tahunajaran', ['idkelas' => Crypt::encrypt($walikelas->idkelas)])->with('success', 'Petugas presensi harian berhasil disimpan');
    }

    public function export($id)
    {

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $walikelas = Kelas::whereHas('tahunajaran', function ($query) use ($id) {
            $query->where('id', $id);
        })->get();
        $tahunajaran = TahunAjaran::find($id);

        return Excel::download(new WalikelasEksport($walikelas, $tahunajaran), 'Data-Walikelas-' . $tahunajaran->awal_tahun_ajaran . '.xlsx');
    }
}
