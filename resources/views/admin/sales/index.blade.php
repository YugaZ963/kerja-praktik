@extends('layouts.customer')

@section('title', 'Laporan Penjualan - Admin')

@section('content')
<x-navbar />

<div class="container py-4">
    <!-- Page Header -->
    <div class="d-flex justify-content-between align-items-center mb-4">
        <div>
            <h1 class="h3 mb-0">
                <i class="fas fa-chart-line me-2 text-primary"></i>
                Laporan Penjualan
            </h1>
            <p class="text-muted mb-0">Dashboard analisis penjualan dan revenue</p>
        </div>
        <div>
            <a href="{{ route('admin.sales.export-pdf', ['start_date' => $startDate->format('Y-m-d'), 'end_date' => $endDate->format('Y-m-d')]) }}" 
               class="btn btn-outline-danger me-2" target="_blank">
                <i class="fas fa-file-pdf me-1"></i>
                Export PDF
            </a>
            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#dateRangeModal">
                <i class="fas fa-calendar me-1"></i>
                Filter Tanggal
            </button>
        </div>
    </div>

    <!-- Date Range Info -->
    <div class="alert alert-info mb-4">
        <i class="fas fa-info-circle me-2"></i>
        <strong>Periode Laporan:</strong> {{ $startDate->format('d M Y') }} - {{ $endDate->format('d M Y') }}
        <span class="ms-3">
            <strong>Total Hari:</strong> {{ $startDate->diffInDays($endDate) + 1 }} hari
        </span>
    </div>

    <!-- Summary Cards -->
    <div class="row g-4 mb-4">
        <div class="col-md-3">
            <div class="card bg-primary text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Revenue</h6>
                            <h3 class="mb-0">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-money-bill-wave"></i>
                        </div>
                    </div>
                    @if($revenueGrowth != 0)
                    <small class="d-block mt-2">
                        <i class="fas fa-{{ $revenueGrowth > 0 ? 'arrow-up text-success' : 'arrow-down text-danger' }}"></i>
                        {{ abs(round($revenueGrowth, 1)) }}% dari bulan lalu
                    </small>
                    @endif
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-success text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Total Pesanan</h6>
                            <h3 class="mb-0">{{ number_format($totalOrders) }}</h3>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-shopping-cart"></i>
                        </div>
                    </div>
                    <small class="d-block mt-2">
                        Pesanan selesai
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-info text-white h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Rata-rata Order</h6>
                            <h3 class="mb-0">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-calculator"></i>
                        </div>
                    </div>
                    <small class="d-block mt-2">
                        Per pesanan
                    </small>
                </div>
            </div>
        </div>
        
        <div class="col-md-3">
            <div class="card bg-warning text-dark h-100">
                <div class="card-body">
                    <div class="d-flex justify-content-between align-items-center">
                        <div>
                            <h6 class="card-title mb-0">Produk Terjual</h6>
                            <h3 class="mb-0">{{ $topProducts->sum('total_quantity') }}</h3>
                        </div>
                        <div class="fs-1 opacity-75">
                            <i class="fas fa-box"></i>
                        </div>
                    </div>
                    <small class="d-block mt-2">
                        Total unit
                    </small>
                </div>
            </div>
        </div>
    </div>

    <!-- Charts Row -->
    <div class="row g-4 mb-4">
        <!-- Daily Sales Chart -->
        <div class="col-lg-8">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-area me-2"></i>
                        Tren Penjualan Harian
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="dailySalesChart" height="300"></canvas>
                </div>
            </div>
        </div>
        
        <!-- Category Sales Pie Chart -->
        <div class="col-lg-4">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-chart-pie me-2"></i>
                        Penjualan per Kategori
                    </h5>
                </div>
                <div class="card-body">
                    <canvas id="categorySalesChart" height="300"></canvas>
                </div>
            </div>
        </div>
    </div>

    <!-- Top Products and Recent Orders -->
    <div class="row g-4 mb-4">
        <!-- Top Selling Products -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-trophy me-2"></i>
                        Produk Terlaris
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Rank</th>
                                    <th>Produk</th>
                                    <th>Terjual</th>
                                    <th>Revenue</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($topProducts as $index => $item)
                                <tr>
                                    <td>
                                        <span class="badge bg-{{ $index < 3 ? ['warning', 'secondary', 'warning'][$index] : 'light' }} text-dark">
                                            #{{ $index + 1 }}
                                        </span>
                                    </td>
                                    <td>
                                        <div class="fw-semibold">{{ $item->product->name ?? 'Produk Dihapus' }}</div>
                                        <small class="text-muted">{{ $item->product->category ?? '-' }}</small>
                                    </td>
                                    <td>
                                        <span class="badge bg-primary">{{ $item->total_quantity }} unit</span>
                                    </td>
                                    <td class="fw-semibold text-success">
                                        Rp {{ number_format($item->total_revenue, 0, ',', '.') }}
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fs-1 mb-2 d-block"></i>
                                        Tidak ada data produk
                                    </td>
                                </tr>
                                @endforelse
                            </tbody>
                        </table>
                    </div>
                </div>
            </div>
        </div>
        
        <!-- Category Sales Table -->
        <div class="col-lg-6">
            <div class="card h-100">
                <div class="card-header bg-white">
                    <h5 class="mb-0">
                        <i class="fas fa-tags me-2"></i>
                        Penjualan per Kategori
                    </h5>
                </div>
                <div class="card-body p-0">
                    <div class="table-responsive">
                        <table class="table table-hover mb-0">
                            <thead class="table-light">
                                <tr>
                                    <th>Kategori</th>
                                    <th>Unit Terjual</th>
                                    <th>Revenue</th>
                                    <th>%</th>
                                </tr>
                            </thead>
                            <tbody>
                                @forelse($categorySales as $category)
                                @php
                                    $percentage = $totalRevenue > 0 ? ($category->total_revenue / $totalRevenue) * 100 : 0;
                                @endphp
                                <tr>
                                    <td class="fw-semibold">{{ $category->category }}</td>
                                    <td>
                                        <span class="badge bg-info">{{ $category->total_quantity }}</span>
                                    </td>
                                    <td class="text-success fw-semibold">
                                        Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                                    </td>
                                    <td>
                                        <div class="d-flex align-items-center">
                                            <div class="progress flex-grow-1 me-2" style="height: 8px;">
                                                <div class="progress-bar" style="width: {{ $percentage }}%"></div>
                                            </div>
                                            <small class="text-muted">{{ round($percentage, 1) }}%</small>
                                        </div>
                                    </td>
                                </tr>
                                @empty
                                <tr>
                                    <td colspan="4" class="text-center py-4 text-muted">
                                        <i class="fas fa-inbox fs-1 mb-2 d-block"></i>
                                        Tidak ada data kategori
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

    <!-- Recent Completed Orders -->
    <div class="card">
        <div class="card-header bg-white">
            <h5 class="mb-0">
                <i class="fas fa-list me-2"></i>
                Pesanan Selesai Terbaru
            </h5>
        </div>
        <div class="card-body p-0">
            <div class="table-responsive">
                <table class="table table-hover mb-0">
                    <thead class="table-light">
                        <tr>
                            <th>No. Pesanan</th>
                            <th>Customer</th>
                            <th>Tanggal Selesai</th>
                            <th>Items</th>
                            <th>Total</th>
                            <th>Aksi</th>
                        </tr>
                    </thead>
                    <tbody>
                        @forelse($completedOrders->take(10) as $order)
                        <tr>
                            <td>
                                <span class="fw-semibold text-primary">{{ $order->order_number }}</span>
                            </td>
                            <td>
                                <div class="fw-semibold">{{ $order->customer_name }}</div>
                                <small class="text-muted">{{ $order->customer_phone }}</small>
                            </td>
                            <td>
                                @if($order->delivered_at)
                                    <div>{{ $order->delivered_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->delivered_at->format('H:i') }}</small>
                                @else
                                    <div>{{ $order->created_at->format('d M Y') }}</div>
                                    <small class="text-muted">{{ $order->created_at->format('H:i') }}</small>
                                @endif
                            </td>
                            <td>
                                <span class="badge bg-secondary">{{ $order->items->count() }} item(s)</span>
                            </td>
                            <td class="fw-semibold text-success">
                                Rp {{ number_format($order->total_amount, 0, ',', '.') }}
                            </td>
                            <td>
                                <a href="{{ route('admin.orders.show', $order) }}" class="btn btn-sm btn-outline-primary">
                                    <i class="fas fa-eye"></i>
                                </a>
                            </td>
                        </tr>
                        @empty
                        <tr>
                            <td colspan="6" class="text-center py-4 text-muted">
                                <i class="fas fa-inbox fs-1 mb-2 d-block"></i>
                                Tidak ada pesanan selesai dalam periode ini
                            </td>
                        </tr>
                        @endforelse
                    </tbody>
                </table>
            </div>
            
            @if($completedOrders->count() > 10)
            <div class="card-footer bg-light text-center">
                <small class="text-muted">
                    Menampilkan 10 dari {{ $completedOrders->count() }} pesanan selesai
                </small>
            </div>
            @endif
        </div>
    </div>
