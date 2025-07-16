<?php

namespace App\Http\Controllers\Keuangan;

use Illuminate\Routing\Controller;

use App\Models\Honor;
use App\Models\HonorDetail;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Route;

class HonorController extends Controller
{
    //
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'honorarium-pegawai.index' ||
                Route::currentRouteName() == 'honorarium-pegawai.store' ||
                Route::currentRouteName() == 'honorarium-pegawai.destroy' ||
                Route::currentRouteName() == 'staf.honorarium'
            ) {
                $this->view = 'Keuangan-Honorarium Pegawai';
            }

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            $title = 'Data Honor!';
            $text = "Yakin ingin menghapus honor bulan ini?";
            confirmDelete($title, $text);

            view()->share('view', $this->view);

            return $next($request);
        });
    }


    public function index()
    {
        $data['bulan'] = date('n');
        $data['honor'] = Honor::orderBy('tahun', 'desc')
            ->orderBy('bulan', 'desc')
            ->get();
        return view('pages.keuangan.honor.index', $data);
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'tahun' => 'required|integer|min:1900|max:2099',
            'bulan' => 'required|string|max:20',
        ]);

        Honor::create($validated);

        return redirect()->route('honorium-pegawai.index')->with('success', 'Data honor berhasil disimpan.');
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $honor = Honor::findOrFail($id);
        $honor->delete();

        return redirect()->route('honorium-pegawai.index')->with('success', 'Data honor berhasil dihapus.');
    }
}
