<?php

namespace Database\Seeders;

use App\Models\Fitur;
use App\Models\Staf;
use App\Models\HakAkses;
use App\Models\Jurusan;
use App\Models\Kategori;
use App\Models\Kelas;
use App\Models\Matpel;
use App\Models\Menu;
use App\Models\Role;
use App\Models\Siswa;
use App\Models\TahunAjaran;
use App\Models\User;
use App\Models\UserRole;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        User::create([
            'username' => 'adminTest',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'guru1',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'guru2',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'siswa1',
            'password' => bcrypt('12341234'),
        ]);
        User::create([
            'username' => 'siswa2',
            'password' => bcrypt('12341234'),
        ]);

        Role::create([
            'role' => 'Admin',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Role::create([
            'role' => 'Guru',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Role::create([
            'role' => 'Siswa',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 1,
            'idrole' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 2,
            'idrole' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 3,
            'idrole' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 4,
            'idrole' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        UserRole::create([
            'iduser' => 5,
            'idrole' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Dashboard',
            'posisi' => 1,
            'icon' => 'home',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Data Master',
            'posisi' => 2,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Administrasi Guru',
            'posisi' => 3,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Kurikulum',
            'posisi' => 4,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Walikelas',
            'posisi' => 5,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'E-Raport',
            'posisi' => 6,
            'icon' => 'book',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Kategori::create([
            'kategori' => 'Pengaturan',
            'posisi' => 7,
            'icon' => 'settings',
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Menu::create([
            'menu' => 'Dashboard',
            'url' => '/pages/dashboard',
            'posisi' => 1,
            'idkategori' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Siswa',
            'url' => '/pages/data-siswa',
            'posisi' => 1,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Staf',
            'url' => '/pages/data-staf',
            'posisi' => 2,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Jurusan',
            'url' => '/pages/data-jurusan',
            'posisi' => 4,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Kelas',
            'url' => '/pages/data-kelas',
            'posisi' => 3,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Rombel',
            'url' => '/pages/data-rombel',
            'posisi' => 5,
            'idkategori' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Menu::create([
            'menu' => 'Masuk Mengajar',
            'url' => '/pages/masuk-mengajar',
            'posisi' => 1,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Mengajar',
            'url' => '/pages/rekap-presensi-mengajar',
            'posisi' => 2,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/rekap-presensi-siswa',
            'posisi' => 3,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Matpel Pengampu',
            'url' => '/pages/matpel-pengampu',
            'posisi' => 4,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Jadwal Mengajar',
            'url' => '/pages/jadwal-mengajar',
            'posisi' => 5,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Pengolahan Nilai Siswa',
            'url' => '/pages/pengolahan-nilai-siswa',
            'posisi' => 6,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekapan Nilai Siswa',
            'url' => '/pages/rekap-nilai-siswa',
            'posisi' => 7,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'CP & TP',
            'url' => '/pages/capaian-tujuan-pembelajaran',
            'posisi' => 8,
            'idkategori' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Mata Pelajaran',
            'url' => '/pages/data-mata-pelajaran',
            'posisi' => 1,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Walikelas',
            'url' => '/pages/data-walikelas',
            'posisi' => 2,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Guru',
            'url' => '/pages/data-rekap-presensi-guru',
            'posisi' => 3,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/data-rekap-presensi-siswa',
            'posisi' => 4,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Jadwal Mengajar Guru',
            'url' => '/pages/data-jadwal-mengajar-guru',
            'posisi' => 5,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Jam Pelajaran',
            'url' => '/pages/data-jam-pelajaran',
            'posisi' => 6,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Jadwal Sistem Blok',
            'url' => '/pages/jadwal-sistem-blok',
            'posisi' => 7,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Pengajuan Kehadiran Mengajar',
            'url' => '/pages/pengajuan-kehadiran-mengajar',
            'posisi' => 8,
            'idkategori' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Data Siswa',
            'url' => '/pages/walikelas/siswa',
            'posisi' => 1,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Presensi Harian Siswa',
            'url' => '/pages/walikelas/presensi-harian-siswa',
            'posisi' => 2,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Rekap Presensi Siswa',
            'url' => '/pages/walikelas/rekap-presensi-siswa',
            'posisi' => 3,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);

        Menu::create([
            'menu' => 'Grafik Presensi Siswa',
            'url' => '/pages/walikelas/grafik-presensi-siswa',
            'posisi' => 4,
            'idkategori' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Aktivasi Raport',
            'url' => '/pages/raport/aktivasi-raport',
            'posisi' => 1,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Nilai Raport',
            'url' => '/pages/raport/nilai-raport',
            'posisi' => 2,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Matpel Kelas',
            'url' => '/pages/raport/matpel-kelas',
            'posisi' => 3,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Absensi Siswa',
            'url' => '/pages/raport/absensi-siswa',
            'posisi' => 4,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Nilai Sikap',
            'url' => '/pages/raport/nilai-sikap',
            'posisi' => 5,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Nilai Ekstrakurikuler',
            'url' => '/pages/raport/nilai-ekstrakurikuler',
            'posisi' => 6,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Kategori Sikap',
            'url' => '/pages/raport/kategori-sikap',
            'posisi' => 7,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Ekstrakurikuler',
            'url' => '/pages/raport/ekstrakurikuler',
            'posisi' => 8,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Kenaikan Kelas',
            'url' => '/pages/raport/kenaikan-kelas',
            'posisi' => 9,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Cetak Raport',
            'url' => '/pages/raport/cetak',
            'posisi' => 10,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Pengaturan Raport',
            'url' => '/pages/raport/pengaturan-raport',
            'posisi' => 11,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Format Raport',
            'url' => '/pages/raport/format',
            'posisi' => 12,
            'idkategori' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Role & Hak Akses',
            'url' => '/pages/role',
            'posisi' => 1,
            'idkategori' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Sistem Blok',
            'url' => '/pages/sistem-blok',
            'posisi' => 2,
            'idkategori' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Tahun Ajaran',
            'url' => '/pages/tahun-ajaran',
            'posisi' => 3,
            'idkategori' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Menu::create([
            'menu' => 'Kalender Akademik',
            'url' => '/pages/kalender-akademik',
            'posisi' => 4,
            'idkategori' => 7,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Tambah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Ubah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 2,
            'fitur' => 'Hapus',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Tambah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Ubah',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        Fitur::create([
            'idmenu' => 3,
            'fitur' => 'Hapus',
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 1,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 2,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 3,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 1,
            'idfitur' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 4,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 5,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        HakAkses::create([
            'idrole' => 2,
            'idfitur' => 6,
            'created_at' => now(),
            'updated_at' => now()
        ]);
        // TahunAjaran::create([
        //     'awal_tahun_ajaran' => '2023',
        //     'akhir_tahun_ajaran' => '2024',
        //     'status' => 1,
        //     'semester' => 'ganjil',
        //     'tgl_mulai' => now(),
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Jurusan::create([
        //     'idtahunajaran' => 1,
        //     'jurusan' => 'RPL',
        //     'kompetensi' => 'Rekayan Perangkat Lunak',
        //     'program_keahlian' => 'Teknik Komputer dan Informatika',
        //     'bidang_keahlian' => 'Teknik Informasi dan Komunikasi',
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Kelas::create([
        //     'idtahunajaran' => 1,
        //     'kelas' => 'X RPL 1',
        //     'tingkat' => 'X',
        //     'idjurusan' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Kelas::create([
        //     'idtahunajaran' => 1,
        //     'kelas' => 'X RPL 2',
        //     'tingkat' => 'X',
        //     'idjurusan' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Kelas::create([
        //     'idtahunajaran' => 1,
        //     'kelas' => 'XI RPL 1',
        //     'tingkat' => 'XI',
        //     'idjurusan' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Kelas::create([
        //     'idtahunajaran' => 1,
        //     'kelas' => 'XI RPL 2',
        //     'tingkat' => 'XI',
        //     'idjurusan' => 1,
        //     'created_at' => now(),
        //     'updated_at' => now()
        // ]);
        // Siswa::create([
        //     'nisn' => '12341234',
        //     'nis' => '12345',
        //     'nama' => 'Siswa Test 1',
        //     'iduser' => 4,
        //     'idtahunajaran' => 1,
        //     'nik' => '77777',
        //     'asal_sekolah' => 'smp',
        //     'alamat_siswa' => 'tasik',
        //     'tempat_lahir' => 'Tasikmalaya',
        //     'tanggal_lahir' => '1991-03-12',
        //     'jenis_kelamin' => 'L',
        //     'diterima_tanggal' => '2024-07-01',
        //     'kelas' => 'X RPL 1',
        //     'no_hp_siswa' => '67667',
        //     'status' => '1',
        //     'nama_ayah' => 'ayah',
        //     'nama_ibu' => 'ibu',
        //     'pekerjaan_ayah' => '-',
        //     'pekerjaan_ibu' => '-',
        //     'alamat_ortu' => '-',
        //     'no_hp_ortu' => '888'
        // ]);

        // Siswa::create([
        //     'nisn' => '12341235',
        //     'nis' => '12346',
        //     'nama' => 'Siswa Test 2',
        //     'iduser' => 4,
        //     'idtahunajaran' => 1,
        //     'nik' => '77778',
        //     'asal_sekolah' => 'smp',
        //     'alamat_siswa' => 'tasik',
        //     'tempat_lahir' => 'Tasikmalaya',
        //     'tanggal_lahir' => '1991-03-12',
        //     'jenis_kelamin' => 'L',
        //     'diterima_tanggal' => '2024-07-01',
        //     'kelas' => 'X RPL 1',
        //     'no_hp_siswa' => '67667',
        //     'status' => '1',
        //     'nama_ayah' => 'ayah',
        //     'nama_ibu' => 'ibu',
        //     'pekerjaan_ayah' => '-',
        //     'pekerjaan_ibu' => '-',
        //     'alamat_ortu' => '-',
        //     'no_hp_ortu' => '888'
        // ]);

        Staf::create([
            'nip' => '1122233',
            'nama' => 'Guru Satu',
            'alamat' => '-',
            'tempat_lahir' => '-',
            'tanggal_lahir' => '1991-03-12',
            'jenis_kelamin' => 'L',
            'no_hp' => '032434',
            'status' => '1',
            'nuptk' => '12323',
            'iduser' => 2,
        ]);

        // Staf::create([
        //     'nip' => '1122234',
        //     'nama' => 'Guru Dua',
        //     'alamat' => '-',
        //     'tempat_lahir' => '-',
        //     'tanggal_lahir' => '1991-03-12',
        //     'jenis_kelamin' => 'L',
        //     'no_hp' => '032434',
        //     'status' => '1',
        //     'nuptk' => '12324',
        //     'iduser' => 3,
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'MTK',
        //     'matpel' => 'Matematika',
        //     'kelompok' => 'adaptif',
        //     'matpels_kode' => null,
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'BIND',
        //     'matpel' => 'Bahasa Indonesia',
        //     'kelompok' => 'normatif',
        //     'matpels_kode' => null,
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'KKRPL',
        //     'matpel' => 'Konsentrasi Keahlian RPL',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => null,
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'KKTKJ',
        //     'matpel' => 'Konsentrasi Keahlian TKJ',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => null,
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'PWCS',
        //     'matpel' => 'Pemrograman Web Cliet Side',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => 'KKRPL',
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'PWSS',
        //     'matpel' => 'Pemrograman Web Server Side',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => 'KKRPL',
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'AS',
        //     'matpel' => 'Administrasi Server',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => 'KKTKJ',
        // ]);

        // Matpel::create([
        //     'kode_matpel' => 'KJ',
        //     'matpel' => 'Keamanan Jaringan',
        //     'kelompok' => 'kejuruan',
        //     'matpels_kode' => 'KKTKJ',
        // ]);
    }
}
