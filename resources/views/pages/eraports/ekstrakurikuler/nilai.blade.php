@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Nilai Ekstrakurikuler</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nilai Ekstrakurikuler</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-4">
            <form action="{{ route('nilai-ekstrakurikuler.store') }}" method="post">
            @csrf
            <div class="card">
                <div class="card-header">
                    <div class="row">
                        <div class="col-12">
                            <div class="row">
                                <div class="col-12">
                                    <label for="idkelas" class="form-label">Kelas</label>
                                    <select name="idkelas" id="idkelas" class="form-select select2">
                                        <option value="">Pilih Kelas</option>
                                        @foreach ($kelas as $item)
                                            <option value="{{ Crypt::encrypt($item->idkelas) }}" {{ isset($idkelas) ? $idkelas == $item->idkelas ? 'selected' : '' : '' }}>
                                                {{ $item->kelas->kelas }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label for="idekstrakurikuler" class="form-label">Ekstrakurikuler</label>
                                    <select name="idekstrakurikuler" id="idekstrakurikuler" class="form-select select2">
                                        <option value="">Pilih Ekstrakurikuler</option>
                                        @foreach ($ekstrakurikuler as $item)
                                            <option value="{{ Crypt::encrypt($item->id) }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label for="nisn" class="form-label">Siswa</label>
                                    <select name="nisn[]" id="nisn" class="form-select select2" multiple>
                                        <option value="" disabled>Siswa</option>
                                        @foreach ($siswa as $item)
                                            <option value="{{ $item->nisn }}">
                                                {{ $item->nama }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12">
                                    <label for="nilai" class="form-label">Nilai Ekstrakurikuler</label>
                                    <select name="nilai" id="nilai" class="form-select select2">
                                        <option value="3">Sangat Baik</option>
                                        <option value="2">Baik</option>
                                        <option value="1">Cukup Baik</option>
                                    </select>
                                </div>
                            </div>
                            <div class="row mt-4">
                                <div class="col-12 d-flex justify-content-end">
                                    <input type="submit" class="btn btn-primary" value="SIMPAN">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            </form>
        </div>
        <div class="col-md-8">
            <div class="card">
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Ekstrakurikuler</th>
                                    <th>Nilai</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilaiekstrakurikuler as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->siswa->nisn_dapodik }}</td>
                                        <td>{{ $subject->siswa->nama }}</td>
                                        <td>{{ $subject->ekstrakurikuler->nama }}</td>
                                        <td>{{ $subject->nilai == 3 ? 'Sangat Baik' : ($subject->nilai == 2 ? 'Baik' : 'Cukup Baik') }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <a href="{{ route('nilai-ekstrakurikuler.destroy', Crypt::encrypt($subject->id)) }}"
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
    <script>
        $('#idkelas').on('change', function(event){
            event.preventDefault();
            window.location.href = '{{ route("nilai-ekstrakurikuler.show", ":id") }}'.replace(':id', event.target.value)
        })
    </script>
@endpush
