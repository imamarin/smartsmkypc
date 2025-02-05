@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Sistem Blok</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Sistem Blok</li>
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
                        <h4 class="card-title">List Sistem Blok</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="btn btn-info me-2">Export Data</a>
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                                Data</button>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Sesi</th>
                                    <th>Semester</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($sistemblok as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->nama_sesi }}</td>
                                        <td>{{ $subject->semester }}</td>
                                        <td>{{ $subject->tahunajaran->awal_tahun_ajaran }}/{{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                        <td>
                                            <form action="{{ route('sistemblok.updateStatus', Crypt::encrypt($subject->id)) }}"
                                                method="post">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="flexSwitchCheckDefault"
                                                        {{ $subject->status == 1 ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                    <span
                                                        class="badge {{ $subject->status == 1 ? 'bg-success' : 'bg-danger' }}">{{ $subject->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span>
                                                </div>
                                                <input type="hidden" name="status"
                                                    value="{{ $subject->status == 1 ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" data-id="{{ $subject->id }}"
                                                data-sesi="{{ $subject->nama_sesi }}"
                                                data-semester="{{ $subject->semester }}"
                                                data-tahunajaran="{{ $subject->tahunajaran->id }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('sistem-blok.destroy', $subject->id) }}"
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

    <!-- Modal untuk Input Mata Pelajaran -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Sistem Blok Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('sistem-blok.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="nama_sesi" class="form-label">Nama Sesi</label>
                            <input type="text" class="form-control" id="nama_sesi" name="nama_sesi" required>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select name="semester" id="semester" class="form-control">
                                @if($tahunajaran->semester == 'ganjil')
                                <option value="ganjil">Ganjil</option>
                                @else
                                <option value="genap">Genap</option>
                                @endif
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                                <option value="{{ $tahunajaran->id }}" {{ $tahunajaran->status==1?'selected':'' }}>
                                    {{ $tahunajaran->awal_tahun_ajaran }}/{{ $tahunajaran->akhir_tahun_ajaran }}
                                </option>
                            </select>
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('addSubjectModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const sesi = button.getAttribute('data-sesi');
            const semester = button.getAttribute('data-semester');
            const tahunajaran = button.getAttribute('data-tahunajaran');

            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                form.action = '{{ route('sistem-blok.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Sistem Blok';
                subjectId.value = id;
                formMethod.value = 'PUT';
                document.getElementById('nama_sesi').value = sesi;
                document.getElementById('semester').value = semester;
                document.getElementById('idtahunajaran').value = tahunajaran;
            } else {
                form.action = '{{ route('sistem-blok.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Sistem Blok Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
