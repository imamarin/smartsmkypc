<?php

namespace App\Http\Controllers\Keuangan;

use App\Http\Controllers\Controller;
use App\Imports\HonorDetailImport;
use App\Models\Honor;
use App\Models\HonorDetail;
use App\Models\Staf;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Crypt;
use Maatwebsite\Excel\Facades\Excel;
use Illuminate\Support\Facades\Auth;


class HonorDetailController extends Controller
{
    //
    public function rincian($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $data['honor'] = Honor::findOrFail($id);
        $data['detail'] = HonorDetail::where('idhonor', $id)->where('nip', Auth::user()->staf->nip)->firstOrFail();
        return view('pages.keuangan.honor.rincian', $data);
    }

    public function kelola($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $title = 'Data Honor!';
        $text = "Yakin ingin menghapus honor staf ini?";
        confirmDelete($title, $text);

        $data['id'] = $id;
        $data['honor'] = HonorDetail::where('idhonor', $id)->get();
        return view('pages.keuangan.honor.kelola', $data);
    }

    public function create($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }
        $data['id'] = $id;
        $data['staf'] = Staf::where('status', '1')->get();
        return view('pages.keuangan.honor.create', $data);
    }

    public function store(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $request->validate([
            'nip' => 'required',
            'jml_jam' => 'required|integer',
            'bonus_hdr' => 'required|integer',
            'yayasan' => 'required|integer',
            'tun_jab_bak' => 'required|integer',
            'tunjab' => 'required|integer',
            'honor' => 'required|integer',
            'sub_non_ser' => 'required|integer',
            'jml' => 'required|integer',
            'tabungan' => 'required|integer',
            'arisan' => 'required|integer',
            'qurban' => 'required|integer',
            'kas_1' => 'required|integer',
            'kas_2' => 'required|integer',
            'lainnya' => 'required|integer',
            'jum_tal' => 'required|integer'
        ]);

        HonorDetail::create([
            'nip' => $request->nip,
            'idhonor' => $id,
            'jml_jam' => $request->jml_jam,
            'bonus_hdr' => $request->bonus_hdr,
            'yayasan' => $request->yayasan,
            'tun_jab_bak' => $request->tun_jab_bak,
            'tunjab' => $request->tunjab,
            'honor' => $request->honor,
            'sub_non_ser' => $request->sub_non_ser,
            'jml' => $request->jml,
            'tabungan' => $request->tabungan,
            'arisan' => $request->arisan,
            'qurban' => $request->qurban,
            'kas_1' => $request->kas_1,
            'kas_2' => $request->kas_2,
            'lainnya' => $request->lainnya,
            'jum_tal' => $request->jum_tal
        ]);

        return redirect()->route('honorium-pegawai.kelola', Crypt::encrypt($id))->with('success', "Data berhasil disimpan!");
    }

    public function edit($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $data['honorDetail'] = HonorDetail::findOrFail($id);
        $data['staf'] = Staf::where('status', '1')->get();
        return view('pages.keuangan.honor.edit', $data);
    }

    public function update(Request $request, $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $request->validate([
            'nip' => 'required',
            'jml_jam' => 'required|integer',
            'bonus_hdr' => 'required|integer',
            'yayasan' => 'required|integer',
            'tun_jab_bak' => 'required|integer',
            'tunjab' => 'required|integer',
            'honor' => 'required|integer',
            'sub_non_ser' => 'required|integer',
            'jml' => 'required|integer',
            'tabungan' => 'required|integer',
            'arisan' => 'required|integer',
            'qurban' => 'required|integer',
            'kas_1' => 'required|integer',
            'kas_2' => 'required|integer',
            'lainnya' => 'required|integer',
            'jum_tal' => 'required|integer'
        ]);

        $honorDetail = HonorDetail::findOrFail($id);
        $honorDetail->update($request->all());

        return redirect()->route('honorium-pegawai.kelola', Crypt::encrypt($honorDetail->idhonor))->with('success', "Data berhasil diperbarui!");
    }

    public function destroy($id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('error', $e->getMessage());
        }

        $honorDetail = HonorDetail::findOrFail($id);
        $honorDetail->delete();

        return redirect()->back()->with('success', "Data berhasil dihapus!");
    }

    public function import(Request $request, $id)
    {
        $request->validate([
            'file' => 'required|mimes:xlsx',
        ]);

        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        if ($request->hasFile('file')) {
            $file = $request->file('file');
            $import = new HonorDetailImport($id);
            Excel::import($import, $file);

            return back()->with('success', "Berhasil mengimpor {$import->successCount} data.");
        }
        return redirect()->back()->with('error', 'File tidak ditemukan atau format tidak sesuai.');
    }
}
