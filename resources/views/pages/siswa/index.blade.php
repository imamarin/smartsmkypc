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
                        <form action="{{ route('data-siswa.tahunajaran') }}" method="get" class="d-flex gap-2 w-100">
                            <div style="width: 45%">
                                <label for="tahunajaran" class="form-label">Tahun Ajaran</label>
                                <select name="id" id="tahunajaran" class="form-control select2">
                                    @foreach($tahunajaran as $tahunajaran)
                                    <option value="{{ Crypt::encrypt($tahunajaran->id) }}" {{ isset($idtahunajaran) ? ($idtahunajaran == $tahunajaran->id ? 'selected' : '') : ''  }}>{{ $tahunajaran->awal_tahun_ajaran }}/{{ $tahunajaran->akhir_tahun_ajaran }}</option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="d-flex align-items-end">
                                <input type="submit" value="Tampilkan" class="btn btn-primary" style="margin-bottom: 5px">
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            @if(in_array('Eksport', $fiturMenu[$view]))
                            <a href="{{ route('data-siswa.export') }}" class="btn btn-info me-2">Export Data</a>
                            @endif
                            @if(in_array('Import', $fiturMenu[$view]))
                            <a href="#" class="btn btn-success me-2" data-bs-toggle="modal" data-bs-target="#importModal">Import Data</a>
                            @endif
                            @if(in_array('Tambah', $fiturMenu[$view]))
                            <a href="{{ route('data-siswa.create') }}" class="btn btn-primary">Tambah Data</a>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example2">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NISN</th>
                                    <th>Nama</th>
                                    <th>Tempat, Tanggal Lahir</th>
                                    <th>Jenis Kelamin</th>
                                    <th>Alamat Siswa</th>
                                    <th>No HP Siswa</th>
                                    <th>Kelas</th>
                                    <th>status</th>
                                    @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($siswa as $key => $item)

                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->nisn_dapodik }}
                                        </td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">{{ $item->nama }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->tempat_lahir }},
                                            {{ $item->tanggal_lahir }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->jenis_kelamin }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->alamat_siswa }}</td>
                                        <td class="{{ $item->status == 1 ? '' : 'text-danger' }}">
                                            {{ $item->no_hp_siswa ?? '-' }}</td>
                                        <td>{{ $item->rombel[0]->kelas->kelas ?? '-' }}</td>
                                        <td>
                                            <form action="{{ route('data-siswa.updateStatus', Crypt::encrypt($item->nisn)) }}"
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
                                        @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]))
                                        <td>
                                            <div class="d-flex">
                                                @if(in_array('Edit', $fiturMenu[$view]))
                                                <a href="{{ route('data-siswa.edit', Crypt::encrypt($item->user?->id)) }}"
                                                    class="btn btn-sm btn-secondary me-2">
                                                    Edit
                                                </a>
                                                @endif
                                                @if(in_array('Hapus', $fiturMenu[$view]))
                                                <a href="{{ route('data-siswa.destroy', Crypt::encrypt($item->user?->id)) }}"
                                                    class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
                                                @endif
                                            </div>
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
    <div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="importForm" action="{{ route('data-siswa.import') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-body">
                        <div class="mb-3">
                            <label for="file" class="form-label">Upload File (.xlsx)</label>
                            <input type="file" class="form-control" accept=".xlsx" id="file" name="file" required>
                        </div>

                        <div class="mb-3">
                            <span>Download Template .xlsx disini</span>
                            <a href="{{ route('data-siswa.template.import', 'Template_Siswa.xlsx') }}">Template_Siswa.xlsx</a>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Import</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
@endpush
