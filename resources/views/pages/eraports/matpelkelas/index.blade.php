@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Matpel Kelas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Matpel Kelas</li>
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
                            <form action="{{ route('matpel-kelas.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
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
                                    <div class="col-12 col-md-3">
                                        <label for="nip" class="form-label">Guru</label>
                                        <select name="nip" id="nip" class="form-select select2">
                                            <option value="">Pilih Guru</option>
                                            @foreach ($staf as $item)
                                                <option value="{{ Crypt::encrypt($item->nip) }}">
                                                    {{ $item->staf->nama }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
                                        <select name="kode_matpel" id="kode_matpel" class="form-select select2">
                                            <option value="">Pilih Mata Pelajaran</option>
                                            @foreach ($matpel as $item)
                                                <option value="{{ Crypt::encrypt($item->kode_matpel) }}">
                                                    {{ $item->matpel->matpel }}
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
                                    <th>Nama Guru</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matpelkelas as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->staf->nama }}</td>
                                        <td>{{ $subject->matpel->matpel }}</td>
                                        <td>{{ $subject->kelas->kelas }}</td>
                                        <td>{{ $subject->semester }}</td>
                                        <td>{{ $subject->tahunajaran->awal_tahun_ajaran }} / {{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <a href="{{ route('matpel-kelas.destroy', Crypt::encrypt($subject->id)) }}"
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
        const idkelas = document.getElementById('idkelas');
        $('#idkelas').on('change', function(event){
            event.preventDefault();
            window.location.href = '{{ route("matpel-kelas.show", ":id") }}'.replace(':id', event.target.value)
        })
    </script>
@endpush
