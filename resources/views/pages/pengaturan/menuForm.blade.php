@extends('layouts.app')
@push('styles')
    <style>
        .remove-input {
            position: absolute;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
            border: none;
            background-color: transparent;
            color: red;
            font-weight: bold;
            cursor: pointer;
        }

        .menu-input,
        .url-input {
            position: relative;
            margin-bottom: 10px;
        }
    </style>
@endpush
@section('content')
    <!-- start page title -->
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Form Menu Sidebar</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item"><a href="{{ route('pengaturan.index') }}">Pengaturan</a></li>
                        <li class="breadcrumb-item active">Form Menu Sidebar</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>
    <!-- end page title -->
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <h4 class="card-title">Tambah Menu Sidebar</h4>
                </div><!-- end card header -->
                <div class="card-body">
                    <form action="{{ route('pengaturan.menuForm.store') }}" method="POST">
                        @csrf
                        <div class="form-group mb-2">
                            <label for="kategori" class="form-label">Kategori</label>
                            <input type="text" name="kategori" id="kategori" class="form-control"
                                placeholder="Masukkan Kategori" required>
                        </div>

                        <div class="form-group mb-2">
                            <label for="icon" class="form-label">Icon Kategori</label>
                            <input type="text" name="icon" id="icon" class="form-control"
                                placeholder="Masukkan Icon">
                        </div>

                        <div class="form-group mb-2">
                            <label for="menu" class="form-label">Menu</label>
                            <div id="menu-container">
                                <div class="menu-input">
                                    <input type="text" name="menu[]" class="form-control" placeholder="Masukkan Menu"
                                        required>
                                    <!-- Tombol hapus pertama akan dinonaktifkan -->
                                    <button type="button" class="btn btn-sm btn-danger remove-input"
                                        disabled>Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="add-menu" class="btn btn-sm btn-link">Tambah Menu</button>
                        </div>

                        <div class="form-group mb-2">
                            <label for="url" class="form-label">URL Menu</label>
                            <div id="url-container">
                                <div class="url-input">
                                    <input type="text" name="url[]" class="form-control" placeholder="Masukkan URL"
                                        required>
                                    <!-- Tombol hapus pertama akan dinonaktifkan -->
                                    <button type="button" class="btn btn-sm btn-danger remove-input"
                                        disabled>Hapus</button>
                                </div>
                            </div>
                            <button type="button" id="add-url" class="btn btn-sm btn-link">Tambah URL</button>
                        </div>

                        <div class="form-group mb-2">
                            <label for="fitur" class="form-label">Fitur Menu</label>
                            <div id="fitur-container">
                                <div class="fitur-input">
                                    <select name="fitur[]" class="form-select select2" multiple="multiple" id="">
                                        <option value="Tambah">Tambah</option>
                                        <option value="Ubah">Ubah</option>
                                        <option value="Hapus">Hapus</option>
                                        <option value="Import">Import</option>
                                        <option value="Export">Export</option>
                                    </select>
                                </div>
                            </div>
                        </div>
                        <div class="form-group mt-3">
                            <button type="submit" class="btn btn-primary">Simpan</button>
                        </div>
                    </form>
                </div>
            </div><!-- end card -->
        </div> <!-- end col -->
    </div>
@endsection
@push('scripts')
    <script>
        // Menambahkan Menu Dinamis
        document.getElementById('add-menu').addEventListener('click', function() {
            var menuContainer = document.getElementById('menu-container');
            var menuInputDiv = document.createElement('div');
            menuInputDiv.classList.add('menu-input');

            var newMenuInput = document.createElement('input');
            newMenuInput.setAttribute('type', 'text');
            newMenuInput.setAttribute('name', 'menu[]');
            newMenuInput.setAttribute('class', 'form-control');
            newMenuInput.setAttribute('placeholder', 'Masukkan Menu');

            var removeButton = document.createElement('button');
            removeButton.setAttribute('type', 'button');
            removeButton.setAttribute('class', 'btn btn-sm btn-danger remove-input');
            removeButton.innerText = 'Hapus';

            menuInputDiv.appendChild(newMenuInput);
            menuInputDiv.appendChild(removeButton);

            menuContainer.appendChild(menuInputDiv);

            // Enable tombol hapus untuk input yang baru ditambahkan
            removeButton.disabled = false;
        });

        // Menambahkan URL Dinamis
        document.getElementById('add-url').addEventListener('click', function() {
            var urlContainer = document.getElementById('url-container');
            var urlInputDiv = document.createElement('div');
            urlInputDiv.classList.add('url-input');

            var newUrlInput = document.createElement('input');
            newUrlInput.setAttribute('type', 'text');
            newUrlInput.setAttribute('name', 'url[]');
            newUrlInput.setAttribute('class', 'form-control');
            newUrlInput.setAttribute('placeholder', 'Masukkan URL');

            var removeButton = document.createElement('button');
            removeButton.setAttribute('type', 'button');
            removeButton.setAttribute('class', 'btn btn-sm btn-danger remove-input');
            removeButton.innerText = 'Hapus';

            urlInputDiv.appendChild(newUrlInput);
            urlInputDiv.appendChild(removeButton);

            urlContainer.appendChild(urlInputDiv);

            // Enable tombol hapus untuk input yang baru ditambahkan
            removeButton.disabled = false;
        });

        // Menghapus Input Dinamis, tapi tidak menghapus yang pertama
        document.addEventListener('click', function(e) {
            if (e.target && e.target.classList.contains('remove-input')) {
                var parentDiv = e.target.closest('div');
                var allInputs = parentDiv.parentElement.children;
                if (allInputs.length > 1) {
                    parentDiv.remove(); // Menghapus input kecuali yang pertama
                }
            }
        });
    </script>
@endpush
