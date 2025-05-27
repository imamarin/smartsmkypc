<?php

namespace App\Http\Controllers;

use App\Models\CapaianPembelajaran;
use App\Models\Matpel;
use App\Models\MatpelPengampu;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;

class CPController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');

            $this->view = 'Administrasi Guru-CP & TP';
            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $title = 'Data Capain Pembelajaran!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $nip = Auth::user()->staf->nip;
        $data['matpel'] = Matpel::with('cp')->whereHas(
            'matpelpengampu',
            function ($query) use ($nip) {
                $query->where('nip', $nip)->whereHas(
                    'tahunajaran',
                    function ($query) {
                        $query->where('status', 1);
                    }
                );
            }
        )->get();

        // dd($data['matpel']);
        return view('pages.cp.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'kode_cp' => 'required',
            'capaian' => 'required',
            'kode_matpel' => 'required',
        ]);

        CapaianPembelajaran::create($validate);
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
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        }

        $validate = $request->validate([
            'kode_cp' => 'required',
            'capaian' => 'required',
            'kode_matpel' => 'required',
        ]);

        CapaianPembelajaran::find($id)->update($validate);
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
            return redirect()->back()->with('success', 'Data Berhasil Diubah');
        }

        $cp = CapaianPembelajaran::find($id);
        $cp->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }
}
