@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Jadwal Mengajar</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Jadwal Mengajar</li>
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
                        <h4 class="card-title">Daftar Jadwal Mengajar</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            @if(in_array('Eksport', $fiturMenu[$view]))
                            <a href="#" class="btn btn-info me-2">Export Data</a>
                            @endif
                            @if(in_array('Import', $fiturMenu[$view]))
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            @endif
                            <!-- Button to trigger modal -->
                            @if(in_array('Tambah', $fiturMenu[$view]))
                            @if(!$kunci_jadwal)
                            <button class="btn btn-primary" 
                            data-bs-toggle="modal" 
                            data-bs-target="#addSubjectModal">TambahData</button>
                            @endif
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
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
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sesi</th>
                                    <th>Hari</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                    @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];   
                                @endphp
                                @foreach ($jadwal as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->sistemblok?->nama_sesi }}</td>
                                        <td>{{ $hari[$subject->jampel->hari-1] }}</td>
                                        <td>Jam Ke: {{ $subject->jampel->jam }} ({{ $subject->jampel->mulai }})</td>
                                        <td>Jam Ke: {{ $subject->jam_keluar }} ({{ $subject->waktu_keluar }})</td>
                                        <td>{{ $subject->matpel->matpel }}</td>
                                        <td>{{ $subject->kelas->kelas }}</td>
                                        @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                        <td>
                                            <!-- Trigger modal untuk Edit -->
                                            @if(!$kunci_jadwal)
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <button class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal" 
                                                data-id="{{ Crypt::encrypt($subject->id) }}"
                                                data-sesi="{{ $subject->sistemblok?->id }}"
                                                data-hari="{{ $subject->jampel->hari }}"
                                                data-jam-masuk="{{ $subject->jampel->jam }}"
                                                data-jumlah-jam="{{ $subject->jumlah_jam }}"
                                                data-matpel="{{ $subject->kode_matpel }}"
                                                data-kelas="{{ $subject->idkelas }}"
                                                data-tahun-ajaran="{{ $subject->sistemblok->idtahunajaran }}">
                                                Edit
                                            </button>
                                            @endif
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('jadwal-mengajar.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
                                            @endif
                                            @else
                                            <button type="button" class="btn btn-sm btn-warning">Jadwal Dikunci</button>
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

    <!-- Modal untuk Input Mata Pelajaran -->
    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Jadwal Mengajar Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('jadwal-mengajar.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" id="nip" name="nip" value="{{ Crypt::encrypt($staf->nip) }}">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Sesi</label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-control select2">
                                @foreach ($tahunajaran as $item)
                                    @if($item->status == 1)
                                        <option value="{{ $item->id }}">{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }} ({{ $item->semester }})</option>
                                    @endif
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idsistemblok" class="form-label">Sesi</label>
                            <select name="idsistemblok" id="idsistemblok" class="form-control select2" required>
                                <option value=""></option>
                                @foreach ($sistemblok as $item)
                                    <option value="{{ $item->id }}">{{ $item->nama_sesi }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="kode_matpel" class="form-label">Mata Pelajaran</label>
                            <select name="kode_matpel" id="kode_matpel" class="form-control select2" required>
                                <option value=""></option>
                                @foreach ($matpel as $item)
                                    <option value="{{ $item->matpel->kode_matpel }}">{{ $item->matpel->matpel }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idkelas" class="form-label">Kelas</label>
                            <select name="idkelas" id="idkelas" class="form-control select2" required>
                                <option value=""></option>
                                @foreach ($kelas as $item)
                                    <option value="{{ $item->id }}">{{ $item->kelas }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <select name="hari" id="hari" class="form-control select2" required>
                                <option value=""></option>
                                @foreach ($hari as $item)
                                    <option value="{{ $loop->iteration }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="idjampel" class="form-label">Jam Ke</label>
                            <select name="idjampel" id="idjampel" class="form-control select2" required>
                                <option value=""></option>
                                @for ($i = 1; $i <= 10; $i++)
                                    <option value="{{ $i }}">{{ $i }}</option>
                                @endfor
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_jam" class="form-label">Jumlah Jam</label>
                            <input type="number" name="jumlah_jam" id="jumlah_jam" class="form-control" required>
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
            const sesi = button.getAttribute('data-sesi');
            const hari = button.getAttribute('data-hari');
            const masuk = button.getAttribute('data-jam-masuk');
            const jumlahJam = button.getAttribute('data-jumlah-jam');
            const matpel = button.getAttribute('data-matpel');
            const kelas = button.getAttribute('data-kelas');
            const tahunAjaran = button.getAttribute('data-tahun-ajaran');

            const form = document.getElementById('subjectForm');
            const submitBtn = document.getElementById('submitBtn');
            const subjectId = document.getElementById('subjectId');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                form.action = '{{ route('jadwal-mengajar.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Sistem Blok';
                subjectId.value = id;
                formMethod.value = 'PUT';
                document.getElementById('idsistemblok').value = sesi;
                document.getElementById('kode_matpel').value = matpel;
                document.getElementById('idkelas').value = kelas;
                document.getElementById('hari').value = hari;
                document.getElementById('idjampel').value = masuk;
                document.getElementById('jumlah_jam').value = jumlahJam;
                document.getElementById('idtahunajaran').value = tahunAjaran;
            } else {
                form.action = '{{ route('jadwal-mengajar.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Jadwal Mengajar Baru';
                subjectId.value = '';
                formMethod.value = 'POST';
                form.reset();
            }
        });

        const hari = $('#hari');
        const idjampel = $('#idjampel');
        hari.on('change',function(event){
            let url = '{{ route('jam-pelajaran.getjam', ':id') }}'.replace(':id', hari.val());
            $.get(url, function(data,status){
                idjampel.html("");
                if(data.data.length > 0){
                    data.data.forEach(element => {
                        let option = new Option(element.jam, element.jam);
                        idjampel.append(option);
                    });
                }
            })
        })
    </script>
@endpush
