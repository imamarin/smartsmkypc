@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Rekap Presensi Mengajar</h4>
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
        <div class="col">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Rekap Presensi Mengajar</h4>
                    </div>
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
                                    <th>Tanggal</th>
                                    <th>Jam Ke</th>
                                    <th>Kelas</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Keterangan</th>
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
