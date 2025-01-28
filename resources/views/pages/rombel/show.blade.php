@extends('layouts.app')
@push('styles')
<style>
    .select2-container.readonly .select2-selection {
      background-color: #e9ecef; /* Warna abu-abu untuk efek readonly */
      pointer-events: none;      /* Cegah klik */
      touch-action: none;
    }
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data Rombel</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Rombel</li>
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
                    <h4 class="card-title">Data Siswa {{ $kdkelas }}</h4>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                            Siswa</button>
                        <a href="#" class="btn btn-info me-2">Edit Rombel</a>
                        <a href="#" class="btn btn-danger me-2">Hapus Rombel</a>
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display nowrap" id="example">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Nisn / Nis</th>
                                <th>Nama Siswa</th>
                                <th>Jenis Kelamin</th>
                                <th>Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($rombel as $key => $item)
                                <tr>
                                    <td>{{ $key+1 }}</td>
                                    <td>{{ $item->nisn }}</td>
                                    <td>{{ $item->siswa->nama }}</td>
                                    <td>{{ $item->siswa->jenis_kelamin }}</td>
                                    <td>
                                        <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#movingClassModal" data-id="{{ $item->id }}"
                                            data-nisn="{{ $item->nisn }}"
                                            data-kdkelas="{{ $item->kdkelas }}"
                                            data-idtahunajaran="{{ $item->idtahunajaran }}"
                                            data-subject="pindahkelas">
                                            Pindah Kelas
                                        </button>
                                        <button class="btn btn-info btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#movingClassModal" data-id="{{ $item->id }}"
                                            data-nisn="{{ $item->nisn }}"
                                            data-kdkelas="{{ $item->kdkelas }}"
                                            data-idtahunajaran="{{ $item->idtahunajaran }}"
                                            data-subject="naikkelas">
                                            Naik Kelas
                                        </button>
                                        <a href="{{ route('data-rombel.destroy', $item->id) }}"
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
<!-- Modal untuk Input Mata Pelajaran -->
<div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Input Siswa Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subjectForm" action="{{ route('data-rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran </label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                            @foreach ($tahunajaran as $item)
                                <option value="{{ $item->id }}" {{ $item->id==$idtahunajaran?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kdkelas" class="form-label">Kelas</label>
                        <select name="kdkelas" id="kdkelas" class="form-control">
                            @foreach ($kelas as $item)
                                <option value="{{ $item->kdkelas }}" {{ $item->kdkelas==$kdkelas?'selected':'' }}>{{ $item->kdkelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nisn" class="form-label">Siswa</label>
                        <select name="nisn[]" id="nisn" class="form-control select2" multiple>
                            @foreach ($siswa as $item)
                                <option value="{{ $item->nisn }}">{{ $item->nama }}</option>
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
<div class="modal fade" id="movingClassModal" tabindex="-1" aria-labelledby="movingClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movingClassModalLabel">Pindah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="movingForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectIdMove" name="id">
                    <input type="hidden" id="nisn" name="nisn">
                    <input type="hidden" name="_method" id="formMethodMove" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran </label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                            @foreach ($tahunajaran as $item)
                                <option value="{{ $item->id }}" {{ $item->id==$idtahunajaran?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kdkelas" class="form-label">Kelas</label>
                        <select name="kdkelas" id="kdkelas" class="form-control select2">
                            @foreach ($kelas as $item)
                                <option value="{{ $item->kdkelas }}" {{ $item->kdkelas==$kdkelas?'selected':'' }}>{{ $item->kdkelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtnMove">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const modalAdd = document.getElementById('addSubjectModal');
        modalAdd.addEventListener('show.bs.modal', function(event){
            const form = document.querySelector('#subjectForm');
            form.querySelector("#idtahunajaran").addEventListener('mousedown',function(e){
                e.preventDefault();
            });
            form.querySelector("#kdkelas").addEventListener('mousedown',function(e){
                e.preventDefault();
            });
        });

        const modalMoving = document.getElementById('movingClassModal');
        modalMoving.addEventListener('show.bs.modal', function(event) {
            
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nisn = button.getAttribute('data-nisn');
            const subject = button.getAttribute('data-subject');
            const kdkelas = button.getAttribute('data-kdkelas');
            const idtahunajaran = button.getAttribute('data-idtahunajaran');

            const form = document.querySelector('#movingForm');
            const submitBtn = document.getElementById('submitBtnMove');
            const subjectId = document.getElementById('subjectIdMove');
            const modalTitle = document.getElementById('movingClassModalLabel');
            const formMethod = document.getElementById('formMethodMove');

            if(subject){
                if (subject == "pindahkelas") {
                    form.action = '{{ route('data-rombel.update', ':id') }}'.replace(':id', id);
                    submitBtn.textContent = 'Pindah Kelas';
                    modalTitle.textContent = 'Pindah Kelas';
                    subjectId.value = id;
                    formMethod.value = 'PUT';
                    form.querySelector('#kdkelas').value = kdkelas;
                    form.querySelector('#nisn').value = nisn;
                    form.querySelector('#idtahunajaran').value = idtahunajaran; 
                    form.querySelector('#idtahunajaran').addEventListener('mousedown', function(e){
                        e.preventDefault();
                    });                              
                } else {
                    form.action = '{{ route('data-rombel.levelUpClass', ':id') }}'.replace(':id', id);
                    submitBtn.textContent = 'Naik Kelas';
                    modalTitle.textContent = 'Naik Kelas';
                    subjectId.value = id;
                    formMethod.value = 'POST';
                    form.querySelector('#kdkelas').value = nisn;
                    form.querySelector('#nisn').value = nisn;
                    form.querySelector('#idtahunajaran').value = idtahunajaran;
                    form.querySelector('#idtahunajaran').classList.add('select2');
                }
            }
        });
    </script>
@endpush
