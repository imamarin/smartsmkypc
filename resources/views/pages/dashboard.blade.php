@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h1 class="mb-0 display-6 font-size-26">Selamat Datang, {{ Auth::user()->staf->nama ?? Auth::user()->siswa->nama }}</h2>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        {{--  <li class="breadcrumb-item"><a href="javascript: void(0);">Dashboard</a></li>  --}}
                        <li class="breadcrumb-item active">Dashboard</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-body">
                    <p>Selamat datang di fasilitas layanan akademik SMK YPC Tasikmalaya.
                        Fasilitas ini dirancang sebagai salah satu bentuk layanan informasi yang ditujukan untuk seluruh
                        pihak yang terkait dengan SMK YPC Tasikmalaya. Kami berharap fasilitas ini dapat mempermudah akses
                        informasi bagi seluruh jajaran secara efektif dan efisien.
                    </p>
                </div>
            </div>
        </div>
    </div>
    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header bg bg-info">
                    <h6>DOWNLOAD FORMAT DOKUMEN</h6>
                </div><!-- end card header -->
                <div class="card-body">
                </div>
            </div>
        </div>
    </div>
@endsection
