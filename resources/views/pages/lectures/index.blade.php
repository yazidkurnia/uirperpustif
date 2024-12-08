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
                            <label for="nidn" class="form-label">NIDN</label>
                            <input type="text" id="nidn" class="form-control">
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
                url: '{{ route('api.datatable.setup.dosen') }}',
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
                        row += '<td><div class="btn-group">' +
                            '<button type="button" class="btn btn-primary btn-icon rounded-pill dropdown-toggle hide-arrow" data-bs-toggle="dropdown" aria-expanded="false"><box-icon name="cog" color="#ffffff"></box-icon></button>' +
                            '<ul class="dropdown-menu dropdown-menu-start" style="">' +
                            '<li>' +
                            '<button type="button" class="btn btn-white" data-bs-toggle="modal" data-bs-target="#basicModal" onclick="set_value_toform(\'' +
                            item.id + '\', \'' + item.nidn + '\', \'' + item.nama +
                            '\', \'' + item.email + '\')">Aktifasi Akun</button>' + '</li>' +
                            '<li>' +
                            '<button type="button" class="btn btn-white" onclick="confirm_to_delete(\'' +
                            item.id + '\', \'' + item.role_name +
                            '\')">Disaktif Akun</button>' + '</li>' +
                            // Hapus parameter 'mahasiswa'
                            '</ul>' +
                            '</div>' +
                            '</td>';
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

        function set_value_toform(id, nidn, nama, email) {
            // Mengisi modal dengan data yang diterima
            $('#id').val(id);
            $('#nama').val(nama);
            $('#nidn').val(nidn); // Reset email field atau set jika ada data
            $('#email_field').val(email); // Reset email field atau set jika ada data
            $('#dobBasic').val(''); // Reset DOB field atau set jika ada data
        }

        function set_account() {
            // Mengambil token CSRF dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('setting.user.account') }}',
                type: 'POST',
                data: {
                    id: $('#id').val(),
                    nama: $('#nama').val(),
                    npm: $('#nidn').val(),
                    email: $('#email_field').val(),
                    _token: csrfToken,
                    role: 'Dosen'
                },
                success: function(data) {
                    console.log(data);
                    $('#basicModal').modal('hide');
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: "Berhasil menambahkan data!"
                    });
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                    $('#basicModal').modal('hide');
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        text: "Maaf terjadi kesalahan pada saat menyimpan data, periksa kembali data yang anda inputkan!"
                    });
                }
            })
        }

        function delete_account(idFromLevel, role) {
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(role);
            $.ajax({
                url: '{{ route('user.delete.account') }}', // Ganti dengan URL yang sesuai
                type: 'DELETE',
                data: {
                    id: idFromLevel,
                    role: role,
                    _token: csrfToken
                },
                success: function(response) {
                    Swal.fire(
                        'Dihapus!',
                        'Data telah berhasil dihapus.',
                        'success'
                    );
                },
                error: function(xhr, status, error) {
                    Swal.fire(
                        'Gagal!',
                        'Terjadi kesalahan saat menghapus data.',
                        'error'
                    );
                }
            });
        }

        function confirm_to_delete(idFromLevel, role) {
            Swal.fire({
                title: 'Apakah Anda yakin?',
                text: "Anda tidak dapat mengembalikan data ini setelah dihapus!",
                icon: 'warning',
                showCloseButton: true,
                showCancelButton: true,
                focusConfirm: false,
                confirmButtonText: `
            <i class="fa fa-thumbs-up"></i> Yess!
        `,
                confirmButtonAriaLabel: "Thumbs up, great!",
                cancelButtonText: `
            <i class="fa fa-thumbs-down"></i> Nah!
        `,
                cancelButtonAriaLabel: "Thumbs down"
            }).then((result) => {
                if (result.isConfirmed) {
                    // Panggil fungsi untuk menghapus data
                    delete_account(idFromLevel, role); // Ganti dengan fungsi yang sesuai
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
