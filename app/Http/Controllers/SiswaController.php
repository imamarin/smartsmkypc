<?php

namespace App\Http\Controllers;

use App\Exports\SiswaExport;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $title = 'Hapus Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['siswa'] = Siswa::select('nisn', 'nama', 'jenis_kelamin', 'tempat_lahir', 'tanggal_lahir', 'alamat_siswa', 'no_hp_siswa', 'status', 'iduser')
            ->with(['user:id'])->where('status', 1)->get();

        return view('pages.siswa.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
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
        ]);

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
        $data['siswa'] = Siswa::with(['tahunajaran', 'user'])->where('iduser', $id)->first();
        $data['tahun_ajaran'] = TahunAjaran::all();
        $data['role'] = Role::all();
        $data['roleUser'] = UserRole::where('iduser', $id)->get()->pluck('idrole')->toArray();
        return view('pages.siswa.edit', $data);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
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
            $editSiswa = $request->validate([
                'nisn' => $siswa->nisn != $request->nisn ? 'required|unique:siswa,nisn' : 'required',
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
            ]);
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

        return redirect()->back()->with('danger', 'Data Gagal Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $userRole = UserRole::where('iduser', $id);
        $siswa = Siswa::where('iduser', $id);
        $user = User::find($id);
        $userRole->delete();
        $siswa->delete();
        $user->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $siswa = Siswa::where('nisn', $id);
        $siswa->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }

    public function export()
    {
        $data['siswa'] = Siswa::all();
        return Excel::download(new SiswaExport($data['siswa']), 'Data Siswa.xlsx');
    }
}
