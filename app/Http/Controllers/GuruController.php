<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Models\Guru;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class GuruController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['guru'] = Guru::all();
        $title = 'Hapus Guru!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.guru.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['role'] = Role::all();
        return view('pages.guru.create', $data);
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

        $requestGuru = $request->validate([
            'kode_guru' => 'required|unique:gurus,kode_guru',
            'nama' => 'required',
            'nip' => 'required|unique:gurus,nip',
            'alamat' => 'required',
            'tempat_lahir' => 'required',
            'tanggal_lahir' => 'required',
            'jenis_kelamin' => 'required',
            'no_hp' => 'required',
            'status' => 'required',
            'nuptk' => 'required',
        ]);

        $dataRole = $request->validate([
            'role' => 'required'
        ]);


        $user = User::create([
            'username' => $request->username,
            'password' => bcrypt($request->password)
        ]);

        $dataGuru = $requestGuru;
        $dataGuru['iduser'] = $user->id;
        Guru::create($dataGuru);

        foreach ($request->role as $key => $value) {
            UserRole::create([
                'iduser' => $user->id,
                'idrole' => $value
            ]);
        }
        return redirect()->route('data-guru.index')->with('success', 'Data Berhasil Disimpan');
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
        $data['guru'] = Guru::where('iduser', $id)->first();
        $data['role'] = Role::all();
        $data['roleUser'] = UserRole::where('iduser', $id)->get()->pluck('idrole')->toArray();
        return view('pages.guru.edit', $data);
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

            $guru = Guru::where('iduser', $id)->first();
            $editGuru = $request->validate([
                'kode_guru' => $guru->kode_guru != $request->kode_guru ? 'required|unique:gurus,kode_guru' : 'required',
                'nama' => 'required',
                'nip' => $guru->nip != $request->nip ? 'required|unique:gurus,nip' : 'required',
                'alamat' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'no_hp' => 'required',
                'status' => 'required',
                'nuptk' => 'required',
            ]);

            $guru->update($editGuru);

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
            return redirect()->route('data-guru.index')->with('success', 'Data Berhasil Diubah');
        }

        return redirect()->back()->with('danger', 'Data Gagal Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = UserRole::where('iduser', $id);
        $guru = Guru::where('iduser', $id);
        $user = User::find($id);
        $role->delete();
        $guru->delete();
        $user->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $guru = Guru::where('kode_guru', $id);
        $guru->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }

    public function export()
    {
        $data['guru'] = Guru::all();
        return Excel::download(new GuruExport($data['guru']), 'Data Guru.xlsx');
    }
}
