@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Kategori Sikap</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kategori Sikap</li>
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
                            <form action="{{ route('kategori-sikap.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="kategori" class="form-label">Kategori</label>
                                        <select name="kategori" id="kategpri" class="form-select select2">
                                            <option value="">Pilih Kategori</option>
                                            <option value="sosial">Sosial</option>
                                            <option value="spiritual">Spiritual</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="sikap" class="form-label">Sikap</label>
                                        <input type="text" name="sikap" id="sikap" class="form-control">
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
                                    <th>Kategori</th>
                                    <th>Sikap</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sikap as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->kategori }}</td>
                                        <td>{{ $subject->sikap }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <a href="{{ route('kategori-sikap.destroy', Crypt::encrypt($subject->id)) }}"
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
@push('scripts')
@endpush
