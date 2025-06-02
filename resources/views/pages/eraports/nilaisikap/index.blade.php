@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Nilap Sikap</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Nilai Sikap</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-white" style="background-color: rgb(31, 177, 188)">
                    <div class="row">
                        <div class="col-12 col-md-12">
                            <form action="" method="get">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idkelas" class="form-label">Kelas</label>
                                        <select name="idkelas" id="idkelas" class="form-select select2">
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($kelas as $item)
                                                <option value="{{ Crypt::encrypt($item->idkelas) }}" {{ $idkelas == $item->idkelas ? 'selected' : '' }}>
                                                    {{ $item->kelas->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3">
                                        <label for="kategori" class="form-label">Kategori Sikap</label>
                                        <select name="kategori" id="kategori" class="form-select select2">
                                            <option value="">Pilih Guru</option>
                                            <option value="{{ Crypt::encrypt('sosial') }}" {{ $kategori == 'sosial' ? 'selected' : '' }}>Sosial</option>
                                            <option value="{{ Crypt::encrypt('spiritual') }}" {{ $kategori == 'spiritual' ? 'selected' : '' }}>Spiritual</option>
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" class="btn btn-primary" value="TAMPIL">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route('nilai-sikap.store') }}" method="post">
                        @csrf
                        <div class="table-responsive">
                            <table class="table display nowrap">
                                <thead>
                                    <tr>
                                        <th>#</th>
                                        <th>NISN</th>
                                        <th>Nama Siswa</th>
                                        @foreach ($sikap as $item)
                                            <th>{{ $item->sikap }}<br><input type="checkbox" id="select-all-{{ $loop->index }}" class="select-all"></th>
                                        @endforeach
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($siswa as $subject)
                                        <tr>
                                            <td>{{ $loop->iteration }}</td>
                                            <td>{{ $subject->nisn }}</td>
                                            <td>{{ $subject->nama }}</td>
                                            @foreach ($sikap as $item)
                                                @php
                                                $checked = '';
                                                foreach ($subject->nilaisikap as $key => $value) {
                                                    # code...
                                                    if($item->sikap == $value->sikap){
                                                        $checked = 'checked';
                                                        break;
                                                    }
                                                } 
                                                @endphp
                                                <td>
                                                    <input type="checkbox" class="sikap-{{ $loop->index }}" name="sikap[{{ $subject->nisn }}][]" value="{{ $item->sikap }}" {{ $checked }}>
                                                </td>
                                            @endforeach
                                        </tr>
                                    @endforeach
                                </tbody>
                                <tfoot>
                                    <tr>
                                        <td colspan="{{ $sikap->count() + 3 }}">
                                            <div class="d-flex justify-content-center">
                                                <input type="hidden" name="kategori" value="{{ Crypt::encrypt($kategori) }}">
                                                <input type="hidden" name="idkelas" value="{{ Crypt::encrypt($idkelas) }}">
                                                <input type="submit" value="SIMPAN NILAI SIKAP" class="btn btn-primary">
                                            </div>
                                        </td>
                                    </tr>
                                </tfoot>
                            </table>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        const idkelas = document.getElementById('idkelas');
        $('#idkelas').on('change', function(event){
            event.preventDefault();
            window.location.href = '{{ route("matpel-kelas.show", ":id") }}'.replace(':id', event.target.value)
        });


        document.addEventListener("DOMContentLoaded", function () {
            document.querySelectorAll(".select-all").forEach(function (selectAllCheckbox) {
                let index = selectAllCheckbox.id.replace("select-all-", "");
                let checkboxes = document.querySelectorAll(".sikap-" + index);

                selectAllCheckbox.addEventListener("change", function () {
                    checkboxes.forEach(function (checkbox) {
                        checkbox.checked = selectAllCheckbox.checked;
                    });
                });

                checkboxes.forEach(function (checkbox) {
                    checkbox.addEventListener("change", function () {
                        let allChecked = [...checkboxes].every(c => c.checked);
                        selectAllCheckbox.checked = allChecked; // Jika semua checkbox tercentang, "Select All" tetap tercentang
                    });
                });
            });
            // const selectAllCheckbox = document.querySelectorAll('.select-all');
            // const itemCheckboxes = document.querySelectorAll('.item-checkbox');
    
            // selectAllCheckbox.addEventListener('change', function () {
            //     const isChecked = this.checked;
            //     itemCheckboxes.forEach(checkbox => {
            //         checkbox.checked = isChecked;
            //     });
            // });
    
            // itemCheckboxes.forEach(checkbox => {
            //     checkbox.addEventListener('change', function () {
            //         if (!this.checked) {
            //             selectAllCheckbox.checked = false;
            //         } else {
            //             const allChecked = Array.from(itemCheckboxes).every(cb => cb.checked);
            //             selectAllCheckbox.checked = allChecked;
            //         }
            //     });
            // });
        });
    </script>
@endpush
