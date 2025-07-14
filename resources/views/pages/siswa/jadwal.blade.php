@extends('layouts.app')
@section('content')
    @push('styles')
        <style>
            /* .card {
                border: 1px solid #ccc;
                margin: 10px;
                box-shadow: 0 2px 5px rgba(0,0,0,0.1);
            } */
            .card-header {
                cursor: pointer;
            }
            .card-body,
            .card-footer {
                padding: 10px;
                display: none;
            }
    </style>
    @endpush
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Profil Siswa</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('siswa.jadwal') }}">Profil Siswa</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    <!-- end page title -->
    @foreach ($hari as $key => $item)    
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <h4 class="card-title">{{ $item }}</h4>
                </div>
                <div class="card-body">
                    @if (isset($jadwal[$key + 1]))
                    <div class="table-responsive">
                        <table class="table display nowrap">
                            <thead>
                                <tr>
                                    <th style="width: 5%">#</th>
                                    <th>Mata Pelajaran</th>
                                    <th>Guru</th>
                                    <th>Jam ke</th>
                                    <th>Jam Masuk</th>
                                    <th>Jam keluar</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($jadwal[$key+1] as $item_jadwal)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $item_jadwal->nama_matpel }}</td>
                                        <td>{{ $item_jadwal->nama_guru}}</td>
                                        <td>{{ $item_jadwal->jampel }}</td>
                                        <td>{{ $item_jadwal->mulai}}</td>
                                        <td>{{ $item_jadwal->selesai }}</td>
                                    </tr>
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                    @endif
                </div>
            </div>
        </div>
    </div>
    @endforeach
    @push('scripts')
        <script>
            document.querySelectorAll('.card-header').forEach(header => {
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
@endsection
