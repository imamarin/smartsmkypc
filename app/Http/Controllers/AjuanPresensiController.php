<?php

namespace App\Http\Controllers;

use App\Models\AjuanPresensiMengajar;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Storage;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class AjuanPresensiController extends Controller
{

    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'ajuan-kehadiran-mengajar.index' ||
                Route::currentRouteName() == 'ajuan-kehadiran-mengajar.update'
            ) {
                $this->view = 'Kurikulum-Pengajuan Kehadiran Mengajar';
            } else if (
                Route::currentRouteName() == 'ajuan-kehadiran-mengajar.store' ||
                Route::currentRouteName() == 'ajuan-kehadiran-mengajar.destroy'
            ) {
                $this->view = 'Rekap Presensi Mengajar';
            }

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    //
    public function index()
    {
        //
        $title = 'Ajuan Kehadiran Mengajar!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['ajuan'] = AjuanPresensiMengajar::orderBy('status', 'asc')->get();
        return view('pages.pengajuan.index', $data);
    }

    public function store(Request $request)
    {
        //
        try {
            $id = explode('*', Crypt::decrypt($request->id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'alasan' => 'required',
            'bukti_file' => 'required|file|mimes:jpg,jpeg,png,pdf|max:3048'
        ]);

        $file = $request->file('bukti_file');
        $fileName = hash('sha256', time() . '_' . $id[0]) . "." . $file->getClientOriginalExtension();
        $file->storeAs('bukti_ajuan_mengajar', $fileName);

        $validate['bukti_file'] = $fileName;
        $validate['idjadwalmengajar'] = $id[0];
        $validate['tanggal_mengajar'] = $id[1];
        $validate['status'] = '0';

        AjuanPresensiMengajar::create($validate);
        return redirect()->back()->with('success', 'Ajuan berhasil dikirim, silakan lakukan cek secara berkala');
    }

    public function update(Request $request, String $id)
    {
        //
        try {
            $id = explode('*', Crypt::decrypt($request->id));
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }


        $request->validate([
            'bukti_file' => 'file|mimes:jpg,jpeg,png,pdf|max:3048'
        ]);

        $ajuan = AjuanPresensiMengajar::find($id[0]);

        if ($request->hasFile('bukti_file')) {
            if (Storage::exists('bukti_ajuan_mengajar/' . $ajuan->bukti_file)) {
                Storage::delete('bukti_ajuan_mengajar/' . $ajuan->bukti_file);
            }

            $file = $request->file('bukti_file');
            $fileName = hash('sha256', time() . '_' . $ajuan->idjadwalmengajar) . "." . $file->getClientOriginalExtension();
            $file->storeAs('bukti_ajuan_mengajar', $fileName);
            $ajuan->bukti_file = $fileName;
        }

        $ajuan->alasan = $request->alasan ?? $ajuan->alasan;
        $ajuan->tanggapan = $request->tanggapan ?? $ajuan->tanggapan;
        $ajuan->status = $id[1] ?? $request->status_ajuan;

        $ajuan->save();

        return redirect()->back()->with('success', 'Perbaikan Ajuan berhasil dikirim, silakan lakukan cek secara berkala');
    }

    public function destroy(String $id)
    {
        //
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $ajuan = AjuanPresensiMengajar::find($id);

        if (Storage::exists('bukti_ajuan_mengajar/' . $ajuan->bukti_file)) {
            Storage::delete('bukti_ajuan_mengajar/' . $ajuan->bukti_file);
        }

        $ajuan->delete();

        return redirect()->back()->with('success', 'Perbaikan Ajuan berhasil dihapus!');
    }
}
