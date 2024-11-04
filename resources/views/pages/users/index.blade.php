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

    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2/dist/umd/popper.min.js"></script>
    <script src="https://stackpath.bootstrapcdn.com/bootstrap/5.1.3/js/bootstrap.min.js"></script>
    <script>
        var idTable = {!! json_encode($id_table) !!}

        function updateRole(id) {
            // Mengambil token CSRF dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            $.ajax({
                url: '{{ route('user.update.role') }}',
                type: 'PUT',
                data: {
                    user_id: id,
                    _token: csrfToken // Menambahkan token CSRF ke data
                },
                success: function(data) {
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    // getData();
                    console.log(data);
                },
                error: function(jqXHR, textStatus, errorThrown) {
                    console.log(errorThrown);
                }
            })
        }

        function getData() {
            $.ajax({
                url: '{{ route('users.datatable') }}',
                type: 'GET',
                success: function(data) {
                    var user = data.data;
                    console.log(data); // Debugging data response
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    $.each(user, function(index, user) {
                        var iteration = index + 1; // Use index for row number
                        var row = '<tr>';
                        row +=
                            '<td><div class="dropdown">' +
                            '<button class="btn" type="button" id="dropdownMenuButton' + iteration +
                            '" data-bs-toggle="dropdown" aria-expanded="false">' +
                            '<box-icon type="solid" name="cog"></box-icon>' +
                            '</button>' +
                            '<ul class="dropdown-menu" aria-labelledby="dropdownMenuButton' +
                            iteration + '">' +
                            '<li><a class="dropdown-item" href="#" onclick="updateRole(\'' + user.id +
                            '\')">Update Role</a></li>' +
                            '<li><a class="dropdown-item" href="#">Another action</a></li>' +
                            '<li><a class="dropdown-item" href="#">Something else here</a></li>' +
                            '</ul></div></td>';
                        row += '<td>' + iteration + '</td>';
                        row += '<td>' + user.name + '</td>';
                        row += '<td>' + user.email + '</td>';
                        row += '<td>' + user.email_verified_at + '</td>';
                        row += '</tr>';
                        $('#dataTable tbody').append(row);
                    });
                },
                error: function(errorData) {
                    console.log(errorData);
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
