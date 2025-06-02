@extends('layouts.app')
@push('styles')
@endpush

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Diagram Aktivitas Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Diagram Aktivitas Siswa</li>
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
                    <form action="{{ route('walikelas.grafik-presensi-siswa') }}" method="get">
                        <div class="row">
                            <div class="col-2">
                                <label for="id" class="form-label">Nama Siswa</label>
                                <select name="id" id="id" class="form-select select2">
                                    @foreach ($siswa as $item)
                                        <option value="{{ encryptSmart($item->nisn) }}" {{ $nisn == $item->nisn ? 'selected' : '' }}>
                                            {{ $item->siswa->nama }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            
                            <div class="col-2 d-flex align-items-end mb-1">
                                <input type="submit" class="btn btn-primary" value="Tampilkan Grafik">
                            </div>
                        </div>
                    </form>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="row {{ $nisn ? 'd-none' : '' }}" class="d-flex justify-content-center">
                        <div class="col-12 d-flex justify-content-center">
                            @foreach ($presensi_siswa_harian as $key => $item)
                            <div class="col-md-9">
                                <canvas id="chartkelas{{ $key }}"></canvas>
                                <script>
                                    const chartkelas{{ $key }} = document.getElementById('chartkelas{{ $key }}').getContext('2d');
                                    Chart.register(ChartDataLabels);
                                    new Chart(chartkelas{{ $key }}, {
                                        type: 'bar',
                                        data: {
                                            labels: {!! json_encode($item['labels']) !!},
                                            datasets: {!! json_encode($item['datasets']) !!}
                                        },
                                        options: {
                                            responsive: true,
                                                interaction: {
                                                intersect: false,
                                            },
                                            scales: {
                                                y: {
                                                    suggestedMin: 0,
                                                    suggestedMax: 100
                                                }
                                            },
                                            plugins: {
                                                title: {
                                                    display: true,
                                                    text: 'Persentase Presensi Harian Kelas {{ $item["nama_kelas"] ?? "" }}'
                                                },
                                                datalabels: {
                                                    display: false,
                                                    formatter: (value) => value +"%"
                                                }
                                            }
                                        },
                                    });
                                </script>
                            </div>
                            @endforeach
                            {{-- @foreach ($presensi_kelas as $key => $item)
                            <div class="col-md-4">
                                <canvas id="chartkelas{{ $key }}"></canvas>
                                <script>
                                    const chartkelas{{ $key }} = document.getElementById('chartkelas{{ $key }}').getContext('2d');
                                    Chart.register(ChartDataLabels);
                                    new Chart(chartkelas{{ $key }}, {
                                        type: 'pie',
                                        data: {
                                            labels: ['Hadir','Sakit','Izin','Tanpa Keterangan'],
                                            datasets: [{
                                                label: 'Persentase Kehadiran',
                                                data: {!! json_encode($item['keterangan']) !!},
                                                backgroundColor: ['#4BC0C0', '#FFCD56',  '#36A2EB', '#FF9F40'],
                                            }]
                                        },
                                        options: {
                                            responsive: true,
                                            plugins: {
                                                legend: {
                                                    position: 'top',
                                                },
                                                title: {
                                                    display: true,
                                                    text: 'Persentase Kehadiran {{ $item["kelas"] }}'
                                                },
                                                datalabels: {
                                                    color: 'white',
                                                    font: { weight: 'bold', size: 14 },
                                                    formatter: (value) => value +"%"
                                                }
                                            }
                                        },
                                    });
                                </script>
                            </div>
                            @endforeach --}}
                        </div>
                    </div>
                    <div class="row {{ $nisn ? '' : 'd-none' }}">
                        <div class="col-md-6 text-center">
                            <h6>Persentase Presensi Harian</h6>
                            <canvas id="lineChart"></canvas>
                        </div>
                        <div class="col-md-6 text-center">
                            <h6>Persentase Presensi per Mata Pelajaran</h6>
                            <canvas id="lineChart2"></canvas>
                        </div>
                    </div>
                    
                </div>
            </div>
        </div>
    </div>

@endsection

@push('scripts')
<script>
document.addEventListener("DOMContentLoaded", function() {
    const ctx = document.getElementById('lineChart').getContext('2d');
    const ctx2 = document.getElementById('lineChart2').getContext('2d');
    new Chart(ctx, {
        type: 'line',
        data: {
            labels: {!! json_encode($labels_presensi_harian) !!},
            datasets: [{
                label: 'Persentase per Bulan',
                data: {!! json_encode($nilai_presensi_harian) !!},
                backgroundColor: 'rgba(75, 192, 192, 0.2)',
                borderColor: 'rgba(75, 192, 192, 1)',
                borderWidth: 2,
                fill: true
            }]
        },
        options: {
            responsive: true,
                interaction: {
                intersect: false,
            },
            scales: {
                x: {
                    display: true,
                    title: {
                        display: true
                    }
                },
                y: {
                    display: true,
                    title: {
                        display: true,
                        text: 'Value'
                    },
                    suggestedMin: 0,
                    suggestedMax: 100
                },
                
            },
            plugins: {
                datalabels: {
                    display: false
                }
            }
        }
    });

    new Chart(ctx2, {
        type: 'bar',
        data: {
            labels: {!! json_encode($labels_presensi_matpel['ganjil'] ?? '') !!},
            datasets: [{
                label: 'Ganjil',
                data: {!! json_encode($nilai_presensi_matpel['ganjil'] ?? '') !!},
                backgroundColor: 'rgba(75, 192, 192)',
                stack: 0
            },{
                label: 'Genap',
                data: {!! json_encode($nilai_presensi_matpel['genap'] ?? '') !!},
                backgroundColor: 'rgb(255, 193, 7)',
                stack: 1
            }]
        },
        options: {
            responsive: true,
                interaction: {
                intersect: false,
            },
            scales: {
                y: {
                    suggestedMin: 0,
                    suggestedMax: 100
                }
            },
            plugins: {
                datalabels: {
                    display: false,
                    formatter: (value) => value +"%"
                }
            }
        }
    });
});
</script>
@endpush
