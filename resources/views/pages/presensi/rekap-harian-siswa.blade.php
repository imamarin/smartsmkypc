@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekapp Presensi Harian Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi Harian Siswa</li>
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
                            <form action="{{ route('rekap-presensi-harian-siswa') }}" id="cariKelas" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                        <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                            @foreach ($tahunajaran as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $idtahunajaran ? 'selected' : '' }}>
                                                    {{ $item->awal_tahun_ajaran }} / {{ $item->akhir_tahun_ajaran }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="semester" class="form-label">Semester</label>
                                        <select name="semester" id="semester" class="form-select select2">
                                            <option value="ganjil" {{ $semester == 'ganjil' ? 'selected' : '' }}>Ganjil</option>
                                            <option value="genap" {{ $semester == 'genap' ? 'selected' : '' }}>Genap</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="idkelas" class="form-label">Kelas</label>
                                        <select name="idkelas" id="idkelas" class="form-select select2">
                                            @foreach ($kelas as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $idkelas ? 'selected' : '' }}>
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
                                    <th>Nisn1</th>
                                    <th>Nama Siswa</th>
                                    <th>Hadir</th>
                                    <th>Sakit</th>
                                    <th>Izin</th>
                                    <th>Tanpa Keterangan</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presensi as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nisn }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->total_hadir }}</td>
                                        <td>{{ $item->total_sakit }}</td>
                                        <td>{{ $item->total_izin }}</td>
                                        <td>{{ $item->total_alfa }}</td>
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
