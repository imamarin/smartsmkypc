<?php

namespace App\Http\Controllers;

use App\Models\JamPelajaran;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class JamPelajaranController extends Controller
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

            $this->view = 'Kurikulum-Data Jam Pelajaran';

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        //
        $data['tahunajaran'] = TahunAjaran::orderBy('awal_tahun_ajaran', 'desc')->get();
        $data['jampel'] = JamPelajaran::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->get();

        $title = 'Data Kelas!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        return view('pages.jampelajaran.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $idtahunajaran = $request->idtahunajaran;
        $request->merge(['idtahunajaran' => $idtahunajaran]);
        $request->validate([
            'idtahunajaran' => 'required',
            'hari' => 'required',
            'jam_masuk' => 'required',
            'durasi' => 'required|numeric|min:1',
            'jumlah_jampel' => 'required|numeric|min:1',
            'jam_istirahat' => 'required|regex:/^\d+(,\d+)*$/',
            'durasi_istirahat' => 'required|numeric|min:1'
        ]);

        $mulai = date("H:i:s", strtotime($request->jam_masuk));
        $akhir = date("H:i:s", strtotime($mulai . " +$request->durasi minutes"));
        $jamIstirahat = explode(",", $request->jam_istirahat);
        for ($i = 1; $i <= $request->jumlah_jampel; $i++) {
            # code...
            JamPelajaran::updateOrCreate([
                'hari' => $request->hari,
                'jam' => $i,
                'mulai' => $mulai,
                'akhir' => $akhir,
                'idtahunajaran' => $request->idtahunajaran
            ], [
                'hari' => $request->hari,
                'jam' => $i,
                'mulai' => $mulai,
                'akhir' => $akhir,
                'idtahunajaran' => $request->idtahunajaran
            ]);

            if (in_array($i, $jamIstirahat)) {
                $mulai = date('H:i:s', strtotime($akhir . " +$request->durasi_istirahat minutes"));
                $akhir = date('H:i:s', strtotime($mulai . " +$request->durasi minutes"));
            } else {
                $mulai = $akhir;
                $akhir = date('H:i:s', strtotime($mulai . " +$request->durasi minutes"));
            }
        }

        return redirect()->back()->with('success', 'Data berhasil disimpan');
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
            $request->merge(['idtahunajaran' => $idtahunajaran]);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'hari' => 'required|min:1',
            'jam' => 'required',
            'mulai' => 'required',
            'akhir' => 'required'
        ]);

        JamPelajaran::find($id)->update($validate);
        return redirect()->back()->with('success', 'Data berhasil diubah!');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //

    }

    public function delete(Request $request)
    {
        JamPelajaran::whereIn('id', $request->jampel)->delete();
        return redirect()->back()->with('success', 'Data berhasil dihapus');
    }

    public function getJam(String $id)
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $jampel = JamPelajaran::select('jam')->where('idtahunajaran', $tahunajaran->id)->where('hari', $id)->orderBy('jam')->get();
        return response()->json([
            'data' => $jampel
        ]);
    }
}
