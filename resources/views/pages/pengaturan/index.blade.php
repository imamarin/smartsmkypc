@extends('layouts.app')

@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Pengaturan</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Pengaturan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header justify-content-between d-flex align-items-center">
                    <h4 class="card-title">Logo Aplikasi</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <div>
                        <form action="#" method="POST" enctype="multipart/form-data">
                            @csrf
                            <div class="d-flex align-items-center">
                                <!-- Gambar Pratinjau -->
                                <div>
                                    <img id="preview" src="#" alt="Preview Gambar"
                                        style="max-width: 120px; display: none; border: 1px solid #ddd; border-radius: 4px; padding: 5px;" />
                                </div>
                                <!-- Input File -->
                                <div class="col">
                                    <div class="form-group ms-3">
                                        <label for="file">Pilih File:</label>
                                        <input type="file" name="file" id="file" class="form-control"
                                            onchange="previewImage(event)">
                                    </div>
                                </div>
                            </div>
                        </form>
                    </div>
                    <div class="text-center mt-4">
                        <button type="button" class="btn btn-primary">Simpan File</button>
                    </div>
                </div>
            </div><!-- end card -->
        </div> <!-- end col -->
    </div>
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">List Menu Sidebar</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <a href="{{ route('pengaturan.menuForm') }}" class="btn btn-primary">Tambah Menu</a>
                        </div>
                    </div>
                </div><!-- end card header -->
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table table-bordered border-secondary">
                            <thead>
                                <tr>
                                    <th>Kategori</th>
                                    <th>Menu</th>
                                    <th>URL</th>
                                    <th>Fitur</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($kategori as $kategoriItem)
                                    <!-- Menampilkan Baris untuk Kategori -->
                                    <tr>
                                        <td colspan="7">
                                            <strong>{{ $kategoriItem->kategori }}</strong><br>
                                            Icon : {{ $kategoriItem->icon }}
                                        </td>
                                    </tr>

                                    @foreach ($kategoriItem->menu as $menuItem)
                                        <!-- Menampilkan Baris untuk Menu -->
                                        <tr>
                                            <td></td>
                                            <td>{{ $menuItem->menu }}</td>
                                            <td>{{ $menuItem->url }}</td>

                                            @if ($menuItem->fitur->isNotEmpty())
                                                <td>
                                                    @foreach ($menuItem->fitur as $fiturItem)
                                                        {{ $fiturItem->fitur }}<br>
                                                    @endforeach
                                                </td>
                                            @else
                                                <td>-</td>
                                            @endif

                                            <td>
                                                <a href="{{ route('data-guru.destroy', $menuItem->id) }}"
                                                    class="btn btn-sm btn-warning">Edit</a>
                                            </td>
                                        </tr>
                                    @endforeach
                                @endforeach
                            </tbody>
                        </table>
                    </div>
                </div>
            </div><!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
@push('scripts')
    <script>
        function previewImage(event) {
            const fileInput = event.target;
            const file = fileInput.files[0];
            const preview = document.getElementById('preview');

            if (file) {
                const reader = new FileReader();

                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block'; // Tampilkan gambar setelah dimuat
                };

                reader.readAsDataURL(file); // Membaca file sebagai data URL
            } else {
                preview.src = "#";
                preview.style.display = 'none'; // Sembunyikan jika tidak ada file
            }
        }
    </script>
@endpush
