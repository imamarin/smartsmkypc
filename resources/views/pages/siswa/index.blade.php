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
                        <li class="breadcrumb-item active">Data Siswa</li>
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
                        <h4 class="card-title">List Siswa</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="btn btn-info me-2">Export Data</a>
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            <a href="{{ route('data-siswa.create') }}" class="btn btn-primary">Tambah Data</a>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
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
                                @foreach ($siswa as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->nis }}/{{ $item->nisn }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nama }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->tempat_lahir }},
                                            {{ $item->tanggal_lahir }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->jenis_kelamin }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nik }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->asal_sekolah }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->nama_ayah }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->nama_ibu }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->pekerjaan_ayah }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->pekerjaan_ibu }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->alamat_ortu }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->alamat_siswa }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->no_hp_siswa ?? '-' }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->no_hp_ortu ?? '-' }}</td>
                                        <td>{{ $item->diterima_tanggal }}</td>
                                        <td>
                                            <form action="{{ route('data-siswa.updateStatus', $item->id) }}"
                                                method="post">
                                                @csrf
                                                <button type="submit"
                                                    class="btn btn-sm {{ $item->status == 1 ? 'btn-success' : 'btn-danger' }}">
                                                    {{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}
                                                </button>
                                                <input type="hidden" name="status"
                                                    value="{{ $item->status == 1 ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        <td>{{ $item->kelas }}</td>
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route('data-siswa.edit', $item->id) }}"
                                                    class="btn btn-sm btn-secondary me-2">
                                                    Edit
                                                </a>
                                                <a href="{{ route('data-siswa.destroy', $item->id) }}"
                                                    class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
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
@endpush
