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
                        <li class="breadcrumb-item active">Capaian Pembelajaran</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center bg bg-info">
                    <div class="col">
                        <h4 class="card-title">Data Capaian Pembelajaran</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end">
                            {{-- <a href="{{ route('capaian-pembelajaran.export') }}" class="btn btn-info me-2">Export Data</a> --}}
                            {{-- <a href="#" class="btn btn-success me-2">Import Data</a> --}}
                            <!-- Button to trigger modal -->
                            @if(in_array('Tambah', $fiturMenu[$view]))
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCPModal">Tambah
                                Data</button>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example2">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Kode</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Capaian Pembelajaran</th>
                                    <th>Tujuan Pembelajaran</th>
                                    @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($matpel as $key => $item)
                                    @foreach($item->cp as $cp)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->matpel }}</td>
                                        <td>{{ $cp->kode_cp }}</td>
                                        <td>{{ $cp->capaian }}</td>
                                        <td><a href="capaian-pembelajaran/{{ Crypt::encrypt($cp->kode_cp) }}" class="btn btn-sm btn-info">{{ $cp->tp->count() }} Tujuan Pembelajaran</a></td>
                                        @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                        <td>
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addCPModal"  data-id="{{ Crypt::encrypt($cp->kode_cp) }}"
                                                data-cp="{{ base64_encode(json_encode($cp)) }}">
                                                Edit
                                            </button>
                                            @endif
                                            @if($cp->tp->count() < 1)
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('capaian-pembelajaran.destroy', Crypt::encrypt($cp->kode_cp)) }}"
                                                class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
                                            @endif
                                            @endif
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="addCPModal" tabindex="-1" aria-labelledby="addCPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addCPModalLabel">Input Capaian Pembelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="CPForm" action="{{ route('capaian-pembelajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="cpId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <div class="mb-3">
                            <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
                            <select class="form-select" id="kode_matpel" name="kode_matpel" required>
                                <option selected disabled>--- Pilih Mata Pelajaran ---</option>
                                @foreach ($matpel as $item)
                                    <option value="{{ $item->kode_matpel }}">
                                        {{ $item->matpel }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="mb-3">
                            <label for="kode_cp" class="form-label">Kode CP</label>
                            <input type="text" class="form-control" id="kode_cp" name="kode_cp" required>
                        </div>

                        <div class="mb-3">
                            <label for="capaian" class="form-label">Deskripsi Capaian</label>
                            <input type="text" class="form-control" id="capaian" name="capaian" required>
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
        const modal = document.getElementById('addCPModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // tombol yang memicu modal
            const id = button.getAttribute('data-id');
            
            // const tahun_ajaran = decodedData.idtahunajaran;

            const form = document.getElementById('CPForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addCPModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const decodedData = JSON.parse(atob(button.getAttribute('data-cp')));
                const kode_matpel = decodedData.kode_matpel;
                const kode_cp = decodedData.kode_cp;
                const capaian = decodedData.capaian;
                
                form.action = '{{ route('capaian-pembelajaran.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Capaian Pembelajaran';
                formMethod.value = 'PUT';

                // Isi data ke form
                document.getElementById('cpId').value = id;
                document.getElementById('kode_cp').value = kode_cp;
                document.getElementById('capaian').value = capaian;
                document.getElementById('kode_matpel').value = kode_matpel;
            } else {
                form.action = '{{ route('capaian-pembelajaran.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Data Capaian Pembelajaran Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
