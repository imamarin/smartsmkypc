@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Masuk Mengajar</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Masuk Mengajar</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-md-3">
            <div class="card">
                <div class="card-header">
                    <h4 class="card-title">Tanggal: {{ date('d-m-Y') }}</h4>
                </div>
                <div class="card-body d-flex flex-column align-items-center justify-content-center text-center">

                    <h1 id="timer">00:00:00</h1>
                    @if(!in_array(date('Y-m-d'), $tanggal_akademik))
                        @if($jadwal->count() > 0)
                        <a href="#" id="masukMengajar" class="btn btn-info btn-lg mt-3">
                            <span class="spinner-border spinner-border-sm"></span>
                            Pengecekan Jadwal
                        </a>
                        @else
                        <button class="btn btn-secondary btn-lg mt-3">
                            Tidak Ada Jadwal
                        </button>
                        @endif
                    @else
                        <button class="btn btn-secondary btn-lg mt-3">
                            Tidak Ada Aktivitas KBM
                        </button>
                    @endif
                    
                </div>
                <div class="card-footer d-flex flex-column align-items-center justify-content-center text-center">
                    <i>Selamat Melaksanakan Kegiatan Belajar dan Mengajar</i>
                </div>
            </div>
        </div>
        <div class="col-md-9">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">Jadwal Hari ini</h4>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example">
                            <thead>
                                <tr>
                                    <th>#</th>
                                    <th>Sesi</th>
                                    <th>Hari</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam Keluar</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Kelas</th>
                                </tr>
                            </thead>
                            <tbody>
                                @php
                                    $hari = ['Senin','Selasa','Rabu','Kamis','Jumat','Sabtu','Minggu'];  
                                    $masukMengajar = 0;
                                    $jampelMulai = 0;
                                    $jampelAkhir = 0;
                                @endphp
                                @foreach ($jadwal as $subject)
                                    @php
                                    if(date('H:i:s', strtotime($subject->jampel->mulai)) <= date('H:i:s') && date('H:i:s', strtotime($subject->waktu_keluar)) >= date('H:i:s')){
                                        $masukMengajar = $subject->id;
                                        $jampelMulai = $subject->jampel->mulai;
                                        $jampelAkhir = $subject->waktu_keluar;
                                    }
                                    @endphp
                                    <tr>
                                        <td>{{ $loop->iteration }} </td>
                                        <td>{{ $subject->sistemblok?->nama_sesi }}</td>
                                        <td>{{ $hari[$subject->jampel->hari-1] }}</td>
                                        <td>Jam Ke: {{ $subject->jampel->jam }} (<label class="jampelMulai">{{ $subject->jampel->mulai }}</label>)</td>
                                        <td>Jam Ke: {{ $subject->jam_keluar }} (<label class="jampelAkhir">{{ $subject->waktu_keluar }}</label>)</td>
                                        <td>{{ $subject->matpel->matpel }}</td>
                                        <td>{{ $subject->kelas->kelas }}</td>
                                    </tr>
                                @endforeach
                                    <button id="cekJadwal" hidden 
                                        data-id-jampel="{{ Crypt::encrypt($masukMengajar) }}" 
                                        data-jampel-mulai="{{ $jampelMulai }}"
                                        data-jampel-akhir="{{ $jampelAkhir }}">
                                    </button>
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
        $(document).ready(function(){
            function timeToSeconds(time) {
                const [h, m, s] = time.replace(/[.:]/g, ":").split(":").map(Number);
                return h * 3600 + m * 60 + s;
            }

            function cekMasukMengajar(){
                const cekJadwal = $("#cekJadwal");
                const masukMengajar = $("#masukMengajar");
                const jampelMulai = $(".jampelMulai");
                const jampelAkhir = $(".jampelAkhir");
                const waktu = $("#timer");
                let status = false;
                jampelMulai.each(function(index){
                    // console.log($(this).text() + " " + jampelAkhir.eq(index).text()+ " "+waktu);
                    const jamMulai = timeToSeconds($(this).text());
                    const jamAkhir = timeToSeconds(jampelAkhir.eq(index).text());
                    const waktuSekarang = timeToSeconds(waktu.text());
                    
                    if(jamMulai <= waktuSekarang && jamAkhir >= waktuSekarang){

                        status = true;

                        return false;
                    }
                    
                })

                if(status)
                {
                    masukMengajar.removeClass("btn-warning")
                    masukMengajar.removeClass("btn-info")
                    masukMengajar.addClass("btn-success")
                    masukMengajar.text("Masuk Mengajar");
                    masukMengajar.attr('href','{{ route('masuk-mengajar.show', ':id') }}'.replace(':id', cekJadwal.attr('data-id-jampel')));

                }else{
                    masukMengajar.removeClass("btn-success")
                    masukMengajar.removeClass("btn-info")
                    masukMengajar.addClass("btn-warning")
                    masukMengajar.text("Belum Waktu Mengajar");
                    masukMengajar.attr('href','#');

                }
                
            }

            setInterval(() => {
                cekMasukMengajar();
            }, 3000);

            setInterval(() => {
                let now = new Date();
                $('#timer').text(now.toLocaleTimeString('id-ID', { hour12: false, timeZone: 'Asia/Jakarta' }));
            }, 1000);
            
        })
        
    </script>
@endpush
