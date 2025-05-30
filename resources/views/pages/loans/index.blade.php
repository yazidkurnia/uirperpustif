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

    <div class="modal fade" id="modalToggle" aria-labelledby="modalToggleLabel" tabindex="-1" style="display: none;"
        aria-hidden="true">
        <div class="modal-dialog modal-dialog-centered">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="modalToggleLabel">Modal 1</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <img id="qrCodeImage" src="" alt="failed" srcset="">
                </div>
                <div class="modal-footer">
                    <button class="btn btn-primary" data-bs-target="#modalToggle2" data-bs-toggle="modal"
                        data-bs-dismiss="modal">Open second modal</button>
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
                    getData();
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: data.message
                    });
                },
                error: function(data) {
                    console.log('data gagal dihapus');

                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        text: 'Tidak dapat menghapus data'
                    });

                }
            });
        }

        function getData() {
            $.ajax({
                url: '{{ route('users.loaning.books') }}',
                type: 'GET',
                success: function(data) {
                    var item = data.data;
                    $('#dataTable tbody').empty();
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
                        row += '<td>' + item.alasan + '</td>';
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

        function show_qr_image(qrUrl) {
            // Set URL gambar ke dalam tag img di modal menggunakan jQuery
            console.log(qrUrl);
            if (qrUrl == null) {
                qrUrl = 'empty'
            }
            $('#qrCodeImage').attr('src', qrUrl);

            // Tampilkan modal menggunakan jQuery
            $('#modalToggle').modal('show');
        }

        function downloadQrImage(filePath) {
            var filename = filePath.split('/').pop(); // Mengambil nama file dari path

            $.ajax({
                url: '{{ route('download.qr.image', ':filename') }}'.replace(':filename', filename),
                type: 'GET',
                xhrFields: {
                    responseType: 'blob'
                },
                success: function(data) {
                    var url = window.URL.createObjectURL(new Blob([data]));
                    var a = document.createElement('a');
                    a.href = url;
                    a.download = filename;
                    document.body.appendChild(a);
                    a.click();
                    window.URL.revokeObjectURL(url);
                },
                error: function(error) {
                    console.log(error);
                    alert('Failed to download image.');
                }
            });
        }
        // $(document).ready(function() {
        //     // Custom search functionality
        //     $('#searchInput').on('keyup', function() {
        //         // Assuming you are using DataTables, you can implement search here
        //     });

        //     // Call the function to get data
        //     getData();
        // });

        $(document).ready(function() {
            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase(); // Ambil nilai input dan ubah menjadi huruf kecil
                $('#dataTable tbody tr').filter(function() {
                    // Periksa apakah teks dalam baris cocok dengan input pencarian
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('.btn[data-bs-toggle="modal"]').on('click', function() {
                // Ambil URL dari data-imgurl
                var qrUrl = $(this).data('imgurl');
                console.log(qrUrl);
                // Set URL gambar ke dalam tag img di modal
                $('#qrCodeImage').attr('src', qrUrl);
            });
            // Call the function to get data
            getData();
        });
    </script>
@endsection
