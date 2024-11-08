@extends('index')

@section('content')
    <div class="container-fluid">
        <h1 class="mt-3">{{ $title }}</h1>

        <div class="row">
            @foreach ($book_list as $book)
                <div class="col-lg-4 col-md-6 mb-4">
                    <!-- Responsif: 3 kolom untuk layar besar, 2 kolom untuk layar sedang -->
                    <div class="bg-transparent">
                        <img src="https://images.unsplash.com/photo-1511108690759-009324a90311?w=500&auto=format&fit=crop&q=60&ixlib=rb-4.0.3&ixid=M3wxMjA3fDB8MHxzZWFyY2h8NHx8Ym9vayUyMGNvdmVyfGVufDB8MXwwfHx8MA%3D%3D"
                            class="card-img-top mb-3 rounded" style="width: 50%" alt="{{ $book->judul }}">
                        <div class="">
                            <h5 class="card-title">{{ $book->judul }}</h5>
                            <p class="card-text">Penulis: {{ $book->penulis }}</p>
                            <a href="{{ route('transaction.proses_peminjaman', $book->book_id) }}"
                                class="btn btn-primary">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @endforeach
        </div>
    </div>
@endsection
