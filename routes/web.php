<?php

use App\Http\Controllers\AjuanPresensiController;
use App\Http\Controllers\CPController;
use App\Http\Controllers\DashboardController;
use App\Http\Controllers\DiagramController;
use App\Http\Controllers\StafController;
use App\Http\Controllers\JadwalMengajarController;
use App\Http\Controllers\JamPelajaranController;
use App\Http\Controllers\JurusanController;
use App\Http\Controllers\KalenderAkademikController;
use App\Http\Controllers\KasusSiswaController;
use App\Http\Controllers\KelasController;
use App\Http\Controllers\Keuangan\HonorController;
use App\Http\Controllers\Keuangan\HonorDetailController;
use App\Http\Controllers\Keuangan\KategoriKeuanganController;
use App\Http\Controllers\Keuangan\KeuanganLainController;
use App\Http\Controllers\Keuangan\NonSPPController;
use App\Http\Controllers\Keuangan\SPPController;
use App\Http\Controllers\Keuangan\TagihanKeuanganController;
use App\Http\Controllers\LoginController;
use App\Http\Controllers\MasukMengajarController;
use App\Http\Controllers\MataPelajaranController;
use App\Http\Controllers\MatpelPengampuController;
use App\Http\Controllers\NilaiSiswaController;
use App\Http\Controllers\PengaturanController;
use App\Http\Controllers\PresensiController;
use App\Http\Controllers\PresensiHarianController;
use App\Http\Controllers\Raport\AbsensiRaportController;
use App\Http\Controllers\Raport\CetakController;
use App\Http\Controllers\Raport\DetailNilaiRaportController;
use App\Http\Controllers\Raport\EkstrakurikulerController;
use App\Http\Controllers\Raport\FormatController;
use App\Http\Controllers\Raport\IdentitasController;
use App\Http\Controllers\Raport\KategoriSikapController;
use App\Http\Controllers\Raport\KenaikanKelasController;
use App\Http\Controllers\Raport\MatpelKelasController;
use App\Http\Controllers\Raport\NilaiEkstraController;
use App\Http\Controllers\Raport\NilaiPrakerinController;
use App\Http\Controllers\Raport\NilaiRaportController;
use App\Http\Controllers\Raport\NilaiSikapController;
use App\Http\Controllers\RoleController;
use App\Http\Controllers\RombelController;
use App\Http\Controllers\SistemBlokController;
use App\Http\Controllers\SiswaController;
use App\Http\Controllers\TahunAjaranController;
use App\Http\Controllers\TPController;
use App\Http\Controllers\WalikelasController;
use App\Http\Middleware\CekStatusLogin;
use App\Models\KasusSiswa;
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
        Route::get('/beranda', [DashboardController::class, 'index'])->name('dashboard');
        //tahun-ajaran
        Route::resource('/tahun-ajaran', TahunAjaranController::class);
        Route::post('/tahun-ajaran/{id}/updateStatus', [TahunAjaranController::class, 'updateStatus'])->name('tahun-ajaran.updateStatus');
        //data-kelas
        Route::get('/data-kelas/tahunajaran', [KelasController::class, 'tahunajaran'])->name('data-kelas.tahunajaran');
        Route::resource('/data-kelas', KelasController::class);
        Route::get('/data-kelas/json-tahunajaran/{id}', [KelasController::class, 'getJsonByIdTahunAjaran'])->name('data-kelas.json-tahunajaran');
        Route::get('/data-kelas/export/data', [KelasController::class, 'export'])->name('data-kelas.export');
        //data-jurusan
        Route::resource('/data-jurusan', JurusanController::class);
        Route::get('/data-jurusan/export/data', [JurusanController::class, 'export'])->name('data-jurusan.export');
        //data-siswa
        Route::get('/siswa/profil',  [SiswaController::class, 'profil'])->name('siswa.profil');
        Route::get('/siswa/jadwal-mata-pelajaran',  [SiswaController::class, 'jadwal'])->name('siswa.jadwal');
        Route::resource('/profil-siswa', SiswaController::class);
        Route::get('/data-siswa/tahunajaran', [SiswaController::class, 'tahunajaran'])->name('data-siswa.tahunajaran');
        Route::resource('/data-siswa', SiswaController::class);
        Route::post('/data-siswa/{id}/updateStatus', [SiswaController::class, 'updateStatus'])->name('data-siswa.updateStatus');
        Route::get('/data-siswa/export/data', [SiswaController::class, 'export'])->name('data-siswa.export');
        Route::post('/data-siswa/import/data', [SiswaController::class, 'import'])->name('data-siswa.import');
        Route::get('/template-import-siswa', function () {
            $file = public_path('storage/template_import/template_siswa.xlsx');
            return response()->download($file);
        })->name('data-siswa.template.import');

        //data-staf
        Route::resource('/profil-staf', StafController::class);
        Route::resource('/data-staf', StafController::class);
        Route::post('/data-staf/{id}/updateStatus', [StafController::class, 'updateStatus'])->name('data-staf.updateStatus');
        Route::get('/data-staf/export/data', [StafController::class, 'export'])->name('data-staf.export');
        Route::post('/data-staf/import/data', [StafController::class, 'import'])->name('data-staf.import');
        Route::get('/template-import-staf', function () {
            $file = public_path('storage/template_import/template_stafs.xlsx');
            return response()->download($file);
        })->name('data-staf.template.import');

        //data-rombel
        Route::get('/data-rombel/tahunajaran', [RombelController::class, 'tahunajaran'])->name('data-rombel.tahunajaran');
        Route::resource('/data-rombel', RombelController::class);
        Route::post('/data-rombel/siswarombel', [RombelController::class, 'SiswaRombel'])->name('data-rombel.siswaRombel');
        Route::post('/data-rombel/deletesiswa', [RombelController::class, 'deleteSiswa'])->name('data-rombel.deleteSiswa');
        Route::post('/data-rombel/pindahtingkat/{id}', [RombelController::class, 'pindahTingkat'])->name('data-rombel.pindahTingkat');
        Route::post('/data-rombel/updaterombel/{idkelas}/{idtahunajaran}', [RombelController::class, 'updateRombel'])->name('data-rombel.updateRombel');
        Route::get('/data-rombel/export/{id}', [RombelController::class, 'export'])->name('data-rombel.export');
        Route::get('/data-rombel/siswa/{id}', [RombelController::class, 'showStudents'])->name('data-rombel.showStudents');
        Route::post('/data-rombel/import/data', [RombelController::class, 'import'])->name('data-rombel.import');
        Route::get('/data-rombel/kelas/json-tahunajaran/{id}', [KelasController::class, 'getJsonByIdTahunAjaran'])->name('data-rombel.kelas.json-tahunajaran');
        Route::get('/template-import-rombel', function () {
            $file = public_path('storage/template_import/template_rombel.xlsx');
            return response()->download($file);
        })->name('data-rombel.template.import');

        //data-mata-pelajaran
        Route::get('/data-mata-pelajaran/export', [MataPelajaranController::class, 'export'])->name('data-matpel.export');
        Route::resource('/data-mata-pelajaran', MataPelajaranController::class);
        //data-matpel-pengampu
        Route::resource('/matpel-pengampu', MatpelPengampuController::class);
        Route::get('/matpel-pengampu/export/data', [MatpelPengampuController::class, 'export'])->name('matpel-pengampu.export');

        //data-walikelas
        Route::get('/data-rombel/export/{id}', [WalikelasController::class, 'export'])->name('data-walikelas.export');
        Route::post('/data-walikelas/tahunajaran', [WalikelasController::class, 'index'])->name('data-walikelas.tahunajaran');
        Route::get('/data-walikelas/tahunajaran', [WalikelasController::class, 'index'])->name('data-walikelas.tahunajaran');
        Route::resource('/data-walikelas', WalikelasController::class);

        //data-jam-pelajaran
        Route::get('/jam-pelajaran/{id}', [JamPelajaranController::class, 'getJam'])->name('jam-pelajaran.getjam');
        Route::resource('/data-jam-pelajaran', JamPelajaranController::class);
        Route::post('/data-jam-pelajaran/delete', [JamPelajaranController::class, 'delete'])->name('data-jam-pelajaran.delete');
        //role
        Route::resource('role', RoleController::class, ['except' => ['show']]);
        Route::get('/role/{id}/access', [RoleController::class, 'getAccess']);
        Route::put('/role/{id}/access', [RoleController::class, 'updateAccess']);
        //sistem-blok
        Route::resource('/sistem-blok', SistemBlokController::class);
        Route::post('/sistem-blok/{id}/updateStatus', [SistemBlokController::class, 'updateStatus'])->name('sistemblok.updateStatus');
        Route::get('/jadwal-sistem-blok', [SistemBlokController::class, 'jadwal'])->name('jadwal-sistem-blok');
        Route::post('/jadwal-sistem-blok', [SistemBlokController::class, 'simpanJadwal'])->name('jadwal-sistem-blok.store');
        Route::get('/jadwal-sistem-blok/{id}', [SistemBlokController::class, 'hapusJadwal'])->name('jadwal-sistem-blok.delete');
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
        Route::get('/siswa/info-kehadiran', [PresensiController::class, 'infokehadiransiswa'])->name('info-kehadiran-siswa');
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
        Route::get('/data-rekap-presensi-guru', [PresensiController::class, 'rekapPresensiGuru'])->name('data-rekap-presensi-guru');
        Route::get('/data-rekap-presensi-guru/{id}', [PresensiController::class, 'rekapGuru'])->name('data-rekap-presensi-guru-detail');
        Route::get('/data-rekap-presensi-guru/export/{id}', [PresensiController::class, 'exportRekapPresensiGuru'])->name('data-rekap-presensi-guru.export');

        Route::middleware('cek-walikelas')->group(function () {
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

            Route::get('/walikelas/tagihan-keuangan-siswa', [TagihanKeuanganController::class, 'index'])->name('walikelas.tagihan-keuangan-siswa');
            Route::get('/walikelas/tagihan-keuangan-siswa/kelas', [TagihanKeuanganController::class, 'show'])->name('walikelas.tagihan-keuangan-siswa.kelas');
            Route::get('/walikelas/tagihan-keuangan-siswa/print/{id}', [TagihanKeuanganController::class, 'print'])->name('walikelas.tagihan-keuangan-siswa.print');

            Route::get('/data-kelas/json-tahunajaran/{id}', [KelasController::class, 'getJsonByIdTahunAjaran'])->name('walikelas.kelas.json-tahunajaran');

            Route::get('/walikelas/laporan-kasus-siswa/detail/{id}', [KasusSiswaController::class, 'detail'])->name('laporan-kasus-siswa.detail');
            Route::get('/walikelas/laporan-kasus-siswa/rombel', [KasusSiswaController::class, 'rombel'])->name('laporan-kasus-siswa.rombel');
            Route::resource('walikelas/laporan-kasus-siswa', KasusSiswaController::class);
        });
        //pengolahan nilai siswa
        Route::get('/pengolahan-nilai-siswa', [NilaiSiswaController::class, 'index'])->name('nilai-siswa');
        Route::post('/pengolahan-nilai-siswa', [NilaiSiswaController::class, 'store'])->name('nilai-siswa-store');
        Route::post('/pengolahan-nilai-siswa/kurmer', [NilaiSiswaController::class, 'kurmerstore'])->name('nilai-siswa-kurmer-store');
        Route::post('/pengolahan-nilai-siswa/{id}', [NilaiSiswaController::class, 'update'])->name('nilai-siswa-update');
        Route::delete('/pengolahan-nilai-siswa/{id}', [NilaiSiswaController::class, 'destroy'])->name('nilai-siswa-destroy');
        Route::get('/pengolahan-nilai-siswa/cp/{id}', [NilaiSiswaController::class, 'getCP'])->name('nilai-siswa.getCP');
        Route::get('/pengolahan-nilai-siswa/tp/{id}', [NilaiSiswaController::class, 'getTP'])->name('nilai-siswa.getTP');
        Route::get('/pengolahan-nilai-siswa/{kategori}/{id}', [NilaiSiswaController::class, 'inputNilai'])->name('nilai-siswa.input');
        Route::post('/pengolahan-nilai-siswa/{kategori}/{id}', [NilaiSiswaController::class, 'simpanNilai'])->name('nilai-siswa.simpan');
        Route::get('/rekap-nilai-siswa', [NilaiSiswaController::class, 'rekapNilaiSiswa'])->name('nilai-siswa.rekap');
        Route::get('/rekap-nilai-siswa/{id}', [NilaiSiswaController::class, 'showRekapNilaiSiswa'])->name('nilai-siswa.rekap.show');
        Route::post('/rekap-nilai-siswa/{id}', [NilaiSiswaController::class, 'storePersentaseNilai'])->name('nilai-siswa.persentase.store');

        //Keuangan
        Route::get('/siswa/info-keuangan', [TagihanKeuanganController::class, 'infoSiswa'])->name('info-keuangan-siswa');
        Route::get('/staf/honorarium', [HonorController::class, 'index'])->name('staf.honorarium');
        Route::prefix('/keuangan')->group(function () {
            Route::resource('/kategori-keuangan', KategoriKeuanganController::class);
            Route::get('/pembayaran-spp/siswa', [SPPController::class, 'siswa'])->name('pembayaran-spp.siswa');
            Route::resource('/pembayaran-spp', SPPController::class);
            Route::get('/pembayaran-lain/siswa', [NonSPPController::class, 'siswa'])->name('pembayaran-lain.siswa');
            Route::get('/pembayaran-lain/detail/{id}', [NonSPPController::class, 'detailNonSPP'])->name('pembayaran-lain.detail');
            Route::post('/pembayaran-lain/detail/{id}', [NonSPPController::class, 'updateDetailNonSPP'])->name('pembayaran-lain.detail.update');
            Route::delete('/pembayaran-lain/detail/delete/{id}', [NonSPPController::class, 'deleteDetailNonSPP'])->name('pembayaran-lain.detailnonspp.destroy');
            Route::resource('/pembayaran-lain', NonSPPController::class);
            Route::get('/tagihan-keuangan/kelas', [TagihanKeuanganController::class, 'show'])->name('tagihan-keuangan.kelas');
            Route::get('/tagihan-keuangan/kelas/data/{id}', [TagihanKeuanganController::class, 'kelas'])->name('tagihan-keuangan.kelas.data');
            Route::get('/tagihan-keuangan/print/{id}', [TagihanKeuanganController::class, 'print'])->name('tagihan-keuangan.print');
            Route::resource('/tagihan-keuangan', TagihanKeuanganController::class);
            Route::get('/honorarium-pegawai/rincian/{id}', [HonorDetailController::class, 'rincian'])->name('honorarium-pegawai.rincian');
            Route::get('/honorarium-pegawai/kelola/{id}', [HonorDetailController::class, 'kelola'])->name('honorarium-pegawai.kelola');
            Route::get('/honorarium-pegawai/kelola/{id}/create', [HonorDetailController::class, 'create'])->name('honorarium-pegawai.kelola.create');
            Route::post('/honorarium-pegawai/kelola/{id}/create', [HonorDetailController::class, 'store'])->name('honorarium-pegawai.kelola.store');
            Route::delete('/honorarium-pegawai/kelola/{id}/destroy', [HonorDetailController::class, 'destroy'])->name('honorarium-pegawai.kelola.destroy');
            Route::get('/honorarium-pegawai/kelola/{id}/edit', [HonorDetailController::class, 'edit'])->name('honorarium-pegawai.kelola.edit');
            Route::put('/honorarium-pegawai/kelola/{id}/edit', [HonorDetailController::class, 'update'])->name('honorarium-pegawai.kelola.update');
            Route::post('/honorarium-pegawai/kelola/{id}/import', [HonorDetailController::class, 'import'])->name('honorarium-pegawai.kelola.import');
            Route::resource('/honorarium-pegawai', HonorController::class);
        });

        Route::prefix('kesiswaan')->group(function () {
            //kasus-siswa
            Route::get('/laporan-kasus-siswa/rombel', [KasusSiswaController::class, 'rombel'])->name('kesiswaan.laporan-kasus-siswa.rombel');
            Route::get('/laporan-kasus-siswa/detail/{id}', [KasusSiswaController::class, 'detail'])->name('kesiswaan.laporan-kasus-siswa.detail');
            Route::get('/laporan-kasus-siswa', [KasusSiswaController::class, 'index'])->name('kesiswaan.laporan-kasus-siswa.index');
            Route::post('/laporan-kasus-siswa', [KasusSiswaController::class, 'store'])->name('kesiswaan.laporan-kasus-siswa.store');
            Route::get('/laporan-kasus-siswa/create', [KasusSiswaController::class, 'create'])->name('kesiswaan.laporan-kasus-siswa.create');
            Route::get('/laporan-kasus-siswa/{id}/edit', [KasusSiswaController::class, 'edit'])->name('kesiswaan.laporan-kasus-siswa.edit');
            Route::put('/laporan-kasus-siswa/{id}/update', [KasusSiswaController::class, 'update'])->name('kesiswaan.laporan-kasus-siswa.update');
            Route::delete('/laporan-kasus-siswa/{id}/destroy', [KasusSiswaController::class, 'destroy'])->name('kesiswaan.laporan-kasus-siswa.destroy');
            Route::get('/laporan-kehadiran-siswa', [PresensiController::class, 'rekapPresensiSiswa'])->name('kesiswaan.data-rekap-presensi-siswa');
            Route::post('/laporan-kehadiran-siswa/kbm', [PresensiController::class, 'rekapPresensiSiswa'])->name('kesiswaan.data-rekap-presensi-siswa.kbm');
            Route::post('/laporan-kehadiran-siswa/harian', [PresensiController::class, 'rekapPresensiSiswa'])->name('kesiswaan.data-rekap-presensi-siswa.harian');
        });

        //pengaturan
        Route::get('/pengaturan', [PengaturanController::class, 'index'])->name('pengaturan.index');
        Route::get('/pengaturan/menu', [PengaturanController::class, 'menuForm'])->name('pengaturan.menuForm');
        Route::post('/pengaturan/menu', [PengaturanController::class, 'menuFormStore'])->name('pengaturan.menuForm.store');

        //pengaturan
        Route::resource('/kalender-akademik', KalenderAkademikController::class);

        //Capaian dan Tujuan
        Route::get('/capaian-pembelajaran/{id}', [TPController::class, 'index'])->name('tp.index');
        Route::resource('/capaian-pembelajaran', CPController::class);
        Route::resource('/tujuan-pembelajaran', TPController::class);

        //Raport
        Route::get('/siswa/hasil-studi-siswa', [CetakController::class, 'infoSiswa'])->name('hasil-studi-siswa');
        Route::prefix('/raport')->group(function () {
            Route::resource('/raport-identitas', IdentitasController::class);
            Route::post('/raport-aktivasi/{id}', [IdentitasController::class, 'aktivasi'])->name('raport.aktivasi');
            Route::middleware('aktivasi-raport')->group(function () {
                Route::get('/nilai-raport/detail/{id}', [DetailNilaiRaportController::class, 'input'])->name('detail-nilai-raport.input');
                Route::post('/nilai-raport/detail/{id}', [DetailNilaiRaportController::class, 'store'])->name('detail-nilai-raport.store');
                Route::get('/nilai-raport/export/{id}', [DetailNilaiRaportController::class, 'export'])->name('detail-nilai-raport.export');
                Route::resource('/nilai-raport', NilaiRaportController::class);
                Route::middleware('cek-walikelas')->group(function () {
                    Route::resource('/matpel-kelas', MatpelKelasController::class);
                    Route::resource('/absensi-siswa', AbsensiRaportController::class);
                    Route::resource('/nilai-sikap', NilaiSikapController::class);
                    Route::resource('/nilai-ekstrakurikuler', NilaiEkstraController::class);
                    Route::resource('/kenaikan-kelas', KenaikanKelasController::class);
                    Route::resource('/nilai-prakerin', NilaiPrakerinController::class);
                    Route::get('/cetak/{page}/{id}/{start}/{end}', [CetakController::class, 'page'])->name('cetak.raport');
                    Route::resource('/cetak', CetakController::class);
                });
            });
            Route::resource('/ekstrakurikuler', EkstrakurikulerController::class);
            Route::resource('/kategori-sikap', KategoriSikapController::class);
            Route::resource('/format', FormatController::class);
            Route::post('/nilai-raport/import', [DetailNilaiRaportController::class, 'import'])->name('nilai-raport.import');
            Route::get('/template-import-nilairaport', function () {
                $file = public_path('storage/template_import/template_nilai_raport_siswa.xlsx');
                return response()->download($file);
            })->name('nilairaport.template.import');
        });


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
