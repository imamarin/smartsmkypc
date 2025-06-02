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
                        <li class="breadcrumb-item active">Data Nilai Raport</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center" style="background-color: rgb(31, 177, 188)">
                    <div class="col">
                        <h4 class="card-title text-white">Data Nilai Raport</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end">
                            <!-- Button to trigger modal -->
                            <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Data Nilai Raport</button>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example2">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th>Semester</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($nilairaport as $key => $item)
                                <tr>
                                    <td>{{ $key + 1 }}</td>
                                    <td>{{ $item->matpel->matpel }}</td>
                                    <td>{{ $item->kelas->kelas }}</td>
                                    <td>{{ $item->semester }}</td>
                                    <td>{{ $item->tahunajaran->awal_tahun_ajaran }} / {{ $item->tahunajaran->akhir_tahun_ajaran }}</td>
                                    <td>
                                        <a href="{{ route('detail-nilai-raport.input', Crypt::encrypt($item->id)) }}"
                                            class="btn btn-sm btn-primary">Input Nilai Raport</a>
                                        <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                            data-bs-target="#addSubjectModal"  data-id="{{ Crypt::encrypt($item->id) }}"
                                            data-matpel="{{ base64_encode($item->kode_matpel) }}"
                                            data-kelas="{{ base64_encode($item->idkelas) }}"
                                            data-semester="{{ base64_encode($item->semester) }}"
                                            data-tahunajaran="{{ base64_encode($item->idtahunajaran) }}">
                                            Edit
                                        </button>
                                        <a href="{{ route('nilai-raport.destroy', Crypt::encrypt($item->id)) }}"
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Nilai Raport Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="nilairaportForm" action="{{ route('nilai-raport.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="nilairaportId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="idkelas" class="form-label">Kelas</label>
                            <select name="idkelas" id="idkelas" class="form-select select2">
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->idkelas }}">{{ $item->kelas->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
                            <select name="kode_matpel" id="kode_matpel" class="form-select select2">
                                @foreach ($matpel as $item)
                                    <option value="{{ $item->kode_matpel }}">{{ $item->matpel->matpel }}</option>
                                @endforeach
                            </select>
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
        // Script untuk mempersiapkan data saat modal dibuka
        const modal = document.getElementById('addSubjectModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // tombol yang memicu modal
            const id = button.getAttribute('data-id');
            
            // const tahun_ajaran = decodedData.idtahunajaran;

            const form = document.getElementById('nilairaportForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const matpel = atob(button.getAttribute('data-matpel'));
                const kelas = atob(button.getAttribute('data-kelas'));

                form.action = '{{ route('nilai-raport.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Data Nilai Raport';
                formMethod.value = 'PUT';
                
                // Isi data ke form
                document.getElementById('nilairaportId').value = id;
                document.getElementById('kode_matpel').value = matpel;
                document.getElementById('idkelas').value = kelas;
            } else {
                form.action = '{{ route('nilai-raport.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Nilai Raport Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
