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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        var idTable = {!! json_encode($id_table) !!}
        var csrfToken = $('meta[name="csrf-token"]').attr('content');

        function cancel_peminjaman(id) {
            $.ajax({
                url: '{{ route('transaction.cancel_peminjaman') }}',
                type: 'DELETE',
                data: {
                    id: id,
                    _token: csrfToken
                },
                success: function(data) {
                    console.log('berhasil menghapus data');
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: data.message
                    });
                },
                error: function(data) {
                    console.log('data gagal dihapus');
                }
            });
        }

        function getData() {
            $.ajax({
                url: '{{ route('users.loaning.books') }}',
                type: 'GET',
                success: function(data) {
                    var item = data.data;
                    console.log(data); // Debugging data response
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    $.each(item, function(index, item) {
                        var iteration = index + 1; // Use index for row number
                        var row = '<tr>';
                        row += item.action
                        row += '<td>' + iteration + '</td>';
                        row += '<td>' + item.npm + '</td>';
                        row += '<td>' + item.nama + '</td>';
                        row += '<td>' + item.tgl_pinjam + '</td>';
                        row += '<td>' + item.tgl_wajib_kembali + '</td>';
                        row += '<td>' + item.tenggat + '</td>';
                        row += '<td>' + item.status_approval + '</td>';
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
