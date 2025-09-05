<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Laporan Penjualan RAVAZKA</title>
    <style>
        body {
            font-family: 'DejaVu Sans', sans-serif;
            font-size: 12px;
            line-height: 1.4;
            color: #333;
            margin: 0;
            padding: 20px;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #007bff;
            padding-bottom: 20px;
        }
        
        .header h1 {
            color: #007bff;
            margin: 0;
            font-size: 24px;
        }
        
        .header h2 {
            color: #666;
            margin: 5px 0;
            font-size: 16px;
            font-weight: normal;
        }
        
        .period {
            background-color: #f8f9fa;
            padding: 15px;
            border-radius: 5px;
            margin-bottom: 20px;
            text-align: center;
        }
        
        .summary {
            display: table;
            width: 100%;
            margin-bottom: 30px;
        }
        
        .summary-item {
            display: table-cell;
            width: 50%;
            padding: 15px;
            text-align: center;
            border: 1px solid #dee2e6;
            background-color: #f8f9fa;
        }
        
        .summary-item h3 {
            margin: 0;
            color: #007bff;
            font-size: 18px;
        }
        
        .summary-item p {
            margin: 5px 0 0 0;
            color: #666;
            font-size: 11px;
        }
        
        .section {
            margin-bottom: 30px;
        }
        
        .section h3 {
            color: #007bff;
            border-bottom: 1px solid #dee2e6;
            padding-bottom: 10px;
            margin-bottom: 15px;
            font-size: 16px;
        }
        
        table {
            width: 100%;
            border-collapse: collapse;
            margin-bottom: 20px;
        }
        
        th, td {
            padding: 8px;
            text-align: left;
            border-bottom: 1px solid #dee2e6;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
        }
        
        .text-right {
            text-align: right;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-success {
            color: #28a745;
            font-weight: bold;
        }
        
        .badge {
            background-color: #007bff;
            color: white;
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
        }
        
        .footer {
            margin-top: 40px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #dee2e6;
            padding-top: 15px;
        }
        
        .no-data {
            text-align: center;
            color: #666;
            font-style: italic;
            padding: 20px;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>RAVAZKA</h1>
        <h2>Laporan Penjualan</h2>
    </div>
    
    <div class="period">
        <strong>Periode Laporan:</strong> {{ date('d/m/Y', strtotime($startDate)) }} - {{ date('d/m/Y', strtotime($endDate)) }}
    </div>
    
    <div class="summary">
        <div class="summary-item">
            <h3>Rp {{ number_format($totalRevenue, 0, ',', '.') }}</h3>
            <p>Total Revenue</p>
        </div>
        <div class="summary-item">
            <h3>{{ number_format($totalOrders) }}</h3>
            <p>Total Pesanan</p>
        </div>
    </div>
    
    <div class="section">
        <h3>Produk Terlaris</h3>
        @if($topProducts->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Nama Produk</th>
                        <th>Kategori</th>
                        <th class="text-center">Qty Terjual</th>
                        <th class="text-right">Total Revenue</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach($topProducts as $index => $product)
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $product->name }}</td>
                            <td>{{ $product->category }}</td>
                            <td class="text-center">{{ $product->total_sold }}</td>
                            <td class="text-right text-success">
                                Rp {{ number_format($product->total_revenue, 0, ',', '.') }}
                            </td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Tidak ada data produk terlaris dalam periode ini
            </div>
        @endif
    </div>
    
    <div class="section">
        <h3>Penjualan per Kategori</h3>
        @if($salesByCategory->count() > 0)
            <table>
                <thead>
                    <tr>
                        <th>No</th>
                        <th>Kategori</th>
                        <th class="text-center">Qty Terjual</th>
                        <th class="text-right">Total Revenue</th>
                        <th class="text-center">Persentase</th>
                    </tr>
                </thead>
                <tbody>
                    @php $totalCategoryRevenue = $salesByCategory->sum('total_revenue'); @endphp
                    @foreach($salesByCategory as $index => $category)
                        @php $percentage = $totalCategoryRevenue > 0 ? ($category->total_revenue / $totalCategoryRevenue) * 100 : 0; @endphp
                        <tr>
                            <td>{{ $index + 1 }}</td>
                            <td>{{ $category->category }}</td>
                            <td class="text-center">{{ $category->total_sold }}</td>
                            <td class="text-right text-success">
                                Rp {{ number_format($category->total_revenue, 0, ',', '.') }}
                            </td>
                            <td class="text-center">{{ number_format($percentage, 1) }}%</td>
                        </tr>
                    @endforeach
                </tbody>
            </table>
        @else
            <div class="no-data">
                Tidak ada data penjualan per kategori dalam periode ini
            </div>
        @endif
    </div>
    
    <div class="footer">
        <p>
            <strong>RAVAZKA - Seragam Sekolah Berkualitas</strong><br>
            Laporan ini dibuat secara otomatis pada {{ $generatedAt }}
        </p>
    </div>
</body>
</html>