@extends('layouts.app')
@section('content')
<div class="row">
    <div class="col-12">
        <div class="page-title-box d-flex align-items-center justify-content-between">
            <h4 class="mb-0">Data Rombel</h4>

            <div class="page-title-right">
                <ol class="breadcrumb m-0">
                    <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                    <li class="breadcrumb-item active">Data Rombel</li>
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
                    <h4 class="card-title">Data Siswa {{ $kdkelas }}</h4>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                        @if(in_array('Tambah Siswa', $fiturMenu[$view]))
                        @if($tingkat == "X")
                        <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Siswa</button>
                        @if(in_array('Import', $fiturMenu[$view]))
                        <a href="#" class="btn btn-sm btn-success me-2"  data-bs-toggle="modal" data-bs-target="#importModal" id="importRombel">Import dari Excel</a>
                        @endif
                        @else
                        <button class="btn btn-sm btn-primary me-2" data-bs-toggle="modal" data-bs-target="#pindahTingkatModal">Tambahkan dari Tingkat Sebelumnya</button>
                        @endif
                        @endif

                        @if(in_array('Hapus Siswa', $fiturMenu[$view]))
                        <a href="#" class="btn btn-sm btn-danger me-2" id="hapusRombel">Hapus Siswa</a>
                        @endif
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <form action="{{ route('data-rombel.deleteSiswa') }}" method="post" id="rombelForm">
                        @csrf
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>No</th>
                                    <th>Nisn</th>
                                    <th>Nama Siswa</th>
                                    <th>Jenis Kelamin</th>
                                    @if(in_array('Pindah Kelas', $fiturMenu[$view]) || in_array('Hapus Siswa', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rombel as $key => $item)
                                    <tr>
                                        <td><input type="checkbox" name="siswa[]" value="{{ $item->id }}" class="item-checkbox"></td>
                                        <td>{{ $key+1 }}</td>
                                        <td>{{ $item->siswa->nisn_dapodik }}</td>
                                        <td>{{ $item->siswa->nama }}</td>
                                        <td>{{ $item->siswa->jenis_kelamin }}</td>
                                        @if(in_array('Pindah Kelas', $fiturMenu[$view]) || in_array('Hapus Siswa', $fiturMenu[$view]))
                                        <td>
                                            @if(in_array('Pindah Kelas', $fiturMenu[$view]))
                                            <button type="button" class="btn btn-secondary btn-sm" data-bs-toggle="modal"
                                                data-bs-target="#movingClassModal" data-id="{{ Crypt::encrypt($item->id) }}"
                                                data-nisn="{{ $item->nisn }}"
                                                data-idkelas="{{ encryptSmart($item->idkelas) }}"
                                                data-idtahunajaran="{{ encryptSmart($item->kelas->idtahunajaran) }}"
                                                data-subject="pindahkelas">
                                                Pindah Kelas
                                            </button>
                                            @endif
                                            @if(in_array('Hapus Siswa', $fiturMenu[$view]))
                                            <a href="{{ route('data-rombel.destroy', Crypt::encrypt($item->id)) }}"
                                                class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
                                            @endif
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
                <h5 class="modal-title" id="addSubjectModalLabel">Tambahkan Siswa ke {{ $kdkelas }}</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subjectForm" action="{{ route('data-rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran </label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                            @foreach ($tahunajaran as $item)
                                <option value="{{ encryptSmart($item->id) }}" {{ $item->id==$idtahunajaran?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idkelas" class="form-label">Kelas</label>
                        <select name="idkelas" id="idkelas" class="form-control">
                            @foreach ($kelas as $item)
                                <option value="{{ encryptSmart($item->id) }}" {{ $item->id == $idkelas?'selected':'' }}>{{ $item->kelas }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="nisn" class="form-label">Siswa</label>
                        <select name="nisn[]" id="nisn" class="form-control select2" multiple>
                            @foreach ($siswa as $item)
                                <option value="{{ $item->nisn }}">{{ $item->nama }}</option>
                            @endforeach
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
<div class="modal fade" id="movingClassModal" tabindex="-1" aria-labelledby="movingClassModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="movingClassModalLabel">Pindah Kelas</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="movingForm" action="" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectIdMove" name="id">
                    <input type="hidden" id="nisn" name="nisn">
                    <input type="hidden" name="_method" id="formMethodMove" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran </label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control">
                            @foreach ($tahunajaran as $item)
                                <option value="{{ encryptSmart($item->id) }}" {{ $item->id==$idtahunajaran?'selected':'' }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="idkelas" class="form-label">Kelas</label>
                        <select name="idkelas" id="idkelas" class="form-control select2">
                            @foreach ($kelas as $item)
                                @if($tingkat == $item->tingkat && $item->idtahunajaran == $idtahunajaran)
                                <option value="{{ encryptSmart($item->id) }}" {{ $item->id == $idkelas?'selected':'' }}>{{ $item->kelas }}</option>
                                @endif
                            @endforeach
                        </select>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtnMove">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="pindahTingkatModal" tabindex="-1" aria-labelledby="pindahTingkatModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="pindahTingkatModalLabel">Pindah Tingkat</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="pindahTingkatForm" action="{{ route('data-rombel.pindahTingkat', Crypt::encrypt($idkelas.'*'.$idtahunajaran)) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectIdPindahTingkat" name="id">
                    <input type="hidden" id="nisn" name="nisn">
                    <input type="hidden" name="_method" id="formMethodPindahTingkat" value="POST">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <div class="mb-3">
                                <label for="idtahunajaran" class="form-label">Tahun Ajaran  </label>
                                <select name="idtahunajaran" id="idtahunajaran" class="form-control select2" required>
                                    <option value="">Pilih Tahun Ajaran</option>
                                    @foreach ($tahunajaran as $item)
                                        @if($item->id < $idtahunajaran)
                                        <option value="{{ encryptSmart($item->id) }}">{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="col-12 col-md-6">                                                 
                            <div class="mb-3">
                                <label for="idkelas" class="form-label">Kelas</label>
                                <select name="idkelas" id="idkelas" class="form-control select2">
                                    <option value="">Pilih Kelas</option>
                                    @foreach ($kelas as $item)
                                        @if($item->tingkat < $tingkat)
                                        <option value="{{ encryptSmart($item->kelas) }}" {{ $item->id == $idkelas?'selected':'' }}>{{ $item->kelas }}</option>
                                        @endif
                                    @endforeach
                                </select>
                            </div>
                        </div>
                    </div>

                    <div class="mb-3">
                        <label for="nisn" class="form-label">Siswa</label>
                        <table class="table display nowrap" id="example3">
                            <thead>
                                <tr>
                                    <th><input type="checkbox" id="select-all"></th>
                                    <th>Nisn</th>
                                    <th>Nama Siswa</th>
                                </tr>
                            </thead>
                        </table>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtnMove">Simpan</button>
                    </div>

                </div>
            </form>
        </div>
    </div>
</div>
<div class="modal fade" id="importModal" tabindex="-1" aria-labelledby="importModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="importModalLabel">Import Data Siswa</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="importForm" action="{{ route('data-rombel.import') }}" method="POST" enctype="multipart/form-data">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <input type="hidden" name="id" value="{{ Crypt::encrypt($idkelas.'*'.$idtahunajaran) }}">
                        <label for="file" class="form-label">Upload File (.xlsx)</label>
                        <input type="file" class="form-control" accept=".xlsx" id="file" name="file" required>
                    </div>

                    <div class="mb-3">
                        <span>Download Template .xlsx disini</span>
                        <a href="{{ route('data-rombel.template.import') }}">Template_Rombel.xlsx</a>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="submit" class="btn btn-primary" id="submitBtn">Import</button>
                </div>
            </form>
        </div>
    </div>
</div>
@endsection
@push('scripts')
    <script>
        const hapusRombel = document.getElementById('hapusRombel');
        hapusRombel.addEventListener('click', function(event){
            const form = document.querySelector('#rombelForm');
            Swal.fire({
                title: "Hapus Siswa",
                text: "Yakin hapus data siswa di kelas ini",
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

        const modalAdd = document.getElementById('addSubjectModal');
        modalAdd.addEventListener('show.bs.modal', function(event){
            const form = document.querySelector('#subjectForm');
            form.querySelector("#idtahunajaran").addEventListener('mousedown',function(e){
                e.preventDefault();
            });
            form.querySelector("#idkelas").addEventListener('mousedown',function(e){
                e.preventDefault();
            });
        });

        const pindahTingkat = document.getElementById('pindahTingkatModal');
        pindahTingkat.addEventListener('show.bs.modal', function(event){
            const button = event.relatedTarget;
            const form = document.querySelector("#pindahTingkatForm");

            
        });

        $(document).ready(function(){
            let table = new DataTable("#example3", {
                ajax: {
                    url: "{{ route('data-rombel.siswaRombel') }}",
                    data: function(d){
                        d.idkelas = $("#pindahTingkatForm #idkelas").val(),
                        d.idtahunajaran = $("#pindahTingkatForm #idtahunajaran").val(),
                        d._token = '{{ csrf_token() }}'
                     },
                    type: 'POST'
                },
                columns: [
                    {
                        data: "nisn",
                        orderable: false,
                        searchable: false,
                        render: function (data, type, row) {
                            return `<input type="checkbox" name="nisn[]" class="item-checkbox" value="${data}">`;
                        }
                    },
                    {data: 'nisn'},
                    {data: 'nama'},
                ],
                processing: true,
                serverSide: true
            }) 

            $("#pindahTingkatForm #idkelas").on('change', function(e){
                console.log($("#pindahTingkatForm #idkelas").val());
                
                table.ajax.reload();
                $("#pindahTingkatForm #select-all").prop("checked", false);
            });

            $("#pindahTingkatForm #select-all").on("change", function () {
                $("#pindahTingkatForm .item-checkbox").prop("checked", $(this).prop("checked"));
            });

            $("#example2 tbody").on("change", ".item-checkbox", function () {
                let totalCheckbox = $("#pindahTingkatForm  .item-checkbox").length;
                let checkedCheckbox = $("#pindahTingkatForm  .item-checkbox:checked").length;
                console.log(totalCheckbox+" "+checkedCheckbox);
                
                $("#pindahTingkatForm #select-all").prop("checked", totalCheckbox === checkedCheckbox);
            });
        })

        const modalMoving = document.getElementById('movingClassModal');
        modalMoving.addEventListener('show.bs.modal', function(event) {
            console.log("asdsd");
            
            const button = event.relatedTarget;
            const id = button.getAttribute('data-id');
            const nisn = button.getAttribute('data-nisn');
            const subject = button.getAttribute('data-subject');
            const idkelas = button.getAttribute('data-idkelas');
            const idtahunajaran = button.getAttribute('data-idtahunajaran');
            
            console.log(idkelas);
            
            const form = document.querySelector('#movingForm');
            const submitBtn = document.getElementById('submitBtnMove');
            const subjectId = document.getElementById('subjectIdMove');
            const modalTitle = document.getElementById('movingClassModalLabel');
            const formMethod = document.getElementById('formMethodMove');

            if(subject){
                if (subject == "pindahkelas") {
                    form.action = '{{ route('data-rombel.update', ':id') }}'.replace(':id', id);
                    submitBtn.textContent = 'Pindah Kelas';
                    modalTitle.textContent = 'Pindah Kelas';
                    subjectId.value = id;
                    formMethod.value = 'PUT';
                    form.querySelector('#idkelas').value = idkelas;
                    form.querySelector('#nisn').value = nisn;
                    form.querySelector('#idtahunajaran').value = idtahunajaran; 
                    form.querySelector('#idtahunajaran').addEventListener('mousedown', function(e){
                        e.preventDefault();
                    });                              
                } 
            }
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

        const tahunajaran = $('#pindahTingkatForm #idtahunajaran');
        const kelas = $('#pindahTingkatForm #idkelas');

        tahunajaran.on('change',function(event){
            let url = '{{ route('data-kelas.json-tahunajaran', ':id') }}'.replace(':id', tahunajaran.val());
            $.get(url, function(data,status){
                kelas.html("");
                kelas.append('<option value="">pilih kelas</option>');
                if(data.data.length > 0){
                    data.data.forEach(element => {
                        let option = new Option(element.kelas, element.id);
                        kelas.append(option);
                    });
                }
            });
        })
    </script>
@endpush
