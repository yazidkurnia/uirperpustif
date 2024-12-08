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
                                data buku</Button>
                        </div>

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

    <div class="modal fade" id="basicModal" tabindex="-1" style="display: none;" aria-hidden="true">
        <div class="modal-dialog" role="document">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel1">Penambahan Data Buku</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="row">
                        <div class="col mb-6">
                            <input type="hidden" name="id">
                            <label for="judul_buku" class="form-label">Judul Buku</label>
                            <input type="text" id="judul_buku" name="judul_buku" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="nama_penulis" class="form-label">Penulis</label>
                            <input type="text" id="nama_penulis" name="nama_penulis" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="kategori" class="form-label">Kategori</label>
                            <select class="form-select" id="kategori" name="kategori" aria-label="Default select example">
                                <option>Pilih Kategori</option>
                                @foreach ($data_kategori as $list)
                                    <option value="{{ $list->id }}">{{ $list->category_name }}</option>
                                @endforeach
                                {{-- <option value="2">Web</option>
                                <option value="3">AI</option> --}}
                            </select>
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="penerbit" class="form-label">Penerbit</label>
                            <input type="text" id="penerbit" name="penerbit" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="tahun_terbit" class="form-label">Tahun Terbit</label>
                            <input type="number" id="tahun_terbit" name="tahun_terbit" class="form-control"
                                placeholder="Enter Name">
                        </div>
                    </div>
                    <div class="row">
                        <div class="col mb-6">
                            <label for="no_revisi" class="form-label">No Revisi</label>
                            <input type="number" id="no_revisi" name="no_revisi" class="form-control"
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
        console.log(idTable);

        function getData() {
            $.ajax({
                url: '{{ route('api.books.data') }}',
                type: 'GET',
                success: function(data) {
                    console.log(data);
                    var item = data.data;
                    console.log(data); // Debugging data response
                    $('#' + idTable + ' tbody').empty(); // Clear existing rows
                    $.each(item, function(index, item) {
                        var iteration = index + 1; // Use index for row number
                        var row = '<tr>';
                        row += item.action;
                        row += '<td>' + iteration + '</td>';
                        row += '<td>' + item.judul + '</td>';
                        row += '<td>' + item.penulis + '</td>';
                        row += '<td>' + item.kategori + '</td>';
                        row += '<td>' + item.penerbit + '</td>';

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

        function store() {
            var judulBuku = $('input[name="judul_buku"]').val();
            var namaPenulis = $('input[name="nama_penulis"]').val();
            // var kategori = $('input[name="kategori"]').val();
            var kategori = $('#kategori').find(":selected").val();
            var penerbit = $('input[name="penerbit"]').val();
            var tahunTerbit = $('input[name="tahun_terbit"]').val();
            var noRevisi = $('input[name="no_revisi"]').val();

            // Mengambil token CSRF dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            // console.log(tanggalKembali);
            // console.log(tanggalPinjam);
            // console.log(bookIds);
            // console.log(kategori);
            data = {
                _token: csrfToken,
                judul_buku: judulBuku,
                nama_penulis: namaPenulis,
                kategori: kategori,
                penerbit: penerbit,
                tahun_terbit: tahunTerbit,
                no_revisi: noRevisi,
            };
            var data;
            $.ajax({
                url: '{{ route('book.store') }}',
                type: "POST",
                data: {
                    _token: csrfToken,
                    judul_buku: judulBuku,
                    nama_penulis: namaPenulis,
                    kategori: kategori,
                    penerbit: penerbit,
                    tahun_terbit: tahunTerbit,
                    no_revisi: noRevisi,
                },
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: "Berhasil menambahkan buku!"
                    });
                    // $('#' + idTable).empty();
                    $('#basicModal').modal('hide');
                },
                error: function(data) {
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        text: "Maaf terjadi kesalahan pada saat menyimpan data, periksa kembali data yang anda inputkan!"
                    });
                    $('#basicModal').modal('hide');
                }

            })
        }

        function update() {
            var id = $('input[name="id"]').val();
            var judul = $('#judul_buku').val();
            var penulis = $('#nama_penulis').val();
            var penerbit = $('#penerbit').val();
            var tahun_terbit = $('#tahun_terbit').val();
            var no_revisi = $('#no_revisi').val();
            var kategori = $('#kategori').find(":selected").val();

            console.log(penulis);
            console.log(id);
            // console.log(penerbit);
            // console.log(kategori);
            $.ajax({
                url: "{{ route('book.update') }}",
                type: 'PUT',
                data: {
                    _token: csrfToken,
                    id: id,
                    judul: judul,
                    penulis: penulis,
                    penerbit: penerbit,
                    kategori: kategori,
                    tahun_terbit: tahun_terbit,
                    no_revisi: no_revisi
                },
                success: function(data) {
                    if (data.success == true) {
                        Swal.fire({
                            icon: "success",
                            title: "Yeahh...",
                            text: "Berhasil Merubah Data Buku!"
                        });
                    } else {
                        Swal.fire({
                            icon: "errors",
                            title: "Oups...",
                            text: "Gagal Merubah Data Buku!"
                        });
                    }
                    $('#basicModal').modal('hide');
                },
                errors: function(data) {}
            });
        }

        function save() {
            // console.log(method);
            if (method == 'add') {
                store();
            } else {
                update();
            }

        }

        function edit(id, judul, penulis, penerbit, tahun_terbit, no_revisi) {
            var bookId = id;
            method = 'edit';
            console.log(id);
            // console.log(bookId);
            $('input[name="judul_buku"]').val(judul);
            $('input[name="nama_penulis"]').val(penulis);
            $('input[name="penerbit"]').val(penerbit);
            $('input[name="tahun_terbit"]').val(tahun_terbit);
            $('input[name="no_revisi"]').val(no_revisi);
            $('input[name="id"]').val(id);
            $('#basicModal').modal('show');
        }

        function delete_category() {
            var id = $('input[name="id"]').val();

            console.log(id);

            $.ajax({
                url: '{{ route('book.destroy') }}',
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
                        // getData();
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

        // $(document).ready(function() {
        //     // Custom search functionality
        //     $('#searchInput').on('keyup', function() {
        //         // Assuming you are using DataTables, you can implement search here
        //     });

        //     // Call the function to get data
        //     


        // });

        function remove(id) {
            console.log(id);
            $('#confirm_delete_modal').modal('show');
            $('input[name="id"]').val(id);
        }

        $(document).ready(function() {
            // Custom search functionality
            $('#searchInput').on('keyup', function() {
                var value = $(this).val().toLowerCase(); // Ambil nilai input dan ubah menjadi huruf kecil
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
