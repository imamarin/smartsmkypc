@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Pembayaran Keuangan Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pembayaran Keuangan Siswa</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header text-white"  style="background-color: #494f4f;">
                    <div class="row">
                        <div class="col col-md-2">
                            <h6 class="text-white">NISN:</h6>
                            {{ $siswa->nisn_dapodik }}
                        </div>
                        <div class="col col-md-3">
                            <h6 class="text-white">Nama Siswa:</h6>
                            {{ $siswa->nama }}
                        </div>
                        <div class="col col-md-2">
                            <h6 class="text-white">Kelas:</h6>
                            {{ $siswa->rombel[0]->kelas->kelas ?? '-' }}
                        </div>
                        <div class="col col-md-3">
                            <h6 class="text-white">Nama Pembayaran:</h6>
                            {{ $detailnonspp[0]->nonspp->kategorikeuangan->nama ?? '-' }}
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th style="text-align: start">Uang Masuk (Rp)</th>
                                    <th>Tanggal Pembayaran</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($detailnonspp as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td style="text-align: start">{{ number_format($item->bayar, '0', ',', '.') }}</td>
                                    <td>{{ date('d-m-Y',strtotime($item->updated_at ?? $item->created_at)) }}</td>
                                    <td>
                                        <button class="btn btn-primary btn-sm" data-bs-toggle="modal"
                                            data-bs-target="#editSubjectModal" 
                                            data-id="{{ Crypt::encrypt($item->id) }}"
                                            data-bayar="{{ $item->bayar }}"
                                            data-tanggal="{{ date('Y-m-d',strtotime($item->updated_at ?? $item->created_at)) }}">
                                            Edit
                                        </button>
                                        <a href="{{ route('pembayaran-lain.detailnonspp.destroy', Crypt::encrypt($item->id)) }}"
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

    <div class="modal fade" id="editSubjectModal" tabindex="-1" aria-labelledby="editSubjectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editSubjectModalLabel">Ubah Uang masuk</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <form id="subjectForm" action="" method="POST">
                    @csrf
                    <div class="modal-body">
                        <input type="hidden" id="subjectId" name="id">
                        <div class="mb-3">
                            <label for="bayar" class="form-label">Uang Masuk: </label>
                            <input type="text" name="bayar" id="bayar" class="form-control">
                        </div>
                        <div class="mb-3">
                            <label for="tanggal" class="form-label">Tanggal Pembayaran: </label>
                            <input type="date" name="tanggal" id="tanggal" class="form-control">
                        </div>
                        <div class="modal-footer">
                            <button type="submit" class="btn btn-primary" id="submitBtn">UBAH</button>
                        </div>

                    </div>
                </form>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script>
        const modal = document.getElementById('editSubjectModal');
        modal.addEventListener('show.bs.modal', function(event) {
            console.log("ss");
            const button = event.relatedTarget;
            const dataId = button.getAttribute('data-id');
            const dataBayar = button.getAttribute('data-bayar');
            const dataTanggal = button.getAttribute('data-tanggal');

            const bayar = document.getElementById('bayar');
            const tanggal = document.getElementById('tanggal');
            const form = document.getElementById('subjectForm');
            
            let formatted = dataTanggal.replace(' ', 'T').slice(0, 16);
            form.action = '{{ route('pembayaran-lain.detail.update', ':id') }}'.replace(':id', dataId);
            bayar.value = formatRupiah(dataBayar);
            tanggal.value = formatted;
        });

        function formatRupiah(angka) {
            return new Intl.NumberFormat('id-ID', {
                style: 'currency',
                currency: 'IDR',
                minimumFractionDigits: 0
            }).format(angka);
        }

        const input = document.getElementById('bayar');
            input.addEventListener('input', function (e) {
            let value = this.value.replace(/[^,\d]/g, '').toString();
            let split = value.split(',');
            let sisa = split[0].length % 3;
            let rupiah = split[0].substr(0, sisa);
            let ribuan = split[0].substr(sisa).match(/\d{3}/gi);

            if (ribuan) {
                let separator = sisa ? '.' : '';
                rupiah += separator + ribuan.join('.');
            }

            rupiah = split[1] !== undefined ? rupiah + ',' + split[1] : rupiah;
            this.value = rupiah ? 'Rp ' + rupiah : '';
        });
    </script>
@endpush
