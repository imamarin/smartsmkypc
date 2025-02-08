@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekap Presensi Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Rekap Presensi Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Rekap Presensi Siswa</h4>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    <th class="text-center">Presentasi Kehadiran Siswa</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rekap as $subject)
                                    @php
                                    if($subject->hadir_count == 0 || $subject->presensi_count == 0){
                                        $presentasi_hadir = 0;
                                    }else{
                                        $presentasi_hadir = round(($subject->hadir_count / $subject->presensi_count)*100);
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->matpel->matpel }}</td>
                                        <td>{{ $subject->kelas->kelas }}</td>
                                        <td>
                                            <div class="d-flex justify-content-center">
                                                <div class="progress w-50">
                                                    <div class="progress-bar progress-bar-striped progress-bar-animated bg-success" style="width:{{ $presentasi_hadir }}%">{{ $presentasi_hadir }}%</div>
                                                </div>
                                            </div>
                                        </td>
                                        <td>
                                            <a href="{{ route('rekap-presensi-siswa-detail', Crypt::encrypt($subject->kode_matpel."-".$subject->idkelas)) }}" class="btn btn-sm btn-info">Rekap Presensi</a>
                                            <button class="btn btn-sm btn-warning" id="historyPresensi"
                                                data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" 
                                                data-id="{{ Crypt::encrypt($subject->kode_matpel."-".$subject->idkelas) }}"
                                                data-matpel="{{ $subject->matpel->matpel }}"
                                                data-kelas="{{ $subject->kelas->kelas }}">
                                                
                                                Histori Presensi
                                            </button>
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Histori Presensi</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <ul class="list-group list-group-flush">
                        <li class="list-group-item">
                            <div class="row">
                                <div class="col-6">
                                    <label for="">Mata Pelajaran:</label><br>
                                    <label style="font-weight: normal" id="matpel"></label>
                                </div>
                                <div class="col-6 text-end">
                                    <label for="">Kelas</label><br>
                                    <label style="font-weight: normal" id="kelas"></label>
                                </div>
                            </div>
                            
                        </li>
                    </ul>
                    <table class="table display nowrap" id="example3">
                        <thead>
                            <th>#</th>
                            <th>Tanggal Presensi</th>
                            <th>Aksi</th>
                        </thead>
                        <tbody id="tbodyHistory">

                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
    <script>
        const history = document.querySelector('#addSubjectModal');
        history.addEventListener('show.bs.modal', function(event){
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const matpel = button.getAttribute('data-matpel');
            const kelas = button.getAttribute('data-kelas');

            document.querySelector('#matpel').textContent = matpel;
            document.querySelector('#kelas').textContent = kelas;
            let tbodyHistory = document.querySelector('#tbodyHistory');
            $.get('{{ route('history-presensi', ':id') }}'.replace(':id',id), function(data, status){
                tbodyHistory.innerHTML = "";               
                data.data.forEach((element, index) => {
                    let no = index + 1
                    let url = '{{ route('show-presensi.tanggal', ['id'=>':id','tgl'=>':tgl']) }}'.replace(':id', element.id).replace(':tgl', element.created_at)
                    tbodyHistory.innerHTML += "<tr>"+
                        "<td>"+no+"</td>"+
                        "<td>" + element.created_at + "</td>"+
                        "<td><a href='"+url+"' class='btn btn-sm btn-primary'>Ubah Presensi</a></td>"+
                        "</tr>";
                });
                
            })
        })

    $(document).ready(function() {
        $('#example3').DataTable({
            searching: false,
            lengthChange: false
        });

    });
    </script>
@endpush
