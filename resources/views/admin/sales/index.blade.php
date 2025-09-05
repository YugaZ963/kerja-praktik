@extends('layouts.customer')

@section('title', 'Laporan Penjualan')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">
                    <i class="bi bi-graph-up me-2"></i>
                    Laporan Penjualan
                </h1>
                <div class="d-flex gap-2">
                    <a href="{{ route('admin.sales.export-pdf', request()->query()) }}" class="btn btn-danger">
                        <i class="bi bi-file-pdf"></i> Export PDF
                    </a>
                    <a href="{{ route('dashboard') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali
                    </a>
                </div>
            </div>
        </div>

        <!-- Filter Periode -->        
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Filter Periode Laporan</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('admin.sales.index') }}">
                    <div class="row g-3 align-items-end">
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Mulai</label>
                            <input type="date" class="form-control" name="start_date" 
                                   value="{{ $startDate }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <label class="form-label fw-semibold">Tanggal Akhir</label>
                            <input type="date" class="form-control" name="end_date" 
                                   value="{{ $endDate }}" max="{{ date('Y-m-d') }}">
                        </div>
                        <div class="col-md-4">
                            <button type="submit" class="btn btn-primary w-100">
                                <i class="bi bi-funnel me-1"></i>Filter Laporan
                            </button>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Summary Cards -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-gradient-primary text-white border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-currency-dollar display-4 mb-2"></i>
                        <h3 class="mb-1">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        <p class="mb-0 opacity-75">Total Revenue</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-gradient-success text-white border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-cart-check display-4 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($totalOrders) }}</h3>
                        <p class="mb-0 opacity-75">Total Pesanan</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-gradient-warning text-white border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-calculator display-4 mb-2"></i>
                        <h3 class="mb-1">Rp {{ number_format($averageOrder, 0, ',', '.') }}</h3>
                        <p class="mb-0 opacity-75">Rata-rata Order</p>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-gradient-info text-white border-0 shadow-sm">
                    <div class="card-body text-center">
                        <i class="bi bi-box-seam display-4 mb-2"></i>
                        <h3 class="mb-1">{{ number_format($totalProductsSold) }}</h3>
                        <p class="mb-0 opacity-75">Produk Terjual</p>
                    </div>
                </div>
            </div>
        </div>

        <!-- Charts Row -->
        <div class="row g-4 mb-4">
            <!-- Daily Sales Trend -->
            <div class="col-lg-8">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-graph-up me-2"></i>
                            Tren Penjualan Harian (7 Hari Terakhir)
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="dailySalesChart" height="100"></canvas>
                    </div>
                </div>
            </div>
            
            <!-- Sales by Category -->
            <div class="col-lg-4">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-pie-chart me-2"></i>
                            Penjualan per Kategori
                        </h5>
                    </div>
                    <div class="card-body">
                        <canvas id="categoryChart" height="200"></canvas>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tables Row -->
        <div class="row g-4">
            <!-- Top Products -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-trophy me-2"></i>
                            Produk Terlaris
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Produk</th>
                                        <th>Kategori</th>
                                        <th class="text-center">Terjual</th>
                                        <th class="text-end">Revenue</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @forelse($topProducts as $product)
                                        <tr>
                                            <td class="fw-semibold">{{ $product->name }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->category }}</span>
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $product->total_sold }}</span>
                                            </td>
                                            <td class="text-end fw-semibold text-success">
                                                Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                                Tidak ada data produk terlaris
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Sales by Category Table -->
            <div class="col-lg-6">
                <div class="card shadow-sm">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">
                            <i class="bi bi-tags me-2"></i>
                            Penjualan per Kategori
                        </h5>
                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Kategori</th>
                                        <th class="text-center">Terjual</th>
                                        <th class="text-end">Revenue</th>
                                        <th class="text-center">%</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @php $totalCategoryRevenue = $salesByCategory->sum('total_revenue'); @endphp
                                    @forelse($salesByCategory as $category)
                                        @php $percentage = $totalCategoryRevenue > 0 ? ($category->total_revenue / $totalCategoryRevenue) * 100 : 0; @endphp
                                        <tr>
                                            <td class="fw-semibold">{{ $category->category }}</td>
                                            <td class="text-center">
                                                <span class="badge bg-primary">{{ $category->total_sold }}</span>
                                            </td>
                                            <td class="text-end fw-semibold text-success">
                                                Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                                            </td>
                                            <td class="text-center">
                                                <span class="badge bg-info">{{ number_format($percentage, 1) }}%</span>
                                            </td>
                                        </tr>
                                    @empty
                                        <tr>
                                            <td colspan="4" class="text-center py-4 text-muted">
                                                <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                                Tidak ada data penjualan per kategori
                                            </td>
                                        </tr>
                                    @endforelse
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        <!-- Recent Orders -->
        <div class="card shadow-sm mt-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">
                    <i class="bi bi-clock-history me-2"></i>
                    Pesanan Selesai Terbaru
                </h5>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>ID Pesanan</th>
                                <th>Customer</th>
                                <th>Tanggal</th>
                                <th class="text-center">Items</th>
                                <th class="text-end">Total</th>
                                <th class="text-center">Status</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse($recentOrders as $order)
                                <tr>
                                    <td class="fw-semibold">#{{ $order->id }}</td>
                                    <td>{{ $order->user->name }}</td>
                                    <td>{{ $order->created_at->format('d/m/Y H:i') }}</td>
                                    <td class="text-center">
                                        <span class="badge bg-secondary">{{ $order->items->count() }} items</span>
                                    </td>
                                    <td class="text-end fw-semibold text-success">
                                        Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                                    </td>
                                    <td class="text-center">
                                        @if($order->status === 'completed')
                                            <span class="badge bg-success">Selesai</span>
                                        @elseif($order->status === 'delivered')
                                            <span class="badge bg-info">Terkirim</span>
                                        @endif
                                    </td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="6" class="text-center py-4 text-muted">
                                        <i class="bi bi-inbox display-6 d-block mb-2"></i>
                                        Tidak ada pesanan selesai dalam periode ini
                                    </td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
        </div>
    </div>

    <!-- Chart.js -->
    <script src="https://cdn.jsdelivr.net/npm/chart.js"></script>
    <script>
        // Daily Sales Chart
        const dailySalesCtx = document.getElementById('dailySalesChart').getContext('2d');
        const dailySalesData = @json($dailySales);
        
        new Chart(dailySalesCtx, {
            type: 'line',
            data: {
                labels: dailySalesData.map(item => {
                    const date = new Date(item.date);
                    return date.toLocaleDateString('id-ID', { day: '2-digit', month: 'short' });
                }),
                datasets: [{
                    label: 'Revenue (Rp)',
                    data: dailySalesData.map(item => item.revenue),
                    borderColor: 'rgb(54, 162, 235)',
                    backgroundColor: 'rgba(54, 162, 235, 0.1)',
                    tension: 0.4,
                    fill: true
                }, {
                    label: 'Jumlah Pesanan',
                    data: dailySalesData.map(item => item.orders),
                    borderColor: 'rgb(255, 99, 132)',
                    backgroundColor: 'rgba(255, 99, 132, 0.1)',
                    tension: 0.4,
                    yAxisID: 'y1'
                }]
            },
            options: {
                responsive: true,
                scales: {
                    y: {
                        type: 'linear',
                        display: true,
                        position: 'left',
                        ticks: {
                            callback: function(value) {
                                return 'Rp ' + value.toLocaleString('id-ID');
                            }
                        }
                    },
                    y1: {
                        type: 'linear',
                        display: true,
                        position: 'right',
                        grid: {
                            drawOnChartArea: false,
                        },
                    }
                },
                plugins: {
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                if (context.datasetIndex === 0) {
                                    return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                                } else {
                                    return 'Pesanan: ' + context.parsed.y + ' orders';
                                }
                            }
                        }
                    }
                }
            }
        });

        // Category Pie Chart
        const categoryCtx = document.getElementById('categoryChart').getContext('2d');
        const categoryData = @json($salesByCategory);
        
        new Chart(categoryCtx, {
            type: 'doughnut',
            data: {
                labels: categoryData.map(item => item.category),
                datasets: [{
                    data: categoryData.map(item => item.total_revenue),
                    backgroundColor: [
                        '#FF6384',
                        '#36A2EB', 
                        '#FFCE56',
                        '#4BC0C0',
                        '#9966FF',
                        '#FF9F40',
                        '#FF6384',
                        '#C9CBCF'
                    ]
                }]
            },
            options: {
                responsive: true,
                maintainAspectRatio: false,
                plugins: {
                    legend: {
                        position: 'bottom'
                    },
                    tooltip: {
                        callbacks: {
                            label: function(context) {
                                return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID');
                            }
                        }
                    }
                }
            }
        });
    </script>
@endsection