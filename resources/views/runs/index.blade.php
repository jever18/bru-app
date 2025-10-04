<x-app-layout>
    <x-slot name="header">
        <h2 class="h5 mb-0 text-white text-shadow-light d-flex justify-content-between align-items-center">
            {{ __('Dashboard Lari') }}
            <a href="{{ route('runs.create') }}" class="btn btn-warning btn-sm shadow text-dark fw-bold">
                + Catat Lari Baru
            </a>
        </h2>
    </x-slot>

    <div class="py-4">
        <div class="container">
            
            @if (session('status'))
                <div class="alert alert-light alert-dismissible fade show glass-card" role="alert">
                    {{ session('status') }}
                    <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                </div>
            @endif

            @if ($run_count > 0)
                <div class="card glass-card mb-4">
                    <div class="card-body">
                        <h3 class="card-title h5 border-bottom pb-2">Tren Jarak ({{ count(json_decode($chart_labels_json)) }} Lari Terakhir)</h3>
                        <canvas id="distanceChart" style="max-height: 400px;"></canvas>
                    </div>
                </div>
            @endif
            
            
            <div class="row g-4 mb-4">
                
                <div class="col-md-4 col-12">
                    <div class="card glass-card h-100 border-start border-5 border-primary">
                        <div class="card-body text-center">
                            <p class="text-uppercase text-light mb-1 small">Total Jarak</p>
                            <h3 class="display-6 fw-bolder text-warning">{{ number_format($total_distance, 2) }} km</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card glass-card h-100 border-start border-5 border-info">
                        <div class="card-body text-center">
                            <p class="text-uppercase text-light mb-1 small">Jumlah Lari</p>
                            <h3 class="display-6 fw-bolder text-white">{{ $run_count }} kali</h3>
                        </div>
                    </div>
                </div>

                <div class="col-md-4 col-12">
                    <div class="card glass-card h-100 border-start border-5 border-danger">
                        <div class="card-body text-center">
                            <p class="text-uppercase text-light mb-1 small">Rata-rata Pace</p>
                            <h3 class="display-6 fw-bolder text-danger">{{ $formatted_avg_pace }} /km</h3>
                        </div>
                    </div>
                </div>
            </div>
            
            <div class="card glass-card mb-4">
                <div class="card-body">
                    <h3 class="card-title h5">Filter Catatan</h3>
                    <form method="GET" action="{{ route('runs.index') }}" class="row g-3 align-items-end">
                        
                        <div class="col-auto">
                            <label for="month" class="form-label mb-0 small text-white">Bulan</label>
                            <select id="month" name="month" class="form-select">
                                <option value="">Semua Bulan</option>
                                @for ($m = 1; $m <= 12; $m++)
                                    <option value="{{ $m }}" {{ (string)$m === $selected_month ? 'selected' : '' }}>
                                        {{ \Carbon\Carbon::createFromDate(null, $m, 1)->translatedFormat('F') }}
                                    </option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-auto">
                            <label for="year" class="form-label mb-0 small text-white">Tahun</label>
                            <select id="year" name="year" class="form-select">
                                <option value="">Semua Tahun</option>
                                {{-- Loop dari tahun sekarang hingga tahun 2020 --}}
                                @for ($y = date('Y'); $y >= 2020; $y--)
                                    <option value="{{ $y }}" {{ (string)$y === $selected_year ? 'selected' : '' }}>{{ $y }}</option>
                                @endfor
                            </select>
                        </div>
                        
                        <div class="col-auto">
                            <button type="submit" class="btn btn-warning text-dark fw-bold">Filter</button>
                        </div>

                        <div class="col-auto">
                            <a href="{{ route('runs.index') }}" class="btn btn-outline-light">Reset</a>
                        </div>
                    </form>
                </div>
            </div>


            <div class="card glass-card">
                <div class="card-body">
                    <h3 class="card-title h5 border-bottom pb-2">Catatan Lari Terakhir</h3>
                    @if ($runs->isEmpty())
                        <p class="text-light">Tidak ada catatan lari yang ditemukan untuk filter ini.</p>
                    @else
                        <ul class="list-group list-group-flush bg-transparent">
                            @foreach ($runs as $run)
                                <li class="list-group-item bg-transparent text-white d-flex justify-content-between align-items-center flex-wrap py-3 px-0">
                                    
                                    <div class="d-flex flex-column mb-2 mb-md-0 me-3">
                                        <small class="text-light text-uppercase fw-bold">{{ \Carbon\Carbon::parse($run->date)->format('d F Y') }}</small>
                                        <h4 class="fw-bolder text-white mb-0">{{ $run->pace }} /km</h4>
                                    </div>

                                    <div class="text-light fst-italic mb-2 mb-md-0 me-3">
                                        <small>{{ $run->notes ?? 'Tidak ada catatan.' }}</small>
                                    </div>

                                    <div class="d-flex align-items-center">
                                        <div class="text-end me-4">
                                            <small class="text-light d-block">Jarak</small>
                                            <span class="fw-bold text-warning">{{ $run->distance }} km</span>
                                        </div>
                                        <div class="text-end me-4">
                                            <small class="text-light d-block">Waktu</small>
                                            <span class="fw-bold text-white">{{ floor($run->duration / 60) }}:{{ sprintf('%02d', $run->duration % 60) }} mnt</span>
                                        </div>
                                        <div class="dropdown">
                                            <button class="btn btn-sm btn-light dropdown-toggle" type="button" data-bs-toggle="dropdown" aria-expanded="false">
                                                Aksi
                                            </button>
                                            <ul class="dropdown-menu dropdown-menu-end">
                                                <li><a class="dropdown-item" href="{{ route('runs.edit', $run) }}">Edit</a></li>
                                                <li><hr class="dropdown-divider"></li>
                                                <li>
                                                    <form method="POST" action="{{ route('runs.destroy', $run) }}" onsubmit="return confirm('Yakin ingin menghapus catatan ini?')">
                                                        @csrf
                                                        @method('delete')
                                                        <button type="submit" class="dropdown-item text-danger">Hapus</button>
                                                    </form>
                                                </li>
                                            </ul>
                                        </div>
                                    </div>
                                </li>
                            @endforeach
                        </ul>
                    @endif
                </div>
            </div>
        </div>
    </div>
    
    @if ($run_count > 0)
        @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/chart.js@4.4.1/dist/chart.umd.min.js"></script>
        
        <script>
            // Pastikan ini dijalankan setelah DOM dimuat
            document.addEventListener('DOMContentLoaded', function() {
                const ctx = document.getElementById('distanceChart');
                
                // Ambil data JSON dari PHP dan parsing
                const labels = {!! $chart_labels_json !!};
                const distances = {!! $chart_distances_json !!};
                
                // Ambil warna primary (Warning/Kuning)
                const primaryColor = getComputedStyle(document.documentElement).getPropertyValue('--bs-warning').trim();
                const primaryRgba = 'rgba(255, 193, 7, 0.4)'; // Warning/Kuning transparan

                new Chart(ctx, {
                    type: 'line',
                    data: {
                        labels: labels,
                        datasets: [{
                            label: 'Jarak (km)',
                            data: distances,
                            borderWidth: 3,
                            borderColor: primaryColor,
                            backgroundColor: primaryRgba,
                            tension: 0.4,
                            pointRadius: 5,
                            fill: true,
                            color: 'white' // Warna teks data set
                        }]
                    },
                    options: {
                        responsive: true,
                        scales: {
                            y: {
                                beginAtZero: true,
                                // Ubah warna label dan grid untuk kontras
                                ticks: {
                                    color: 'white'
                                },
                                grid: {
                                    color: 'rgba(255, 255, 255, 0.2)'
                                }
                            },
                            x: {
                                // Ubah warna label
                                ticks: {
                                    color: 'white'
                                },
                                grid: {
                                    display: false
                                }
                            }
                        },
                        plugins: {
                            legend: {
                                display: false
                            },
                            tooltip: {
                                titleColor: 'black',
                                bodyColor: 'black',
                                backgroundColor: 'white',
                                borderColor: primaryColor,
                                borderWidth: 1
                            }
                        }
                    }
                });
            });
        </script>
        @endpush
    @endif
</x-app-layout>
