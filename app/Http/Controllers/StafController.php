<?php

namespace App\Http\Controllers;

use App\Exports\GuruExport;
use App\Models\Staf;
use App\Models\Role;
use App\Models\User;
use App\Models\UserRole;
use Illuminate\Http\Request;
use Maatwebsite\Excel\Facades\Excel;

class StafController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $data['staf'] = Staf::where('status', 1)->orderBy('nip', 'desc')->get();
        $title = 'Hapus Staf!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);
        return view('pages.staf.index', $data);
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $data['role'] = Role::all();
        return view('pages.staf.create', $data);
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

        $requestStaf = $request->validate([
            'nip' => 'required|unique:stafs,nip',
            'nama' => 'required',
            'nip' => 'required|unique:stafs,nip',
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

        $dataStaf = $requestStaf;
        $dataStaf['iduser'] = $user->id;
        Staf::create($dataStaf);

        foreach ($request->role as $key => $value) {
            UserRole::create([
                'iduser' => $user->id,
                'idrole' => $value
            ]);
        }
        return redirect()->route('data-staf.index')->with('success', 'Data Berhasil Disimpan');
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
        $data['staf'] = Staf::where('iduser', $id)->first();
        $data['role'] = Role::all();
        $data['roleUser'] = UserRole::where('iduser', $id)->get()->pluck('idrole')->toArray();
        return view('pages.staf.edit', $data);
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

            $staf = Staf::where('iduser', $id)->first();
            $editStaf = $request->validate([
                'nip' => $staf->nip != $request->nip ? 'required|unique:stafs,nip' : 'required',
                'nama' => 'required',
                'nip' => $staf->nip != $request->nip ? 'required|unique:stafs,nip' : 'required',
                'alamat' => 'required',
                'tempat_lahir' => 'required',
                'tanggal_lahir' => 'required',
                'jenis_kelamin' => 'required',
                'no_hp' => 'required',
                'status' => 'required',
                'nuptk' => 'required',
            ]);

            $staf->update($editStaf);

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
            return redirect()->route('data-staf.index')->with('success', 'Data Berhasil Diubah');
        }

        return redirect()->back()->with('danger', 'Data Gagal Diubah');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        $role = UserRole::where('iduser', $id);
        $staf = Staf::where('iduser', $id);
        $user = User::find($id);
        $role->delete();
        $staf->delete();
        $user->delete();
        return redirect()->back()->with('success', 'Data Berhasil Dihapus');
    }

    public function updateStatus(Request $request, $id)
    {
        $staf = Staf::where('nip', $id);
        $staf->update([
            'status' => $request->status,
        ]);
        return redirect()->back()->with('success', 'Status Berhasil Diubah');
    }

    public function export()
    {
        $data['staf'] = Staf::all();
        return Excel::download(new GuruExport($data['staf']), 'Data Staf.xlsx');
    }
}
