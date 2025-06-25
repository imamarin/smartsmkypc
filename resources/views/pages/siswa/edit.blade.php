@extends('layouts.app')
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Input Data Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('data-siswa.index') }}">Data Siswa</a></li>
                        <li class="breadcrumb-item active">Form Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <form action="{{ route('data-siswa.update', $siswa->user->id) }}" method="POST">
        @csrf
        @method('PUT')
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Identitas Siswa</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NISN<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nisn"
                                            value="{{ $siswa->nisn }}" required readonly>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NIS<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nis"
                                            value="{{ $siswa->nis }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">NIK<span class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="nik"
                                            value="{{ $siswa->nik }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Siswa<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama"
                                            value="{{ $siswa->nama }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tempat Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="tempat_lahir"
                                            value="{{ $siswa->tempat_lahir }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Tanggal Lahir<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" name="tanggal_lahir"
                                            value="{{ $siswa->tanggal_lahir }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Jenis Kelamin<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <div class="form-check mb-2">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="P" {{ $siswa->jenis_kelamin == 'P' ? 'checked' : '' }} required>
                                            <label class="form-check-label">
                                                Perempuan
                                            </label>
                                        </div>
                                        <div class="form-check">
                                            <input class="form-check-input" type="radio" name="jenis_kelamin"
                                                value="L" {{ $siswa->jenis_kelamin == 'L' ? 'checked' : '' }}
                                                required>
                                            <label class="form-check-label">
                                                Laki-laki
                                            </label>
                                        </div>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 mb-lg-0 row">
                                    <label class="col-md-2 col-form-label">Asal Sekolah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="asal_sekolah"
                                            value="{{ $siswa->asal_sekolah }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-lg-0 row">
                                    <label class="col-md-2 col-form-label">Alamat Siswa<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat_siswa" class="form-control" cols="30" rows="5" required>{{ $siswa->alamat_siswa }}</textarea>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->

                            <div class="col-xl-6">
                                <div class="row mb-3  mt-3 mt-xl-0">
                                    <label class="col-md-2 col-form-label">No HP Siswa</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp_siswa"
                                            value="{{ $siswa->no_hp_siswa }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Status Keluarga</label>
                                    <div class="col-md-10">
                                        <select class="form-control" name="status_keluarga">
                                            <option value="Anak Kandung" {{ $siswa->status_keluarga == 'Anak Kandung' ? 'selected' : '' }}>Anak Kandung</option>
                                            <option value="Anak Tiri"  {{ $siswa->status_keluarga == 'Anak Tiri' ? 'selected' : '' }}>Anak Tiri</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Anak Ke</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="anak_ke" value="{{ $siswa->anak_ke }}">
                                    </div>
                                </div>
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Tanggal Terima<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="date" name="diterima_tanggal"
                                            value="{{ $siswa->diterima_tanggal }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Diterima Dikelas<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="kelas"
                                            value="{{ $siswa->kelas }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Masuk Tahun Ajaran<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="idtahunajaran" class="form-select select2" required>
                                            <option value="{{ $siswa->tahunajaran->id }}">
                                                {{ $siswa->tahunajaran->awal_tahun_ajaran }}/{{ $siswa->tahunajaran->akhir_tahun_ajaran }}
                                                ({{ $siswa->tahunajaran->semster == 'ganjil' ? 'Ganjil' : 'Genap' }})
                                            </option>
                                            @foreach ($tahun_ajaran as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                                    ({{ $item->semster == 'ganjil' ? 'Ganjil' : 'Genap' }})
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- end row -->
                                <div class="row">
                                    <label class="col-md-2 col-form-label">Status<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <select name="status" class="form-select select2" required>
                                            <option value="{{ $siswa->status }}" selected>
                                                {{ $siswa->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</option>
                                            <option value="1">Aktif</option>
                                            <option value="0">Tidak Aktif</option>
                                        </select>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Data Orang Tua Siswa</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Ayah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama_ayah"
                                            value="{{ $siswa->nama_ayah }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Ibu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="nama_ibu"
                                            value="{{ $siswa->nama_ibu }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Pekerjaan Ayah<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="pekerjaan_ayah"
                                            value="{{ $siswa->pekerjaan_ayah }}" required>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                            <div class="col-xl-6">
                                <div class="row mb-3 mt-3 mt-xl-0">
                                    <label class="col-md-2 col-form-label">Pekerjaan Ibu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="pekerjaan_ibu"
                                            value="{{ $siswa->pekerjaan_ibu }}" required>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Alamat Ortu<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat_ortu" class="form-control" cols="30" rows="4" required>{{ $siswa->alamat_ortu }}</textarea>
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">No HP Ortu</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp_ortu"
                                            value="{{ $siswa->no_hp_ortu }}">
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Data Wali Siswa</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl-6">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Nama Wali<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="walisiswa" value="{{ $siswa->walisiswa }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Pekerjaan Wali<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="pekerjaan_wali" value="{{ $siswa->pekerjaan_wali }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">No HP Wali</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="number" name="no_hp_wali" value="{{ $siswa->no_hp_wali }}">
                                    </div>
                                </div>
                            </div><!-- end col -->
                            <div class="col-xl-6">
                                <div class="row mb-3">
                                    <label class="col-md-2 col-form-label">Alamat Wali<span
                                            class="text-danger">*</span></label>
                                    <div class="col-md-10">
                                        <textarea name="alamat_wali" class="form-control" cols="30" rows="4">{{ $siswa->alamat_wali }}</textarea>
                                    </div>
                                </div><!-- end row -->
                            </div>
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="row">
            <div class="col-12">
                <div class="card">
                    <div class="card-header justify-content-between d-flex align-items-center">
                        <h4 class="card-title">Validasi Pengguna</h4>
                    </div><!-- end card header -->
                    <div class="card-body">
                        <div class="row">
                            <div class="col-xl">
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Username</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="text" name="username" value="{{ $siswa->user->username }}">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Password</label>
                                    <div class="col-md-10">
                                        <input class="form-control" type="password" name="password" placeholder="Silakan isi jika ingin ubah password">
                                    </div>
                                </div><!-- end row -->
                                <div class="mb-3 row">
                                    <label class="col-md-2 col-form-label">Level</label>
                                    <div class="col-md-10">
                                        <select name="role[]" class="form-select select2" multiple>
                                            @foreach ($role as $item)
                                                <option value="{{ $item->id }}" {{ in_array($item->id, $roleUser)?'selected':'' }}>{{ $item->role }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div><!-- end row -->
                            </div><!-- end col -->
                        </div><!-- end row -->
                    </div><!-- end card body -->
                </div><!-- end card -->
            </div><!-- end col -->
        </div>
        <div class="me-3 text-end">
            <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
            <button type="reset" class="btn btn-danger">Cancel</button>
        </div>
    </form>
@endsection
