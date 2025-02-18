@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Kalender Akdemik</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Kalender Akdemik</li>
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
                        <h4 class="card-title">Data Kalender Akademik</h4>
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
                                    <th>Nama Kegiatan</th>
                                    <th>Tanggal</th>
                                    <th>Status KBM</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kalender as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->kegiatan }}</td>
                                        <td>
                                            @if($subject->tanggal_mulai == $subject->tanggal_akhir)
                                            {{ date('d-m-Y', strtotime($subject->tanggal_mulai)) }}
                                            @else
                                            {{ date('d-m-Y', strtotime($subject->tanggal_mulai)) }} s.d {{ date('d-m-Y', strtotime($subject->tanggal_akhir))  }}
                                            @endif
                                        </td>
                                        <td>
                                            @if($subject->status_kbm == 'efektif')
                                            <span class="badge bg-success">Efektif</span>
                                            @elseif($subject->status_kbm == 'tidak efektif')
                                            <span class="badge bg-warning">Tidak Efektif</span>
                                            @else
                                            <span class="badge bg-danger">Libur</span>
                                            @endif
                                        </td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" data-id="{{ Crypt::encrypt($subject->id) }}"
                                                data-kegiatan="{{ $subject->kegiatan }}"
                                                data-tanggal_mulai="{{ $subject->tanggal_mulai }}"
                                                data-tanggal_akhir="{{ $subject->tanggal_akhir }}"
                                                data-status_kbm="{{ $subject->status_kbm }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('kalender-akademik.destroy', Crypt::encrypt($subject->id)) }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Kalender Akademik</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('sistem-blok.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="kegiatan" class="form-label">Nama Kegiatan</label>
                            <input type="text" class="form-control" id="kegiatan" name="kegiatan" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_mulai" class="form-label">Dari Tanggal</label>
                            <input type="date" class="form-control" id="tanggal_mulai" name="tanggal_mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="tanggal_akhir" class="form-label">Sampai Tanggal</label>
                            <input type="date" class="form-control" id="tanggal_akhir" name="tanggal_akhir" required>
                        </div>
                        <div class="mb-3">
                            <label for="status_kbm" class="form-label">Status KBM</label>
                            <select name="status_kbm" id="status_kbm" class="form-select">
                                <option value="efektif">Efektif</option>
                                <option value="tidak efektif">Tidak Efektif</option>
                                <option value="libur">Libur</option>
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
            const kegiatan = button.getAttribute('data-kegiatan');
            const tanggalAwal = button.getAttribute('data-tanggal_mulai');
            const tanggalAkhir = button.getAttribute('data-tanggal_akhir');
            const status_kbm = button.getAttribute('data-status_kbm');

            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                form.action = '{{ route('kalender-akademik.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Kalender Akademik';
                subjectId.value = id;
                formMethod.value = 'PUT';
                document.getElementById('kegiatan').value = kegiatan;
                document.getElementById('tanggal_mulai').value = tanggalAwal;
                document.getElementById('tanggal_akhir').value = tanggalAkhir;
                document.getElementById('status_kbm').value = status_kbm;
            } else {
                form.action = '{{ route('kalender-akademik.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Kalender Akademik Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
