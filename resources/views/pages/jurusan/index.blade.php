@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Jurusan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Jurusan</li>
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
                        <h4 class="card-title">List Data Jurusan</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('data-jurusan.export') }}" class="btn btn-info me-2">Export Data</a>
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addJurusanModal">Tambah
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
                                    <th>Jurusan</th>
                                    <th>Kompetensi</th>
                                    <th>Program Keahlian</th>
                                    <th>Bidang Keahlian</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jurusan as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->jurusan }}</td>
                                        <td>{{ $item->kompetensi }}</td>
                                        <td>{{ $item->program_keahlian }}</td>
                                        <td>{{ $item->bidang_keahlian }}</td>
                                        <td>
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addJurusanModal"  data-id="{{ Crypt::encrypt($item->id) }}"
                                                data-jurusan="{{ base64_encode(json_encode($item)) }}">
                                                Edit
                                            </button>
                                            <a href="{{ route('data-jurusan.destroy', Crypt::encrypt($item->id)) }}"
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

    <div class="modal fade" id="addJurusanModal" tabindex="-1" aria-labelledby="addJurusanModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addJurusanModalLabel">Input Data Jurusan Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="jurusanForm" action="{{ route('data-jurusan.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="jurusanId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">

                        <!-- Input Tahun Ajaran -->
                        {{-- <div class="mb-3">
                            <label for="tahun_ajaran" class="form-label">Tahun Ajaran</label>
                            <select class="form-select" id="tahun_ajaran" name="idtahunajaran" required>
                                <option selected disabled>--- Pilih Tahun Ajaran ---</option>
                                @foreach ($tahun_ajaran as $item)
                                    <option value="{{ base64_encode($item->id) }}">
                                        {{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}
                                        ({{ $item->semester == 'ganjil' ? 'Ganjil' : 'Genap' }})
                                    </option>
                                @endforeach
                            </select>
                        </div> --}}

                        <!-- Input Jurusan -->
                        <div class="mb-3">
                            <label for="jurusan" class="form-label">Nama Jurusan</label>
                            <input type="text" class="form-control" id="jurusan" name="jurusan" required>
                        </div>

                        <!-- Input Kompetensi -->
                        <div class="mb-3">
                            <label for="kompetensi" class="form-label">Kompetensi</label>
                            <input type="text" class="form-control" id="kompetensi" name="kompetensi" required>
                        </div>

                        <!-- Input Program Keahlian -->
                        <div class="mb-3">
                            <label for="program_keahlian" class="form-label">Program Keahlian</label>
                            <input type="text" class="form-control" id="program_keahlian" name="program_keahlian"
                                required>
                        </div>

                        <!-- Input Bidang Keahlian -->
                        <div class="mb-3">
                            <label for="bidang_keahlian" class="form-label">Bidang Keahlian</label>
                            <input type="text" class="form-control" id="bidang_keahlian" name="bidang_keahlian"
                                required>
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
        const modal = document.getElementById('addJurusanModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // tombol yang memicu modal
            const id = button.getAttribute('data-id');
            
            // const tahun_ajaran = decodedData.idtahunajaran;

            const form = document.getElementById('jurusanForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addJurusanModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const decodedData = JSON.parse(atob(button.getAttribute('data-jurusan')));
                const jurusan = decodedData.jurusan;
                const kompetensi = decodedData.kompetensi;
                const program_keahlian = decodedData.program_keahlian;
                const bidang_keahlian = decodedData.bidang_keahlian;
                
                form.action = '{{ route('data-jurusan.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Data Jurusan';
                formMethod.value = 'PUT';

                // Isi data ke form
                document.getElementById('jurusanId').value = id;
                document.getElementById('jurusan').value = jurusan;
                document.getElementById('kompetensi').value = kompetensi;
                document.getElementById('program_keahlian').value = program_keahlian;
                document.getElementById('bidang_keahlian').value = bidang_keahlian;
                // document.getElementById('tahun_ajaran').value = tahun_ajaran;
            } else {
                console.log("ss ss");
                
                form.action = '{{ route('data-jurusan.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Data Jurusan Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
