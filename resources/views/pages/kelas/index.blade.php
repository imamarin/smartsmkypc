@extends('layouts.app')
@push('styles')
@endpush

@section('content')
<!-- start page title -->
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data Kelas</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Kelas</li>
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
                    <h4 class="card-title">Data Kelas</h4>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        <a href="{{ route('data-kelas.export') }}" class="btn btn-info me-2">Export Data</a>
                        <a href="#" class="btn btn-success me-2">Import Data</a>
                        <!-- Button to trigger modal -->
                        <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                            Data</button>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display nowrap" id="example2">
                        <thead>
                            <tr>
                                <th style="width: 5%">#</th>
                                <th>Tahun Ajaran</th>
                                <th>Kelas</th>
                                <th>Tingkat</th>
                                <th>Jurusan</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelas as $key => $item)
                            <tr>
                                <td>{{ $key + 1 }}</td>
                                <td>{{ $item->tahunajaran->awal_tahun_ajaran }}/{{ $item->tahunajaran->akhir_tahun_ajaran }}
                                </td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->tingkat }}
                                </td>
                                <td>{{ $item->jurusan->jurusan }}</td>
                                <td>
                                    <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                        data-bs-target="#addSubjectModal" data-id="{{ Crypt::encrypt($item->id) }}" 
                                        data-kelas="{{ $item->kelas }}"
                                        data-tahun_ajaran="{{ encryptSmart($item->tahunajaran->id) }}" data-tingkat="{{ $item->tingkat }}"
                                        data-jurusan="{{ encryptSmart($item->jurusan->id) }}">
                                        Edit
                                    </button>
                                    <a href="{{ route('data-kelas.destroy', Crypt::encrypt($item->id)) }}"
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

<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Input Data Kelas Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="kelasForm" action="{{ route('data-kelas.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">

                    <div class="mb-3">
                        <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                        <select class="form-select" id="tahun_ajaran" name="idtahunajaran" required>
                            <option selected disabled>--- Pilih Tahun Ajaran ---</option>
                            @foreach ($tahun_ajaran as $tahun)
                            <option value="{{ encryptSmart($tahun->id) }}">
                                {{ $tahun->awal_tahun_ajaran }}/{{ $tahun->akhir_tahun_ajaran }}
                                ({{ $tahun->semester == 'ganjil' ? 'Ganjil' : 'Genap' }})
                            </option>
                            @endforeach
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="kelas" class="form-label">Kelas</label>
                        <input type="text" class="form-control" id="kelas" name="kelas" required>
                    </div>

                    <div class="mb-3">
                        <label for="tingkat" class="form-label">Tingkat</label>
                        <select class="form-select" id="tingkat" name="tingkat" required>
                            <option selected disabled>--- Pilih Tingkat ---</option>
                            <option value="X">10</option>
                            <option value="XI">11</option>
                            <option value="XII">12</option>
                        </select>
                    </div>

                    <div class="mb-3">
                        <label for="jurusan" class="form-label">Jurusan</label>
                        <select class="form-select" id="jurusan" name="idjurusan" required>
                            <option selected disabled>--- Pilih Jurusan ---</option>
                            @foreach ($jurusan as $item)
                            <option value="{{ encryptSmart($item->id) }}">{{ $item->jurusan }}</option>
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
            const button = event.relatedTarget; // Tombol yang memicu modal
            const id = button.getAttribute('data-id');

            const form = document.getElementById('kelasForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const tahun_ajaran = button.getAttribute('data-tahun_ajaran');
                const kelas = button.getAttribute('data-kelas');
                const tingkat = button.getAttribute('data-tingkat');
                const jurusan = button.getAttribute('data-jurusan');
                
                form.action = '{{ route('data-kelas.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Data Kelas';
                formMethod.value = 'PUT';

                // Isi data ke form
                document.getElementById('subjectId').value = id;
                document.getElementById('kelas').value = kelas;
                document.getElementById('tingkat').value = tingkat;
                document.getElementById('jurusan').value = jurusan;
                document.getElementById('tahun_ajaran').value = tahun_ajaran;
            } else {
                form.action = '{{ route('data-kelas.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Data Kelas Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
