@extends('layouts.app')
@push('styles')
<style>
.nav-link.active{
    background-color: rgb(243, 216, 60) !important;
    color: black !important;
}
</style>
@endpush
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Pengolahan Nilai Siswa</h4>

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
            <div class="card-header d-flex align-items-center bg bg-info">
                <div class="col">
                    <ul class="nav nav-pills">
                        <li class="nav-item">
                            <a class="nav-link {{ $kategori == 'tugas' ? 'active' : '' }}" data-bs-toggle="pill" href="#tugas">Nilai Tugas</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link {{ $kategori == 'harian' ? 'active' : '' }}" data-bs-toggle="pill" href="#harian">Nilai Ujian Harian</a>
                        </li>
                        <li class="nav-item">
                          <a class="nav-link {{ $kategori == 'uts' ? 'active' : '' }}" data-bs-toggle="pill" href="#uts">Nilai UTS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $kategori == 'uas' ? 'active' : '' }}" data-bs-toggle="pill" href="#uas">Nilai UAS</a>
                        </li>
                        <li class="nav-item">
                            <a class="nav-link {{ $kategori == 'us' ? 'active' : '' }}" data-bs-toggle="pill" href="#us">Nilai US</a>
                        </li>
                    </ul>
                    {{-- <h4 class="card-title">List Rombel</h4> --}}
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end">
                        <!-- Button to trigger modal -->
                        @if(in_array('Tambah', $fiturMenu[$view]))
                        <button class="btn btn-sm btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Data Nilai</button>
                        @endif
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="tab-content">
                    @foreach ($data_kategori as $value)
                    <div class="tab-pane {{ $kategori == $value ? 'active' : 'fade' }}" id="{{ $value }}">
                        <div class="table-responsive">
                            <table class="table display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>Kelas</th>
                                        <th>Mata Pelajaran</th>
                                        @if($value != 'us')
                                        <th>Tanggal Pelaksanaan</th>
                                        @endif
                                        <th>Nilai Rata-Rata</th>
                                        <th>Keterangan</th>
                                        @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]) || in_array('input', $fiturMenu[$view]))                                        
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($$value as $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->kelas->kelas }}</td>
                                        <td>{{ $item->matpel->matpel }}</td>
                                        @if($value != 'us')
                                        <td>{{ date('d-m-Y', strtotime($item->tanggal_pelaksanaan)) }}</td>
                                        @endif
                                        <td>{{ floor($item->detailnilaisiswa_avg_nilai) ?? 0 }}</td>
                                        <td>{{ $item->keterangan }}</td>
                                        @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]) || in_array('Input', $fiturMenu[$view]))                                        
                                        <td>
                                            @if(in_array('Input', $fiturMenu[$view]))
                                            <a href="{{ route('nilai-siswa.input', ['kategori' => $item->kategori, 'id' => Crypt::encrypt($item->id)]) }}" class="btn btn-sm btn-secondary">Input Nilai Siswa</a>
                                            @endif
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <button class="btn btn-sm btn-info" id="editModal"
                                                data-id = "{{ Crypt::encrypt($item->id) }}"
                                                data-nilai = "{{ base64_encode(json_encode($item)) }}"
                                                data-bs-toggle="modal" data-bs-target="#addSubjectModal">Edit</button>
                                            @endif
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('nilai-siswa-destroy', Crypt::encrypt($item->id)) }}" class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
                                            @endif
                                            <button class="btn btn-sm btn-info cptpModal" id="cptpModal"
                                                    data-id = "{{ Crypt::encrypt($item->id) }}"
                                                    data-nilai = "{{ base64_encode(json_encode($item)) }}"
                                                    data-bs-toggle="modal" data-bs-target="#cptpSubjectModal">CP & TP</button>
                                        </td>
                                        @endif
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                    @endforeach
                    
                
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
                            <option value="harian">Ujian Harian</option>
                            <option value="uts">Ujian Tengah Semester</option>
                            <option value="uas">Ujian Akhir Semester</option>
                            <option value="us">Ujian Sekolah</option>
                        </select>
                    </div>
                    <div class="mb-3" id="class-all">
                        <label for="idkelas" class="form-label">Kelas</label>
                        <select name="idkelas" id="idkelas-all" class="form-control select2">
                            @foreach ($kelas as $item)
                            <option value="{{ $item->idkelas }}">{{ $item->kelas->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3" id="class-xii">
                        <label for="idkelas" class="form-label">Kelas</label>
                        <select name="idkelas" id="idkelas-xii" class="form-control select2">
                            @foreach ($kelas as $item)
                            @if($item->kelas->tingkat == 'XII')
                            <option value="{{ $item->idkelas }}">{{ $item->kelas->kelas }}</option>
                            @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
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
</div>

<div class="modal fade" id="cptpSubjectModal" tabindex="-1" aria-labelledby="cptpSubjectModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="tpSubjectModalLabel">Data Nilai Siswa Kurikulum Merdeka</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="formCPTP" action="{{ route('nilai-siswa-kurmer-store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" id="kategori" name="kategori">
                    <input type="hidden" id="idnilaisiswa" name="idnilaisiswa">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="cp" class="form-label">Capaian Pembelajaran</label>
                        <select name="cp" id="cp" class="form-control select2" required>
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="tp" class="form-label">Tujuan Pembelajaran</label>
                        <select name="idtujuanpembelajaran" id="tp" class="form-control select2" required>
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
    const modal = $('#addSubjectModal');
    const navKategori = $('.nav-link');
    let kategori = '{{ $kategori }}';

    if(kategori == 'harian'){
        $('.cptpModal').show();
    }else{
        $('.cptpModal').hide();
    }

    console.log(kategori);
    
    modal.on('show.bs.modal', function(event) {
        const data = event.relatedTarget;
        const id = data.getAttribute('data-id');
        const form = $('#formNilaiSiswa');
        var idkelas;
        if(kategori == 'us'){
            $('#class-all').hide(); 
            $('#class-xii').show(); 
            idkelas = $('#idkelas-xii');
        }else{
            $('#class-xii').hide(); 
            $('#class-all').show();
            idkelas = $('#idkelas-all');
        }
        if(id){
            form.attr("action", '{{ route('nilai-siswa-update', ':id') }}'.replace(':id', id));
            const decodedData = JSON.parse(atob(data.getAttribute('data-nilai')));
            $('#kategori').val(decodedData.kategori);
            idkelas.val(decodedData.idkelas);
            $('#kode_matpel').val(decodedData.kode_matpel);
            $('#tanggal_pelaksanaan').val(decodedData.tanggal_pelaksanaan);
            $('#keterangan').val(decodedData.keterangan);
            $('#submitBtn').text('Ubah');
            idkelas.prop("disabled", true)
        }else{
            form.attr("action", '{{ route('nilai-siswa-store') }}');
            $('#submitBtn').text('Simpan');
            idkelas.prop("disabled", false)
            form.trigger("reset");
            $('#kategori').val(kategori);
        }
        
    });

    $(document).ready(function () {
        $('table').each(function () {
            $(this).DataTable();
        });

        navKategori.on('click', function(event){
            console.log($(this).html());
            $(".cptpModal").hide();
            if($(this).html() === 'Nilai Ujian Harian'){
                kategori = 'harian';
                $(".cptpModal").show();
            }else if($(this).html() === 'Nilai Tugas'){
                kategori = 'tugas';
            }else if($(this).html() === 'Nilai UTS'){
                kategori = 'uts';
            }else if($(this).html() === 'Nilai UAS'){
                kategori = 'uas';
            }else if($(this).html() === 'Nilai US'){
                kategori = 'us';
            }
        });
    });


    const cptp = $('#cptpSubjectModal');
    cptp.on('show.bs.modal', function(event) {
        const data = event.relatedTarget;
        const decodedData = JSON.parse(atob(data.getAttribute('data-nilai')));
        $('#formCPTP #kategori').val(kategori);
        $('#formCPTP #idnilaisiswa').val(decodedData.id);
        let url = '{{ route('nilai-siswa.getCP', ':id') }}'.replace(':id', decodedData.kode_matpel);

        $.get(url, function(data,status){
            
            $('#cp').html("");
            if(data.length > 0){
                var key = 0;
                data.forEach((element, index) => {
                    let isSelected = decodedData.kurmer && element.tp.find(item => item.id === decodedData.kurmer.idtujuanpembelajaran);
                    let option = new Option(element.capaian, element.kode_cp, false, isSelected);
                    $('#cp').append(option);
                    if(isSelected){
                        key = index;
                    }
                });

                $('#tp').html("");
                if(data[key].tp.length > 0){
                    data[key].tp.forEach(element => {
                        let isSelected = decodedData.kurmer && element.id === decodedData.kurmer.idtujuanpembelajaran;
                        let option = new Option(element.tujuan, element.id, false, isSelected);
                        $('#tp').append(option);
                    });
                }
            }
        });
    });

    $('#cp').on('change',function(event){
        let url = '{{ route('nilai-siswa.getTP', ':id') }}'.replace(':id', $('#cp').val());
        $.get(url, function(data,status){
            $('#tp').html("");
            console.log(data);
            
            if(data.length > 0){
                data.forEach(element => {
                    let option = new Option(element.tujuan, element.id);
                    $('#tp').append(option);
                });
            }
        });
    });
</script>
@endpush
