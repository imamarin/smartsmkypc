@extends('layouts.app')
@push('styles')
<style>
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Cetak Raport</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Cetak Raport</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between">
                    <h4 class="card-title text-start">Cetak Raport</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped display nowrap">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kelas</th>
                                    <th>Pilih Siswa</th>
                                    <th>Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kelas as $key => $subject)
                                    <form action="" method="post">
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->kelas->kelas }}</td>
                                        <td>      
                                            <div class="row">
                                                <div class="col-md-6">
                                                    Dari:
                                                    <select name="nisn" id="" class="form-select">
                                                        @foreach ($subject->kelas->rombel->sortBy('siswa.nama') as $item)
                                                        <option value="{{ $loop->iteration }}">{{ $loop->iteration.". ".$item->siswa->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                                <div class="col-md-6">
                                                    Sampai: 
                                                    <select name="nisn2" id="" class="form-select">
                                                        @php
                                                            $total = $subject->kelas->rombel->count();
                                                        @endphp
                                                        @foreach ($subject->kelas->rombel->sortByDesc('siswa.nama') as $item)
                                                        <option value="{{ $total-- }}">{{ $total-- }}. {{ $item->siswa->nama }}</option>
                                                        @endforeach
                                                    </select>
                                                </div>
                                            </div> 
                                        </td>
                                        <td class="align-content-center">
                                            <div class="col-md-12">
                                                <input type="submit" value="Cover dan Identitas" class="btn btn-sm btn-primary me-1">
                                                <input type="submit" value="Raport 1" class="btn btn-sm btn-success me-1">
                                                <input type="submit" value="Raport 2" class="btn btn-sm btn-success me-1">
                                                <input type="submit" value="Raport 3" class="btn btn-sm btn-success me-1">
                                                <input type="submit" value="Peringkat" class="btn btn-sm btn-info me-1">
                                                <input type="submit" value="Transkrip" class="btn btn-sm btn-warning me-1">
                                                <input type="submit" value="Surat Keluar" class="btn btn-sm btn-danger me-1">
                                            </div>
                                        </td>
                                    </tr>
                                    </form>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
        window.location.href = '{{ route("absensi-siswa.show", ":id") }}'.replace(':id', event.target.value)
    })
</script>
@endpush
