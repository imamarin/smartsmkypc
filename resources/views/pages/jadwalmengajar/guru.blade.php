@extends('layouts.app')
@push('styles')
<style>
    .card-hide {
        cursor: pointer;
    }
    .card-body-hide,
    .card-footer-hide {
        padding: 10px;
        display: none;
    }
</style>
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Jadwal Mengajar Guru</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Data Jadwal Mengajar Guru</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header card-hide justify-content-between d-flex align-items-center" style="background-color: #167fc6;">
                    <h4 class="card-title text-white">Jadwal Hari ini</h4>
                </div>
                <div class="card-body card-body-hide">
                    <div class="table-responsive">
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 5%" rowspan="2">#</th>
                                    <th rowspan="2">NIP</th>
                                    <th rowspan="2">Guru</th>
                                    <th colspan="{{ $jampel->count() }}" style="text-align: center">Jam Ke</th>
                                </tr>
                                <tr>
                                    @foreach ($jampel as $item)
                                        <th>{{ $item->jam }}</th>
                                    @endforeach
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwalguru as $key => $item)
                                <tr>
                                    <td>{{ $loop->iteration }}</td>
                                    <td>{{ $key }}</td>
                                    <td>{{ $item['nama'] }}</td>
                                    @php
                                        $kelas = '-';
                                        $jumlah = 0;
                                        $hadir = 0;
                                    @endphp
                                    @foreach ($jampel as $item2)
                                        @php
                                            if($jumlah <= 1){
                                                foreach ($item['jam'] as $key => $item3){
                                                    $kelas = '-';
                                                    $hadir = 0;
                                                    if ($item2->jam == $key ){
                                                        $kelas = $item3['kelas'];
                                                        $jumlah = $item3['jumlah'];
                                                        $hadir = $item3['hadir'];
                                                        break;
                                                    }
                                                }
                                            }else{
                                                $jumlah--;
                                            }
                                        @endphp
                                        <td>
                                            @if ($hadir > 0)
                                                <span class="badge bg-success">{{ $kelas }}</span>
                                            @else
                                                @if($kelas != '-')
                                                <span class="badge bg-danger">{{ $kelas }}</span>   
                                                @else
                                                -
                                                @endif
                                            @endif
                                        </td>
                                    @endforeach
                                </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
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
                        <h4 class="card-title">Data Jadwal Mengajar Guru</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end">
                            @if($tahunajaran->kunci_jadwal == '1')
                            <a href="{{ route('kunci', Crypt::encrypt($tahunajaran->id)) }}" class="btn btn-success">Jadwal Mengajar Dibuka</a>
                            @else
                            <a href="{{ route('kunci', Crypt::encrypt($tahunajaran->id)) }}" class="btn btn-danger">Jadwal Mengajar Dikunci</a>
                            @endif
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Kode Guru</th>
                                    <th>Nama</th>
                                    <th>Total Jam Mengajar</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($staf as $key => $item)
                                    <tr>
                                        <td>{{ $key + 1 }}</td>
                                        <td>
                                            {{ $item->nip }}
                                        </td>
                                        <td>
                                            {{ $item->nama }}
                                        </td>
                                        <td>
                                            <span class="{{ $item->jadwal_mengajar_sum < 1 ? 'badge bg-danger' : 'badge bg-info' }}">
                                                {{ $item->jadwal_mengajar_sum > 0 ? ceil($item->jadwal_mengajar_sum / $sistemblok) : 0 }} Jam
                                            </span>
                                        </td>
                                        <td>
                                            <a href="{{ route('data-jadwal-mengajar-guru.show', Crypt::encrypt($item->nip.'*'.$tahunajaran->semester.'*'.$tahunajaran->id)) }}" class="btn btn-sm btn-primary">Lihat Jadwal</a>
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
@endsection
@push('scripts')
    <script>
        document.querySelectorAll('.card-hide').forEach(header => {
            header.addEventListener('click', () => {
                const card = header.parentElement; // ambil parent .card
                const body = card.querySelector('.card-body');
                const footer = card.querySelector('.card-footer');

                const isVisible = body.style.display === 'block';
                body.style.display = isVisible ? 'none' : 'block';
                footer.style.display = isVisible ? 'none' : 'block';
            });
        });
    </script>
@endpush
