@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Tujuan Pembelajaran</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tujuan Pembelajaran</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center bg bg-success">
                    <div class="col-md-6">
                        <div class="row">
                            <div class="col-md-4 fw-bold">Mata Pelajaran</div>
                            <div class="col-md-8">: {{ $cp->matpel->matpel }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 fw-bold">Kode Capaian Pembelajaran</div>
                            <div class="col-md-8">: {{ $cp->kode_cp }}</div>
                        </div>
                        <div class="row">
                            <div class="col-md-4 fw-bold">Deksripsi Capaian Pembelajaran</div>
                            <div class="col-md-8">: {{ $cp->capaian }}</div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="d-flex justify-content-end mb-3">
                            {{-- <a href="{{ route('capaian-pembelajaran.export') }}" class="btn btn-info me-2">Export Data</a> --}}
                            <a href="#" class="btn btn-secondary me-2">Import Data</a>
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addTPModal">Tambah
                                Data</button>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Deksrisi Tujuan Pembelajaran</th>
                                    <th>Kriteria Ketuntasan</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                $no = 1;
                                @endphp
                                @foreach ($cp->tp as $key => $item)
                                    <tr>
                                        <td>{{ $no++ }}</td>
                                        <td>{{ $item->tujuan }}</td>
                                        <td>
                                            <ul>
                                                <li>Maksimal <b>{{ $item->bt1 }}</b> Belum Tuntas, remedial seluruh bagian</li>
                                                <li>Maksimal <b>{{ $item->bt2 }}</b> Belum Tuntas, remedial sebagian</li>
                                                <li>Maksimal <b>{{ $item->t1 }}</b> Tuntas</li>
                                                <li>Maksimal <b>{{ $item->t2 }}</b> Tuntas, perlu pengayaan</li>
                                            </ul>
                                        </td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addTPModal"  data-id="{{ Crypt::encrypt($item->id) }}"
                                                data-tp="{{ base64_encode(json_encode($item)) }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('tujuan-pembelajaran.destroy', Crypt::encrypt($item->id)) }}"
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

    <div class="modal fade" id="addTPModal" tabindex="-1" aria-labelledby="addTPModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addTPModalLabel">Input Tujuan Pembelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="TPForm" action="{{ route('tujuan-pembelajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="tpId" name="id">
                        <input type="hidden" id="kode_cp" name="kode_cp" value="{{ $cp->kode_cp }}">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="tujuan" class="form-label">Deskripsi Tujuan Pembelajaran</label>
                            <input type="text" class="form-control" id="tujuan" name="tujuan" required>
                        </div>
                        <div class="mb-3 text-center fw-bold">
                            ~ Kriteria Ketuntasan Tujuan Pembelajaran ~
                        </div>
                        <div class="mb-3">
                            <label for="bt1" class="form-label">Belum Tuntas (remedial seluruh bagian)</label>
                            <input type="number" min="0" max="100" class="form-control" id="bt1" name="bt1" required>
                        </div>
                        <div class="mb-3">
                            <label for="bt2" class="form-label">Belum Tuntas (remedial bagian)</label>
                            <input type="number" min="0" max="100" class="form-control" id="bt2" name="bt2" required>
                        </div>
                        <div class="mb-3">
                            <label for="t1" class="form-label">Tuntas</label>
                            <input type="number" min="0" max="100" class="form-control" id="t1" name="t1" required>
                        </div>
                        <div class="mb-3">
                            <label for="t2" class="form-label">Tuntas (perlu pengayaan)</label>
                            <input type="number" min="0" max="100" class="form-control" id="t2" name="t2" required>
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
        const modal = document.getElementById('addTPModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // tombol yang memicu modal
            const id = button.getAttribute('data-id');
            
            // const tahun_ajaran = decodedData.idtahunajaran;

            const form = document.getElementById('TPForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addTPModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const decodedData = JSON.parse(atob(button.getAttribute('data-tp')));
                const tujuan = decodedData.tujuan;
                const kode_cp = decodedData.kode_cp;
                const bt1 = decodedData.bt1;
                const bt2 = decodedData.bt2;
                const t1 = decodedData.t1;
                const t2 = decodedData.t2;
                
                form.action = '{{ route('tujuan-pembelajaran.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Tujuan Pembelajaran';
                formMethod.value = 'PUT';

                // Isi data ke form
                document.getElementById('tpId').value = id;
                document.getElementById('tujuan').value = tujuan;
                document.getElementById('bt1').value = bt1;
                document.getElementById('bt2').value = bt2;
                document.getElementById('t1').value = t1;
                document.getElementById('t2').value = t2;
            } else {
                form.action = '{{ route('tujuan-pembelajaran.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Data Tujuan Pembelajaran Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
