@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Presensi Harian Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Presensi Harian Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ route('presensi-harian-siswa') }}" id="cariKelas" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idkelas" class="form-label">Kelas</label>
                                        <select name="idkelas" id="idkelas" class="form-select select2">
                                            @foreach ($kelas as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $kelas_selected ? 'selected' : '' }}>
                                                    {{ $item->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" value="Tampilkan" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                    
                    
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tanggal</th>
                                    <th>Jumlah Siswa</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Tanpa Keterangan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presensi as $key => $item)
                                    <tr>
                                        <th>{{ $loop->iteration }}</th>
                                        <th>{{ date('Y-m-d', strtotime($item->created_at ?? $tanggal[$key])) }}</th>
                                        <th>{{ $item->total_siswa ?? 0 }}</th>
                                        <th>{{ $item->total_hadir ?? 0 }}</th>
                                        <th>{{ $item->total_sakit ?? 0 }}</th>
                                        <th>{{ $item->total_izin  ?? 0}}</th>
                                        <th>{{ $item->total_alfa ?? 0 }}</th>
                                        <th>
                                            
                                            @if ($tanggal[$key] == date('Y-m-d'))
                                                <a href="{{ route('presensi-harian-siswa-create', Crypt::encrypt($kelas_selected."*".$tahunajaran->id."*".$tanggal[$key])) }}" class="btn btn-sm btn-primary">Input Kehadiran</a>
                                            @else
                                                @if(isset($item->total_siswa))
                                                <a href="{{ route('presensi-harian-siswa-create', Crypt::encrypt($kelas_selected."*".$tahunajaran->id."*".$tanggal[$key])) }}" class="btn btn-sm btn-success">Input Kehadiran</a>
                                                @else
                                                <a href="{{ route('presensi-harian-siswa-create', Crypt::encrypt($kelas_selected."*".$tahunajaran->id."*".$tanggal[$key])) }}" class="btn btn-sm btn-danger">Input Kehadiran</a>
                                                @endif
                                            @endif
                                            
                                        </th>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
@endpush
