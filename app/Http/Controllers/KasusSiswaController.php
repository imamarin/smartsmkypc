<?php

namespace App\Http\Controllers;

use App\Models\KasusSiswa;
use App\Models\Kelas;
use App\Models\Rombel;
use App\Models\Walikelas;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Route;

class KasusSiswaController extends Controller
{
    protected $view;
    protected $fiturMenu;

    public function __construct()
    {
        $this->middleware(function ($request, $next) {
            $this->fiturMenu = session('fiturMenu');
            if (
                Route::currentRouteName() == 'laporan-kasus-siswa.index' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.rombel' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.create' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.store' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.edit' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.update' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.destroy' ||
                Route::currentRouteName() == 'laporan-kasus-siswa.detail'

            ) {
                $this->view = 'Walikelas-Laporan Kasus Siswa';
            }

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $title = 'Data Kasus Siswa';
        $text = "Yakin ingin menghapus data kasus siswa ini?";
        confirmDelete($title, $text);

        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get();

        $data['kelas'] = $kelas;
        $data['kelas_selected'] = '';
        $data['kasus'] = [];
        return view('pages.walikelas.kasussiswa', $data);
    }

    public function rombel(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->idkelas);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $title = 'Data Kasus Siswa';
        $text = "Yakin ingin menghapus data kasus siswa ini?";
        confirmDelete($title, $text);

        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get();

        $data['kelas'] = $kelas;
        $data['kasus'] = KasusSiswa::whereHas('siswa.rombel', function ($query) use ($id) {
            $query->where('idkelas', $id);
        })->get();

        $data['kelas_selected'] = $id;
        return view('pages.walikelas.kasussiswa', $data);
    }

    public function create(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->idkelas);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Kelas tidak ditemukan.');
        }

        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get()->pluck('id');

        $siswa = Rombel::whereHas('kelas', function ($query) use ($kelas) {
            $query->whereIn('idkelas', $kelas);
        })->get();
        return view('pages.walikelas.kasussiswa_create', compact('kelas', 'siswa'));
    }

    public function store(Request $request)
    {
        $validated = $request->validate([
            'nisn' => 'required',
            'jenis_kasus' => 'required',
            'deskripsi' => 'nullable',
            'tanggal_kasus' => 'nullable|date',
            'status' => 'required|in:private,open,closed',
            'penanganan' => 'nullable',
        ]);

        KasusSiswa::create($validated);
        $kelas = Rombel::where('nisn', $validated['nisn'])->whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->first();
        $idkelas = $kelas ? $kelas->idkelas : null;
        return redirect()->route('laporan-kasus-siswa.rombel', ['idkelas' => Crypt::encrypt($idkelas)])->with('success', 'Laporan kasus siswa berhasil dibuat.');
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        $kasus = KasusSiswa::findOrFail($id);
        $kelas = Kelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->whereHas('walikelas', function ($query) {
            $query->where('nip', Auth::user()->staf->nip);
        })->get()->pluck('id');

        $siswa = Rombel::whereHas('kelas', function ($query) use ($kelas) {
            $query->whereIn('idkelas', $kelas);
        })->get();

        return view('pages.walikelas.kasussiswa_edit', compact('kasus', 'siswa'));
    }

    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $validated = $request->validate([
            'nisn' => 'required',
            'jenis_kasus' => 'required',
            'deskripsi' => 'nullable',
            'tanggal_kasus' => 'nullable|date',
            'status' => 'required|in:private,open,closed,sp1,sp2,sp3',
            'penanganan' => 'nullable',
        ]);

        $kasus = KasusSiswa::findOrFail($id);
        $kasus->update($validated);

        $kelas = Rombel::where('nisn', $validated['nisn'])->whereHas('tahunajaran', function ($query) {
            $query->where('status', '1');
        })->first();
        $idkelas = $kelas ? $kelas->idkelas : null;
        return redirect()->route('laporan-kasus-siswa.rombel', ['idkelas' => Crypt::encrypt($idkelas)])->with('success', 'Laporan kasus siswa berhasil diperbarui.');
    }

    public function detail(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }

        $kasus = KasusSiswa::with(['siswa.rombel' => function ($q) {
            $q->whereHas('tahunajaran', function ($query) {
                $query->where('status', '1');
            });
        }])
            ->findOrFail($id);

        return view('pages.walikelas.kasussiswa_detail', compact('kasus'));
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', 'Data tidak ditemukan.');
        }
        $kasus = KasusSiswa::findOrFail($id);
        $kasus->delete();
        return redirect()->back()->with('success', 'Laporan kasus siswa berhasil dihapus.');
    }
}
