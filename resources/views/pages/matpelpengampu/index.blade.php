@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data Matpel Pengampu</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Matpel Pengampu</li>
                </ol>
            </div>

        </div>
    </div>
</div>
<!-- end page title -->
<div class="row">
    <div class="col-md-12">
        <div class="card">
            <div class="card-header bg bg-info">
                @if(in_array('Tambah', $fiturMenu[$view]))
                <div class="row">
                    <div class="col-12 col-md-8">
                        {{-- <h4 class="card-title">List Matpel Pengampu</h4> --}}
                        <form action="{{ route('matpel-pengampu.store') }}" method="post" class="">
                            @csrf
                            <div class="row">
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                        <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                                            <option value="{{ $tahunajaran->id }}">{{ $tahunajaran->awal_tahun_ajaran }}/{{ $tahunajaran->akhir_tahun_ajaran }}</option>
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4">
                                    <div class="form-group">
                                        <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
                                        <select name="kode_matpel" id="kode_matpel" class="form-control select2">
                                            @foreach ($matpel as $item)
                                            <option value="{{ $item->kode_matpel }}">{{ $item->matpel }}</option>
                                            @endforeach
                                        </select>
                                    </div>
                                </div>
                                <div class="col-md-4 d-flex align-items-end mb-3">
                                    <input type="submit" value="Tambahkan" class="btn btn-primary" style="margin-bottom: 5px">
                                </div>
                            </div>
                        </form>
                    </div>
                    @endif
                    <div class="col-12 col-md-4">
                        <div class="d-flex justify-content-end mb-3">
                            <!-- Button to trigger modal -->
                            {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Walikelas</button> --}}
                        </div>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display nowrap" id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Mata Pelajaran</th>
                                <th>Tahun Ajaran</th>
                                @if(in_array('Hapus', $fiturMenu[$view]))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($matpelpengampu as $subject)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td>{{ $subject->matpel->matpel }}</td>
                                <td>{{ $subject->tahunajaran->awal_tahun_ajaran }}/{{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                @if(in_array('Hapus', $fiturMenu[$view]))
                                <td>
                                    <a href="{{ route('matpel-pengampu.destroy', $subject->id) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
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