</div>

<!-- Date Range Modal -->
<div class="modal fade" id="dateRangeModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <form method="GET" action="{{ route('admin.sales.index') }}">
                <div class="modal-header">
                    <h5 class="modal-title">
                        <i class="fas fa-calendar me-2"></i>
                        Filter Periode Laporan
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <div class="row g-3">
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Mulai</label>
                            <input type="date" name="start_date" class="form-control" 
                                   value="{{ $startDate->format('Y-m-d') }}" required>
                        </div>
                        <div class="col-md-6">
                            <label class="form-label">Tanggal Akhir</label>
                            <input type="date" name="end_date" class="form-control" 
                                   value="{{ $endDate->format('Y-m-d') }}" required>
                        </div>
                    </div>
                    
                    <div class="mt-3">
                        <label class="form-label">Quick Select:</label>
                        <div class="btn-group w-100" role="group">
                            <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('today')">
                                Hari Ini
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('week')">
                                7 Hari
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('month')">
                                Bulan Ini
                            </button>
                            <button type="button" class="btn btn-outline-secondary" onclick="setDateRange('year')">
                                Tahun Ini
                            </button>
                        </div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="fas fa-filter me-1"></i>
                        Terapkan Filter
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection

@push('scripts')
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
            return date.toLocaleDateString('id-ID', { day: 'numeric', month: 'short' });
        }),
        datasets: [{
            label: 'Revenue (Rp)',
            data: dailySalesData.map(item => item.daily_revenue),
            borderColor: 'rgb(75, 192, 192)',
            backgroundColor: 'rgba(75, 192, 192, 0.1)',
            tension: 0.4,
            fill: true
        }, {
            label: 'Jumlah Pesanan',
            data: dailySalesData.map(item => item.orders_count),
            borderColor: 'rgb(255, 99, 132)',
            backgroundColor: 'rgba(255, 99, 132, 0.1)',
            tension: 0.4,
            yAxisID: 'y1'
        }]
    },
    options: {
        responsive: true,
        maintainAspectRatio: false,
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
                }
            }
        },
        plugins: {
            tooltip: {
                callbacks: {
                    label: function(context) {
                        if (context.datasetIndex === 0) {
                            return 'Revenue: Rp ' + context.parsed.y.toLocaleString('id-ID');
                        } else {
                            return 'Pesanan: ' + context.parsed.y + ' order';
                        }
                    }
                }
            }
        }
    }
});

