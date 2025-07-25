<?php

namespace App\Http\Controllers;

use App\Exports\MatpelPengampuExport;
use App\Models\Matpel;
use App\Models\MatpelPengampu;
use App\Models\TahunAjaran;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;

class MatpelPengampuController extends Controller
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

            $this->view = 'Administrasi Guru-Matpel Pengampu';
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
        $data['matpel'] = Matpel::all();
        $data['tahunajaran'] = TahunAjaran::where('status', 1)->first();
        $data['matpelpengampu'] = MatpelPengampu::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->where('nip', Auth::user()->staf->nip)->get();

        $title = 'Matpel Pengampu!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        return view('pages.matpelpengampu.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
        $validate = $request->validate([
            'idtahunajaran' => 'required',
            'kode_matpel' => 'required',
        ]);
        $validate['nip'] = Auth::user()->staf->nip;

        MatpelPengampu::create($validate);
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
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        //
        try {
            //code...
            MatpelPengampu::findOrFail($id)->delete();
            return redirect()->back()->with('success', 'Data berhasil dihapus!');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data tidak bisa dihapus');
        }
    }

    public function export()
    {
        $data['matpelpengampu'] = MatpelPengampu::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->where('nip', Auth::user()->staf->nip)->get();
        return Excel::download(new MatpelPengampuExport($data['matpelpengampu']), 'Matpel Pengampu.xlsx');
    }
}
