@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Tahun Ajaran</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Tahun Ajaran</li>
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
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#modalTambahData">Tambah
                                Data</button>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Semester</th>
                                    <th>Tanggal Mulai</th>
                                    <th>Status</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($tahun_ajaran as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</td>
                                        <td>{{ $item->semester == 'ganjil' ? 'Ganjil' : 'Genap' }}</td>
                                        <td>{{ Carbon\Carbon::parse($item->tgl_mulai)->format('d-m-Y') }}</td>
                                        <td>
                                            <form action="{{ route('tahun-ajaran.updateStatus', $item->id) }}"
                                                method="post">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="flexSwitchCheckDefault"
                                                        {{ $item->status == 1 ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                    <span
                                                        class="badge {{ $item->status == 1 ? 'bg-success' : 'bg-danger' }}">{{ $item->status == 1 ? 'Aktif' : 'Tidak Aktif' }}</span>
                                                </div>
                                                <input type="hidden" name="status"
                                                    value="{{ $item->status == 1 ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#modalTambahData" data-id="{{ $item->id }}"
                                                data-awal_tahun_ajaran="{{ $item->awal_tahun_ajaran }}"
                                                data-akhir_tahun_ajaran="{{ $item->akhir_tahun_ajaran }}"
                                                data-semester="{{ $item->semester }}"
                                                data-tgl_mulai="{{ $item->tgl_mulai }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('tahun-ajaran.destroy', $item->id) }}"
                                                class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
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

    <!-- Modal -->
    <div class="modal fade" id="modalTambahData" tabindex="-1" aria-labelledby="modalTambahDataLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalTambahDataLabel">Tambah Tahun Ajaran</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="formTahunAjaran" action="{{ route('tahun-ajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="awal_tahun_ajaran" class="form-label">Tahun Ajaran Awal</label>
                            <input type="number" class="form-control" id="awal_tahun_ajaran" name="awal_tahun_ajaran"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="akhir_tahun_ajaran" class="form-label">Tahun Ajaran Akhir</label>
                            <input type="number" class="form-control" id="akhir_tahun_ajaran" name="akhir_tahun_ajaran"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="semester" class="form-label">Semester</label>
                            <select class="form-select" id="semester" name="semester" required>
                                <option selected disabled>--- Pilih Semester ---</option>
                                <option value="ganjil">Ganjil</option>
                                <option value="genap">Genap</option>
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="tgl_mulai" class="form-label">Tanggal Mulai</label>
                            <input type="date" class="form-control" id="tgl_mulai" name="tgl_mulai" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('modalTambahData');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const awal_tahun_ajaran = button.getAttribute('data-awal_tahun_ajaran');
            const akhir_tahun_ajaran = button.getAttribute('data-akhir_tahun_ajaran');
            const semester = button.getAttribute('data-semester');
            const tgl_mulai = button.getAttribute('data-tgl_mulai');

            const form = document.getElementById('formTahunAjaran');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('modalTambahDataLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                console.log(semester);
                form.action = '{{ route('tahun-ajaran.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Tahun Ajaran';
                subjectId.value = id;
                formMethod.value = 'PUT';
                document.getElementById('awal_tahun_ajaran').value = awal_tahun_ajaran;
                document.getElementById('akhir_tahun_ajaran').value = akhir_tahun_ajaran;
                document.getElementById('semester').value = semester;
                document.getElementById('tgl_mulai').value = tgl_mulai;
            } else {
                form.action = '{{ route('tahun-ajaran.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Tahun Ajaran Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
