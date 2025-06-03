@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekap Presensi Pengajar</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi Mengajar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-4">
            <div class="card border-success shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-success">
                        <i class="bi bi-person-check-fill fs-1"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1 text-muted">Total Hadir</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ $total_hadir }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-danger shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-danger">
                        <i class="bi bi-person-x-fill fs-1"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1 text-muted">Total Tidak Hadir</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ $total_tidak_hadir }}</h4>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-md-4">
            <div class="card border-primary shadow-sm">
                <div class="card-body d-flex align-items-center">
                    <div class="me-3 text-primary">
                        <i class="bi bi-bar-chart-line-fill fs-1"></i>
                    </div>
                    <div>
                        <h6 class="card-title mb-1 text-muted">Persentase Kehadiran</h6>
                        <h4 class="mb-0 fw-bold text-dark">{{ round($persentase_kehadiran) }} %</h4>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col">
            <div class="card">
                {{-- <div class="card-header d-flex flex-row align-items-center bg bg-info">
                    <h4 class="card-title">Rekap Presensi Mengajar</h4>
                </div><!-- end card header --> --}}
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
                                    <th>Tanggal</th>
                                    <th>Jam Ke</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Keterangan</th>
                                    @if(in_array('Ajuan', $fiturMenu[$view]))
                                    <th>Pengajuan Kehadiran Mengajar</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($presensi as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->tanggal }}</td>
                                        <td>{{ $subject->jam }}</td>
                                        <td>{{ $subject->kelas }}</td>
                                        <td>{{ $subject->matpel }}</td>
                                        <td>
                                            <span class="badge {{ $subject->keterangan=='Hadir'?'bg-success':'bg-danger' }}">
                                                {{ $subject->keterangan }}
                                            </span>
                                        </td>
                                        @if(in_array('Ajuan', $fiturMenu[$view]))
                                        <td>
                                            @if($subject->keterangan == 'Tidak Hadir')
                                                @if($subject->ajuan)
                                                    @if($subject->ajuan->status == '2')
                                                    <a href="{{ route('ajuan-kehadiran-mengajar.presensi',['id' => Crypt::encrypt($subject->jadwal), 'tgl' => $subject->tanggal]) }}" class="btn btn-sm btn-primary">Input Kehadiran</a>
                                                    @elseif($subject->ajuan->status == '1')
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-success"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectModal"
                                                    data-id="{{ Crypt::encrypt($subject->ajuan->id.'*0') }}"
                                                    data-tanggal="{{ $subject->tanggal }}"
                                                    data-jam="{{ $subject->jam }}"
                                                    data-kelas="{{ $subject->kelas }}"
                                                    data-matpel="{{ $subject->matpel }}"
                                                    data-ajuan = "{{ Crypt::encrypt($subject->ajuan->id) }}"
                                                    data-alasan = "{{ $subject->ajuan->alasan }}"
                                                    data-bukti = "{{ $subject->ajuan->bukti_file }}"
                                                    data-tanggapan = "{{ $subject->ajuan->tanggapan }}"
                                                    data-status_ajuan = "{{ $subject->ajuan->status }}"
                                                    >Revisi Pengajuan</a>
                                                    @else
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-warning"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectModal"
                                                    data-id="{{ Crypt::encrypt($subject->jadwal.'*'.$subject->tanggal) }}"
                                                    data-tanggal="{{ $subject->tanggal }}"
                                                    data-jam="{{ $subject->jam }}"
                                                    data-kelas="{{ $subject->kelas }}"
                                                    data-matpel="{{ $subject->matpel }}"
                                                    data-ajuan = "{{ Crypt::encrypt($subject->ajuan->id) }}"
                                                    data-alasan = "{{ $subject->ajuan->alasan }}"
                                                    data-bukti = "{{ $subject->ajuan->bukti_file }}"
                                                    >Menunggu Persetujuan</a>
                                                    @endif
                                                    <a href="{{ route('ajuan-kehadiran-mengajar.destroy',  Crypt::encrypt($subject->ajuan->id)) }}" class="btn btn-sm btn-danger" data-confirm-delete="true">Batalkan</a>
                                                @else
                                                    <a href="javascript:void(0)" class="btn btn-sm btn-info"
                                                    data-bs-toggle="modal"
                                                    data-bs-target="#addSubjectModal"
                                                    data-id="{{ Crypt::encrypt($subject->jadwal.'*'.$subject->tanggal) }}"
                                                    data-tanggal="{{ $subject->tanggal }}"
                                                    data-jam="{{ $subject->jam }}"
                                                    data-kelas="{{ $subject->kelas }}"
                                                    data-matpel="{{ $subject->matpel }}"
                                                    >Ajukan</a>
                                                @endif
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
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Ajukan Kehadiran Mengajar</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('ajuan-kehadiran-mengajar.store') }}" method="POST" enctype="multipart/form-data">
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
                    <div class="mb-3" id="input_alasan">
                        <label for="alasan" class="fw-semibold">Alasan Tidak Melakukan Presensi: </label><br>
                        <textarea id="alasan" name="alasan" class="form-control"></textarea>
                    </div>
                    <div class="mb-3" id="lihat_alasan">
                        <label for="lihat_alasan" class="fw-semibold">Alasan Tidak Melakukan Presensi: </label><br>
                        <p></p>
                    </div>
                    <div class="mb-3" id="upload_bukti_mengajar">
                        <label for="bukti_file" class="fw-semibold">Bukti Mengajar: </label>
                        <input type="file" name="bukti_file" id="bukti_file" class="form-control"></input>
                        <p><i>*Upload file image atau pdf minimal ukuran 3 MB</i></p>
                    </div>
                    <div class="mb-3" id="lihat_bukti_mengajar">
                        <label for="download_bukti" class="fw-semibold">Bukti Mengajar: </label><br>
                        <a href="" class="btn btn-sm btn-secondary" id="download_bukti" download>Download Bukti Mengajar</a>
                    </div>
                    <div class="mb-3" id="tanggapan">
                        <label for="tanggapan" class="fw-semibold">Catatan: </label><br>
                        <p></p>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Ajukan</button>
                    </div>
                </div>
            </form>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
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
            const ajuan = data.getAttribute('data-ajuan');
  
            $('#subjectId').val(id);
            $('#idkelas').text(': '+idkelas);
            $('#matpel').text(': '+matpel);
            $('#jam').text(': '+jam);
            $('#tanggal').text(': '+tanggal);
            $('#subjectForm').attr('action','{{ route('ajuan-kehadiran-mengajar.store') }}');
            $('#submitBtn').text('Ajukan');
            if(ajuan){
                if(data.getAttribute('data-status_ajuan') == '1'){
                    $('#subjectForm').attr('action','{{ route('ajuan-kehadiran-mengajar.update',':id') }}'.replace(':id', id));
                    $('#upload_bukti_mengajar').show();
                    $('#lihat_bukti_mengajar').hide();
                    $('#lihat_alasan').hide();
                    $('#input_alasan').show();
                    $('#input_alasan textarea').text(data.getAttribute('data-alasan'));
                    $('#tanggapan').show();
                    $('#tanggapan p').text(data.getAttribute('data-tanggapan'));
                    $('#submitBtn').show();
                    $('#submitBtn').text('Ajukan Perbaikan');
                }else{
                    $('#subjectForm').attr('action','');
                    $('#upload_bukti_mengajar').hide();
                    $('#lihat_bukti_mengajar').show();
                    $('#lihat_alasan').show();
                    $('#lihat_alasan p').text(data.getAttribute('data-alasan'))
                    $('#input_alasan').hide();
                    $('#submitBtn').hide();
                    $('#tanggapan').hide();
                    if(data.getAttribute('data-bukti')){
                        $('#download_bukti').attr('href','{{ route('download-bukti-mengajar', ':id') }}'.replace(':id', data.getAttribute('data-bukti')))
                    }
                    $('#submitBtn').text('Batalkan Perbaikan');
                }
            }else{
                $('#upload_bukti_mengajar').show();
                $('#lihat_bukti_mengajar').hide();
                $('#lihat_alasan').hide();
                $('#tanggapan').hide();
                $('#input_alasan').show();
                $('#input_alasan textarea').text('');
                $('#submitBtn').show();
            }
        });

    });
    </script>
@endpush
