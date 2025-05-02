@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Format Raport</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Format Raport</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <form action="{{ route('format.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="versi" class="form-label">Versi</label>
                                        <input type="text" name="versi" id="versi" class="form-control">
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="tingkat" class="form-label">Tingkat</label>
                                        <select name="tingkat" id="tingkat" class="form-select select2">
                                            <option value="X">X</option>
                                            <option value="XI">XI</option>
                                            <option value="XII">XII</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                        <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                            @foreach ($tahunajaran as $item)
                                                <option value="{{ $item->id }}">
                                                    {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" class="btn btn-primary" value="SIMPAN">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tingkat</th>
                                    <th>Versi</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($format as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->tingkat }}</td>
                                        <td>{{ $subject->versi }}</td>
                                        <td>{{ $subject->tahunajaran->awal_tahun_ajaran }} / {{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <a href="{{ route('format.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
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
