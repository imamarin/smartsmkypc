@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Edit Kasus Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Edit Kasus Siswa</li>
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
                            Edit Kasus Siswa
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route('laporan-kasus-siswa.update', Crypt::encrypt($kasus->id)) }}" method="POST">
                        @csrf
                        @method('PUT')
                        <div class="mb-3">
                            <label for="nisn" class="form-label">Nama Siswa</label>
                            <select name="nisn" id="nisn" class="form-select select2" required>
                                <option value="" disabled selected>Pilih Siswa</option>
                                @foreach ($siswa as $item)
                                    <option value="{{ $item->nisn }}" {{ $item->nisn == $kasus->nisn ? 'selected' : '' }}>{{ $item->siswa->nama }}</option>
                                @endforeach 
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jenis_kasus" class="form-label">Jenis Kasus</label>
                            <input type="text" class="form-control" id="jenis_kasus" name="jenis_kasus" required value="{{ $kasus->jenis_kasus }}">
                        </div>
                        <div class="mb-3">
                            <label for="deskripsi" class="form-label">Deskripsi Kasus</label>
                            <textarea class="form-control" id="deskripsi" name="deskripsi" rows="3" required>{{ $kasus->deskripsi }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_kasus" class="form-label">Tanggal Kejadian</label>
                            <input type="date" class="form-control" id="tanggal_kasus" name="tanggal_kasus" required value="{{ $kasus->tanggal_kasus }}">
                        </div>
                        <div class="mb-3">
                            <label for="penanganan" class="form-label">Tindakan yang Diambil</label>
                            <textarea class="form-control" id="penanganan" name="penanganan" rows="3" required>{{ $kasus->penanganan }}</textarea>
                        </div>
                        <div class="mb-3">
                            <label for="status" class="form-label">Status Kasus</label>
                            <select class="form-select" id="status2" name="status" required>
                                <option value="private" {{ $kasus->status == 'private' ? 'selected' : '' }}>Tangani Sendiri</option>
                                <option value="open" {{ $kasus->status == 'open' ? 'selected' : '' }}>Proses BK</option>
                                <option value="closed" {{ $kasus->status == 'closed' ? 'selected' : '' }}>Kasus Selesai</option>
                                <option value="sp1" {{ $kasus->status == 'sp1' ? 'selected' : '' }}>Surat Peringatan 1</option>
                                <option value="sp2" {{ $kasus->status == 'sp2' ? 'selected' : '' }}>Surat Peringatan 2</option>
                                <option value="sp3" {{ $kasus->status == 'sp3' ? 'selected' : '' }}>Surat Peringatan 3</option>
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
