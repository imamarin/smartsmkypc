@extends('layouts.app')
@push('styles')
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Rekap Pengolahan Nilai Siswa</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Pengolahan Nilai Siswa</li>
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
                    <h4 class="card-title">Rekap Nilai Siswa</h4>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        <!-- Button to trigger modal -->
                        {{-- <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Data Nilai</button> --}}
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="tab-content">
                    <div class="table-responsive">
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->kelas->kelas }}</td>
                                    <td>{{ $item->matpel->matpel }}</td>
                                    <td>
                                        {{-- <a href="{{ route('nilai-siswa.input', ['kategori' => $item->kategori, 'id' => Crypt::encrypt($item->id)]) }}" class="btn btn-sm btn-secondary">Input Nilai Siswa</a>
                                        <button class="btn btn-sm btn-info" id="editModal"
                                            data-id = "{{ Crypt::encrypt($item->id) }}"
                                            data-nilai = "{{ base64_encode(json_encode($item)) }}"
                                            data-bs-toggle="modal" data-bs-target="#addSubjectModal">Edit</button>
                                        <a href="{{ route('nilai-siswa-destroy', Crypt::encrypt($item->id)) }}" class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a> --}}
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
</div>
<!-- Modal untuk Input Mata Pelajaran -->
{{-- <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addSubjectModalLabel">Data Nilai Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formNilaiSiswa" action="{{ route('nilai-siswa-store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="kategori" class="form-label">Kategori Nilai</label>
                        <select name="kategori" id="kategori" class="form-control select2">
                            <option value="tugas">Tugas</option>
                            <option value="sumatif">Sumatif</option>
                            <option value="uts">Ujian Tengah Semester</option>
                            <option value="uas">Ujian Akhir Semester</option>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idkelas" class="form-label">Kelas</label>
                        <select name="idkelas" id="idkelas" class="form-control select2">
                            @foreach ($kelas as $item)
                            <option value="{{ $item->idkelas }}">{{ $item->kelas->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="matpel" class="form-label">Mata Pelajaran</label>
                        <select name="kode_matpel" id="kode_matpel" class="form-control select2">
                            @foreach ($matpel as $item)
                            <option value="{{ $item->kode_matpel }}">{{ $item->matpel->matpel }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tanggal_pelaksanaan" class="form-label">Tanggal Pelaksanaan</label>
                        <input type="date" name="tanggal_pelaksanaan" id="tanggal_pelaksanaan" value="{{ date('Y-m-d') }}" class="form-control">
                    </div>
                    <div class="mb-3">
                        <label for="keterangan" class="form-label">Keterangan</label>
                        <textarea name="keterangan" id="keterangan" class="form-control" rows="10"></textarea>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div> --}}
@endsection
@push('scripts')
<script>
    const modal = $('#addSubjectModal');
    const navKategori = $('.nav-link');
    let kategori = 'tugas';

    modal.on('show.bs.modal', function(event) {
        const data = event.relatedTarget;
        const id = data.getAttribute('data-id');
        const form = $('#formNilaiSiswa');
        if(id){
            form.attr("action", '{{ route('nilai-siswa-update', ':id') }}'.replace(':id', id));
            const decodedData = JSON.parse(atob(data.getAttribute('data-nilai')));
            $('#kategori').val(decodedData.kategori);
            $('#idkelas').val(decodedData.idkelas);
            $('#kode_matpel').val(decodedData.kode_matpel);
            $('#tanggal_pelaksanaan').val(decodedData.tanggal_pelaksanaan);
            $('#keterangan').val(decodedData.keterangan);
            $('#submitBtn').text('Ubah');
            $('#idkelas').prop("disabled", true)
        }else{
            form.attr("action", '{{ route('nilai-siswa-store') }}');
            $('#submitBtn').text('Simpan');
            $('#idkelas').prop("disabled", false)
            form.trigger("reset");
            $('#kategori').val(kategori);
        }
        
    });

    navKategori.on('click', function(event){
        if($(this).html() === 'Nilai Sumatif'){
            kategori = 'sumatif';
        }else if($(this).html() === 'Nilai Tugas'){
            kategori = 'tugas';
        }else if($(this).html() === 'Nilai UTS'){
            kategori = 'uts';
        }else if($(this).html() === 'Nilai UAS'){
            kategori = 'uas';
        }
    })

    $(document).ready(function () {
        $('table').each(function () {
            $(this).DataTable();
        });
    });
</script>
@endpush
