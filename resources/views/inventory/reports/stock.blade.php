@extends('layouts.customer')

@section('title', ' - Laporan Stok Inventaris')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/inventory">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page">Laporan Stok</li>
            </ol>
        </nav>

        <!-- Report Header -->
        <div class="card mb-4">
            <div class="card-body">
                <div class="d-flex justify-content-between align-items-center mb-3">
                    <h2 class="mb-0">Laporan Stok Inventaris</h2>
                    <div>
                        <button class="btn btn-outline-secondary me-2">
                            <i class="bi bi-printer"></i> Cetak
                        </button>
                        <button class="btn btn-outline-primary">
                            <i class="bi bi-file-earmark-excel"></i> Export Excel
                        </button>
                    </div>
                </div>

                <div class="row">
                    <div class="col-md-6">
                        <p class="mb-1"><strong>Tanggal Laporan:</strong> {{ $report_date }}</p>
                        <p class="mb-1"><strong>Dibuat Oleh:</strong> Admin</p>
                    </div>
                    <div class="col-md-6 text-md-end">
                        <p class="mb-1"><strong>Total Kategori:</strong> {{ count($categories) }}</p>
                        <p class="mb-1"><strong>Total Nilai Inventaris:</strong> Rp
                            {{ number_format(array_sum(array_column($categories, 'total_value'))) }}</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Summary by Category -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Ringkasan per Kategori</h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kategori</th>
                                <th class="text-center">Jumlah Item</th>
                                <th class="text-center">Total Stok</th>
                                <th class="text-end">Nilai Inventaris</th>
                                <th class="text-center">Persentase Nilai</th>
                            </tr>
                        </thead>
                        <tbody>
                            @php
                                $totalValue = array_sum(array_column($categories, 'total_value'));
                            @endphp

                            @foreach ($categories as $category => $data)
                                <tr>
                                    <td>{{ $category }}</td>
                                    <td class="text-center">{{ $data['total_items'] }}</td>
                                    <td class="text-center">{{ $data['total_stock'] }}</td>
                                    <td class="text-end">Rp {{ number_format($data['total_value']) }}</td>
                                    <td class="text-center">
                                        @php
                                            $percentage = ($data['total_value'] / $totalValue) * 100;
                                        @endphp
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1" style="height: 8px;">
                                                <div class="progress-bar bg-primary" role="progressbar"
                                                    style="width: {{ $percentage }}%" aria-valuenow="{{ $percentage }}"
                                                    aria-valuemin="0" aria-valuemax="100">
                                                </div>
                                            </div>
                                            <span class="ms-2">{{ number_format($percentage, 1) }}%</span>
                                        </div>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                        <tfoot class="table-light fw-bold">
                            <tr>
                                <td>Total</td>
                                <td class="text-center">{{ array_sum(array_column($categories, 'total_items')) }}</td>
                                <td class="text-center">{{ array_sum(array_column($categories, 'total_stock')) }}</td>
                                <td class="text-end">Rp {{ number_format($totalValue) }}</td>
                                <td class="text-center">100%</td>
                            </tr>
                        </tfoot>
                    </table>
                </div>
            </div>
        </div>

        <!-- Low Stock Items -->
        <div class="card mb-4">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Item dengan Stok Rendah</h5>
                <a href="/inventory" class="btn btn-sm btn-outline-primary">Lihat Semua Item</a>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th class="text-center">Stok Saat Ini</th>
                                <th class="text-center">Stok Minimum</th>
                                <th class="text-center">Status</th>
                                <th class="text-center">Aksi</th>
                            </tr>
                        </thead>
                        <tbody>
                            @foreach ($low_stock_items as $item)
                                <tr>
                                    <td>{{ $item['code'] }}</td>
                                    <td>{{ $item['name'] }}</td>
                                    <td class="text-center">{{ $item['current_stock'] }}</td>
                                    <td class="text-center">{{ $item['min_stock'] }}</td>
                                    <td class="text-center">
                                        @if ($item['current_stock'] <= $item['min_stock'])
                                            <span class="badge bg-danger">Stok Rendah</span>
                                        @elseif($item['current_stock'] <= $item['min_stock'] * 1.5)
                                            <span class="badge bg-warning text-dark">Perlu Restock</span>
                                        @else
                                            <span class="badge bg-success">{{ $item['status'] }}</span>
                                        @endif
                                    </td>
                                    <td class="text-center">
                                        <a href="/inventory/{{ $item['code'] }}" class="btn btn-sm btn-info me-1">
                                            <i class="bi bi-eye"></i>
                                        </a>
                                        <button class="btn btn-sm btn-primary">
                                            <i class="bi bi-plus-circle"></i>
                                        </button>
                                    </td>
                                </tr>
                            @endforeach
                        </tbody>
                    </table>
                </div>
            </div>
        </div>

        <!-- Stock Value Chart -->
        <div class="card">
            <div class="card-header bg-white">
                <h5 class="mb-0">Distribusi Nilai Inventaris</h5>
            </div>
            <div class="card-body">
                <div class="row">
                    <div class="col-md-8">
                        <canvas id="stockValueChart" height="300"></canvas>
                    </div>
                    <div class="col-md-4">
                        <h6 class="mb-3">Analisis Inventaris</h6>
                        <ul class="list-group">
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Kategori dengan Nilai Tertinggi
                                @php
                                    $highestCategory = array_keys($categories, max($categories))[0];
                                @endphp
                                <span class="badge bg-primary rounded-pill">{{ $highestCategory }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Item Inventaris
                                <span
                                    class="badge bg-primary rounded-pill">{{ array_sum(array_column($categories, 'total_items')) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Total Stok Keseluruhan
                                <span
                                    class="badge bg-primary rounded-pill">{{ array_sum(array_column($categories, 'total_stock')) }}</span>
                            </li>
                            <li class="list-group-item d-flex justify-content-between align-items-center">
                                Rata-rata Stok per Item
                                @php
                                    $totalItems = array_sum(array_column($categories, 'total_items'));
                                    $totalStock = array_sum(array_column($categories, 'total_stock'));
                                    $avgStock = $totalItems > 0 ? round($totalStock / $totalItems, 1) : 0;
                                @endphp
                                <span class="badge bg-primary rounded-pill">{{ $avgStock }}</span>
                            </li>
                        </ul>
                    </div>
                </div>
            </div>
        </div>
    </div>
@endsection

@push('scripts')
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        document.addEventListener('DOMContentLoaded', function() {
            // Data untuk chart
            const categories = @json(array_keys($categories));
            const values = @json(array_column($categories, 'total_value'));

            // Membuat chart
            const ctx = document.getElementById('stockValueChart').getContext('2d');
            new Chart(ctx, {
                type: 'pie',
                data: {
                    labels: categories,
                    datasets: [{
                        data: values,
                        backgroundColor: [
                            '#4e73df',
                            '#1cc88a',
                            '#36b9cc',
                            '#f6c23e',
                            '#e74a3b',
                            '#858796'
                        ],
                        borderWidth: 1
                    }]
                },
                options: {
                    responsive: true,
                    maintainAspectRatio: false,
                    plugins: {
                        legend: {
                            position: 'right',
                        },
                        tooltip: {
                            callbacks: {
                                label: function(context) {
                                    const value = context.raw;
                                    const total = context.dataset.data.reduce((a, b) => a + b, 0);
                                    const percentage = ((value / total) * 100).toFixed(1);
                                    return `Rp ${value.toLocaleString()} (${percentage}%)`;
                                }
                            }
                        }
                    }
                }
            });
        });
    </script>
@endpush
