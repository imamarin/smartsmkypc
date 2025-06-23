@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Tagihan Keuangan Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Tagihan Keuangan Siswa</li>
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
                        <div class="col">
                            <form action="{{ route('tagihan-keuangan.kelas') }}" method="get">
                                <div class="row">
                                    <div class="col-12 col-md-2">
                                        <label for="idtahunajaran" class="form-label text-white">Tahun Ajaran</label>
                                        <select name="idtahunajaran" id="idtahunajaran" class="form-select select2">
                                            @foreach ($tahunajaran as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $idtahunajaran ? 'selected':'' }}>
                                                    {{ $item->awal_tahun_ajaran.'/'. $item->akhir_tahun_ajaran}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-4">
                                        <label for="idkelas" class="form-label text-white">Kelas</label>
                                        <select name="idkelas" id="idkelas" class="form-select select2">
                                            <option value="">Pilih Kelas</option>
                                            @foreach ($kelas as $item)
                                                <option value="{{ Crypt::encrypt($item->id) }}" {{ $item->id == $idkelas ? 'selected':'' }}>
                                                    {{ $item->kelas}}
                                                </option>
                                            @endforeach
                                        </select>
                                    </div>
                                    <div class="col-12 col-md-3 d-flex align-items-end">
                                        <input type="submit" class="btn btn-primary" value="Tampilkan">
                                    </div>
                                </div>
                            </form>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>NISN</th>
                                    <th>Nama Siswa</th>
                                    <th style="text-align: end">Tagihan SPP</th>
                                    <th style="text-align: end">Tagihan Keuangan Siswa</th>
                                    <th>Cetak</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($rombel as $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $item->nisn }}</td>
                                    <td>{{ $item->nama }}</td>
                                    <td style="text-align: end">{{ $item->spp <= $item->jmlBulan ? $item->jmlBulan - $item->spp : '0' }} Bulan</td>
                                    <td style="text-align: end">{{ number_format($item->totalKeuangan - $item->nonspp <= 0 ? '0' : $item->totalKeuangan - $item->nonspp, '0', ',', '.') }}</td>
                                    <td style="text-align: center">
                                        <a href="{{ route('tagihan-keuangan.print', Crypt::encrypt($item->nisn.'*'.$idkelas.'*'.$idtahunajaran)) }}" class="btn btn-sm btn-primary" target="_blank"><i class="fas fa-print"></i></a>
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
        const tahunajaran = $('#idtahunajaran');
        const kelas = $('#idkelas');
        $('#idtahunajaran').on('change',function(event){
            let url = '{{ route('tagihan-keuangan.kelas.data', ':id') }}'.replace(':id', tahunajaran.val());
            $.get(url, function(data,status){
                kelas.html("");
                let option = new Option('Pilih Kelas', '');
                kelas.append(option);
                if(data.length > 0){
                    data.forEach(element => {
                        let option = new Option(element.kelas, element.id);
                        kelas.append(option);
                    });
                }
            })
        });

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
