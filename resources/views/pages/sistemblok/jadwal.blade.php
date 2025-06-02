@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Jadwal Sistem Blok</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jadwal Sistem Blok</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                @if(in_array('Tambah', $fiturMenu[$view]))
                <div class="card-header"  style="background-color: #cbd931 ;">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <form action="{{ route('jadwal-sistem-blok.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idsistemblok" class="form-label">Sesi</label>
                                        <select name="idsistemblok" id="idsistemblok" class="form-select select2">
                                            @foreach ($sesi as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->nama_sesi }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="tanggal_mulai" class="form-label ">Dari Tanggal</label>
                                        <input type="date" name="tanggal_mulai" id="tanggal_mulai" class="form-control">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                                        <input type="date" name="tanggal_akhir" id="tanggal_akhir" class=" form-control">
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" class="btn btn-primary" value="SIMPAN" name="simpan_sesi">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->
                @endif
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Sesi</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Tanggal Akhir</th>
                                    @if(in_array('Hapus', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sistemblok as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->sistemblok->nama_sesi }}</td>
                                        <td>{{ $subject->tanggal_mulai }}</td>
                                        <td>{{ $subject->tanggal_akhir }}</td>
                                        @if(in_array('Hapus', $fiturMenu[$view]))
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <a href="{{ route('jadwal-sistem-blok.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
                                        </td>
                                        @endif
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
