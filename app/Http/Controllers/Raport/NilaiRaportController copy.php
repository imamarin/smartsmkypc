<?php

namespace App\Http\Controllers\Raport;

use App\Http\Controllers\Controller;
use App\Models\JadwalMengajar;
use App\Models\MatpelPengampu;
use App\Models\Raport\Format;
use App\Models\Raport\IdentitasRaport;
use App\Models\Raport\NilaiRaport;
use App\Models\TahunAjaran;
use Illuminate\Contracts\Encryption\DecryptException;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Crypt;
use Illuminate\Support\Facades\Session;

use function PHPUnit\Framework\isEmpty;

class NilaiRaportController extends Controller
{
    //
    protected $tahunajaran;
    protected $aktivasi;
    public function __construct()
    {
        $this->tahunajaran = TahunAjaran::where('status', 1)->first();
        $this->aktivasi = Session::get('aktivasi');
    }
    //
    public function index()
    {
        if (!$this->aktivasi) {
            return redirect()->route('raport-identitas.index')->with('warning', 'Belum melakukan aktivasi raport!');
        }

        $title = 'Data Nilai Raport!';
        $text = "Yakin ingin menghapus data ini?";
        confirmDelete($title, $text);

        $data['matpel'] = MatpelPengampu::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->get();
        $aktivasi = $this->aktivasi;
        $data['kelas'] = JadwalMengajar::whereHas('sistemblok', function ($query) use ($aktivasi) {
            $query->where([
                'idtahunajaran' => $aktivasi->idtahunajaran,
                'semester' => $aktivasi->semester
            ]);
        })->where('nip', Auth::user()->staf->nip)->groupBy('idkelas')->get();

        $data['nilairaport'] = NilaiRaport::where([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'nip' => Auth::user()->staf->nip
        ])->orderBy('id', 'desc')->get();

        return view('pages.eraports.nilairaport.index', $data);
    }

    public function store(Request $request)
    {
        $request->validate([
            'kode_matpel' => 'required',
            'idkelas' => 'required',
            'kkm' => 'numeric|min:0|max:100',
            'bobot_pengetahuan' => 'numeric|min:0|max:100',
            'bobot_keterampilan' => 'numeric|min:0|max:100'
        ]);

        NilaiRaport::updateOrCreate([
            'idtahunajaran' => $this->aktivasi->idtahunajaran,
            'semester' => $this->aktivasi->semester,
            'nip' =>  Auth::user()->staf->nip,
            'kode_matpel' => $request->kode_matpel,
            'idkelas' => $request->idkelas
        ], [
            'kkm' => $request->kkm,
            'bobot_pengetahuan' => $request->bobot_pengetahuan,
            'bobot_keterampilan' => $request->bobot_keterampilan
        ]);
        return redirect()->back()->with('success', 'Nilai raport berhasil disimpan');
    }

    public function update(Request $request, String $id)
    {
        try {
            $id = Crypt::decrypt($id);
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }

        $validate = $request->validate([
            'kode_matpel' => 'required',
            'idkelas' => 'required',
            'kkm' => 'numeric|min:0|max:100',
            'bobot_pengetahuan' => 'numeric|min:0|max:100',
            'bobot_keterampilan' => 'numeric|min:0|max:100'
        ]);

        $validate['idtahunajaran'] = $this->aktivasi->idtahunajaran;
        $validate['semester'] = $this->aktivasi->semester;
        $validate['nip'] =  Auth::user()->staf->nip;

        NilaiRaport::find($id)->update($validate);
        return redirect()->back()->with('success', 'Nilai raport berhasil diubah');
    }

    public function destroy(String $id)
    {
        try {
            $id = Crypt::decrypt($id);
            NilaiRaport::find($id)->delete();
            return redirect()->back()->with('success', 'Data nilai raport berhasil dihapus');
        } catch (DecryptException $e) {
            return redirect()->back()->with('warning', $e->getMessage());
        }
    }
}
