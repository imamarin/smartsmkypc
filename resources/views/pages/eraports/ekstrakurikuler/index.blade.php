@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Ekstrakurikuler</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Ekstrakuriluer</li>
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
                            <form action="{{ route('ekstrakurikuler.store') }}" method="post">
                                @csrf
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="nama" class="form-label">Ekstrakuriluer</label>
                                        <input type="text" name="nama" id="nama" class="form-control" required>
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
                                    <th>Ekstrakurikuler</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ekstrakurikuler as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->nisn_dapodik }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal"  data-id="{{ Crypt::encrypt($subject->id) }}"
                                                data-nama="{{ $subject->nama }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('ekstrakurikuler.destroy', Crypt::encrypt($subject->id)) }}"
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
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Edit Ekstrakurikuler</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="ekstrakurikulerForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="ekstrakurikulerId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Ekstrakurikuler</label>
                            <input type="text" class="form-control" id="edit-nama" name="nama" required>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Ubah</button>
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
        const form = document.getElementById('ekstrakurikulerForm');
        const button = event.relatedTarget;
        const nama = button.getAttribute('data-nama');
        const id = button.getAttribute('data-id');

        document.getElementById('formMethod').value = "PUT";
        document.getElementById('edit-nama').value = nama;
        form.action = '{{ route('ekstrakurikuler.update', ':id') }}'.replace(':id', id);
    });
</script>
@endpush
