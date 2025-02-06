@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Jadwal Mengajar Guru</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Jadwal Mengajar Guru</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Data Jadwal Mengajar Guru</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="btn btn-danger">Kunci Jadwal Mengajar</a>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Guru</th>
                                    <th>Nama</th>
                                    <th>Total Jam Mengajar</th>
                                    <th>Jadwal hari ini</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staf as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->nip }}
                                        </td>
                                        <td>
                                            {{ $item->nama }}
                                        </td>
                                        <td>
                                            <span class="{{ $item->jadwal_mengajar_sum < 1 ? 'badge bg-danger' : 'badge bg-info' }}">
                                                {{ $item->jadwal_mengajar_sum ?? 0 }} Jam
                                            </span>
                                        </td>
                                        <td>
                                            @foreach ($item->jadwalmengajar as $value)
                                            @if ($value->presensi->count() > 0)
                                                <span class="badge bg-success">Jam Ke: {{ $value->jampel->jam }}</span>
                                            @else
                                                <span class="badge bg-danger">Jam Ke: {{ $value->jampel->jam }}</span>    
                                            @endif
                                            @endforeach
                                        </td>
                                        <td>
                                            <a href="{{ route('data-jadwal-mengajar-guru.show', Crypt::encrypt($item->nip.'*'.$tahunajaran->semester.'*'.$tahunajaran->id)) }}" class="btn btn-sm btn-primary">Lihat Jadwal</a>
                                        </td>
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
