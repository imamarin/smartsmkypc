@extends('layouts.app')

@section('content')
    <div class="row">
        <div class="col-12">
            <div class="page-title-box d-flex align-items-center justify-content-between">
                <h4 class="mb-0">Role & Hak Akses</h4>

                <div class="page-title-right">
                    <ol class="breadcrumb m-0">
                        <li class="breadcrumb-item"><a href="{{ route('dashboard') }}">Dashboard</a></li>
                        <li class="breadcrumb-item active">Role & Hak Akses</li>
                    </ol>
                </div>

            </div>
        </div>
    </div>

    <div class="row">
        <div class="col-lg-12">
            <div class="card">
                <div class="card-header d-flex align-items-center">
                    <div class="col">
                        <h4 class="card-title">List Role</h4>
                    </div>
                    <div class="col">
                        <div class="d-flex justify-content-end mb-3">
                            <button class="btn btn-primary" data-toggle="modal" data-target="#addRoleModal">
                                <i class="fas fa-plus fa-sm"></i> Tambah Role
                            </button>
                        </div>
                    </div>
                </div>
                <div class="card-body">
                    <div class="table-responsive">
                        <table class="table display nowrap" id="example2">
                            <thead>
                                <tr>
                                    <th>No</th>
                                    <th>Role</th>
                                    <th>Aksi</th>
                                </tr>
                            </thead>
                            <tbody>
                                @foreach ($roles as $role)
                                    <tr>
                                        <td>{{ $loop->iteration }}</td>
                                        <td>{{ $role->role }}</td>
                                        <td>
                                            <button class="btn btn-info btn-sm" data-toggle="modal"
                                                data-target="#editRoleModal" onclick="editRole({{ $role->id }})">
                                                <i class="fas fa-edit"></i>
                                            </button>
                                            <button class="btn btn-warning btn-sm" data-toggle="modal"
                                                data-target="#accessModal" onclick="manageAccess({{ $role->id }})">
                                                <i class="fas fa-key"></i> Hak Akses
                                            </button>
                                            <button class="btn btn-danger btn-sm" onclick="deleteRole({{ $role->id }})">
                                                <i class="fas fa-trash"></i>
                                            </button>
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

    <!-- Add Role Modal -->
    <div class="modal fade" id="addRoleModal" tabindex="-1" role="dialog" aria-labelledby="addRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="addRoleModalLabel">Tambah Role Baru</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form action="{{ route('role.store') }}" method="POST">
                    @csrf
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="role">Nama Role</label>
                            <input type="text" class="form-control" id="role" name="role" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Edit Role Modal -->
    <div class="modal fade" id="editRoleModal" tabindex="-1" role="dialog" aria-labelledby="editRoleModalLabel"
        aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="editRoleModalLabel">Edit Role</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="editRoleForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="form-group">
                            <label for="edit_role">Nama Role</label>
                            <input type="text" class="form-control" id="edit_role" name="role" required>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <!-- Access Rights Modal -->
    <div class="modal fade" id="accessModal" tabindex="-1" role="dialog" aria-labelledby="accessModalLabel"
        aria-hidden="true">
        <div class="modal-dialog modal-lg" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="accessModalLabel">Pengaturan Hak Akses</h5>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                <form id="accessForm" method="POST">
                    @csrf
                    @method('PUT')
                    <div class="modal-body">
                        <div class="table-responsive">
                            <table class="table table-bordered">
                                <thead>
                                    <tr>
                                        <th>Fitur Menu</th>
                                        <th>Akses</th>
                                    </tr>
                                </thead>
                                <tbody id="accessTableBody">
                                    <!-- Will be filled dynamically -->
                                </tbody>
                            </table>
                        </div>
                    </div>
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Tutup</button>
                        <button type="submit" class="btn btn-primary">Simpan Perubahan</button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    @push('scripts')
        <script>
            function editRole(roleId) {
                $.get(`/pages/role/${roleId}/edit`, function(data) {
                    $('#edit_role').val(data.role);
                    $('#editRoleForm').attr('action', `/pages/role/${roleId}`);
                });
            }

            function manageAccess(roleId) {
                $.get(`/pages/role/${roleId}/access`, function(data) {
                    let html = '';
                    data.forEach(function(item) {
                        html += `
                        <tr>
                            <td class='bg bg-secondary text-white'>
                                <span>${item.menu}</span>
                            </td>
                            <td class='bg bg-secondary text-white' style='border-left:none;'>
                                <div class="custom-control custom-switch">
                                    <input type="checkbox" class="custom-control-input select-all"
                                        data-menu-id="${item.id}" id="access_all_${item.id}" 
                                        name="access_all[]" value="${item.id}"
                                        ${item.has_access ? 'checked' : ''}>
                                    <label class="custom-control-label" for="access_all_${item.id}"></label>
                                </div>
                            </td> 
                        </tr>`;
                        item.fitur.forEach(function(item, index){
                            html += `
                            <tr>
                                <td>&#x2022 ${item.fitur}</td>
                                <td>
                                    <div class="custom-control custom-switch">
                                        <input type="checkbox" class="custom-control-input fitur-checkbox"
                                            data-menu-id="${item.menu_id}" id="access_${item.id}" 
                                            name="access[]" value="${item.id}"
                                            ${item.has_access ? 'checked' : ''}>
                                        <label class="custom-control-label" for="access_${item.id}"></label>
                                    </div>
                                </td>
                            </tr>`;
                        });
                        
                    });
                    $('#accessTableBody').html(html);
                    $('#accessForm').attr('action', `/pages/role/${roleId}/access`);
                });
            }

            function manageAccess2(roleId) {
                $.get(`/pages/role/${roleId}/access`, function(data) {
                    let html = '';
                    data.forEach(function(item) {
                        html += `
                    <tr>
                        <td>${item.menu}</td>
                        <td>${item.fitur}</td>
                        <td>
                            <div class="custom-control custom-switch">
                                <input type="checkbox" class="custom-control-input"
                                    id="access_${item.id}" name="access[]" value="${item.id}"
                                    ${item.has_access ? 'checked' : ''}>
                                <label class="custom-control-label" for="access_${item.id}"></label>
                            </div>
                        </td>
                    </tr>
                `;
                    });
                    $('#accessTableBody').html(html);
                    $('#accessForm').attr('action', `/pages/role/${roleId}/access`);
                });
            }

            function deleteRole(roleId) {
                Swal.fire({
                    title: 'Apakah Anda yakin?',
                    text: "Role yang dihapus tidak dapat dikembalikan!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#d33',
                    cancelButtonColor: '#3085d6',
                    confirmButtonText: 'Ya, hapus!',
                    cancelButtonText: 'Batal'
                }).then((result) => {
                    if (result.isConfirmed) {
                        $.ajax({
                            url: `/pages/role/${roleId}`,
                            type: 'DELETE',
                            data: {
                                _token: '{{ csrf_token() }}'
                            },
                            success: function() {
                                Swal.fire(
                                    'Terhapus!',
                                    'Role berhasil dihapus.',
                                    'success'
                                ).then(() => {
                                    location.reload();
                                });
                            },
                            error: function() {
                                Swal.fire(
                                    'Gagal!',
                                    'Terjadi kesalahan saat menghapus role.',
                                    'error'
                                );
                            }
                        });
                    }
                });
            }

            $(document).on('change', '.select-all', function () {
                const menuId = $(this).data('menu-id');
                const isChecked = $(this).is(':checked');
                $(`.fitur-checkbox[data-menu-id="${menuId}"]`).prop('checked', isChecked);
            });
        </script>
    @endpush
@endsection
