@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Detail Kasus Siswa</h4>
            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Detail Kasus Siswa</li>
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
                        Detail Kasus Siswa
                    </div>
                    <div class="col-12 col-md-6 d-flex justify-content-end">
                        <a href="{{ route('laporan-kasus-siswa.rombel', ['idkelas' => Crypt::encrypt($kasus->siswa->rombel[0]->idkelas)]) }}" class="btn btn-light btn-sm mt-3">
                            <i class="bi bi-arrow-left"></i> Kembali
                        </a> 
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="row g-3">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12">
                                <div class="fw-bold">NISN</div>
                                <div>{{ $kasus->nisn ?? '-' }}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Nama Siswa</div>
                                <div>{{ $kasus->siswa->nama ?? '-' }}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Kelas</div>
                                <div>{{ $kasus->siswa->rombel[0]->kelas->kelas ?? '-' }}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Tanggal Kejadian</div>
                                <div>{{ \Carbon\Carbon::parse($kasus->tanggal_kasus)->format('d-m-Y') }}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Status</div>
                                <div>
                                    @if($kasus->status == 'private')
                                        <span class="badge bg-secondary p-2">Tangani Sendiri</span>
                                    @elseif($kasus->status == 'open')
                                        <span class="badge bg-info p-2">Proses BK</span>
                                    @elseif($kasus->status == 'closed')
                                        <span class="badge bg-success p-2">Kasus Selesai</span>
                                    @elseif($kasus->status == 'sp1')
                                        <span class="badge bg-primary p-2">Surat Peringatan 1</span>
                                    @elseif($kasus->status == 'sp2')
                                        <span class="badge bg-warning p-2">Surat Peringatan 2</span>
                                    @elseif($kasus->status == 'sp3')
                                        <span class="badge bg-danger p-2">Surat Peringatan 3</span>    
                                    @else
                                        <span class="badge bg-danger">Tidak Diketahui</span>
                                    @endif
                                </div>
                            </div> 
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-12 g-3">
                                <div class="fw-bold">Jenis Kasus</div>
                                <div>{{ $kasus->jenis_kasus ?? '-' }}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Deskripsi</div>
                                <div>{!! nl2br(e($kasus->deskripsi ?? '-')) !!}</div>
                            </div>
                            <div class="col-12 g-3">
                                <div class="fw-bold">Penanganan</div>
                                <div>{!! nl2br(e($kasus->deskripsi ?? '-')) !!}</div>
                            </div>
                            
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</div>

@endsection

@push('scripts')
@endpush