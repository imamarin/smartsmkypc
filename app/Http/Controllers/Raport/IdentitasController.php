<?php

namespace App\Http\Controllers\Raport;

// use App\Http\Controllers\Controller;
use App\Models\Raport\IdentitasRaport;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Throwable;

class IdentitasController extends Controller
{
    protected $tahunajaran;
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            $this->view = 'E-Raport-Aktivasi Raport';

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });

        $this->tahunajaran = TahunAjaran::where('status', 1)->first();
    }

    //
    public function index()
    {
        $title = 'Identitas Raport!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['identitas'] = IdentitasRaport::orderBy('id', 'desc')->get();
        $data['aktivasi'] = Session::get('aktivasi');
        return view('pages.eraports.identitas.index', $data);
    }

    public function store(Request $request)
    {
        $validate = $request->validate([
            'tanggal_terima_raport' => 'required',
            'nama_sekolah' => 'required',
            'nss_sekolah' => 'required|numeric',
            'kepala_sekolah' => 'required',
            'nip_kepala_sekolah' => 'required',
            'email' => 'required|email',
            'website' => 'required',
            'alamat' => 'required'
        ]);

        $validate['idtahunajaran'] = $this->tahunajaran->id;
        $validate['semester'] = $this->tahunajaran->semester;

        IdentitasRaport::create($validate);
        return redirect()->back()->with('success', 'Identitas raport berhasil disimpan');
    }

    public function update(Request $request, String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'tanggal_terima_raport' => 'required',
            'nama_sekolah' => 'required',
            'nss_sekolah' => 'required|numeric',
            'kepala_sekolah' => 'required',
            'nip_kepala_sekolah' => 'required',
            'email' => 'required|email',
            'website' => 'required',
            'alamat' => 'required'
        ]);

        $validate['idtahunajaran'] = $this->tahunajaran->id;
        $validate['semester'] = $this->tahunajaran->semester;

        IdentitasRaport::find($id)->update($validate);
        return redirect()->back()->with('success', 'Identitas raport berhasil disimpan');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        try {
            $id = Crypt::decrypt($id);
            IdentitasRaport::find($id)->delete();
            return redirect()->back()->with('success', 'Data Identitas berhasil dihapus');
        } catch (\Throwable $th) {
            return redirect()->back()->with('error', 'Data tidak bisa dihapus');
        }
    }

    public function aktivasi(Request $request, $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        Session::put('aktivasi', IdentitasRaport::find($id));
        // IdentitasRaport::query()->update([
        //     'status_raport' => '0'
        // ]);
        // IdentitasRaport::find($id)->update([
        //     'status_raport' => '1'
        // ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }
}
