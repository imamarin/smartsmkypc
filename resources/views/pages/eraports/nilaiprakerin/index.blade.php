@extends('layouts.app')
@push('styles')
<style>
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Nilai Prakerin Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nilai Prakerin Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-body text-white" style="background-color: rgb(31, 177, 188)">
                    <div class="row">
                        <div class="col-4">
                            <label for="kelas" class="form-label">Semester:</label><br>
                            {{ $aktivasi->semester}}<br>
                        </div>
                        <div class="col-4">
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $aktivasi->tahunajaran->awal_tahun_ajaran }} / {{ $aktivasi->tahunajaran->akhir_tahun_ajaran }}
                        </div>
                        <div class="col-4">
                            <label for="kelas" class="form-label">Kelas:</label><br>
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
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Input Nilai Prakerin Siswa</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('nilai-prakerin.store') }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nisn</th>
                                        <th>Nama</th>
                                        <th>Dudi</th>
                                        <th>Alamat</th>
                                        <th>Waktu</th>
                                        <th>Nilai</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn_dapodik }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            <td>
                                                <input type="text" value="{{ $subject->nilaiprakerin->dudi ?? '-' }}" name="dudi[{{ $subject->nisn }}]" id="dudi" class="form-control">
                                            </td>
                                            <td>
                                                <input type="text" value="{{ $subject->nilaiprakerin->alamat ?? '-' }}" name="alamat[{{ $subject->nisn }}]" id="alamat" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="{{ $subject->nilaiprakerin->waktu ?? 0 }}" min="0" name="waktu[{{ $subject->nisn }}]" id="waktu" class="form-control">
                                            </td>
                                            <td>
                                                <input type="number" value="{{ $subject->nilaiprakerin->nilai ?? 0 }}" min="0" max="100" name="nilai[{{ $subject->nisn }}]" id="nilai" class="form-control">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="7">
                                            <div class="mb-3 d-flex justify-content-end">
                                                <input type="submit" value="Simpan Nilai Prakerin" class="btn btn-primary">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </form>
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
        window.location.href = '{{ route("nilai-prakerin.show", ":id") }}'.replace(':id', event.target.value)
    })
</script>
@endpush
