@extends('layout.admin')
@section('content')
<div class="content-wrapper" style="min-height: 690.2px;">
    <div class="content-header bg-primary text-white">
        <div class="container-fluid">
            <div class="row mb-2">
                <div class="col-sm-6">
                    <h1 class="m-0">Peramalan Penjualan</h1>
                </div>
                <div class="col-sm-6">
                    <ol class="breadcrumb float-sm-right text-white">
                        <li class="breadcrumb-item"><a href="dashboard" class="text-white">Home</a></li>
                        <li class="breadcrumb-item active">Data Peramalan</li>
                    </ol>
                </div>
            </div>
        </div>
    </div>

    <div class="content mt-3">
        <div class="animated fadeIn">
            <div class="row">
                <div class="col-lg-12">
                    <div class="card">
                        <h4 class="mb-10">Parameter Peramalan DES</h4>
                        <div class="card-header bg-light d-flex align-items-center justify-content-between">
                            <form id="peramalanForm" action="" class="form-inline">
                                <label for="id_produk" class="mr-2">Nama Produk</label>
                                <select class="form-control mr-4" name="id_produk">
                                    @foreach ($items as $item)
                                    <option value="{{ $item->id_produk }}" {{ request()->id_produk == $item->id_produk ? 'selected' : '' }}>
                                        {{ $item->nama_produk }}
                                    </option>
                                    @endforeach
                                </select>

                                <label for="bulan_ramalan" class="mr-2">Ramal Bulan Kedepan</label>
                                <select class="form-control mr-3" name="bulan_ramalan">
                                    @for ($i = 1; $i <= 6; $i++)
                                    <option value="{{ $i }}" {{ request()->bulan_ramalan == $i ? 'selected' : '' }}>
                                        {{ $i }} Bulan
                                    </option>
                                    @endfor
                                </select>

                                {{-- <label for="alpha" class="mr-2">Pilih Alpha</label>
                                <select class="form-control mr-3" name="alpha">
                                    <option value="">Otomatis (Alpha Terbaik)</option>
                                    @foreach ($alphaValues as $alpha)
                                    <option value="{{ $alpha }}" {{ request()->alpha == $alpha ? 'selected' : '' }}>
                                        {{ $alpha }}
                                    </option>
                                    @endforeach
                                </select> --}}

                                <button type="submit" class="btn btn-success btn-sm" style="font-size: 15px; padding: 5px 40px;">Hitung</button>
                            </form>
                        </div>

                        <div class="card-body table-responsive">
                            <div>
                                <strong>Mean Absolute Error (MAE): </strong> {{ number_format($mae, 2) }}
                            </div>
                            <div>
                                <strong>Alpha Value Used: </strong> {{ $bestAlpha ? number_format($bestAlpha, 1) : 'N/A' }}
                            </div>

                            <table class="table table-bordered table-hover mt-3">
                                <thead class="thead-dark">
                                    <tr>
                                        <th scope="col">Tahun</th>
                                        <th scope="col">Bulan</th>
                                        <th scope="col">Aktual</th>
                                        <th scope="col">A'</th>
                                        <th scope="col">A''</th>
                                        <th scope="col">at</th>
                                        <th scope="col">bt</th>
                                        <th scope="col">Forecast</th>
                                        <th scope="col">Error MAE</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach ($bestValues as $value)
                                    <tr>
                                        <td>{{ $value['tahun'] }}</td>
                                        <td>{{ $value['bulan'] }}</td>
                                        <td style="color: red;">{{ $value['jumlah'] }}</td>
                                        <td>{{ number_format($value['st'], 2) }}</td>
                                        <td>{{ number_format($value['sst'], 2) }}</td>
                                        <td>{{ number_format($value['at'], 2) }}</td>
                                        <td>{{ number_format($value['bt'], 2) }}</td>
                                        <td style="color: blue;">{{ number_format($value['forecast'], 0) }}</td>
                                        <td>{{ $value['jumlah'] && $value['forecast'] ? number_format(abs($value['jumlah'] - $value['forecast']), 2) : 0 }}</td>
                                    </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <div class="card mt-4">
                <div class="card-header bg-secondary text-white">
                    <h4 class="mb-0">Grafik Peramalan Penjualan Alat Musik</h4>
                </div>
                <div class="card-body">
                    <div class="chart-container" style="position: relative; height: 60vh;">
                        <canvas id="myChart"></canvas>
                    </div>
                </div>
            </div>

            <!-- Script Chart.js -->
            <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
            <script>
                // Ambil data
                var labels = {!! json_encode(array_map(function($value) {
                    return $value['tahun'] . '-' . str_pad($value['bulan'], 2, '0', STR_PAD_LEFT);
                }, $bestValues)) !!};

                var actualData = {!! json_encode(array_column($bestValues, 'jumlah')) !!};
                var forecastData = {!! json_encode(array_column($bestValues, 'forecast')) !!};

                // Atur forecast null untuk bulan pertama
                // forecastData.unshift(null);

                var ctx = document.getElementById('myChart').getContext('2d');
                var chart = new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [
                            {
                                label: 'Penjualan',
                                data: actualData,
                                borderColor: 'red',
                                borderWidth: 2,
                                fill: false,
                                pointRadius: 4,
                                pointBackgroundColor: 'red'
                            },
                            {
                                label: 'Peramalan',
                                data: forecastData,
                                borderColor: 'blue',
                                borderWidth: 2,
                                fill: false,
                                pointRadius: 4,
                                pointBackgroundColor: 'blue'
                            }
                        ]
                    },
                    options: {
                        responsive: true,
                        maintainAspectRatio: false,
                        plugins: {
                            title: {
                                display: true,
                                text: 'Grafik Peramalan Penjualan'
                            }
                        },
                        scales: {
                            x: {
                                title: {
                                    display: true,
                                    text: 'Periode (Tahun-Bulan)'
                                }
                            },
                            y: {
                                beginAtZero: true,
                                title: {
                                    display: true,
                                    text: 'Jumlah Penjualan'
                                }
                            }
                        }
                    }
                });
            </script>
        </div>
    </div>
</div>
@endsection
