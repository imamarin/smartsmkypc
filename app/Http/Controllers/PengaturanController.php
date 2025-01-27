<?php

namespace App\Http\Controllers;

use App\Models\Fitur;
use App\Models\Kategori;
use App\Models\Menu;
use Illuminate\Http\Request;
use Symfony\Contracts\Service\Attribute\Required;

class PengaturanController extends Controller
{
    public function index()
    {
        $data['kategori'] = Kategori::with(['menu.fitur'])->get();
        return view('pages.pengaturan.index', $data);
    }

    public function menuForm()
    {
        return view('pages.pengaturan.menuForm');
    }

    public function menuFormStore(Request $request)
    {
        // Validasi data
        $validated = $request->validate([
            'kategori' => 'required|string',
            'icon' => 'nullable|string',
            'menu' => 'required|array',
            'menu.*' => 'required|string',
            'url' => 'required|array',
            'url.*' => 'required',
            'fitur' => 'nullable|array',
            'fitur.*' => 'nullable|string',
        ]);

        // Menyimpan kategori
        // Dapatkan posisi kategori terakhir, jika ada
        $lastKategori = Kategori::latest()->first();
        $kategoriPosisi = $lastKategori ? $lastKategori->posisi + 1 : 1;

        // Simpan kategori dengan posisi terakhir
        $kategoriItem = Kategori::create([
            'kategori' => $validated['kategori'],
            'icon' => $validated['icon'] ?? null,
            'posisi' => $kategoriPosisi,  // Menyimpan posisi kategori
        ]);

        // Menyimpan menu terkait dengan kategori
        foreach ($validated['menu'] as $index => $menu) {
            // Dapatkan posisi menu terakhir berdasarkan kategori
            $lastMenu = Menu::where('idkategori', $kategoriItem->id)->latest()->first();
            $menuPosisi = $lastMenu ? $lastMenu->posisi + 1 : 1;

            // Simpan menu dengan posisi terakhir
            $menuItem = Menu::create([
                'menu' => $menu,
                'url' => $validated['url'][$index],
                'idkategori' => $kategoriItem->id,
                'posisi' => $menuPosisi,  // Menyimpan posisi menu
            ]);

            // Menyimpan fitur terkait dengan menu
            if (isset($validated['fitur'])) {
                foreach ($validated['fitur'] as $fiturItem) {
                    Fitur::create([
                        'fitur' => $fiturItem,
                        'idmenu' => $menuItem->id,
                    ]);
                }
            }
        }
        return redirect('/pages/pengaturan')->with('success', 'Data Berhasil Ditambahkan');
    }
}
