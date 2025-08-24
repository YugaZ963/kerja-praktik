<!DOCTYPE html>
<html>
<head>
    <meta charset="utf-8">
    <title>Laporan Penjualan - {{ $startDate->format('d M Y') }} s/d {{ $endDate->format('d M Y') }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            line-height: 1.4;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 20px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #333;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .summary-section {
            margin-bottom: 30px;
        }
        
        .summary-cards {
            display: table;
            width: 100%;
            margin-bottom: 20px;
        }
        
        .summary-card {
            display: table-cell;
            width: 25%;
            padding: 15px;
            border: 1px solid #ddd;
            text-align: center;
            vertical-align: top;
        }
        
        .summary-card h3 {
            margin: 0 0 5px 0;
            font-size: 18px;
            color: #333;
        }
        
        .summary-card p {
            margin: 0;
            color: #666;
            font-size: 11px;
        }
        
        .section-title {
            font-size: 16px;
            font-weight: bold;
            margin: 30px 0 15px 0;
            color: #333;
            border-bottom: 1px solid #ddd;
            padding-bottom: 5px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            border: 1px solid #ddd;
            padding: 8px;
            text-align: left;
        }
        
        th {
            background-color: #f5f5f5;
            font-weight: bold;
            font-size: 11px;
        }
        
        td {
            font-size: 10px;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .currency {
            font-weight: bold;
            color: #0d6efd;
        }
        
        .rank {
            background-color: #fcdf10;
            color: #c9b012;
            padding: 2px 6px;
            border-radius: 3px;
            font-weight: bold;
        }
        
        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #ddd;
            text-align: center;
            color: #666;
            font-size: 10px;
        }
        
        .page-break {
            page-break-before: always;
        }
        
        .no-data {
            text-align: center;
            color: #999;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <!-- Header -->
    <div class="header">
        <h1>LAPORAN PENJUALAN</h1>
        <p><strong>Periode:</strong> {{ $startDate->format('d F Y') }} - {{ $endDate->format('d F Y') }}</p>
        <p><strong>Tanggal Cetak:</strong> {{ now()->format('d F Y H:i:s') }}</p>
        <p><strong>Total Hari:</strong> {{ $startDate->diffInDays($endDate) + 1 }} hari</p>
    </div>

    <!-- Summary Section -->
    <div class="summary-section">
        <div class="section-title">RINGKASAN PENJUALAN</div>
        
        <div class="summary-cards">
            <div class="summary-card">
                <h3 class="currency">Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
                <p>Total Revenue</p>
            </div>
            <div class="summary-card">
                <h3>{{ number_format($totalOrders) }}</h3>
                <p>Total Pesanan Selesai</p>
            </div>
            <div class="summary-card">
                <h3 class="currency">Rp {{ number_format($averageOrderValue, 0, ',', '.') }}</h3>
                <p>Rata-rata per Pesanan</p>
            </div>
            <div class="summary-card">
                <h3>{{ $topProducts->sum('total_quantity') }}</h3>
                <p>Total Unit Terjual</p>
            </div>
        </div>
        
        @if($revenueGrowth != 0)
        <p style="text-align: center; margin-top: 10px;">
            <strong>Pertumbuhan Revenue:</strong> 
            <span style="color: {{ $revenueGrowth > 0 ? '#0d6efd' : '#cb2368' }};">
                {{ $revenueGrowth > 0 ? '+' : '' }}{{ round($revenueGrowth, 1) }}%
            </span>
            dari bulan sebelumnya
        </p>
        @endif
    </div>

    <!-- Top Products Section -->
    <div class="section-title">PRODUK TERLARIS</div>
    @if($topProducts->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="8%">Rank</th>
                <th width="40%">Nama Produk</th>
                <th width="20%">Kategori</th>
                <th width="12%">Unit Terjual</th>
                <th width="20%">Total Revenue</th>
            </tr>
        </thead>
        <tbody>
            @foreach($topProducts as $index => $item)
            <tr>
                <td class="text-center">
                    <span class="rank">#{{ $index + 1 }}</span>
                </td>
                <td>{{ $item->product->name ?? 'Produk Dihapus' }}</td>
                <td>{{ $item->product->category ?? '-' }}</td>
                <td class="text-center">{{ number_format($item->total_quantity) }}</td>
                <td class="text-right currency">Rp {{ number_format($item->total_revenue, 0, ',', '.') }}</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">Tidak ada data produk dalam periode ini</div>
    @endif

    <!-- Category Sales Section -->
    <div class="section-title">PENJUALAN PER KATEGORI</div>
    @if($categorySales->count() > 0)
    <table>
        <thead>
            <tr>
                <th width="30%">Kategori</th>
                <th width="20%">Unit Terjual</th>
                <th width="30%">Total Revenue</th>
                <th width="20%">Persentase</th>
            </tr>
        </thead>
        <tbody>
            @foreach($categorySales as $category)
            @php
                $percentage = $totalRevenue > 0 ? ($category->total_revenue / $totalRevenue) * 100 : 0;
            @endphp
            <tr>
                <td>{{ $category->category }}</td>
                <td class="text-center">{{ number_format($category->total_quantity) }}</td>
                <td class="text-right currency">Rp {{ number_format($category->total_revenue, 0, ',', '.') }}</td>
                <td class="text-center">{{ round($percentage, 1) }}%</td>
            </tr>
            @endforeach
        </tbody>
    </table>
    @else
    <div class="no-data">Tidak ada data kategori dalam periode ini</div>
    @endif

    <!-- Daily Sales Section -->
    @if($dailySales->count() > 0)
    <div class="page-break">
        <div class="section-title">TREN PENJUALAN HARIAN</div>
        <table>
            <thead>
                <tr>
                    <th width="20%">Tanggal</th>
                    <th width="15%">Hari</th>
                    <th width="20%">Jumlah Pesanan</th>
                    <th width="25%">Revenue Harian</th>
                    <th width="20%">Rata-rata per Order</th>
                </tr>
            </thead>
            <tbody>
                @foreach($dailySales as $daily)
                @php
                    $date = \Carbon\Carbon::parse($daily->date);
                    $avgPerOrder = $daily->orders_count > 0 ? $daily->daily_revenue / $daily->orders_count : 0;
                @endphp
                <tr>
                    <td>{{ $date->format('d M Y') }}</td>
                    <td>{{ $date->format('l') }}</td>
                    <td class="text-center">{{ $daily->orders_count }}</td>
                    <td class="text-right currency">Rp {{ number_format($daily->daily_revenue, 0, ',', '.') }}</td>
                    <td class="text-right currency">Rp {{ number_format($avgPerOrder, 0, ',', '.') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
    </div>
    @endif

    <!-- Recent Completed Orders -->
    @if($completedOrders->count() > 0)
    <div class="page-break">
        <div class="section-title">PESANAN SELESAI TERBARU (10 Terakhir)</div>
        <table>
            <thead>
                <tr>
                    <th width="15%">No. Pesanan</th>
                    <th width="25%">Customer</th>
                    <th width="15%">Tanggal Selesai</th>
                    <th width="10%">Items</th>
                    <th width="20%">Total Amount</th>
                    <th width="15%">Metode Bayar</th>
                </tr>
            </thead>
            <tbody>
                @foreach($completedOrders->take(10) as $order)
                <tr>
                    <td>{{ $order->order_number }}</td>
                    <td>
                        <strong>{{ $order->customer_name }}</strong><br>
                        <small>{{ $order->customer_phone }}</small>
                    </td>
                    <td>{{ $order->completed_at->format('d M Y H:i') }}</td>
                    <td class="text-center">{{ $order->items->count() }}</td>
                    <td class="text-right currency">Rp {{ number_format($order->total_amount, 0, ',', '.') }}</td>
                    <td class="text-center">{{ ucfirst($order->payment_method ?? '-') }}</td>
                </tr>
                @endforeach
            </tbody>
        </table>
        
        @if($completedOrders->count() > 10)
        <p style="text-align: center; margin-top: 10px; font-style: italic; color: #666;">
            * Menampilkan 10 dari {{ $completedOrders->count() }} total pesanan selesai dalam periode ini
        </p>
        @endif
    </div>
    @endif

    <!-- Footer -->
    <div class="footer">
        <p>Laporan ini dibuat secara otomatis oleh sistem pada {{ now()->format('d F Y \p\u\k\u\l H:i:s') }}</p>
        <p>© {{ date('Y') }} - Sistem Manajemen Penjualan</p>
    </div>
</body>
</html>