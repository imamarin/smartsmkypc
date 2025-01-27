<?php

namespace App\Http\Controllers;

use App\Models\Role;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;

class SiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['siswa'] = Siswa::all();
        $title = 'Hapus Siswa!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
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
            'kelas' => 'required',
            'idtahunajaran' => 'required',
            'status' => 'required',
            'nama_ayah' => 'required',
            'nama_ibu' => 'required',
            'pekerjaan_ayah' => 'required',
            'pekerjaan_ibu' => 'required',
            'alamat_ortu' => 'required',
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
            Role::create([
                'iduser' => $user->id,
                'idrole' => $request->role
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

            $siswa = Siswa::where('iduser', $id)->first();
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
                'kelas' => 'required',
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
            $siswa->update($editSiswa);

            $role = Role::find($id);
            if ($role) {
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
        $siswa = Siswa::find($id);
        $siswa->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }
}
