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
                <h4 class="mb-0">Keuangan Siswa</h4>
                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('info-keuangan-siswa') }}">Keuangan Siswa</a></li>
                    </ol>
                </div>

            </div>
        </div>
    </div>
    
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="table-responsive">
                <table class="table table-striped table-bordered display nowrap" id="example">
                    <thead>
                        <tr>
                            <th>#</th>
                            <th style="text-align: center">Nama Keuangan</th>
                            <th style="text-align: center">Biaya</th>
                            <th style="text-align: center">Sisa Tagihan</th>
                            <th style="text-align: center">Keterangan</th>
                        </tr>
                    </thead>
                    <tbody>
                       @foreach ($keuangan as $item)
                            <tr>
                                <td>{{ $loop->iteration }}</td>
                                <td style="text-align: center">{{ $item->keuangan }}</td>
                                <td style="text-align: center">{{ $item->biaya }}</td>
                                <td style="text-align: center">{{ $item->sisa_tagihan }}</td>
                                <td style="text-align: center">{{ $item->keterangan }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            </div>
        </div>
    </div>
@endsection
