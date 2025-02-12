<!doctype html>
<html lang="en">

<head>

    <meta charset="utf-8" />
    <title>Sistem Manajemen Akademik</title>
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <meta content="Premium Multipurpose Admin & Dashboard Template" name="description" />
    <meta content="Pichforest" name="author" />
    <!-- App favicon -->
    <link rel="shortcut icon" href="{{ asset('assets/images/logo-smk-ypc.png') }}">
    <!-- Bootstrap Css -->
    <link href="{{ asset('assets/css/bootstrap.min.css') }}" id="bootstrap-style" rel="stylesheet" type="text/css" />
    <!-- Icons Css -->
    <link href="{{ asset('assets/css/icons.min.css') }}" rel="stylesheet" type="text/css" />
    <!-- App Css-->
    <link href="{{ asset('assets/css/app.min.css') }}" id="app-style" rel="stylesheet" type="text/css" />
    <!-- Material Design Icons -->
    <link href="https://cdn.materialdesignicons.com/5.4.55/css/materialdesignicons.min.css" rel="stylesheet">
    <!-- datatable -->
    <link rel="stylesheet" href="https://cdn.datatables.net/2.2.1/css/dataTables.dataTables.css">
    <link rel="stylesheet" href="https://cdn.datatables.net/responsive/3.0.3/css/responsive.dataTables.css">
    <!-- select 2 -->
    <link href="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/css/select2.min.css" rel="stylesheet" />
    <!-- Plugins css -->
    <link href="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone.css" rel="stylesheet" type="text/css" />
    <style>
        /* General styling for form elements */
        .form-select,
        .form-control,
        .select2-selection {
            padding: 10px;
            font-size: 14px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            outline: none;
            box-sizing: border-box;
            background-color: #fff;
        }

        /* Ensure consistent height across input and select */
        .form-select,
        .form-control,
        .select2-selection {
            height: auto;
            line-height: 1.5;
        }

        /* Styling for select2 elements */
        .select2-container--default .select2-selection--single {
            border: 1px solid #ced4da;
            border-radius: 4px;
            height: 42px;
            /* Match input height */
            display: flex;
            align-items: center;
            background-color: #fff;
        }

        .select2-container--default .select2-selection--single .select2-selection__arrow {
            height: 100%;
            right: 10px;
            top: 50%;
            transform: translateY(-50%);
        }

        /* Styling for multiple select2 elements */
        .select2-container--default .select2-selection--multiple {
            min-height: 50px;
            border: 1px solid #ced4da;
            border-radius: 4px;
            background-color: #fff;
            display: flex;
            flex-wrap: wrap;
            padding: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__rendered {
            display: flex;
            flex-wrap: wrap;
            align-items: center;
            padding: 5px;
        }

        .select2-container--default .select2-selection--multiple .select2-selection__choice {
            margin: 2px;
            padding: 8px 16px;
            background-color: #007bff;
            color: #fff;
            border-radius: 20px;
            font-size: 12px;
        }

        /* Remove default margin and padding for options */
        .select2-container--default .select2-results__options {
            padding: 0;
            margin: 0;
        }

        .select2-container--default .select2-results__option {
            padding: 10px;
            font-size: 14px;
        }
    </style>

    @stack('styles')

</head>


<body>

    <!-- <body data-layout="horizontal"> -->

    <!-- Begin page -->
    <div id="layout-wrapper">
        @include('components.header')

        <!-- ========== Left Sidebar Start ========== -->
        @include('components.sidebar')
        <!-- Left Sidebar End -->

        <!-- ============================================================== -->
        <!-- Start right Content here -->
        <!-- ============================================================== -->
        <div class="main-content">
            <div class="page-content">
                <div class="container-fluid">
                    @yield('content')
                </div>
                <!-- container-fluid -->
            </div>
            <!-- End Page-content -->

            @include('components.footer')
        </div>
        <!-- end main content-->


    </div>
    <!-- END layout-wrapper -->

    <!-- Right bar overlay-->
    <div class="rightbar-overlay"></div>

    <!-- JAVASCRIPT -->
    <script src="https://code.jquery.com/jquery-3.7.1.js"></script>
    <script src="{{ asset('assets/libs/bootstrap/js/bootstrap.bundle.min.js') }}"></script>
    <script src="{{ asset('assets/libs/metismenujs/metismenujs.min.js') }}"></script>
    <script src="{{ asset('assets/libs/simplebar/simplebar.min.js') }}"></script>
    <script src="{{ asset('assets/libs/feather-icons/feather.min.js') }}"></script>
    <!-- analytics dashboard init -->
    <script src="{{ asset('assets/js/pages/dashboard-analytics.init.js') }}"></script>
    <script src="{{ asset('assets/js/app.js') }}"></script>
    <!-- datatable -->
    <script src="https://cdn.datatables.net/2.2.1/js/dataTables.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/dataTables.responsive.js"></script>
    <script src="https://cdn.datatables.net/responsive/3.0.3/js/responsive.dataTables.js"></script>
    <!-- select 2 -->
    <script src="https://cdn.jsdelivr.net/npm/select2@4.1.0-rc.0/dist/js/select2.min.js"></script>
    <!-- Plugins js -->
    <script src="https://unpkg.com/dropzone@6.0.0-beta.1/dist/dropzone-min.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons/font/bootstrap-icons.css">


    <script>
        // Inisialisasi Select2
        $('.select2').select2();

        // Atur ulang dropdown untuk Select2 di dalam modal
        $('.modal').on('shown.bs.modal', function() {
            $(this).find('.select2').select2({
                dropdownParent: $(this)
            });
        });
        new DataTable('#example', {
            paging: false,
            responsive: {
                details: {
                    renderer: function(api, rowIdx, columns) {
                        let data = columns
                            .map((col, i) => {
                                return col.hidden ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            })
                            .join('');

                        let table = document.createElement('table');
                        table.innerHTML = data;

                        return data ? table : false;
                    }
                }
            }
        });

        new DataTable('#example2', {
            paging: true,
            responsive: {
                details: {
                    renderer: function(api, rowIdx, columns) {
                        let data = columns
                            .map((col, i) => {
                                return col.hidden ?
                                    '<tr data-dt-row="' +
                                    col.rowIndex +
                                    '" data-dt-column="' +
                                    col.columnIndex +
                                    '">' +
                                    '<td>' +
                                    col.title +
                                    ':' +
                                    '</td> ' +
                                    '<td>' +
                                    col.data +
                                    '</td>' +
                                    '</tr>' :
                                    '';
                            })
                            .join('');

                        let table = document.createElement('table');
                        table.innerHTML = data;

                        return data ? table : false;
                    }
                }
            }
        });

        

        function formatTanggal(isoString) {
            const date = new Date(isoString);

            const day = String(date.getUTCDate()).padStart(2, '0');
            const month = String(date.getUTCMonth() + 1).padStart(2, '0'); // Month dimulai dari 0
            const year = date.getUTCFullYear();
            const hours = String(date.getUTCHours()).padStart(2, '0');
            const minutes = String(date.getUTCMinutes()).padStart(2, '0');
            const seconds = String(date.getUTCSeconds()).padStart(2, '0');

            return `${day}-${month}-${year} ${hours}:${minutes}:${seconds}`;
        }
    </script>

    @stack('scripts')
    @include('sweetalert::alert')
</body>

</html>
