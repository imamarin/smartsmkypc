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
                    <form action="{{ route('data-rombel.tahunajaran') }}" method="get" class="d-flex gap-2 w-100">
                        <div style="width: 45%">
                            <label for="tahunajaran" class="form-label">Tahun Ajaran</label>
                            <select name="id" id="tahunajaran" class="form-control select2">
                                @foreach($tahunajaran as $item)
                                <option value="{{ Crypt::encrypt($item->id) }}" {{ isset($idtahunajaran) ? ($idtahunajaran == $item->id ? 'selected' : '') : ''  }}>{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                                @endforeach
                            </select>
                        </div>
                        <div class="d-flex align-items-end">
                            <input type="submit" value="Tampilkan" class="btn btn-primary" style="margin-bottom: 5px">
                        </div>
                    </form>
                </div>
                <div class="col">
                    <div class="d-flex justify-content-end mb-3">
                    </div>
                </div>
            </div><!-- end card header -->
            <div class="card-body">
                <div class="table-responsive">
                    <table class="table display nowrap" id="example2">
                        <thead>
                            <tr>
                                <th>#</th>
                                <th>Kelas</th>
                                <th>Jurusan</th>
                                <th>Jumlah Siswa</th>
                                <th>Walikelas</th>
                                @if(in_array('Tampil Siswa', $fiturMenu[$view]))
                                <th>Aksi</th>
                                @endif
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($kelas as $key => $item)
                            <tr>
                                <td>{{ $key+1 }}</td>
                                <td>{{ $item->kelas }}</td>
                                <td>{{ $item->jurusan->jurusan }}</td>
                                <td>{{ $item->rombel->count() }}</td>
                                <td>
                                    {{ $item->walikelas[0]->staf->nama ?? '-'}}
                                </td>
                                @if(in_array('Tampil Siswa', $fiturMenu[$view]) || in_array('Eksport', $fiturMenu[$view]) || in_array('Import', $fiturMenu[$view]))
                                <td>
                                    @if(in_array('Tampil Siswa', $fiturMenu[$view]))
                                    <a href="{{ route('data-rombel.showStudents', Crypt::encrypt($item->id.'*'.$item->idtahunajaran)) }}"
                                        class="btn btn-sm btn-info">Lihat Siswa</a>
                                    @endif
                                    @if(in_array('Eksport', $fiturMenu[$view]))
                                    {{-- <a href="{{ route('data-rombel.export', Crypt::encrypt($item->id.'*'.$item->idtahunajaran)) }}" class="btn btn-sm btn-success"><i class="mdi mdi-file-excel"></i></a> --}}
                                    @endif
                                    <a href="#" class="btn btn-sm btn-secondary"><i class="mdi mdi-printer"></i></a>
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
                <h5 class="modal-title" id="addSubjectModalLabel">Input Rombel Baru</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="subjectForm" action="{{ route('data-rombel.store') }}" method="POST">
                @csrf
                <div class="modal-body">
                    <input type="hidden" id="subjectId" name="id">
                    <input type="hidden" name="_method" id="formMethod" value="POST">
                    <div class="mb-3">
                        <label for="idtahunajaran" class="form-label">Tahun Ajaran</label>
                        <select name="idtahunajaran" id="idtahunajaran" class="form-control select2">
                            @foreach ($tahunajaran as $item)
                            <option value="{{ $item->id }}">{{ $item->awal_tahun_ajaran }}/{{ $item->akhir_tahun_ajaran }}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="mb-3">
                        <label for="kdkelas" class="form-label">Kelas</label>
                        <select name="kdkelas" id="kdkelas" class="form-control select2">
                            @foreach ($kelas as $item)
                            <option value="{{ $item->kdkelas }}">{{ $item->kdkelas }}</option>
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
@endsection
@push('scripts')

@endpush
