@extends('index')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library</li>
            </ol>
        </nav>

        <!-- DataTable with Buttons -->
        <div class="card">
            <div class="card-header d-flex justify-content-between align-items-center">
                <h5 class="mb-0">{{ $title }}</h5>
            </div>
            <div class="card-body">
                <div class="mb-3">
                    <div class="col-lg-4">
                        <input type="text" id="searchInput" class="form-control" placeholder="Search..." />
                    </div>
                </div>
                @include('partials.table')
            </div>
        </div>
    </div>

    <div class="modal fade" id="basicModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Modal title</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <input type="hidden" id="id" name="id">
                    <div class="row">
                        <div class="col mb-6">
                            <label for="nama" class="form-label">Name</label>
                            <input type="text" id="nama" name="nama" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="email_field" class="form-label">Email</label>
                            <input type="text" id="email_field" class="form-control">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="npm" class="form-label">Npm</label>
                            <input type="text" id="npm" class="form-control">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary mx-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="set_account()">Aktifkan Akun</button>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var idTable = {!! json_encode($id_table) !!}

        function getData() {
            $.ajax({
                url: '{{ route('api.datatable.setup.admin') }}',
                type: 'GET',
                success: function(data) {
                    var item = data.data;
                    console.log(data); // Debugging data response
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    $.each(item, function(index, item) {
                        var iteration = index + 1; // Use index for row number
                        var row = '<tr>';
                        // row +=
                        //     '<td><button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="set_value_toform(\'' +
                        //     item.id + '\', \'' + item.npm + '\', \'' + item.nama +
                        //     '\', \'' + item.email + '\')">Ubah Role</button></td>';
                        row += item.action;
                        row += '<td>' + iteration + '</td>';
                        row += '<td>' + item.nidn + '</td>';
                        row += '<td>' + item.nama + '</td>';
                        // row += '<td>' + collager.email_verified_at + '</td>';
                        row += '</tr>';
                        $('#dataTable tbody').append(row);
                    });
                },
                error: function(errorData) {
                    console.log(errorData);
                }
            });
        }

        function setAsAdm(id) {
            // Mengambil ID dari parameter
            var lectureId = '' + id;
            encryptedLectureId = lectureId.replace(new RegExp('/', 'g'), '');
            // Mengambil token CSRF dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            $.ajax({
                url: '{{ route('update.role.to.admin') }}',
                type: 'PUT',
                data: {
                    '_token': csrfToken,
                    'id': encryptedLectureId,
                },
                success: function(data) {
                    console.log(data);
                    if (data.success == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Yeay...",
                            text: data.message
                        });
                    } else {
                        Swal.fire({
                            icon: "error",
                            title: "Oups...",
                            text: "Gagal merubah data, periksa data atau hubungi contact support"
                        });
                    }

                },
                error: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        text: data.message
                    });
                }
            });
        }

        $(document).ready(function() {
            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                // Assuming you are using DataTables, you can implement search here
            });

            // Call the function to get data
            getData();
        });
    </script>
@endsection
