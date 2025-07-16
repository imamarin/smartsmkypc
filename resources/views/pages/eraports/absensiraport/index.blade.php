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
                <h4 class="mb-0">Raport Kehadiran Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Raport Kehadiran Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-3">
            <div class="card" style="border: 2px solid rgb(31, 177, 188)">
                <div class="card-body">
                    <div class="row">
                        <div class="col-6">
                            <label for="kelas" class="form-label">Semester:</label><br>
                            {{ $aktivasi->semester}}<br>
                        </div>
                        <div class="col-6">
                            <label for="kelas" class="form-label mt-2">Tahun Ajaran:</label><br>
                            {{ $aktivasi->tahunajaran->awal_tahun_ajaran }} / {{ $aktivasi->tahunajaran->akhir_tahun_ajaran }}
                        </div>
                    </div>
                    <div class="row">
                        <div class="col-12">
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
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex justify-content-between" style="background-color: rgb(31, 177, 188)">
                    <h4 class="card-title text-start text-white">Input Raport Kehadiran Siswa</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('absensi-siswa.store') }}" method="post">
                            @csrf
                            <table class="table table-striped display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Nisn</th>
                                        <th>Nama Siswa</th>
                                        <th>Izin</th>
                                        <th>Sakit</th>
                                        <th>Tanpa Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $key => $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }} </td>
                                            <td>{{ $subject->nisn_dapodik }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            <td>
                                                <input type="number" name="izin[{{ $subject->nisn }}]" id="izin_{{ $subject->nisn }}" value="{{ $subject->absensiraport[0]?->izin ?? $subject->presensihariansiswa[0]?->izin ?? 0 }}" class="form-control" min="0" max="100">
                                            </td>
                                            <td>
                                                <input type="number" name="sakit[{{ $subject->nisn }}]" id="sakit_{{ $subject->nisn }}" value="{{ $subject->absensiraport[0]?->sakit ?? $subject->presensihariansiswa[0]?->sakit ?? 0 }}" class="form-control" min="0" max="100">
                                            </td>
                                            <td>
                                                <input type="number" name="alfa[{{ $subject->nisn }}]" id="alfa_{{ $subject->nisn }}" value="{{ $subject->absensiraport[0]?->alfa ?? $subject->presensihariansiswa[0]?->alfa ?? 0 }}" class="form-control" min="0" max="100">
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="6">
                                            <div class="mb-3 d-flex justify-content-end">
                                                <input type="submit" value="Simpan Absensi Raport" class="btn btn-primary">
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
        window.location.href = '{{ route("absensi-siswa.show", ":id") }}'.replace(':id', event.target.value)
    })
</script>
@endpush
