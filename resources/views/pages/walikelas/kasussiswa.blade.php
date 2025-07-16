@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data Kasus Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Kasus Siswa</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col">
            <div class="card">
                <div class="card-header text-white" style="background-color: rgb(42, 101, 149)">
                    <div class="row">
                        <div class="col-12 col-md-6">
                            <form action="{{ route($route.'laporan-kasus-siswa.rombel') }}" method="get">
                                <div class="row">
                                    <div class="col-12 col-md-3">
                                        <label for="idkelas" class="form-label">Kelas</label>
                                        <select name="idkelas" id="idkelas" class="form-select select2">
                                            @foreach ($kelas as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $kelas_selected ? 'selected' : '' }}>
                                                    {{ $item->kelas }}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-md-3 d-flex align-items-end mb-1">
                                        <input type="submit" value="Tampilkan" class="btn btn-primary">
                                    </div>
                                </div>
                            </form>
                        </div>
                        <div class="col-12 col-md-6  d-flex align-items-end justify-content-end">
                            <a href="{{ route($route.'laporan-kasus-siswa.create', ['idkelas' => Crypt::encrypt($kelas_selected)]) }}"
                                class="btn btn-success">
                                Tambah Kasus Siswa
                            </a>
                        </div>
                    </div>
                    
                    
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-striped table-bordered display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th>Kelas</th>
                                    <th>Jenis Kasus</th>
                                    <th>Tanggal Kasus</th>
                                    <th>Status Kasus</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kasus as $key => $item)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item->nisn}}</td>
                                        <td>{{ $item->siswa->rombel[0]->kelas->kelas }}</td>
                                        <td>{{ $item->siswa->nama ?? '-' }}</td>
                                        <td>{{ $item->jenis_kasus ?? 0 }}</td>
                                        <td>{{ $item->tanggal_kasus ?? 0 }}</td>
                                        <td>
                                            @if ($item->status == 'private')
                                                Penanganan walikelas
                                            @elseif ($item->status == 'open')
                                                Penanganan BK
                                            @elseif ($item->status == 'closed')
                                                Kasus Selesai
                                            @elseif ($item->status == 'sp1')
                                                Surat Peringatan 1
                                            @elseif ($item->status == 'sp2')
                                                Surat Peringatan 2
                                            @elseif ($item->status == 'sp3')
                                                Surat Peringatan 3
                                            @else
                                                -
                                            @endif
                                        </td>
                                        @if(in_array('Hapus', $fiturMenu[$view]) || in_array('Edit', $fiturMenu[$view]))
                                        <td>
                                            <div class="d-flex">
                                                <a href="{{ route($route.'laporan-kasus-siswa.detail', Crypt::encrypt($item->id)) }}"
                                                    class="btn btn-sm btn-info me-2">
                                                    Detail Kasus
                                                </a>
                                                @if(in_array('Edit', $fiturMenu[$view]))
                                                <a href="{{ route($route.'laporan-kasus-siswa.edit', Crypt::encrypt($item->id)) }}"
                                                    class="btn btn-sm btn-secondary me-2">
                                                    Edit
                                                </a>
                                                @endif
                                                @if(in_array('Hapus', $fiturMenu[$view]))
                                                <a href="{{ route($route.'laporan-kasus-siswa.destroy', Crypt::encrypt($item->id)) }}"
                                                    class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
                                                @endif
                                            </div>
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

@endsection

@push('scripts')
@endpush
