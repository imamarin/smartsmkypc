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
                        <li class="breadcrumb-item active">Kelola Honor Pegawai</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center" style="background-color: #494f4f ;">
                    <h4 class="card-title text-white">Kelola Honor Pegawai</h4>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3 gap-2">
                            <!-- Button to trigger modal -->
                            <a href="{{ route('honorarium-pegawai.kelola.create', Crypt::encrypt($id)) }}"
                                class="btn btn-primary btn-sm">Tambah Data</a>
                            <button class="btn btn-success btn-sm" data-toggle="modal" data-target="#modal-default"><i class="fa fa-upload"></i> Import Data</button>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Nama Guru</th>
                                    <th>Jumlah Jam</th>
                                    <th>Bonus Hadir</th>
                                    <th>Yayasan</th>
                                    <th>Tunj Ms Bakti</th>
                                    <th>Tunj Jabatan</th>
                                    <th>Honor</th>
                                    <th>Sub Non Sert</th>
                                    <th>Jumlah Honor</th>
                                    <th>Tabungan</th>
                                    <th>Arisan</th>
                                    <th>Kurban</th>
                                    <th>Kas 1</th>
                                    <th>Kas 2</th>
                                    <th>Lainnya</th>
                                    <th>Total Honor</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($honor as $subject)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $subject->staf->nip }}</td>
                                        <td>{{ $subject->jml_jam }}</td>
                                        <td>{{ $subject->bonus_hdr }}</td>
                                        <td>{{ $subject->yayasan }}</td>
                                        <td>{{ $subject->tun_jab_bak }}</td>
                                        <td>{{ $subject->tunjab }}</td>
                                        <td>{{ $subject->honor }}</td>
                                        <td>{{ $subject->sub_non_ser }}</td>
                                        <td>{{ $subject->jml }}</td>
                                        <td>{{ $subject->tabungan }}</td>
                                        <td>{{ $subject->arisan }}</td>
                                        <td>{{ $subject->qurban }}</td>
                                        <td>{{ $subject->kas_1 }}</td>
                                        <td>{{ $subject->kas_2 }}</td>
                                        <td>{{ $subject->lainnya }}</td>
                                        <td>{{ $subject->jum_tal }}</td>
                                        <td>
                                            <a href="{{ route('honorarium-pegawai.kelola.edit', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-info btn-sm" >Edit</a>
                                            <a href="{{ route('honorarium-pegawai.kelola.destroy', Crypt::encrypt($subject->id)) }}"
                                                class="btn btn-danger btn-sm" data-confirm-delete="true">Hapus</a>
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
    <div class="modal fade" id="modal-default">
        <div class="modal-dialog">
            <div class="modal-content">
                <form method="post" action="{{ route('honorarium-pegawai.kelola.import', Crypt::encrypt($id)) }}" enctype="multipart/form-data">
                    @csrf
                    <div class="modal-header">
                        <h4 class="modal-title">Import Honor Staf</h4>
                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                            <span aria-hidden="true">&times;</span>
                        </button>
                    </div>
                    <div class="modal-body">
                    <p>Silahkan upload file excel yang sudah di export dari aplikasi ini.</p>
                    <p>Format file excel harus sesuai dengan template yang sudah disediakan.</p>
                        <input type="file" name="file" class="form-control">
                    
                    </div>
                    <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" value="import" class="btn btn-primary" name="submit">KIRIM</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
