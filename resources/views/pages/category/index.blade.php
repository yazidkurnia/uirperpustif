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
                    <div class="row d-flex justify-content-between">
                        <div class="col-lg-4">
                            <input type="text" id="searchInput" class="form-control" placeholder="Search..." />
                        </div>
                        <div class="col-lg-3 mx-0">
                            <Button class="btn btn-primary" id="btnAdd" data-bs-toggle="modal"
                                data-bs-target="#basicModal">+ Tambah
                                data category</Button>
                        </div>

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
                    <h5 class="modal-title" id="exampleModalLabel1">Penambahan Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-6">
                            <input type="hidden" name="id">
                            <label for="nama_kategori" class="form-label">Name</label>
                            <input type="text" id="nama_kategori" name="nama_kategori" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" id="confirm_delete_modal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Hapus Data</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <input type="hidden" name="id_to_delete" id="id_to_delete">
                        <span>Apakah anda yakin ingin menghapus kategori ini?</span>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-outline-secondary" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-danger" onclick="delete_category()">Save changes</button>
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
        var method = 'add';

        function getData() {
            $.ajax({
                url: '{{ route('api.category.datatable') }}',
                type: 'GET',
                success: function(data) {
                    var item = [];
                    var item = data.data;
                    $('#dataTable tbody').empty(); // Clear existing rows
                    console.log(data); // Debugging data response
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    $.each(item, function(index, item) {
                        var iteration = index + 1; // Use index for row number
                        var row = '<tr>';
                        row += item.action
                        row += '<td>' + iteration + '</td>';
                        row += '<td>' + item.category_name + '</td>';
                        row += '</tr>';
                        $('#dataTable tbody').append(row);
                    });
                },
                error: function(errorData) {
                    console.log(errorData);
                }
            });
        }

        function update() {
            var id = $('input[name="id"]').val();
            var namaKategori = $('#nama_kategori').val();

            console.log(id);
            console.log(namaKategori);
            $.ajax({
                url: "{{ route('category.update') }}",
                type: 'PUT',
                data: {
                    _token: csrfToken,
                    id: id,
                    nama_kategori: namaKategori
                },
                success: function(data) {
                    if (data.success == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Yeahh...",
                            text: "Berhasil menambahkan kategori!"
                        });
                    } else {
                        Swal.fire({
                            icon: "errors",
                            title: "Oups...",
                            text: "Gagal merubah data lukisan yang asli!"
                        });
                    }
                },
                errors: function(data) {}
            });
        }

        function store() {
            var namaKategori = $('#nama_kategori').val();
            $.ajax({
                url: '{{ route('category.store') }}',
                type: 'POST',
                data: {
                    _token: csrfToken,
                    namaKategori: namaKategori
                },
                success: function(data) {
                    console.log(data)
                    if (data.success == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Yeahh...",
                            text: "Berhasil menambahkan kategori!"
                        });
                        // getData();
                        $('#' + idTable).empty();
                        $('#basicModal').modal('hide');
                    } else {
                        console.log(data);
                        Swal.fire({
                            icon: "error",
                            title: "Oups...",
                            text: "Maaf terjadi kesalahan pada saat menyimpan data, periksa kembali data yang anda inputkan!"
                        });
                    }
                },
                error: function(jqXHR, textStatus, errorTh) {
                    console.log(jqXHR, textStatus);
                }
            });
        }

        function save() {
            console.log(method);
            if (method == 'add') {
                store();
            } else {
                update();
            }

        }

        function edit(id, nama_kategori) {
            var categoryId = id;
            method = 'edit';

            console.log(categoryId);
            console.log(nama_kategori);
            $('input[name="nama_kategori"]').val(nama_kategori);
            $('input[name="id"]').val(id);
            $('#basicModal').modal('show');
        }

        function delete_category() {
            var id = $('input[name="id"]').val();

            console.log(id);

            $.ajax({
                url: '{{ route('category.destroy') }}',
                type: 'DELETE',
                data: {
                    _token: csrfToken,
                    id: id
                },
                success: function(data) {
                    if (data.success == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Yeahh...",
                            text: "Berhasil menghapus kategori!"
                        });
                        $('#' + idTable).empty();
                        getData();
                        $('#confirm_delete_modal').modal('hide');
                    } else {
                        console.log(data);
                        Swal.fire({
                            icon: "error",
                            title: "Oups...",
                            text: "Maaf terjadi kesalahan pada saat menghapus data, periksa kembali data yang anda inputkan!"
                        });
                        $('#confirm_delete_modal').modal('hide');
                    }
                },
                error: function(xhr, staus, error) {
                    console.log(xhr);
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

        function remove(id) {
            console.log(id);
            $('#confirm_delete_modal').modal('show');
            $('input[name="id"]').val(id);
        }

        $(document).ready(function() {
            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val()
                    .toLowerCase(); // Ambil nilai input dan ubah menjadi huruf kecil
                $('#dataTable tbody tr').filter(function() {
                    // Periksa apakah teks dalam baris cocok dengan input pencarian
                    $(this).toggle($(this).text().toLowerCase().indexOf(value) > -1);
                });
            });

            $('#btnAdd').on('click', function() {
                method = 'add';
                console.log(method);
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
