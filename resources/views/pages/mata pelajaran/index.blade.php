@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Mata Pelajaran</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Mata Pelajaran</li>
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
                        <h4 class="card-title">List Mata Pelajaran</h4>
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
                                    <th>Kode</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelompok</th>
                                    <th>Gabungan Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($matpel as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->kode_matpel }}</td>
                                        <td>{{ $subject->matpel }}</td>
                                        <td>{{ $subject->kelompok }}</td>
                                        <td>{{ $subject->parent->matpel ?? '-' }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" data-id="{{ $subject->kode_matpel }}"
                                                data-kode="{{ $subject->kode_matpel }}" data-matpel="{{ $subject->matpel }}"
                                                data-kelompok="{{ $subject->kelompok }}"
                                                data-kategori="{{ $subject->matpels_kode }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('data-mata-pelajaran.destroy', $subject->kode_matpel) }}"
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Mata Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('data-mata-pelajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="kode_matpel" class="form-label">Kode Mata Pelajaran</label>
                            <input type="text" class="form-control" id="kode_matpel" name="kode_matpel" required>
                        </div>
                        <div class="mb-3">
                            <label for="matpel" class="form-label">Nama Mata Pelajaran</label>
                            <input type="text" class="form-control" id="matpel" name="matpel" required>
                        </div>
                        <div class="mb-3">
                            <label for="kelompok" class="form-label">Kelompok Mata Pelajaran</label>
                            <select name="kelompok" id="kelompok" class="form-select select2">
                                <option value="" disabled selected>Tidak Ada</option>
                                <option value="adaptif">Adaptif</option>
                                <option value="normatif">Normatif</option>
                                <option value="kejuruan">Kejuruan</option>
                                <option value="pilihan">Pilihan</option>
                                
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="matpels_kode" class="form-label">Gabungan Mata Pelajaran</label>
                            <select name="matpels_kode" id="matpels_kode" class="form-select select2">
                                <option value="">Tidak Ada</option>
                                @foreach ($matpel as $subject)
                                <option value="{{ $subject->kode_matpel }}">{{ $subject->matpel }}</option>    
                                @endforeach
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
            const kode = button.getAttribute('data-kode');
            const matpel = button.getAttribute('data-matpel');
            const kelompok = button.getAttribute('data-kelompok');
            const kategori = button.getAttribute('data-kategori');

            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                form.action = '{{ route('data-mata-pelajaran.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Mata Pelajaran';
                subjectId.value = id;
                formMethod.value = 'PUT';
                document.getElementById('kode_matpel').value = kode;
                document.getElementById('kode_matpel').readOnly = true;
                document.getElementById('matpel').value = matpel;
                document.getElementById('kelompok').value = kelompok;
                document.getElementById('matpels_kode').value = kategori;
            } else {
                form.action = '{{ route('data-mata-pelajaran.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Mata Pelajaran Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
