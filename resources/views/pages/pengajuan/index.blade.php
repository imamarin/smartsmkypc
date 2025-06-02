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
                        <li class="breadcrumb-item active">Pengajuan Kehadiran Mengajar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center" style="background-color: #cbd931 ;">
                    <h3 class="card-title">Pengajuan Kehadiran Mengajar</h3>
                </div><!-- end card header -->
                <div class="card-body">
                    @if(isset($staf))
                    <div class="row">
                        <div class="col-12 col-md-4">
                            <div class="row">
                                <div class="col-6 col-md-5"><h6>NIP</h6></div>
                                <div class="col-6 col-md-7"><h6>: {{ $staf->nip }}</h6></div>
                                <hr>
                            </div>
                            <div class="row">
                                <div class="col-6 col-md-5"><h6>Nama Guru</h6></div>
                                <div class="col-6 col-md-7"><h6>: {{ $staf->nama }}</h6></div>
                                <hr>
                            </div>
                        </div>
                    </div>
                    @endif
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Guru</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Tanggal Mengajar</th>
                                    <th>Status Pengajauan</th>
                                    @if(in_array('Proses Pengajuan', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($ajuan as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->jadwalmengajar->staf->nama }}</td>
                                        <td>{{ $subject->jadwalmengajar->kelas->kelas }}</td>
                                        <td>{{ $subject->jadwalmengajar->matpel->matpel }}</td>
                                        <td>{{ $subject->tanggal_mengajar }}</td>
                                        <td>
                                            @if($subject->status == '2')
                                            <span class="badge bg-success">Diterima</span>
                                            @elseif($subject->status == '1')
                                            <span class="badge bg-info">Revisi Pengajuan</span>
                                            @else
                                            <span class="badge bg-warning">Menunggu Persetujuan</span>
                                            @endif
                                        </td>
                                        @if(in_array('Proses Pengajuan', $fiturMenu[$view]))
                                        <td>
                                            <a href="javascript:void(0)" class="btn btn-sm btn-primary"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal"
                                                data-id="{{ Crypt::encrypt($subject->id) }}"
                                                data-tanggal="{{ $subject->tanggal_mengajar }}"
                                                data-jam="{{ $subject->jadwalmengajar->jampel->jam }}"
                                                data-kelas="{{ $subject->jadwalmengajar->kelas->kelas }}"
                                                data-matpel="{{ $subject->jadwalmengajar->matpel->matpel }}"
                                                data-alasan = "{{ $subject->alasan }}"
                                                data-bukti = "{{ $subject->bukti_file }}"
                                                data-tanggapan = "{{ $subject->tanggapan }}"
                                                >Proses Pengajuan</a>
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
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Pengajuan Kehadiran Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    
                    <div class="row mb-3">
                        <div for="idkelas" class="col-md-3 fw-semibold">Kelas</div>
                        <div id="idkelas" class="col-md-9">: -</div>
                    </div>
                    <div class="row mb-3">
                        <div for="matpel" class="col-md-3 fw-semibold">Mata Pelajaran</div>
                        <div id="matpel" class="col-md-9">: -</div>
                    </div>
                    <div class="row mb-3">
                        <div for="tanggal" class="col-md-3 fw-semibold">Tanggal</div>
                        <div id="tanggal" class="col-md-9">: -</div>
                    </div>
                    <div class="row mb-3">
                        <div for="jam" class="col-md-3 fw-semibold">Jam Ke</div>
                        <div id="jam" class="col-md-9">: -</div>
                    </div>
                    <div class="mb-3" id="lihat_alasan">
                        <label for="lihat_alasan" class="fw-semibold">Alasan Tidak Melakukan Presensi: </label><br>
                        <p></p>
                    </div>
                    <div class="mb-3" id="lihat_bukti_mengajar">
                        <label for="download_bukti" class="fw-semibold">Bukti Mengajar: </label><br>
                        <a href="" class="btn btn-sm btn-success" id="download_bukti" download>Download Bukti Mengajar</a>
                    </div>
                    <div class="mb-3" >
                        <label for="tanggapan" class="fw-semibold">Catatan: </label><br>
                        <textarea id="tanggapan" name="tanggapan" class="form-control"></textarea>
                    </div>
                    <div class="mb-3" >
                        <label for="status_ajuan" class="fw-semibold">Status: </label><br>
                        <select id="status_ajuan" name="status_ajuan" class="form-select">
                            <option value="1">Revisi</option>
                            <option value="2">Disetujui</option>
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Proses Pengajuan</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        // const history = document.querySelector('#addSubjectModal');
        // history.addEventListener('show.bs.modal', function(event){
        //     const button = event.relatedTarget;
        //     const id = button.getAttribute('data-id');
        //     const matpel = button.getAttribute('data-matpel');
        //     const kelas = button.getAttribute('data-kelas');

        //     document.querySelector('#matpel').textContent = matpel;
        //     document.querySelector('#kelas').textContent = kelas;
        //     let tbodyHistory = document.querySelector('#tbodyHistory');
        //     $.get('{{ route('history-presensi', ':id') }}'.replace(':id',id), function(data, status){
        //         tbodyHistory.innerHTML = "";               
        //         data.data.forEach((element, index) => {
        //             let no = index + 1
        //             let url = '{{ route('show-presensi.tanggal', ['id'=>':id','tgl'=>':tgl']) }}'.replace(':id', element.id).replace(':tgl', element.created_at)
        //             tbodyHistory.innerHTML += "<tr>"+
        //                 "<td>"+no+"</td>"+
        //                 "<td>" + element.created_at + "</td>"+
        //                 "<td><a href='"+url+"' class='btn btn-sm btn-primary'>Ubah Presensi</a></td>"+
        //                 "</tr>";
        //         });
                
        //     })
        // })

    $(document).ready(function() {
        $('#example3').DataTable({
            searching: false,
            lengthChange: false
        });

        $('#addSubjectModal').on('show.bs.modal', function(event){
            const data = event.relatedTarget;
            const id = data.getAttribute('data-id');
            const idkelas = data.getAttribute('data-kelas');
            const matpel = data.getAttribute('data-matpel');
            const jam = data.getAttribute('data-jam');
            const tanggal = data.getAttribute('data-tanggal');
  
            $('#subjectId').val(id);
            $('#idkelas').text(': '+idkelas);
            $('#matpel').text(': '+matpel);
            $('#jam').text(': '+jam);
            $('#tanggal').text(': '+tanggal);
            
            $('#subjectForm').attr('action','{{ route('ajuan-kehadiran-mengajar.update',':id') }}'.replace(':id', id));
            $('#lihat_bukti_mengajar').show();
            $('#lihat_alasan').show();
            $('#lihat_alasan p').text(data.getAttribute('data-alasan'));
            $('#tanggapan').show();
            $('#tanggapan').text(data.getAttribute('data-tanggapan'));
            if(data.getAttribute('data-bukti')){
                $('#download_bukti').attr('href','{{ route('download-bukti-mengajar', ':id') }}'.replace(':id', data.getAttribute('data-bukti')))
            }
        });

    });
    </script>
@endpush
