<?php

namespace App\Http\Controllers;

use App\Models\DetailPresensi;
use App\Models\JadwalMengajar;
use App\Models\Kelas;
use App\Models\Presensi;
use App\Models\PresensiHarianSiswa;
use App\Models\Rombel;
use App\Models\TahunAjaran;
use App\Models\Walikelas;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Auth;

class DiagramController extends Controller
{
    //
    public function siswa(Request $request)
    {
        //
        $nisn = decryptSmart($request->id);
        $kelas = Walikelas::whereHas('tahunajaran', function ($query) {
            $query->where('status', 1);
        })->where('nip', Auth::user()->staf->nip)->pluck('idkelas');

        $presensi_kelas = $this->presensi_kelas();

        $data['siswa'] = Rombel::with('siswa')->whereIn('idkelas', $kelas)->get();
        $presensi_matpel_siswa = $this->presensi_matpel_siswa($nisn);

        $presensi_harian_siswa = $this->presensi_harian_siswa($nisn);
        $data['nisn'] = $nisn ?? '';

        $data = array_merge($data, $presensi_harian_siswa, $presensi_matpel_siswa, $presensi_kelas);

        // dd($data);
        return view('pages.diagram.siswa', $data);
    }

    public function presensi_kelas()
    {

        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $kelas = Walikelas::where([
            'nip' => Auth::user()->staf->nip
        ])->where('idtahunajaran', $tahunajaran->id)->get()->pluck('idkelas');

        $presensi = PresensiHarianSiswa::select('*')->selectRaw("
            COUNT(*) as total_pertemuan,
            SUM(keterangan = 'h') as total_hadir,
            SUM(keterangan = 's') as total_sakit,
            SUM(keterangan = 'i') as total_izin,
            SUM(keterangan = 'a') as total_alfa
        ")->whereHas('siswa.rombel', function ($query) use ($kelas) {
            $query->whereIn('idkelas', $kelas);
        })->where([
            'semester' => $tahunajaran->semester,
            'idtahunajaran' => $tahunajaran->id
        ])->get();


        $data['presensi_kelas'] = $presensi->map(function ($item) {
            $kelas = $item->siswa->rombel[0]->kelas->kelas;
            $hadir = floor($item->total_hadir / $item->total_pertemuan * 100);
            $sakit = floor($item->total_sakit / $item->total_pertemuan * 100);
            $izin = floor($item->total_izin / $item->total_pertemuan * 100);
            $alfa = floor($item->total_alfa / $item->total_pertemuan * 100);
            return [
                'kelas' => $kelas,
                'total_pertemuan' => $item->total_pertemuan,
                'keterangan' => [$hadir, $sakit, $izin, $alfa]
            ];
        });

        return $data;
    }

    public function presensi_harian_siswa(String $id)
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $labels_presensi_harian  = [];
        $nilai_presensi_harian  = [];

        if ($id) {
            $nisn = $id;
            $presensiHarian = PresensiHarianSiswa::where([
                'nisn' => $nisn
            ])->where([
                'idtahunajaran' => $tahunajaran->id
            ])->get();

            $bulan = [];
            $jml_hadir_harian = [];
            $jml_pertemuan_harian = [];
            foreach ($presensiHarian as $value) {
                # code...
                if (!in_array(date('F', strtotime($value->created_at)), $bulan)) {
                    array_push($bulan, date('F', strtotime($value->created_at)));
                }

                if ($value->keterangan == 'h') {
                    if (isset($jml_hadir_harian[date('F', strtotime($value->created_at))])) {
                        $jml_hadir_harian[date('F', strtotime($value->created_at))]++;
                    } else {
                        $jml_hadir_harian[date('F', strtotime($value->created_at))] = 1;
                    }
                }

                if (isset($jml_pertemuan_harian[date('F', strtotime($value->created_at))])) {
                    $jml_pertemuan_harian[date('F', strtotime($value->created_at))]++;
                } else {
                    $jml_pertemuan_harian[date('F', strtotime($value->created_at))] = 1;
                }
            }

            foreach ($bulan as $value) {
                # code...
                array_push($labels_presensi_harian, $value);
                array_push($nilai_presensi_harian, $jml_hadir_harian[$value] / $jml_pertemuan_harian[$value] * 100);
            }
        }

        $data['labels_presensi_harian'] = $labels_presensi_harian;
        $data['nilai_presensi_harian'] = $nilai_presensi_harian;

        return $data;
    }

    public function presensi_matpel_siswa(String $id)
    {
        $tahunajaran = TahunAjaran::where('status', 1)->first();

        $labels_presensi_matpel = [];
        $nilai_presensi_matpel = [];

        if ($id) {
            $presensiMatpel = DetailPresensi::join('presensis', 'presensis.id', '=', 'detail_presensis.idpresensi')->whereHas('presensi', function ($query) use ($tahunajaran) {
                $query->where([
                    'idtahunajaran' => $tahunajaran->id,
                ]);
            })->where('nisn', $id)->get();

            $jml_presensi_matpel = [];
            $total_presensi_matpel = [];
            foreach ($presensiMatpel as $key => $value) {
                # code..
                if ($value->keterangan == 'h') {
                    if (isset($jml_presensi_matpel[$value->semester][$value->kode_matpel])) {
                        $jml_presensi_matpel[$value->semester][$value->kode_matpel]++;
                    } else {
                        $jml_presensi_matpel[$value->semester][$value->kode_matpel] = 1;
                    }
                }

                if (isset($total_presensi_matpel[$value->semester][$value->kode_matpel])) {
                    $total_presensi_matpel[$value->semester][$value->kode_matpel]++;
                } else {
                    $total_presensi_matpel[$value->semester][$value->kode_matpel] = 1;
                }
            }

            $jadwal = JadwalMengajar::select('kode_matpel')
                ->join('sistem_bloks', 'sistem_bloks.id', '=', 'jadwal_mengajars.idsistemblok')
                ->whereHas('sistemblok.tahunajaran', function ($query) {
                    $query->where('status', 1);
                })->whereHas('kelas.rombel', function ($query) use ($id) {
                    $query->where('nisn', $id);
                })->groupBy('kode_matpel')->get();

            foreach ($jadwal as $key => $value) {
                # code...
                foreach (['ganjil', 'genap'] as $key => $semester) {
                    # code...
                    if (isset($jml_presensi_matpel[$semester][$value->kode_matpel])) {
                        $nilai = floor($jml_presensi_matpel[$semester][$value->kode_matpel] / $total_presensi_matpel[$semester][$value->kode_matpel] * 100);
                        if (isset($labels_presensi_matpel[$semester])) {
                            array_push($labels_presensi_matpel[$semester], $value->kode_matpel);
                            array_push($nilai_presensi_matpel[$semester], $nilai);
                        } else {
                            $labels_presensi_matpel[$semester][0] = $value->kode_matpel;
                            $nilai_presensi_matpel[$semester][0]  = $nilai;
                        }
                    } else {
                        $labels_presensi_matpel[$semester][0] = $value->kode_matpel;
                        $nilai_presensi_matpel[$semester][0]  = 0;
                    }
                }
            }
        }
        // dd([$jml_presensi_matpel, $total_presensi_matpel]);
        $data['labels_presensi_matpel'] = $labels_presensi_matpel;
        $data['nilai_presensi_matpel'] = $nilai_presensi_matpel;
        // dd($data);
        return $data;
    }
}
