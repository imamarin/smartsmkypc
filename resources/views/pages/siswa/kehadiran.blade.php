@extends('layouts.app')
@section('content')
    @push('styles')
        <style>
            /* .card {
                border: 1px solid #ccc;
                margin: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            } */
            .card-header {
                cursor: pointer;
            }
            .card-body,
            .card-footer {
                padding: 10px;
                display: none;
            }
    </style>
    @endpush
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Kehadiran Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('info-kehadiran-siswa') }}">Kehadiran Siswa</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <!-- Nav tabs -->
            <ul class="nav nav-tabs">
                <li class="nav-item">
                    <a class="nav-link active" data-bs-toggle="tab" href="#harian">Kehadiran Harian</a>
                </li>
                <li class="nav-item">
                    <a class="nav-link" data-bs-toggle="tab" href="#matpel">Kehadiran Mata Pelajaran</a>
                </li>
            </ul>

            <!-- Tab panes -->
            <div class="tab-content">
                <div class="tab-pane container-fluid active pt-3" id="harian">
                    <div class="row">
                        <div class="col-md-3">
                            <div class="card border-success shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-success">
                                        <i class="bi bi-person-check-fill fs-1"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-1 text-muted">Total Hadir</h6>
                                        <h4 class="mb-0 fw-bold text-dark">{{ $total_hadir }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-danger shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-danger">
                                        <i class="bi bi-person-x-fill fs-1"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-1 text-muted">Total Tanpa Keterangan</h6>
                                        <h4 class="mb-0 fw-bold text-dark">{{ $total_alfa }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-primary shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-primary">
                                        <i class="bi bi-person-raised-hand fs-1"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-1 text-muted">Total Izin</h6>
                                        <h4 class="mb-0 fw-bold text-dark">{{ $total_izin }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-3">
                            <div class="card border-warning shadow-sm">
                                <div class="card-body d-flex align-items-center">
                                    <div class="me-3 text-warning">
                                        <i class="bi bi-person-wheelchair fs-1"></i>
                                    </div>
                                    <div>
                                        <h6 class="card-title mb-1 text-muted">Total Sakit</h6>
                                        <h4 class="mb-0 fw-bold text-dark">{{ $total_sakit }}</h4>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered display nowrap" id="example">
                                    <thead>
                                        <tr>
                                            <th>#</th>
                                            <th style="text-align: center">Tanggal</th>
                                            <th style="text-align: center">Keterangan</th>
                                            <th>Semester</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensi as $key => $item)    
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td style="text-align: center">{{ date('d-m-Y', strtotime($item->created_at ?? $tanggal[$key])) }}</td>
                                            <td style="text-align: center">
                                                @if(isset($item['keterangan']))
                                                    @if($item['keterangan'] == 'h')
                                                    <button class="btn btn-sm btn-success">Hadir</button>
                                                    @elseif($item['keterangan'] == 'i')
                                                    <button class="btn btn-sm btn-primary">Izin</button>
                                                    @elseif($item['keterangan'] == 's')
                                                    <button class="btn btn-sm btn-warning">Sakit</button>
                                                    @elseif($item['keterangan'] == 'a')
                                                    <button class="btn btn-sm btn-danger">Tanpa Keterangan</button>
                                                    @endif
                                                @else
                                                -
                                                @endif
                                            </td>
                                            <td>{{ $item['semester'] ?? '-' }}</td>
                                        </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="tab-pane container-fluid fade" id="matpel">
                    <div class="row mt-4">
                        <div class="col-md-12">
                            <div class="table-responsive">
                                <table class="table table-striped table-bordered display nowrap" id="example">
                                    <thead>
                                        <tr>
                                            <th rowspan="2">#</th>
                                            <th style="text-align: center" rowspan="2">Mata Pelajaran</th>
                                            <th style="text-align: center" rowspan="2">Guru</th>
                                            <th colspan="4">Semester Ganjil</th>
                                            <th colspan="4">Semester Genap</th>
                                        </tr>
                                        <tr>
                                            <th>H</th>
                                            <th>S</th>
                                            <th>I</th>
                                            <th>A</th>
                                            <th>H</th>
                                            <th>S</th>
                                            <th>I</th>
                                            <th>A</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        @foreach ($presensi_kbm as $item)
                                            <tr>
                                                <td>{{ $loop->iteration }}</td>
                                                <td>{{ $item->nama_matpel }}</td>
                                                <td>{{ $item->nama_guru }}</td>
                                                <td>{{ $item->ganjil->hadir ?? '-' }}</td>
                                                <td>{{ $item->ganjil->sakit ?? '-' }}</td>
                                                <td>{{ $item->ganjil->izin ?? '-' }}</td>
                                                <td>{{ $item->ganjil->alfa ?? '-' }}</td>
                                                <td>{{ $item->genap->hadir ?? '-' }}</td>
                                                <td>{{ $item->genap->sakit ?? '-' }}</td>
                                                <td>{{ $item->genap->izin ?? '-' }}</td>
                                                <td>{{ $item->genap->alfa ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection
