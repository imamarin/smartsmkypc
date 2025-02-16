@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Walikelas</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Walikelas</li>
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
                        <form action="{{ route('data-walikelas.tahunajaran') }}" method="post">
                            @csrf
                            <div class="row">
                                <div class="col-12 col-md-3">
                                    <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                                    <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                        @foreach ($tahunajaran as $item)
                                            <option value="{{ encryptSmart($item->id) }}" {{ $item->id == $idtahunajaran ? 'selected':'' }}>
                                                {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-12 col-md-3 d-flex align-items-end mb-1">
                                    <input type="submit" class="btn btn-primary" value="Tampilkan">
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="#" class="btn btn-info me-2">Export Data</a>
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            <!-- Button to trigger modal -->
                            {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Walikelas</button> --}}
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kelas</th>
                                    <th>Walikelas</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($walikelas as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->tahunajaran->awal_tahun_ajaran }}/{{ $subject->tahunajaran->akhir_tahun_ajaran }}</td>
                                        <td>{{ $subject->kelas }}</td>
                                        <td>{{ $subject->walikelas[0]->staf->nama ?? '-' }}</td>
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            <button class="btn {{ $subject->walikelas != null ? 'btn-secondary' : 'btn-primary' }} btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" 
                                                data-id="{{  isset($subject->walikelas[0]->id) ? Crypt::encrypt($subject->walikelas[0]->id) : '' }}"
                                                data-kodestaf="{{  $subject->walikelas[0]->nip ?? '' }}" 
                                                data-idtahunajaran="{{ encryptSmart($subject->idtahunajaran) }}"
                                                data-tahunajaran="{{ $subject->tahunajaran->awal_tahun_ajaran }}/{{ $subject->tahunajaran->akhir_tahun_ajaran }}"
                                                data-kelas="{{ $subject->kelas }}"
                                                {{-- data-rombel="{{ $subject->id }}" --}}
                                                data-idkelas="{{ encryptSmart($subject->id) }}">
                                                {{ isset($subject->walikelas[0]) ? 'Edit Walikelas' : 'Tambahkan Walikelas' }}
                                            </button>
                                            @if(isset($subject->walikelas[0]) != null)
                                            <a href="{{ route('data-walikelas.destroy', isset($subject->walikelas[0]->id) ? Crypt::encrypt($subject->walikelas[0]->id) : '') }}" class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
                                            @endif
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Tambahkan Walikelas</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('data-walikelas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        {{-- <input type="hidden" id="idrombel" name="idrombel"> --}}
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Tahun Ajaran </label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idkelas" class="form-label">Kelas</label>
                            <select name="idkelas" id="idkelas" class="form-control">
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="nip" class="form-label">Staf</label>
                            <select name="nip" id="nip" class="form-control select2">
                                @foreach ($staf as $item)
                                    <option value="{{ $item->nip }}">{{ $item->nama }}</option>
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
            const kodestaf = button.getAttribute('data-kodestaf');
            const idkelas = button.getAttribute('data-idkelas');
            const kelas = button.getAttribute('data-kelas');
            const idtahunajaran = button.getAttribute('data-idtahunajaran');
            const tahunajaran = button.getAttribute('data-tahunajaran');
            // const idrombel = button.getAttribute('data-rombel');

            const form = document.querySelector('#subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if(id){
                form.action = '{{ route('data-walikelas.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Edit';
                modalTitle.textContent = 'Edit Walikelas';
                subjectId.value = id;
                formMethod.value = 'PUT';
                // form.querySelector('#idrombel').value = idrombel;
                form.querySelector('#idkelas').innerHTML = "";
                let optionkelas = new Option(kelas, idkelas);
                form.querySelector('#idkelas').add(optionkelas);
                form.querySelector('#nip').value = kodestaf;
                form.querySelector('#idtahunajaran').innerHTML = "";
                let optiontahunajaran = new Option(tahunajaran, idtahunajaran);
                form.querySelector('#idtahunajaran').add(optiontahunajaran);
            }else{
                form.action = '{{ route('data-walikelas.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Tambahkan Walikelas';
                formMethod.value = 'POST';
                form.querySelector('#idkelas').innerHTML = "";
                let optionkelas = new Option(kelas, idkelas);
                form.querySelector('#idkelas').add(optionkelas);
                form.querySelector('#idtahunajaran').innerHTML = "";
                let optiontahunajaran = new Option(tahunajaran, idtahunajaran);
                form.querySelector('#idtahunajaran').add(optiontahunajaran);
            }
        });
    </script>
@endpush
