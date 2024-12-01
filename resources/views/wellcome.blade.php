<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">

    <!-- Bootstrap CSS -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/css/bootstrap.min.css" rel="stylesheet"
        integrity="sha384-EVSTQN3/azprG1Anm3QDgpJLIm9Nao0Yz1ztcQTwFspd3yD65VohhpuuCOmLASjC" crossorigin="anonymous">
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-icons@1.5.0/font/bootstrap-icons.css">

    <title>Landing Page - Aplikasi Peminjaman Buku</title>
    <style>
        .hero {
            position: relative;
            width: 100%;
            height: 50vh;
            overflow: hidden;
        }

        .hero img {
            width: 100%;
            height: 100%;
            object-fit: cover;
        }

        .hero-text {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            color: white;
            font-size: 2rem;
            text-shadow: 2px 2px 4px rgba(0, 0, 0, 0.7);
        }

        .book-image {
            width: 150px;
            position: absolute;
            bottom: 20px;
            right: 20px;
        }

        .feature-icon {
            font-size: 3rem;
            color: #007bff;
        }

        .footer {
            background-color: #f8f9fa;
            padding: 20px 0;
        }
    </style>
</head>

<body>
    <!-- Navbar -->
    <nav class="navbar navbar-expand-lg navbar-light bg-white">
        <div class="container-fluid">
            <a class="navbar-brand" href="#">Aplikasi Peminjaman Buku</a>
            <button class="navbar-toggler" type="button" data-bs-toggle="collapse" data-bs-target="#navbarText"
                aria-controls="navbarText" aria-expanded="false" aria-label="Toggle navigation">
                <span class="navbar-toggler-icon"></span>
            </button>
            <div class="collapse navbar-collapse" id="navbarText">
                <ul class="navbar-nav ms-auto mb-2 mb-lg-0">
                    <li class="nav-item">
                        <a href="{{ route('login') }}" class="nav-link">Login</a>
                    </li>
                    <li class="nav-item">
                        <a href="{{ route('register') }}" class="nav-link">Register</a>
                    </li>
                </ul>
            </div>
        </div>
    </nav>
    <!-- End of navbar -->

    <!-- Hero -->
    <div class="hero">
        <img src="{{ asset('assets/images/1.jpg') }}" alt="Hero Image">
        <div class="hero-text">Selamat Datang di Perpustakaan</div>
        <img src="{{ asset('assets/images/book-stack.png') }}" alt="Buku" class="book-image">
    </div>
    <!-- End of hero -->

    <!-- Features Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Fitur Kami</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-book"></i>
                </div>
                <h5>Pinjam Buku</h5>
                <p>Pinjam buku dengan mudah dan cepat hanya dengan beberapa klik.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-person-check"></i>
                </div>
                <h5>Registrasi Mudah</h5>
                <p>Daftar dan mulai meminjam buku dalam waktu singkat.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-clock-history"></i>
                </div>
                <h5>Pengingat Peminjaman</h5>
                <p>Dapatkan pengingat untuk mengembalikan buku tepat waktu </p>
            </div>
        </div>
    </div>
    <!-- End of Features Section -->

    <!-- Categories Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Kategori Buku</h2>
        <div class="row text-center">
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ asset('assets/images/fiction.jpg') }}" class="card-img-top" alt="Fiksi">
                    <div class="card-body">
                        <h5 class="card-title">Fiksi</h5>
                        <p class="card-text">Temukan berbagai novel dan cerita fiksi menarik.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ asset('assets/images/non-fiction.jpg') }}" class="card-img-top" alt="Non-Fiksi">
                    <div class="card-body">
                        <h5 class="card-title">Non-Fiksi</h5>
                        <p class="card-text">Buku-buku yang memberikan informasi dan pengetahuan.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ asset('assets/images/science.jpg') }}" class="card-img-top" alt="Sains">
                    <div class="card-body">
                        <h5 class="card-title">Sains</h5>
                        <p class="card-text">Jelajahi dunia sains dengan buku-buku yang menarik.</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card mb-4">
                    <img src="{{ asset('assets/images/history.jpg') }}" class="card-img-top" alt="Sejarah">
                    <div class="card-body">
                        <h5 class="card-title">Sejarah</h5>
                        <p class="card-text">Pelajari sejarah dunia melalui buku-buku yang mendalam.</p>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Categories Section -->

    <!-- How It Works Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Cara Kerja Aplikasi</h2>
        <div class="row text-center">
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-person-plus"></i>
                </div>
                <h5>1. Registrasi</h5>
                <p>Daftar sebagai pengguna baru untuk memulai.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-search"></i>
                </div>
                <h5>2. Cari Buku</h5>
                <p>Cari buku yang ingin Anda pinjam dengan mudah.</p>
            </div>
            <div class="col-md-4">
                <div class="feature-icon">
                    <i class="bi bi-check-circle"></i>
                </div>
                <h5>3. Pinjam Buku</h5>
                <p>Pinjam buku dan nikmati membaca!</p>
            </div>
        </div>
    </div>
    <!-- End of How It Works Section -->

    <!-- Testimonial Section -->
    <div class="container my-5">
        <h2 class="text-center mb-4">Apa Kata Mereka?</h2>
        <div class="row">
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text">"Aplikasi ini sangat membantu saya dalam mencari dan meminjam buku.
                            Prosesnya cepat dan mudah!"</p>
                        <h5 class="card-title">- Sarah, Mahasiswa</h5>
                    </div>
                </div>
            </div>
            <div class="col-md-6">
                <div class="card mb-4">
                    <div class="card-body">
                        <p class="card-text">"Saya sangat menyukai fitur pengingatnya. Tidak pernah terlambat
                            mengembalikan buku!"</p>
                        <h5 class="card-title">- John, Pengguna Setia</h5>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <!-- End of Testimonial Section -->

    <!-- Footer Section -->
    <footer class ="footer text-center">
        <div class="container">
            <p class="mb-0">© 2023 Aplikasi Peminjaman Buku. Semua hak dilindungi.</p>
        </div>
    </footer>
    <!-- End of Footer Section -->

    <!-- Optional JavaScript; choose one of the two! -->

    <!-- Option 1: Bootstrap Bundle with Popper -->
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.0.2/dist/js/bootstrap.bundle.min.js"
        integrity="sha384-MrcW6ZMFYlzcLA8Nl+NtUVF0sA7MsXsP1UyJoMp4YLEuNSfAP+JcXn/tWtIaxVXM" crossorigin="anonymous">
    </script>

</body>

</html>
