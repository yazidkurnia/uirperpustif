@extends('index')

@section('content')
    <style>
        #chart {
            height: 300;
            /* Atur tinggi sesuai kebutuhan */
            width: 100%;
            /* Atur lebar agar responsif */
        }
    </style>
    <meta name="csrf-token" content="{{ csrf_token() }}">
    <div class="container-fluid mt-3">
        <nav aria-label="breadcrumb">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="#">{{ $title }}</a></li>
                <li class="breadcrumb-item active" aria-current="page">Library</li>
            </ol>
        </nav>

        <div class="row">
            <div class="col-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/paypal.png" alt="paypal" class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    {{-- <a class="dropdown-item" href="javascript:void(0);">Delete</a> --}}
                                </div>
                            </div>
                        </div>
                        <p class="mb-1">Peminjaman Tertunda</p>
                        <h4 class="card-title mb-3">{{ $total_peminjaman_pending }}</h4>
                        {{-- <small class="text-danger fw-medium"><i class="bx bx-down-arrow-alt"></i> -14.82%</small> --}}
                    </div>
                </div>
            </div>
            <div class="col-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/cc-primary.png" alt="Credit Card" class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt1" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                </button>
                                <div class="dropdown-menu" aria-labelledby="cardOpt1">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    {{-- <a class="dropdown-item" href="javascript:void(0);">Delete</a> --}}
                                </div>
                            </div>
                        </div>
                        <p class="mb-1">Peminjaman Approved</p>
                        <h4 class="card-title mb-3">{{ $total_peminjaman_approved }}</h4>
                        {{-- <small class="text-success fw-medium"><i class="bx bx-up-arrow-alt"></i> +28.14%</small> --}}
                    </div>
                </div>
            </div>
            <div class="col-4 mb-6">
                <div class="card h-100">
                    <div class="card-body">
                        <div class="card-title d-flex align-items-start justify-content-between mb-4">
                            <div class="avatar flex-shrink-0">
                                <img src="../assets/img/icons/unicons/paypal.png" alt="paypal" class="rounded">
                            </div>
                            <div class="dropdown">
                                <button class="btn p-0" type="button" id="cardOpt4" data-bs-toggle="dropdown"
                                    aria-haspopup="true" aria-expanded="false">
                                    <i class="bx bx-dots-vertical-rounded text-muted"></i>
                                </button>
                                <div class="dropdown-menu dropdown-menu-end" aria-labelledby="cardOpt4">
                                    <a class="dropdown-item" href="javascript:void(0);">View More</a>
                                    {{-- <a class="dropdown-item" href="javascript:void(0);">Delete</a> --}}
                                </div>
                            </div>
                        </div>
                        <p class="mb-1">Pengembalian Tertunda</p>
                        <h4 class="card-title mb-3">{{ $total_pengembalian_pending }}</h4>
                        {{-- <small class="text-danger fw-medium"><i class="bx bx-down-arrow-alt"></i> -14.82%</small> --}}
                    </div>
                </div>
            </div>
        </div>
        <div class="row">
            <div class="col-md-12 col-lg-12 order-1 mb-6">
                <div class="card h-100">
                    <div class="card-header nav-align-top">
                        <span>
                            Grafik Peminjaman dan Pengembalian Buku di Perpustakaan Teknik Informatika Uir
                        </span>
                    </div>
                    <div class="card-body">
                        <div id="chart">

                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
    {{-- <script src="https://cdn.jsdelivr.net/npm/apexcharts"></script> --}}
    <script src="{{ asset('assets/vendor/libs/apex-charts/apexcharts.js') }}"></script>
    <script>
        const monthlyData = @json($formattedChartData);
        const labels = monthlyData.labels;
        const data = monthlyData.data;
        const dataPengembalian = monthlyData.data_pengembalian;

        // Konfigurasi Chart
        console.log(data);
        const options = {
            chart: {
                height: '300%',
                type: 'area',
                zoom: {
                    enabled: true
                },
                stroke: {
                    curve: 'smooth',
                }
            },
            series: [{
                name: 'Peminjaman',
                data: data
            }, {
                name: 'Pengembalian',
                data: dataPengembalian,
            }],
            xaxis: {
                categories: labels,
                tickPlacement: 'on'
            }
        };


        const chart = new ApexCharts(document.querySelector("#chart"), options);
        chart.render();
    </script>
@endsection