// Category Sales Pie Chart
const categorySalesCtx = document.getElementById('categorySalesChart').getContext('2d');
const categorySalesData = @json($categorySales);

new Chart(categorySalesCtx, {
    type: 'doughnut',
    data: {
        labels: categorySalesData.map(item => item.category),
        datasets: [{
            data: categorySalesData.map(item => item.total_revenue),
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
            tooltip: {
                callbacks: {
                    label: function(context) {
                        const total = context.dataset.data.reduce((a, b) => a + b, 0);
                        const percentage = ((context.parsed / total) * 100).toFixed(1);
                        return context.label + ': Rp ' + context.parsed.toLocaleString('id-ID') + ' (' + percentage + '%)';
                    }
                }
            }
        }
    }
});

// Date range quick select functions
function setDateRange(period) {
    const today = new Date();
    let startDate, endDate;
    
    switch(period) {
        case 'today':
            startDate = endDate = today;
            break;
        case 'week':
            startDate = new Date(today.getTime() - 6 * 24 * 60 * 60 * 1000);
            endDate = today;
            break;
        case 'month':
            startDate = new Date(today.getFullYear(), today.getMonth(), 1);
            endDate = new Date(today.getFullYear(), today.getMonth() + 1, 0);
            break;
        case 'year':
            startDate = new Date(today.getFullYear(), 0, 1);
            endDate = new Date(today.getFullYear(), 11, 31);
            break;
    }
    
    document.querySelector('input[name="start_date"]').value = startDate.toISOString().split('T')[0];
    document.querySelector('input[name="end_date"]').value = endDate.toISOString().split('T')[0];
}
</script>
@endpush