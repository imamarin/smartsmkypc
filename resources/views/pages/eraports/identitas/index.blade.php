@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Data E-Raport</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data E-Raport</li>
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
                        <h4 class="card-title">Daftar Aktivasi Raport</h4>
                        {{ Storage::get('aktivasi'); }}
                    </div>
                    @if(in_array('Tambah', $fiturMenu[$view]))
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <!-- Button to trigger modal -->
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addSubjectModal">Tambah Data</button>
                        </div>
                    </div>
                    @endif
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example2">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Tanggal Terima Raport</th>
                                    <th>Semester</th>
                                    <th>Tahun Ajaran</th>
                                    <th>Kepala Sekolah</th>
                                    @if(in_array('Ubah Status', $fiturMenu[$view]))
                                    <th>Status Aktivasi</th>
                                    @endif
                                    @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                    <th>Aksi</th>
                                    @endif
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($identitas as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>{{ $item->tanggal_terima_raport }}</td>
                                        <td>{{ $item->semester }}</td>
                                        <td>{{ $item->tahunajaran->awal_tahun_ajaran }} / {{ $item->tahunajaran->akhir_tahun_ajaran }}</td>
                                        <td>{{ $item->kepala_sekolah }}</td>
                                        @if(in_array('Ubah Status', $fiturMenu[$view]))
                                        <td>
                                            <form action="{{ route('raport.aktivasi', Crypt::encrypt($item->id)) }}"
                                                method="post">
                                                @csrf
                                                <div class="form-check form-switch">
                                                    <input class="form-check-input" type="checkbox" role="switch"
                                                        id="flexSwitchCheckDefault"
                                                        {{ $aktivasi?->id == $item->id ? 'checked' : '' }}
                                                        onchange="this.form.submit()">
                                                    <span
                                                        class="badge {{ $aktivasi?->id == $item->id ? 'bg-success' : 'bg-danger' }}">{{ $aktivasi?->id == $item->id ? 'Aktif' : 'Tidak Aktif' }}</span>
                                                </div>
                                                <input type="hidden" name="status_raport"
                                                    value="{{ $aktivasi?->id == $item->id ? 0 : 1 }}">
                                            </form>
                                        </td>
                                        @endif
                                        @if(in_array('Edit', $fiturMenu[$view]) || in_array('Hapus', $fiturMenu[$view]))
                                        <td>
                                            @if(in_array('Edit', $fiturMenu[$view]))
                                            <button class="btn btn-sm btn-secondary" data-bs-toggle="modal"
                                                data-bs-target="#addSubjectModal"  data-id="{{ Crypt::encrypt($item->id) }}"
                                                data-tanggal="{{ base64_encode($item->tanggal_terima_raport) }}"
                                                data-nama_sekolah="{{ base64_encode($item->nama_sekolah) }}"
                                                data-nss_sekolah="{{ base64_encode($item->nss_sekolah) }}"
                                                data-kepala_sekolah="{{ base64_encode($item->kepala_sekolah) }}"
                                                data-email="{{ base64_encode($item->email) }}"
                                                data-website="{{ base64_encode($item->website) }}"
                                                data-alamat="{{ base64_encode($item->alamat) }}">
                                                Edit
                                            </button>
                                            @endif
                                            @if(in_array('Hapus', $fiturMenu[$view]))
                                            <a href="{{ route('raport-identitas.destroy', Crypt::encrypt($item->id)) }}"
                                                class="btn btn-sm btn-danger" data-confirm-delete="true">Hapus</a>
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

    <div class="modal fade" id="addSubjectModal" tabindex="-1" aria-labelledby="addSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addSubjectModalLabel">Input Identitas Raport Baru</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="identitasForm" action="{{ route('raport-identitas.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="identitasId" name="id">
                        <input type="hidden" name="_method" id="formMethod" value="POST">
                        <div class="mb-3">
                            <label for="tanggal_terima_raport" class="form-label">Tanggal Terima Raport</label>
                            <input type="date" class="form-control" id="tanggal_terima_raport" name="tanggal_terima_raport" required>
                        </div>
                        <div class="mb-3">
                            <label for="nama_sekolah" class="form-label">Nama Sekolah</label>
                            <input type="text" class="form-control" id="nama_sekolah" name="nama_sekolah" required>
                        </div>
                        <div class="mb-3">
                            <label for="nss_sekolah" class="form-label">NSS Sekolah</label>
                            <input type="text" class="form-control" id="nss_sekolah" name="nss_sekolah"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="kepala_sekolah" class="form-label">Kepala Sekolah</label>
                            <input type="text" class="form-control" id="kepala_sekolah" name="kepala_sekolah"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="email" class="form-label">Email Sekolah</label>
                            <input type="email" class="form-control" id="email" name="email"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="website" class="form-label">Website Sekolah</label>
                            <input type="text" class="form-control" id="website" name="website"
                                required>
                        </div>
                        <div class="mb-3">
                            <label for="alamat" class="form-label">Alamat Sekolah</label>
                            <input type="text" class="form-control" id="alamat" name="alamat"
                                required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="submit" class="btn btn-primary" id="submitBtn">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection
@push('scripts')
    <script>
        // Script untuk mempersiapkan data saat modal dibuka
        const modal = document.getElementById('addSubjectModal');
        modal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget; // tombol yang memicu modal
            const id = button.getAttribute('data-id');
            
            // const tahun_ajaran = decodedData.idtahunajaran;

            const form = document.getElementById('identitasForm');
            const submitBtn = document.getElementById('submitBtn');
            const modalTitle = document.getElementById('addSubjectModalLabel');
            const formMethod = document.getElementById('formMethod');

            if (id) {
                const tanggal = atob(button.getAttribute('data-tanggal'));
                const nama_sekolah = atob(button.getAttribute('data-nama_sekolah'));
                const nss_sekolah = atob(button.getAttribute('data-nss_sekolah'));
                const kepala_sekolah = atob(button.getAttribute('data-kepala_sekolah'));
                const email = atob(button.getAttribute('data-email'));
                const website = atob(button.getAttribute('data-website'));
                const alamat = atob(button.getAttribute('data-alamat'));
                
                form.action = '{{ route('raport-identitas.update', ':id') }}'.replace(':id', id);
                submitBtn.textContent = 'Simpan Perubahan';
                modalTitle.textContent = 'Update Identitas Raport';
                formMethod.value = 'PUT';

                // Isi data ke form
                document.getElementById('identitasId').value = id;
                document.getElementById('tanggal_terima_raport').value = tanggal;
                document.getElementById('nama_sekolah').value = nama_sekolah;
                document.getElementById('nss_sekolah').value = nss_sekolah;
                document.getElementById('kepala_sekolah').value = kepala_sekolah;
                document.getElementById('email').value = email;
                document.getElementById('website').value = website;
                document.getElementById('alamat').value = alamat;
            } else {
                form.action = '{{ route('raport-identitas.store') }}';
                submitBtn.textContent = 'Simpan';
                modalTitle.textContent = 'Input Identitas Raport Baru';
                formMethod.value = 'POST';
                form.reset();
            }
        });
    </script>
@endpush
