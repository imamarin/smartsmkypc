<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Imports\SiswaImport;
use App\Models\JadwalMengajar;
use App\Models\JamPelajaran;
use App\Models\Role;
use App\Models\Rombel;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\TokenMengajar;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Routing\Controller;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Route;

class SiswaController extends Controller
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
                Route::currentRouteName() == 'siswa.profil' ||
                Route::currentRouteName() == 'profil-siswa.update' ||
                Route::currentRouteName() == 'siswa.jadwal'
            ) {
                $this->view = 'Layanan Siswa-Profil';
            } else if (Route::currentRouteName() == 'walikelas.siswa.edit') {
                $this->view = 'Walikelas-Data Siswa';
            } else if (Route::currentRouteName() == 'data-siswa.index') {
                $this->view = 'Data Master-Data Siswa';
            } else if (Route::currentRouteName() == 'data-siswa.update') {
                $this->view = 'Data Master-Data Siswa';
                if (!isset($this->fiturMenu[$this->view])) {
                    $this->view = 'Walikelas-Data Siswa';
                }
            } else {
                $this->view = 'Data Master-Data Siswa';
            }

            // dd($this->view);

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            $title = 'Hapus Siswa!';
            $text = "Yakin ingin menghapus data ini?";
            confirmDelete($title, $text);

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {

        $data['tahunajaran'] = TahunAjaran::orderBy('id', 'desc')->get();

        // $data['siswa'] = Rombel::whereHas('tahunajaran', function ($query) {
        //     $query->where('status', 1);
        // })->get();
        $data['siswa'] = Siswa::where('status', 1)->get();
        return view('pages.siswa.index', $data);
    }

    public function tahunajaran(Request $request)
    {
        try {
            $id = Crypt::decrypt($request->id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $data['tahunajaran'] = TahunAjaran::orderBy('id', 'desc')->get();

        $data['siswa'] = Siswa::whereHas('rombel.tahunajaran', function ($query) use ($id) {
            $query->where('idtahunajaran', $id);
        })->get();

        $data['idtahunajaran'] = $id;

        return view('pages.siswa.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        if (!in_array('Tambah', $this->fiturMenu[$this->view])) {
            return redirect()->back();
        }

        $data['tahun_ajaran'] = TahunAjaran::all();
        $data['role'] = Role::all();
        return view('pages.siswa.create', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'username' => 'required|unique:users,username',
            'password' => 'required',
        ]);

        $requestSiswa = $request->validate([
            'nisn' => 'required|unique:siswas,nisn',
            'nis' => 'required|unique:siswas,nis',
            'asal_sekolah' => 'required',
            'nik' => 'required|unique:siswas,nik',
            'alamat_siswa' => 'required',
            'nama' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'diterima_tanggal' => 'required',
            'idtahunajaran' => 'required',
            'status' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_ayah' => 'required',
            'pekerjaan_ibu' => 'required',
            'alamat_ortu' => 'required',
            'no_hp_siswa' => 'required',
            'no_hp_ortu' => 'required',
            'status_keluarga' => 'required',
            'anak_ke' => 'required',
        ]);
        $requestSiswa['nisn_dapodik'] = $request->nisn;
        $requestSiswa['walisiswa'] = $request->walisiswa;
        $requestSiswa['pekerjaan_wali'] = $request->pekerjaan_wali;
        $requestSiswa['alamat_wali'] = $request->alamat_wali;
        $requestSiswa['no_hp_wali'] = $request->no_hp_wali;
        $dataRole = $request->validate([
            'role' => 'required'
        ]);


        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        $dataSiswa = $requestSiswa;
        $dataSiswa['iduser'] = $user->id;
        Siswa::create($dataSiswa);

        foreach ($request->role as $key => $value) {
            UserRole::create([
                'iduser' => $user->id,
                'idrole' => $value
            ]);
        }
        return redirect()->route('data-siswa.index')->with('success', 'Data Berasil Disimpan');
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
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
        $data['siswa'] = Siswa::with(['tahunajaran', 'user'])->where('iduser', $id)->first();
        $data['tahun_ajaran'] = TahunAjaran::all();
        $data['role'] = Role::all();
        $data['roleUser'] = UserRole::where('iduser', $id)->get()->pluck('idrole')->toArray();

        if ($this->view == 'Layanan Siswa-Profil') {
            return view('pages.siswa.profil', $data);
        }

        return view('pages.siswa.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $users = User::find($id);
        if ($users) {
            if ($users->username != $request->username) {
                $request->validate([
                    'username' => 'required|unique:users,username'
                ]);
                $users->username = $request->username;
            }

            if ($request->password) {
                $users->password = bcrypt($request->password);
            }

            $users->save();

            $updateSiswa = Siswa::where('iduser', $id);
            $siswa = $updateSiswa->first();

            if ($this->view == 'Layanan Siswa-Profil') {
                $editSiswa = $request->validate([
                    'nis' => $siswa->nis != $request->nis ? 'unique:siswa,nis' : 'required',
                    'asal_sekolah' => 'required',
                    'nik' => 'required',
                    'alamat_siswa' => 'required',
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'tanggal_lahir' => 'required',
                    'jenis_kelamin' => 'required',
                    'anak_ke' => 'required',
                    'no_hp_siswa' => 'required',
                    'nama_ayah' => 'required',
                    'nama_ibu' => 'required',
                    'pekerjaan_ayah' => 'required',
                    'pekerjaan_ibu' => 'required',
                    'alamat_ortu' => 'required',
                    'no_hp_ortu' => 'required',
                    'status_keluarga' => 'required',
                ]);
                $editSiswa['nisn_dapodik'] = $request->nisn;
                $editSiswa['walisiswa'] = $request->walisiswa;
                $editSiswa['pekerjaan_wali'] = $request->pekerjaan_wali;
                $editSiswa['alamat_wali'] = $request->alamat_wali;
                $editSiswa['no_hp_wali'] = $request->no_hp_wali;
                $updateSiswa->update($editSiswa);
                return redirect()->route('siswa.profil')->with('success', 'Data Berhasil Diubah');
            } else {
                $editSiswa = $request->validate([
                    // 'nisn' => $siswa->nisn != $request->nisn ? 'required|unique:siswa,nisn' : 'required',
                    'nis' => $siswa->nis != $request->nis ? 'unique:siswa,nis' : 'required',
                    'asal_sekolah' => 'required',
                    'nik' => 'required',
                    'alamat_siswa' => 'required',
                    'nama' => 'required',
                    'tempat_lahir' => 'required',
                    'tanggal_lahir' => 'required',
                    'jenis_kelamin' => 'required',
                    'diterima_tanggal' => 'required',
                    'no_hp_siswa' => 'required',
                    'idtahunajaran' => 'required',
                    'status' => 'required',
                    'nama_ayah' => 'required',
                    'nama_ibu' => 'required',
                    'pekerjaan_ayah' => 'required',
                    'pekerjaan_ibu' => 'required',
                    'alamat_ortu' => 'required',
                    'no_hp_ortu' => 'required',
                    'status_keluarga' => 'required',
                    'anak_ke' => 'required',
                ]);
                $editSiswa['nisn_dapodik'] = $request->nisn;
                $editSiswa['walisiswa'] = $request->walisiswa;
                $editSiswa['pekerjaan_wali'] = $request->pekerjaan_wali;
                $editSiswa['alamat_wali'] = $request->alamat_wali;
                $editSiswa['no_hp_wali'] = $request->no_hp_wali;
                $updateSiswa->update($editSiswa);

                $role = UserRole::where('iduser', $id);
                if ($role->count() > 0) {
                    $role->delete();
                }
                foreach ($request->role as $key => $value) {
                    UserRole::create([
                        'iduser' => $id,
                        'idrole' => $value
                    ]);
                }
                return redirect()->route('data-siswa.index')->with('success', 'Data Berhasil Diubah');
            }
        }

        return redirect()->back()->with('danger', 'Data Gagal Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        try {
            //code...
            $userRole = UserRole::where('iduser', $id);
            $siswa = Siswa::where('iduser', $id);
            $user = User::find($id);
            $siswa->delete();
            $userRole->delete();
            $user->delete();
            return redirect()->back()->with('success', 'Data Berhasil Dihapus');
        } catch (\Throwable $th) {
            //throw $th;
            return redirect()->back()->with('error', 'Data tidak bisa dihapus');
        }
    }

    public function updateStatus(Request $request, $id)
    {
        $siswa = Siswa::where('nisn', $id);
        $siswa->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }

    public function profil()
    {
        $id = Crypt::encrypt(Auth::user()->id);
        return $this->edit($id);
    }

    public function export()
    {
        if (!in_array('Eksport', $this->fiturMenu['Data Siswa'])) {
            return redirect()->back();
        }

        $data['siswa'] = Siswa::all();
        return Excel::download(new SiswaExport($data['siswa']), 'Data Siswa.xlsx');
    }

    public function import(Request $request)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx'
        ]);

        $import = new SiswaImport;
        Excel::import($import, $request->file('file'));

        return back()->with('success', "Berhasil mengimpor {$import->successCount} data.");
    }

    public function jadwal()
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();
        $rombel = Rombel::where('nisn', Auth::user()->siswa->nisn)->where('idtahunajaran', $tahunajaran->id)->first();
        $jadwalMengajar = JadwalMengajar::with('jampel')->where('idkelas', $rombel->idkelas)->whereHas('sistemblok', function ($query) use ($tahunajaran) {
            $query->where('semester', $tahunajaran->semester);
        })->get()->sortBy(function ($item) {
            return $item->jampel->jam ?? 0;
        })->values();
        $jadwal = [];
        $jadwalMengajar = $jadwalMengajar->map(function ($item) {
            $jam_akhir = $item->jampel->jam + $item->jumlah_jam - 1;

            $token = TokenMengajar::where('idjadwalmengajar', $item->id)
                ->where('expired_at', '>=', now())
                ->where('status', 'aktif')
                ->where('ajuan', '0')
                ->first();

            return (object)[
                'id' => $item->id,
                'hari' => $item->jampel->hari ?? '-',
                'jampel' => $item->jampel->jam ?? '-',
                'mulai' => $item->jampel->mulai ?? '-',
                'selesai' => JamPelajaran::where('jam', $jam_akhir)->first()->akhir ?? '-',
                'kode_matpel' => $item->kode_matpel,
                'nama_matpel' => $item->matpel->matpel ?? '-',
                'nip' => $item->nip,
                'nama_guru' => $item->staf->nama ?? '-',
                'token' => $token->token ?? '-'
            ];
        });
        foreach ($jadwalMengajar as $key => $value) {
            # code...
            $jadwal[$value->hari][] = $value;
        }
        $data['hari'] = ['Senin', 'Selasa', 'Rabu', 'Kamis', 'Jumat', 'Sabtu'];
        $data['jadwal'] = $jadwal;
        // dd($rombel);
        return view('pages.siswa.jadwal', $data);
    }
}
