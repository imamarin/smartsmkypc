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
                        <li class="breadcrumb-item active">Data Jam Pelajaran</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center"  style="background-color: #cbd931 ;">
                    <div class="col">
                        <h4 class="card-title">Data Jam Pelajaran</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end">
                            @if(in_array('Eksport', $fiturMenu[$view]))
                            <a href="#" class="btn btn-info me-2">Export Data</a>
                            @endif
                            @if(in_array('Import', $fiturMenu[$view]))
                            <a href="#" class="btn btn-success me-2">Import Data</a>
                            @endif
                            <!-- Button to trigger modal -->
                            @if(in_array('Hapus', $fiturMenu[$view]))
                            <button class="btn btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Data</button>
                            <a href="#" class="btn btn-danger" id="hapusJampel">Hapus Jam Pelajaran</a>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <form action="{{ route('data-jam-pelajaran.delete') }}" method="post" id="jampelForm">
                            @csrf
                            <table class="table display nowrap" id="example">
                                <thead>
                                    <tr>
                                        @if(in_array('Hapus', $fiturMenu[$view]))
                                        <th><input type="checkbox" id="select-all"></th>
                                        @endif
                                        <th>No</th>
                                        <th>hari</th>
                                        <th>Jam</th>
                                        <th>Waktu Mulai</th>
                                        <th>Jam Mulai</th>
                                        <th>Tahun Ajaran</th>
                                        @if(in_array('Edit', $fiturMenu[$view]))
                                        <th>Aksi</th>
                                        @endif
                                    </tr>
                                </thead>
                                <tbody>
                                    @php
                                    $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','minggu'];
                                    @endphp
                                    @foreach ($jampel as $subject)
                                        <tr>
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <td><input type="checkbox" name="jampel[]" class="item-checkbox" value="{{ $subject->id }}"></td>
                                            @endif
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $hari[$subject->hari-1] }}</td>
                                            <td>{{ $subject->jam }}</td>
                                            <td>{{ $subject->mulai }}</td>
                                            <td>{{ $subject->akhir }}</td>
                                            <td>{{ $subject->tahunajaran?->awal_tahun_ajaran }}/{{ $subject->tahunajaran?->akhir_tahun_ajaran }}</td>
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <td>
                                                <!-- Trigger modal untuk Edit -->
                                                <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                    data-bs-target="#editJampelModal" data-id="{{ Crypt::encrypt($subject->id) }}"
                                                    data-jam="{{ $subject->jam }}" 
                                                    data-hari="{{ $subject->hari }}" 
                                                    data-mulai="{{ $subject->mulai }}"
                                                    data-akhir="{{ $subject->akhir }}"
                                                    data-idtahunajaran="{{ encryptSmart($subject->idtahunajaran) }}">
                                                    Edit Jam Pelajaran
                                                </button>
                                            </td>
                                            @endif
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </form>
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
                    <h5 class="modal-title" id="addSubjectModalLabel">Setting Jam Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="{{ route('data-jam-pelajaran.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-control select2">
                                @foreach ($tahunajaran as $item)
                                    <option value="{{ $item->id }}" {{ $item->status==1?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="matpel" class="form-label">Hari</label>
                            <select name="hari" id="hari" class="form-control select2">
                                @foreach ($hari as $item)
                                    <option value="{{ $loop->iteration }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jam_masuk" class="form-label">Jam Awal Pembelajaran</label>
                            <input type="time" class="form-control" id="jam_masuk" name="jam_masuk" required>
                        </div>
                        <div class="mb-3">
                            <label for="durasi" class="form-label">Durasi / Jam Pelajaran</label>
                            <input type="number" class="form-control" id="durasi" name="durasi" required>
                            <i>*hitungan menit</i>
                        </div>
                        <div class="mb-3">
                            <label for="jumlah_jampel" class="form-label">Jumlah Jam Pelajaran</label>
                            <input type="number" class="form-control" id="jumlah_jampel" name="jumlah_jampel">
                            
                        </div>
                        <div class="mb-3">
                            <label for="jam_istirahat" class="form-label">Jam Istirahat</label>
                            <input type="text" class="form-control" id="jam_istirahat" name="jam_istirahat" placeholder="5,7">
                            <i>*artinya istirahat setelah jam ke 5 dan ke 7</i>
                        </div>
                        <div class="mb-3">
                            <label for="durasi_istirahat" class="form-label">Durasi Istirahat</label>
                            <input type="number" class="form-control" id="durasi_istirahat" name="durasi_istirahat">
                            <i>*hitungan menit</i>

                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>

    <div class="modal fade" id="editJampelModal" tabindex="-1" aria-labelledby="editJampelModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editJampelModalLabel">Setting Jam Pelajaran Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="editJampelForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="PUT">
                        <div class="mb-3">
                            <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                            <select name="idtahunajaran" id="idtahunajaran" class="form-control select2">
                                @foreach ($tahunajaran as $item)
                                    <option value="{{ encryptSmart($item->id) }}" {{ $item->status==1?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="hari" class="form-label">Hari</label>
                            <select name="hari" id="hari" class="form-control select2">
                                @foreach ($hari as $item)
                                    <option value="{{ $loop->iteration }}">{{ $item }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="mb-3">
                            <label for="jam" class="form-label">Jam Ke-</label>
                            <input type="number" class="form-control" id="jam" name="jam" required>
                        </div>
                        <div class="mb-3">
                            <label for="mulai" class="form-label">Jam Masuk</label>
                            <input type="time" class="form-control" id="mulai" name="mulai" required>
                        </div>
                        <div class="mb-3">
                            <label for="akhir" class="form-label">Jam Akhir</label>
                            <input type="time" class="form-control" id="akhir" name="akhir" required>
                            
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">Ubah</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const hapusJampel = document.getElementById('hapusJampel');
        hapusJampel.addEventListener('click', function(event){
            const form = document.querySelector('#jampelForm');
            Swal.fire({
                title: "Hapus Jam Pelajaran",
                text: "Yakin hapus data yang dipilih?",
                icon: "warning",
                showCancelButton: true,
                confirmButtonColor: "#3085d6",
                cancelButtonColor: "#d33",
                confirmButtonText: "Yes, delete it!"
            }).then((result) => {
            if (result.isConfirmed) {
                form.submit()
            }
            });
        });

        const selectAllCheckbox = document.getElementById('select-all');
        const itemCheckboxes = document.querySelectorAll('.item-checkbox');

        selectAllCheckbox.addEventListener('change', function () {
            const isChecked = this.checked;
            itemCheckboxes.forEach(checkbox => {
                checkbox.checked = isChecked;
            });
        });

        itemCheckboxes.forEach(checkbox => {
            checkbox.addEventListener('change', function () {
                if (!this.checked) {
                    selectAllCheckbox.checked = false;
                } else {
                    const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
                    selectAllCheckbox.checked = allChecked;
                }
            });
        });

        const editJampel = document.getElementById('editJampelModal');
        editJampel.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const hari = button.getAttribute('data-hari');
            const jam = button.getAttribute('data-jam');
            const mulai = button.getAttribute('data-mulai');
            const akhir = button.getAttribute('data-akhir');
            const idtahunajaran = button.getAttribute('data-idtahunajaran');

            const form = document.querySelector('#editJampelForm');

            form.action = '{{ route('data-jam-pelajaran.update', ':id') }}'.replace(':id', id);
            form.querySelector('#subjectId').value = id;
            form.querySelector('#formMethod').value = "PUT";
            form.querySelector('#jam').value = jam;
            form.querySelector('#hari').value = hari;
            form.querySelector('#mulai').value = mulai;
            form.querySelector('#akhir').value = akhir;
            form.querySelector('#idtahunajaran').value = idtahunajaran;
        });
    </script>
@endpush
