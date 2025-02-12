@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Siswa </li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="row">
                                <div class="col-6 col-md-6"><h6>Kelas</h6></div>
                                <div class="col-6 col-md-6">
                                    <form action="{{ route('walikelas.tahunajaran') }}" id="searchByTahunAjaran" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-md-12 d-flex align-content-center">
                                                <h6 style="z-index: 1000">: </h6>
                                                <select name="idkelas" id="idkelas" class="form-select select2">
                                                    @foreach ($kelas as $item)
                                                        <option value="{{ $item->idkelas }}" {{ $walikelas->idkelas == $item->idkelas ? 'selected' : '' }}>
                                                            {{ $item->kelas->kelas }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6"><h6>Jumlah Siswa</h6></div>
                                <div class="col-6 col-md-6"><h6>: {{ $walikelas->kelas->rombel->count() ?? 0 }}</h6></div>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6"><h6>Siswa Laki-Laki</h6></div>
                                <div class="col-6 col-md-6"><h6>: {{ $walikelas->kelas->laki_count ?? 0 }}</h6></div>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6"><h6>Siswa Perempuan</h6></div>
                                <div class="col-6 col-md-6"><h6>: {{ $walikelas->kelas->perempuan_count ?? 0 }}</h6></div>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-6"><h6>Petugas Presensi</h6></div>
                                <div class="col-6 col-md-6">
                                    <form action="{{ route('walikelas.petugaspresensi', Crypt::encrypt($walikelas->id)) }}" id="setPetugasPresensi" method="post">
                                        @csrf
                                        <div class="row">
                                            <div class="col-12 col-md-12 d-flex align-content-start">
                                                <h6 style="z-index: 1000">: </h6>
                                                <select name="nisn" id="nisn" class="form-select select2">
                                                    <option value="">Belum Memilih Siswa</option>
                                                    @foreach ($walikelas->kelas->rombel as $item)
                                                        <option value="{{ $item->siswa->nisn }}" {{ $item->siswa->nisn == $walikelas->petugas_presensi ? 'selected' : '' }}>
                                                            {{ $item->siswa->nama }}
                                                        </option>
                                                    @endforeach
                                                </select>
                                            </div>
                                        </div>
                                    </form>
                                </div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NIS/NISN</th>
                                    <th>Nama</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>NIK</th>
                                    <th>Asal Sekolah</th>
                                    <th>Nama Ayah</th>
                                    <th>Nama Ibu</th>
                                    <th>Pekerjaan Ayah</th>
                                    <th>Pekerjaan Ibu</th>
                                    <th>Alamat ortu</th>
                                    <th>Alamat Siswa</th>
                                    <th>No HP Ortu</th>
                                    <th>No HP Siswa</th>
                                    <th>Diterima Tanggal</th>
                                    <th>Status</th>
                                    <th>Kelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($walikelas->kelas->rombel as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->nis }}/{{ $item->siswa->nisn }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">{{ $item->siswa->nama }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->tempat_lahir }},
                                            {{ $item->siswa->tanggal_lahir }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->jenis_kelamin }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">{{ $item->siswa->nik }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->asal_sekolah }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->nama_ayah }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->nama_ibu }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->pekerjaan_ayah }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->pekerjaan_ibu }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->alamat_ortu }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->alamat_siswa }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->no_hp_siswa ?? '-' }}</td>
                                        <td class="{{ $item->siswa->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->siswa->no_hp_ortu ?? '-' }}</td>
                                        <td>{{ $item->siswa->diterima_tanggal }}</td>
                                        <td>
                                            <form action="{{ route('data-siswa.updateStatus', $item->siswa->nisn) }}"
                                                method="post">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $item->siswa->status == 1 ? 'btn-success' : 'btn-danger' }}">
                                                    {{ $item->siswa->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                                </button>
                                                <input type="hidden" name="status"
                                                    value="{{ $item->siswa->status == 1 ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        <td>{{ $item->siswa->kelas }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('walikelas.siswa.edit', Crypt::encrypt($item->siswa->user->id)) }}"
                                                    class="btn btn-sm btn-secondary me-2">
                                                    Edit
                                                </a>
                                            </div>
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
    $(document).ready(function() {
        $('.select2').select2();
        $('.select2-selection').addClass('border-0');
        $('.select2-selection').css('margin-top', '-13px');
        $('.select2-selection').css('margin-left', '-15px');
        $('.select2-selection').css('z-index', '1');

        $('#idkelas').change(function(){
            $('#searchByTahunAjaran').submit();
        });

        $('#nisn').change(function(){
            $('#setPetugasPresensi').submit();
        });
    });
</script>
@endpush
