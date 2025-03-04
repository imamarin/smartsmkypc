<?php

use App\Http\Controllers\AjuanPresensiController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\JadwalMengajarController;
use App\Http\Controllers\JamPelajaranController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasukMengajarController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MatpelPengampuController;
use App\Http\Controllers\NilaiSiswaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiHarianController;
use App\Http\Controllers\Raport\DetailNilaiRaportController;
use App\Http\Controllers\Raport\IdentitasController;
use App\Http\Controllers\Raport\MatpelKelasController;
use App\Http\Controllers\Raport\NilaiRaportController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SistemBlokController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\WalikelasController;
use App\Http\Middleware\CekStatusLogin;
use Illuminate\Support\Facades\Route;
use Illuminate\Support\Facades\Storage;

Route::get('/', function () {
    return view('pages.auth.login');
});
//login
Route::post('/login', [LoginController::class, 'login'])->name('login');
Route::get('/logout', [LoginController::class, 'logout'])->name('logout');
Route::middleware('cek-status-login')->group(function () {
    Route::prefix('/pages')->group(function () {
        //dashboard
        Route::get('/dashboard', [DashboardController::class, 'index'])->name('dashboard');
        //tahun-ajaran
        Route::resource('/tahun-ajaran', TahunAjaranController::class);
        Route::post('/tahun-ajaran/{id}/updateStatus', [TahunAjaranController::class, 'updateStatus'])->name('tahun-ajaran.updateStatus');
        //data-kelas
        Route::resource('/data-kelas', KelasController::class);
        Route::get('/data-kelas/json-tahunajaran/{id}', [KelasController::class, 'getJsonByIdTahunAjaran'])->name('data-kelas.json-tahunajaran');
        Route::get('/data-kelas/export/data', [KelasController::class, 'export'])->name('data-kelas.export');
        //data-jurusan
        Route::resource('/data-jurusan', JurusanController::class);
        Route::get('/data-jurusan/export/data', [JurusanController::class, 'export'])->name('data-jurusan.export');
        //data-siswa
        Route::resource('/data-siswa', SiswaController::class);
        Route::post('/data-siswa/{id}/updateStatus', [SiswaController::class, 'updateStatus'])->name('data-siswa.updateStatus');
        Route::get('/data-siswa/export/data', [SiswaController::class, 'export'])->name('data-siswa.export');
        //data-staf
        Route::resource('/data-staf', StafController::class);
        Route::post('/data-staf/{id}/updateStatus', [StafController::class, 'updateStatus'])->name('data-staf.updateStatus');
        Route::get('/data-staf/export/data', [StafController::class, 'export'])->name('data-staf.export');
        //data-rombel
        Route::resource('/data-rombel', RombelController::class);
        Route::post('/data-rombel/siswarombel', [RombelController::class, 'SiswaRombel'])->name('data-rombel.siswaRombel');
        Route::post('/data-rombel/deletesiswa', [RombelController::class, 'deleteSiswa'])->name('data-rombel.deleteSiswa');
        Route::post('/data-rombel/pindahtingkat/{id}', [RombelController::class, 'pindahTingkat'])->name('data-rombel.pindahTingkat');
        Route::post('/data-rombel/updaterombel/{idkelas}/{idtahunajaran}', [RombelController::class, 'updateRombel'])->name('data-rombel.updateRombel');
        Route::get('/data-rombel/export/data', [RombelController::class, 'export'])->name('data-rombel.export');
        Route::get('/data-rombel/siswa/{id}', [RombelController::class, 'showStudents'])->name('data-rombel.showStudents');


        //data-mata-pelajaran
        Route::resource('/data-mata-pelajaran', MataPelajaranController::class);
        //data-matpel-pengampu
        Route::resource('/matpel-pengampu', MatpelPengampuController::class);
        Route::get('/matpel-pengampu/export/data', [MatpelPengampuController::class, 'export'])->name('matpel-pengampu.export');
        //data-walikelas
        Route::post('/data-walikelas/tahunajaran', [WalikelasController::class, 'index'])->name('data-walikelas.tahunajaran');
        Route::get('/data-walikelas/tahunajaran', [WalikelasController::class, 'index'])->name('data-walikelas.tahunajaran');
        Route::resource('/data-walikelas', WalikelasController::class);
        //data-jam-pelajaran
        Route::get('/jam-pelajaran/{id}', [JamPelajaranController::class, 'getJam'])->name('jam-pelajaran.getjam');
        Route::resource('/data-jam-pelajaran', JamPelajaranController::class);
        Route::post('/data-jam-pelajaran/delete', [JamPelajaranController::class, 'delete'])->name('data-jam-pelajaran.delete');
        //role
        Route::resource('/role', RoleController::class);
        //sistem-blok
        Route::resource('/sistem-blok', SistemBlokController::class);
        Route::post('/sistem-blok/{id}/updateStatus', [SistemBlokController::class, 'updateStatus'])->name('sistemblok.updateStatus');
        Route::get('/jadwal-sistem-blok', [SistemBlokController::class, 'jadwal'])->name('jadwal-sistem-blok');
        Route::post('/jadwal-sistem-blok', [SistemBlokController::class, 'simpanJadwal'])->name('jadwal-sistem-blok.store');
        Route::get('/jadwal-sistem-blok/{id}', [SistemBlokController::class, 'hapusJadwal'])->name('jadwal-sistem-blok.destroy');
        //jadwal-Mengajar
        Route::get('/data-jadwal-mengajar-guru', [JadwalMengajarController::class, 'dataJadwalMengajarGuru'])->name('data-jadwal-mengajar-guru');
        Route::get('/data-jadwal-mengajar-guru/{id}', [JadwalMengajarController::class, 'show'])->name('data-jadwal-mengajar-guru.show');
        Route::get('/kunci/{id}', [JadwalMengajarController::class, 'kunci'])->name('kunci');
        Route::resource('/jadwal-mengajar', JadwalMengajarController::class);
        //Masuk-Mengajar
        Route::get('/masuk-mengajar', [MasukMengajarController::class, 'index'])->name('masuk-mengajar.index');
        Route::get('/masuk-mengajar/{id}', [MasukMengajarController::class, 'show'])->name('masuk-mengajar.show');
        Route::post('/masuk-mengajar/catatan/{id}', [MasukMengajarController::class, 'updateCatatan'])->name('masuk-mengajar.updateCatatan');
        //presensi
        Route::get('/rekap-presensi-siswa', [PresensiController::class, 'rekapSiswa'])->name('rekap-presensi-siswa');
        Route::get('/rekap-presensi-mengajar', [PresensiController::class, 'rekapGuru'])->name('rekap-presensi-mengajar');
        Route::get('/rekap-presensi-mengajar/{id}/presensi/{tgl}', [MasukMengajarController::class, 'show'])->name('ajuan-kehadiran-mengajar.presensi');
        Route::get('/rekap-presensi-siswa-detail/{id}', [PresensiController::class, 'rekapSiswaDetail'])->name('rekap-presensi-siswa-detail');
        Route::get('/history-presensi/{id}', [PresensiController::class, 'historyPresensi'])->name('history-presensi');
        Route::get('/rekap-presensi-siswa/{id}/tanggal/{tgl}', [MasukMengajarController::class, 'show'])->name('show-presensi.tanggal');
        Route::resource('/presensi', PresensiController::class);
        //Ajuan Kehadiran Mengajar
        Route::get('/pengajuan-kehadiran-mengajar', [AjuanPresensiController::class, 'index'])->name('ajuan-kehadiran-mengajar.index');
        Route::post('/pengajuan-kehadiran-mengajar', [AjuanPresensiController::class, 'store'])->name('ajuan-kehadiran-mengajar.store');
        Route::post('/pengajuan-kehadiran-mengajar/{id}', [AjuanPresensiController::class, 'update'])->name('ajuan-kehadiran-mengajar.update');
        Route::delete('/pengajuan-kehadiran-mengajar/{id}', [AjuanPresensiController::class, 'destroy'])->name('ajuan-kehadiran-mengajar.destroy');
        //data rekap presensi
        Route::get('/data-rekap-presensi-siswa', [PresensiController::class, 'rekapPresensiSiswa'])->name('data-rekap-presensi-siswa');
        Route::post('/data-rekap-presensi-siswa/kbm', [PresensiController::class, 'rekapPresensiSiswa'])->name('data-rekap-presensi-siswa.kbm');
        Route::post('/data-rekap-presensi-siswa/harian', [PresensiController::class, 'rekapPresensiSiswa'])->name('data-rekap-presensi-siswa.harian');
        Route::get('/data-rekap-presensi-guru', [PresensiController::class, 'rekapPresensiGuru']);
        Route::get('/data-rekap-presensi-guru/{id}', [PresensiController::class, 'rekapGuru'])->name('data-rekap-presensi-guru-detail');
        Route::post('/data-rekap-presensi-guru', [PresensiController::class, 'rekapPresensiGuru'])->name('data-rekap-presensi-guru');
        //walikelas
        Route::get('/walikelas/siswa', [WalikelasController::class, 'siswa'])->name('walikelas');
        Route::post('/walikelas/siswa', [WalikelasController::class, 'siswa'])->name('walikelas.tahunajaran');
        Route::get('/walikelas/siswa/{id}', [SiswaController::class, 'edit'])->name('walikelas.siswa.edit');
        Route::post('/walikelas/petugas-presensi/{id}', [WalikelasController::class, 'petugasPresensi'])->name('walikelas.petugaspresensi');
        Route::get('/walikelas/presensi-harian-siswa', [PresensiHarianController::class, 'siswa'])->name('presensi-harian-siswa');
        Route::post('/walikelas/presensi-harian-siswa', [PresensiHarianController::class, 'siswa'])->name('presensi-harian-siswa');
        Route::get('/walikelas/rekap-presensi-harian-siswa', [PresensiHarianController::class, 'rekapSiswa'])->name('rekap-presensi-harian-siswa');
        Route::post('/walikelas/rekap-presensi-harian-siswa', [PresensiHarianController::class, 'rekapSiswa'])->name('rekap-presensi-harian-siswa');
        Route::get('/walikelas/presensi-harian-siswa/{id}', [PresensiHarianController::class, 'create'])->name('presensi-harian-siswa-create');
        Route::post('/walikelas/presensi-harian-siswa/{id}', [PresensiHarianController::class, 'store'])->name('presensi-harian-siswa-store');
        Route::get('/walikelas/rekap-presensi-siswa', [PresensiController::class, 'rekapPresensiSiswa'])->name('walikelas.rekap-presensi-siswa');
        Route::post('/walikelas/rekap-presensi-siswa/kbm', [PresensiController::class, 'rekapPresensiSiswa'])->name('walikelas.rekap-presensi-siswa.kbm');
        Route::post('/walikelas/rekap-presensi-siswa/harian', [PresensiController::class, 'rekapPresensiSiswa'])->name('walikelas.rekap-presensi-siswa.harian');
        //grafik
        Route::get('/walikelas/grafik-presensi-siswa', [DiagramController::class, 'siswa'])->name('walikelas.grafik-presensi-siswa');
        //pengolahan nilai siswa
        Route::get('/pengolahan-nilai-siswa', [NilaiSiswaController::class, 'index'])->name('nilai-siswa');
        Route::post('/pengolahan-nilai-siswa', [NilaiSiswaController::class, 'store'])->name('nilai-siswa-store');
        Route::post('/pengolahan-nilai-siswa/{id}', [NilaiSiswaController::class, 'update'])->name('nilai-siswa-update');
        Route::delete('/pengolahan-nilai-siswa/{id}', [NilaiSiswaController::class, 'destroy'])->name('nilai-siswa-destroy');
        Route::get('/pengolahan-nilai-siswa/{kategori}/{id}', [NilaiSiswaController::class, 'inputNilai'])->name('nilai-siswa.input');
        Route::post('/pengolahan-nilai-siswa/{kategori}/{id}', [NilaiSiswaController::class, 'simpanNilai'])->name('nilai-siswa.simpan');
        Route::get('/rekap-nilai-siswa', [NilaiSiswaController::class, 'rekapNilaiSiswa'])->name('nilai-siswa.rekap');
        Route::get('/rekap-nilai-siswa/{id}', [NilaiSiswaController::class, 'showRekapNilaiSiswa'])->name('nilai-siswa.rekap.show');
        Route::post('/rekap-nilai-siswa/{id}', [NilaiSiswaController::class, 'storePersentaseNilai'])->name('nilai-siswa.persentase.store');
        //pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/menu', [PengaturanController::class, 'menuForm'])->name('pengaturan.menuForm');
        Route::post('/pengaturan/menu', [PengaturanController::class, 'menuFormStore'])->name('pengaturan.menuForm.store');

        //pengaturan
        Route::resource('/kalender-akademik', KalenderAkademikController::class);

        Route::prefix('/raport')->group(function () {
            Route::resource('/raport-identitas', IdentitasController::class);
            Route::post('/raport-aktivasi/{id}', [IdentitasController::class, 'aktivasi'])->name('raport.aktivasi');
            Route::get('/nilai-raport/detail/{id}', [DetailNilaiRaportController::class, 'input'])->name('detail-nilai-raport.input');
            Route::post('/nilai-raport/detail/{id}', [DetailNilaiRaportController::class, 'store'])->name('detail-nilai-raport.store');
            Route::resource('/nilai-raport', NilaiRaportController::class);
            Route::resource('/matpel-kelas', MatpelKelasController::class);
        });
        //Raport


        //download
        Route::get('/download-bukti-mengajar/{filename}', function ($filename) {
            $filePath = public_path("storage/bukti_ajuan_mengajar/{$filename}");

            if (!file_exists($filePath)) {
                abort(404, 'File not found.');
            }

            return response()->download($filePath);
        })->name('download-bukti-mengajar');
    });
});
