@extends('index')

@section('content')
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid">
        <span class="my-3">
            <h2 class="my-3">{{ $title }}</h2>
        </span>

        <div class="container-fluid bg-white rounded py-3">

            <table class="table-border-less">
                <tr>
                    <td>
                        <h5><span>Nama Peminjam</span></h5>
                    </td>
                    <td>
                        <h5>:</h5>
                    </td>
                    <td>
                        <h5><span>{{ $transaksi->name }}</span></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><span>Email</span></h5>
                    </td>
                    <td>
                        <h5>:</h5>
                    </td>
                    <td>
                        <h5><span>{{ $transaksi->email }}</span></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><span>Tanggal Peminjaman</span></h5>
                    </td>
                    <td>
                        <h5>:</h5>
                    </td>
                    <td>
                        <h5><span>{{ $transaksi->tgl_pinjam }}</span></h5>
                    </td>
                </tr>
                <tr>
                    <td>
                        <h5><span>Tanggal Wajib Kembali</span></h5>
                    </td>
                    <td>
                        <h5>:</h5>
                    </td>
                    <td>
                        <h5><span>{{ $transaksi->tgl_wajib_kembali }}</span></h5>
                    </td>
                </tr>
                @if ($title == 'Detail Pengembalian Buku')
                    <tr>
                        <td>
                            <h5><span>Denda</span></h5>
                        </td>
                        <td>
                            <h5>:</h5>
                        </td>
                        <td>
                            <h5><span>Rp. {{ $denda }}</span></h5>
                        </td>
                    </tr>
                @endif
                <tr>
                    <td>
                        <h5><span>Item</span></h5>
                    </td>
                    <td>
                        <h5>:</h5>
                    </td>
                </tr>
            </table>
            <div class="accordion" id="accordionExample">
                @forelse ($transaksi_detail as $list)
                    <div class="accordion-item">
                        <h2 class="accordion-header" id="heading{{ $loop->iteration }}">
                            <button class="accordion-button" type="button" data-bs-toggle="collapse"
                                data-bs-target="#collapse{{ $loop->iteration }}" aria-expanded="false"
                                aria-controls="collapse{{ $loop->iteration }}">
                                {{ $list->judul }}
                            </button>
                        </h2>
                        <div id="collapse{{ $loop->iteration }}" class="accordion-collapse collapse"
                            aria-labelledby="heading{{ $loop->iteration }}" data-bs-parent="#accordionExample">
                            <div class="accordion-body">
                                <table class="table table-responsive">
                                    <thead>
                                        <tr>
                                            <th>Judul</th>
                                            <th>Nomor Revisi</th>
                                            <th>Penulis</th>
                                            <th>Tahun Terbit</th>
                                            <th>Penerbit</th>
                                        </tr>
                                    </thead>
                                    <tbody>
                                        <tr>
                                            <td>{{ $list->judul }}</td>
                                            <td>{{ $list->no_revisi }}</td>
                                            <td>{{ $list->penulis }}</td>
                                            <td>{{ $list->tahun_terbit }}</td>
                                            <td>{{ $list->penerbit }}</td>
                                        </tr>
                                    </tbody>
                                </table>
                            </div>
                        </div>
                    </div>
                @empty
                    <div class="accordion-item">
                        <div class="accordion-body">
                            Tidak ada item yang dipinjam.
                        </div>
                    </div>
                @endforelse
            </div>
            <!-- Dropdown kecil di sudut kanan atas -->
            <div class="row d-flex justify-content-between">
                <div class="col-2">
                    <div class="d-flex justify-content-end mb-3">
                        <select class="form-select form-select-sm" name="status_approval" id="status_approval"
                            aria-label="Small select example">
                            <option>Pilih opsi</option>
                            <option value="Approved">Approved</option>
                            <option value="Reject">Reject</option>
                        </select>
                    </div>
                </div>
                <div class="col-2">
                    <div class="d-flex justify-content-end mb-3">
                        <button type="submit" class="btn btn-primary" onclick="save()">Simpan</button>
                    </div>

                </div>
            </div>

        </div>
    </div>
    <!-- Modal for rejection reason -->
    <div class="modal fade" id="rejectModal" tabindex="-1" aria-labelledby="rejectModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="rejectModalLabel">Alasan Penolakan</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <form id="rejectForm">
                        <div class="mb-3">
                            <label for="reject_note" class="form-label">Alasan</label>
                            <textarea class="form-control" id="reject_note" rows="3" required></textarea>
                        </div>
                        <input type="hidden" id="transaction_id" value="{{ $transaction_id }}">
                    </form>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Tutup</button>
                    <button type="button" class="btn btn-primary" onclick="submitRejection()">Kirim</button>
                </div>
            </div>
        </div>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@11"></script>
    <script>
        function save() {
            var transaction_id = {!! json_encode($transaction_id) !!};
            var request_approval = $('#status_approval :selected').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            console.log(transaction_id);

            if (request_approval == 'Reject') {
                $('#rejectModal').modal('show');
            } else {
                $.ajax({
                    url: '{{ route('transaction.approval.peminjaman') }}',
                    type: 'POST',
                    data: {
                        id: transaction_id,
                        status_approval: request_approval,
                        _token: csrfToken
                    },
                    success: function(data) {
                        console.log(data);
                        Swal.fire({
                            icon: "success",
                            title: "Yeay...",
                            text: "Berhasil menambahkan data!"
                        });
                    },
                    error: function(xhr, textStatus, error) {
                        console.log(error);
                        Swal.fire({
                            icon: "Error",
                            title: "Yeay...",
                            text: "Peminjaman telah diterima dan stok telah diperbarui"
                        });
                        // window.location.replace({{ route('data.return') }});
                        window.location.href = "{{ route('data.return') }}";
                    }
                });
            }
        }

        function submitRejection() {
            var transaction_id = $('#transaction_id').val();
            var request_approval = $('#status_approval :selected').val();
            var csrfToken = $('meta[name="csrf-token"]').attr('content');
            var reject_note = $('#reject_note').val();
            console.log(transaction_id);

            console.dir(reject_note);

            $.ajax({
                url: '{{ route('transaction.approval.peminjaman') }}',
                type: 'POST',
                data: {
                    id: transaction_id,
                    status_approval: 'Reject',
                    reject_note: reject_note,
                    _token: csrfToken
                },
                success: function(data) {
                    console.log(data);
                    Swal.fire({
                        icon: "success",
                        title: "Yeay...",
                        text: "Berhasil menambahkan data!"
                    });
                },
                error: function(xhr, textStatus, error) {
                    console.log(error);
                    Swal.fire({
                        icon: "Error",
                        title: "Yeay...",
                        text: "Peminjaman telah diterima dan stok telah diperbarui"
                    });
                    // // window.location.replace({{ route('data.return') }});
                    // window.location.href = "{{ route('data.return') }}";
                }
            });
        }
    </script>
@endsection
