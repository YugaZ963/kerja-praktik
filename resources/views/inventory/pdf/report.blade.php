<!DOCTYPE html>
<html lang="id">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>{{ $title }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            font-size: 12px;
            margin: 0;
            padding: 20px;
            color: #333;
        }
        
        .header {
            text-align: center;
            margin-bottom: 30px;
            border-bottom: 2px solid #333;
            padding-bottom: 15px;
        }
        
        .header h1 {
            margin: 0;
            font-size: 24px;
            color: #2c3e50;
        }
        
        .header p {
            margin: 5px 0;
            color: #666;
        }
        
        .filters {
            background-color: #f8f9fa;
            padding: 10px;
            margin-bottom: 20px;
            border-radius: 5px;
            border: 1px solid #dee2e6;
        }
        
        .filters h3 {
            margin: 0 0 10px 0;
            font-size: 14px;
            color: #495057;
        }
        
        .filter-item {
            display: inline-block;
            margin-right: 20px;
            margin-bottom: 5px;
        }
        
        .filter-label {
            font-weight: bold;
            color: #495057;
        }
        
        .filter-value {
            color: #0d6efd;
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
            vertical-align: top;
        }
        
        th {
            background-color: #f8f9fa;
            font-weight: bold;
            color: #495057;
            text-align: center;
        }
        
        .text-center {
            text-align: center;
        }
        
        .text-right {
            text-align: right;
        }
        
        .status-badge {
            padding: 2px 6px;
            border-radius: 3px;
            font-size: 10px;
            font-weight: bold;
            text-transform: uppercase;
        }
        
        .status-ready {
            background-color: #d4edda;
            color: #155724;
        }
        
        .status-low {
            background-color: #fff3cd;
            color: #856404;
        }
        
        .status-out {
            background-color: #f8d7da;
            color: #721c24;
        }
        
        .footer {
            margin-top: 30px;
            text-align: center;
            font-size: 10px;
            color: #666;
            border-top: 1px solid #ddd;
            padding-top: 10px;
        }
        
        .summary {
            background-color: #e9ecef;
            padding: 15px;
            margin-bottom: 20px;
            border-radius: 5px;
        }
        
        .summary h3 {
            margin: 0 0 10px 0;
            color: #495057;
        }
        
        .summary-item {
            display: inline-block;
            margin-right: 30px;
            margin-bottom: 5px;
        }
        
        .summary-label {
            font-weight: bold;
            color: #495057;
        }
        
        .summary-value {
            color: #0d6efd;
            font-weight: bold;
        }
        
        .sizes {
            font-size: 10px;
            color: #666;
        }
        
        .page-break {
            page-break-after: always;
        }
    </style>
</head>
<body>
    <div class="header">
        <h1>{{ $title }}</h1>
        <p>Tanggal Cetak: {{ $date }}</p>
        <p>Sistem Manajemen Inventaris</p>
    </div>

    @if($filters['category'] || $filters['status'] || $filters['period'])
    <div class="filters">
        <h3>Filter yang Diterapkan:</h3>
        @if($filters['category'])
            <div class="filter-item">
                <span class="filter-label">Kategori:</span>
                <span class="filter-value">{{ $filters['category'] }}</span>
            </div>
        @endif
        @if($filters['status'])
            <div class="filter-item">
                <span class="filter-label">Status Stok:</span>
                <span class="filter-value">
                    @if($filters['status'] == 'low') Stok Rendah
                    @elseif($filters['status'] == 'out') Stok Habis
                    @elseif($filters['status'] == 'ready') Stok Siap
                    @else {{ $filters['status'] }}
                    @endif
                </span>
            </div>
        @endif
        @if($filters['period'])
            <div class="filter-item">
                <span class="filter-label">Periode:</span>
                <span class="filter-value">
                    @if($filters['period'] == 'today') Hari Ini
                    @elseif($filters['period'] == 'week') Minggu Ini
                    @elseif($filters['period'] == 'month') Bulan Ini
                    @elseif($filters['period'] == 'year') Tahun Ini
                    @else {{ $filters['period'] }}
                    @endif
                </span>
            </div>
        @endif
    </div>
    @endif

    <div class="summary">
        <h3>Ringkasan Laporan</h3>
        <div class="summary-item">
            <span class="summary-label">Total Item:</span>
            <span class="summary-value">{{ $inventory_items->count() }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Total Stok:</span>
            <span class="summary-value">{{ number_format($inventory_items->sum('stock')) }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Nilai Total Pembelian:</span>
            <span class="summary-value">Rp {{ number_format($inventory_items->sum(function($item) { return $item->stock * $item->purchase_price; }), 0, ',', '.') }}</span>
        </div>
        <div class="summary-item">
            <span class="summary-label">Nilai Total Penjualan:</span>
            <span class="summary-value">Rp {{ number_format($inventory_items->sum(function($item) { return $item->stock * $item->selling_price; }), 0, ',', '.') }}</span>
        </div>
    </div>

    <table>
        <thead>
            <tr>
                <th style="width: 8%;">No</th>
                <th style="width: 12%;">Kode</th>
                <th style="width: 20%;">Nama Barang</th>
                <th style="width: 12%;">Kategori</th>
                <th style="width: 8%;">Stok</th>
                <th style="width: 8%;">Min. Stok</th>
                <th style="width: 10%;">Harga Beli</th>
                <th style="width: 10%;">Harga Jual</th>
                <th style="width: 12%;">Ukuran</th>
            </tr>
        </thead>
        <tbody>
            @forelse($inventory_items as $index => $item)
                <tr>
                    <td class="text-center">{{ $index + 1 }}</td>
                    <td>{{ $item->code }}</td>
                    <td>{{ $item->name }}</td>
                    <td>{{ $item->category }}</td>
                    <td class="text-center">
                        {{ number_format($item->stock) }}
                        @if($item->stock <= $item->min_stock)
                            @if($item->stock == 0)
                                <span class="status-badge status-out">Habis</span>
                            @else
                                <span class="status-badge status-low">Rendah</span>
                            @endif
                        @else
                            <span class="status-badge status-ready">Siap</span>
                        @endif
                    </td>
                    <td class="text-center">{{ number_format($item->min_stock) }}</td>
                    <td class="text-right">Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                    <td class="text-right">Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                    <td class="sizes">
                        @if(is_array($item->sizes_available) && count($item->sizes_available) > 0)
                            {{ implode(', ', $item->sizes_available) }}
                        @else
                            -
                        @endif
                    </td>
                </tr>
            @empty
                <tr>
                    <td colspan="9" class="text-center">Tidak ada data inventaris yang ditemukan</td>
                </tr>
            @endforelse
        </tbody>
    </table>

    <div class="footer">
        <p>Laporan ini digenerate secara otomatis oleh Sistem Manajemen Inventaris RAVAZKA</p>
        <p>Dicetak pada: {{ $date }}</p>
    </div>
</body>
</html>