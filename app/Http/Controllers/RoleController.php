<?php

namespace App\Http\Controllers;

use App\Models\Fitur;
use App\Models\HakAkses;
use App\Models\Kategori;
use App\Models\Menu;
use App\Models\Role;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Routing\Controller;

class RoleController extends Controller
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
            $this->view = 'Pengaturan-Role & Hak Akses';

            if (!isset($this->fiturMenu[$this->view])) {
                return redirect()->back();
            }

            view()->share('view', $this->view);

            return $next($request);
        });
    }

    public function index()
    {
        $data['roles'] = Role::withCount('user_role')->get();
        $data['menus'] = Menu::with('kategori')->get();
        $data['fiturs'] = Fitur::all();
        return view('pages.role.index', $data);
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles'
        ]);

        Role::create($request->all());

        return redirect()->route('role.index')
            ->with('success', 'Role berhasil ditambahkan');
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(string $id)
    {
        $role = Role::findOrFail($id);
        return response()->json($role);
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, string $id)
    {
        $request->validate([
            'role' => 'required|string|max:255|unique:roles,role,' . $id
        ]);

        $role = Role::findOrFail($id);
        $role->update($request->all());

        return redirect()->route('role.index')
            ->with('success', 'Role berhasil diperbarui');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(string $id)
    {
        try {
            $role = Role::findOrFail($id);

            // Hapus hak akses terkait
            $role->hak_akses()->delete();

            // Hapus role
            $role->delete();

            DB::commit();

            return response()->json(['message' => 'Role berhasil dihapus']);
        } catch (\Exception $e) {
            DB::rollBack();
            return response()->json(['message' => 'Gagal menghapus role'], 500);
        }
    }

    /**
     * Get access rights for a role
     */
    public function getAccess(string $id)
    {
        $role = Role::findOrFail($id);
        $existingAccess = $role->hak_akses->pluck('idfitur')->toArray();

        $menus = Menu::with('fitur')->get();
        $accessList = [];

        foreach ($menus as $menu) {
            $fiturList = [];
            foreach ($menu->fitur as $fitur) { // Mengubah fiturs menjadi fitur
                $fiturList[] = [
                    'id' => $fitur->id,
                    'menu_id' => $menu->id,
                    'fitur' => $fitur->fitur,
                    'has_access' => in_array($fitur->id, $existingAccess)
                ];
            }

            $accessList[] = [
                'id' => $menu->id,
                'menu' => $menu->menu,
                'fitur' => $fiturList,
            ];
        }

        return response()->json($accessList);
    }

    /**
     * Update access rights for a role
     */
    public function updateAccess(Request $request, string $id)
    {
        try {
            DB::beginTransaction();

            $role = Role::findOrFail($id);

            // Hapus hak akses yang ada
            $role->hak_akses()->delete();

            // Tambah hak akses baru
            if ($request->has('access')) {
                foreach ($request->access as $fiturId) {
                    HakAkses::create([
                        'idrole' => $id,
                        'idfitur' => $fiturId
                    ]);
                }
            }

            DB::commit();

            return redirect()->route('role.index')
                ->with('success', 'Hak akses berhasil diperbarui');
        } catch (\Exception $e) {
            DB::rollBack();
            return redirect()->route('role.index')
                ->with('error', 'Gagal memperbarui hak akses');
        }
    }
}
