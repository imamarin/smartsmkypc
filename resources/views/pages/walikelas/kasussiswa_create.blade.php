@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Masukan Kasus Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Masukan Kasus Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-white" style="background-color: rgb(42, 101, 149)">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            Masukan Data Kasus Siswa
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route($route.'laporan-kasus-siswa.store') }}" method="POST">
                        @csrf
                        <div class="mb-3">
                            <label for="nisn" class="form-label">Nama Siswa</label>
                            <select name="nisn" id="nisn" class="form-select select2" required>
                                <option value="" disabled selected>Pilih Siswa</option>
                                @foreach ($siswa as $item)
                                    <option value="{{ $item->nisn }}">{{ $item->siswa->nama }}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kasus" class="form-label">Jenis Kasus</label>
                            <input type="text" class="form-control" id="jenis_kasus" name="jenis_kasus" required>
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Kasus</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kasus" class="form-label">Tanggal Kejadian</label>
                            <input type="date" class="form-control" id="tanggal_kasus" name="tanggal_kasus" required>
                        </div>
                        <div class="mb-3">
                            <label for="penanganan" class="form-label">Tindakan yang Diambil</label>
                            <textarea class="form-control" id="penanganan" name="penanganan" rows="3" required></textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kasus</label>
                            <select class="form-select" id="status2" name="status" required>
                                <option value="" disabled selected>Pilih Status</option>
                                <option value="private">Penanganan Walikelas</option>
                                <option value="open">Penanganan BK</option>
                                <option value="closed">Kasus Selesai</option>
                                <option value="sp1">Surat Peringatan 1</option>
                                <option value="sp2">Surat Peringatan 2</option>
                                <option value="sp3">Surat Peringatan 3</option>
                            </select>
                        </div>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@endpush
