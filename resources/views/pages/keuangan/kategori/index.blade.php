@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0"></h4>

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
                <div class="card-header d-flex align-items-center" style="background-color: #494f4f ;">
                    <h4 class="card-title text-white">Data Kategori Keuangan</h4>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            @if(in_array('Eksport', $fiturMenu[$view]))
                            {{-- <a href="{{ route('kategori-keuangan.export') }}" class="btn btn-info me-2">Export Data</a> --}}
                            @endif
                            {{-- @if(in_array('Import', $fiturMenu[$view]))
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            @endif --}}
                            <!-- Button to trigger modal -->
                            @if(in_array('Tambah', $fiturMenu[$view]))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah
                                Data</button>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Keuangan</th>
                                    <th>Nominal Biaya</th>
                                    <th>Jurusan</th>
                                    <th>Tahun Ajaran</th>
                                    @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategorikeuangan as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->nama }}</td>
                                        <td>{{ $subject->biaya }}</td>
                                        <td>{{ $subject->jurusan }}</td>
                                        <td>{{ $subject->tahunajaran->awal_tahun_ajaran.'/'.$subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                        @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]))
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" data-id="{{ $subject->id }}"
                                                data-nama="{{ $subject->nama }}"
                                                data-biaya="{{ $subject->biaya }}"
                                                data-jurusan="{{ $subject->jurusan }}"
                                                data-idtahunajaran="{{ $subject->idtahunajaran }}">
                                                Edit
                                            </button>
                                            @endif
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('kategori-keuangan.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
                                            @endif
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

    <!-- Modal untuk Input Mata Pelajaran -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Kategori Keuangan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('kategori-keuangan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="nama" class="form-label">Nama Kategori</label>
                            <input type="text" class="form-control" id="nama" name="nama" required>
                        </div>
                        <div class="mb-3">
                            <label for="biaya" class="form-label">Nominal Biaya</label>
                            <input type="text" class="form-control" id="biaya" name="biaya" required>
                        </div>
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Jurusan</label>
                            <select name="jurusan" id="jurusan" class="form-select select2">
                                <option value="semua" selected>Semua</option>
                                @foreach ($jurusan as $item)
                                    <option value="{{ $item->jurusan }}">{{ $item->kompetensi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                @foreach ($tahunajaran as $item)
                                    <option value="{{ $item->id }}">{{ $item->awal_tahun_ajaran.'/'.$item->akhir_tahun_ajaran }}</option>
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
            const nama = button.getAttribute('data-nama');
            const biaya = button.getAttribute('data-biaya');
            const jurusan = button.getAttribute('data-jurusan');
            const idtahunajaran = button.getAttribute('data-idtahunajaran');
            
            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                form.action = '{{ route('kategori-keuangan.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Edit Kategori Keuangan';
                subjectId.value = id;
                formMethod.value = 'PUT';
                console.log(id);
                
                document.getElementById('subjectId').value = id;
                document.getElementById('nama').value = nama;
                document.getElementById('biaya').value = biaya;
                document.getElementById('jurusan').value = jurusan;
                document.getElementById('idtahunajaran').value = idtahunajaran;
            } else {
                form.action = '{{ route('kategori-keuangan.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Kategori Keuangan Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
