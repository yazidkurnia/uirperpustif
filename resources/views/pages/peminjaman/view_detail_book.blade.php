@extends('index')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <h2 class="my-3">{{ $title }}</h2>
        <div class="row">
            <div class="col-md-3 d-flex justify-content-center">
                <div class="card">
                    <div class="text-center">
                        <img src="https://images.unsplash.com/photo-1511108690759-009324a90311?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Ym9vayUyMGNvdmVyfGVufDB8MXwwfHx8MA%3D%3D"
                            class="card-img-top mb-3 rounded" style="width: 75%; height: auto;" alt="Cover Image">
                    </div>
                    <div class="card-body">
                        <h5 class="card-title">{{ $detail_buku->judul }}</h5>
                        <p class="card-text">Deskripsi singkat tentang buku ini.</p>
                        <a href="#" class="btn btn-primary">Lihat Detail</a>
                    </div>
                </div>
            </div>
            <div class="col-md-9">
                <div class="card">
                    <div class="row d-flex justify-content-between align-items-center">
                        <!-- Kelas untuk menyelaraskan item -->
                        <div class="col-auto">
                            <h5 class="card-title px-3 pt-3">Deskripsi</h5>
                        </div>
                        <div class="col-auto">
                            <button class="btn btn-primary px-3 pt-3" data-bs-toggle="modal"
                                data-bs-target="#exampleModal">Pinjam Sekarang</button>
                            <!-- Gaya tombol dengan Bootstrap -->
                        </div>
                    </div>
                    <hr>
                    <div class="card-body">
                        <span>Lorem ipsum dolor sit amet consectetur adipisicing elit. Quo minima temporibus, expedita a hic
                            facilis nam quia molestiae fugiat fugit et illo necessitatibus veniam eaque animi eos provident
                            sequi sint?</span>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <div class="modal fade" id="exampleModal" tabindex="-1" aria-labelledby="exampleModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="exampleModalLabel">Create new peminjaman</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form action="" method="post">
                        <input type="hidden" name="book_id" value="{{ $id_buku }}">
                        <div class="row">
                            <div class="col-md-6 mt-3">
                                <label for="">Tanggal peminjaman</label>
                                <input class="form-control" type="date" name="tgl_peminjaman" placeholder="Default input"
                                    aria-label="default input example">
                            </div>
                            <div class="col-md-6 mt-3">
                                <label for="">Tanggal pengembalian</label>
                                <input class="form-control" type="date" name="tgl_pengembalian"
                                    placeholder="Default input" aria-label="default input example">
                            </div>
                        </div>
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary mx-3" data-bs-dismiss="modal">Close</button>
                    <button type="button" class="btn btn-primary" onclick="save()">Save changes</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://code.jquery.com/jquery-3.6.0.min.js"></script>
    <script>
        function save() {
            var tanggalPinjam = $('input[name="tgl_peminjaman"]').val();
            var tanggalKembali = $('input[name="tgl_pengembalian"]').val();
            var bookId = $('input[name="book_id"]').val();
            // Mengambil token CSRF dari meta tag
            var csrfToken = $('meta[name="csrf-token"]').attr('content');

            console.log(tanggalPinjam);
            console.log(tanggalKembali);

            $.ajax({
                url: '{{ route('transaction.store') }}',
                type: "POST",
                data: {
                    _token: csrfToken,
                    book_id: bookId,
                    tanggal_pinjam: tanggalPinjam,
                    tanggal_kembali: tanggalKembali
                },
                success: function(data) {
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: "Berhasil menambahkan data!"
                    });
                },
                error: function(data) {
                    Swal.fire({
                        icon: "error",
                        title: "Oups...",
                        text: "Maaf terjadi kesalahan pada saat menyimpan data, periksa kembali data yang anda inputkan!"
                    });
                }
            })
        }
    </script>
@endsection
