@extends('layouts.customer')

@section('title', 'Laporan Inventaris')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <h1 class="h3 mb-0 text-primary">Laporan Inventaris</h1>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
                </a>
            </div>
        </div>

        <!-- Filter dan Periode Laporan -->
        <div class="card mb-4">
            <div class="card-header bg-white">
                <h5 class="mb-0">Filter & Pencarian Laporan</h5>
            </div>
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.report') }}">
                    <!-- Search Bar -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Cari berdasarkan nama, kode, kategori, atau supplier..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters -->
                    <div class="row g-3">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                <option value="Kemeja Sekolah" {{ request('category') == 'Kemeja Sekolah' ? 'selected' : '' }}>Kemeja Sekolah</option>
                                <option value="Kemeja Batik" {{ request('category') == 'Kemeja Batik' ? 'selected' : '' }}>Kemeja Batik</option>
                                <option value="Kemeja Batik Koko" {{ request('category') == 'Kemeja Batik Koko' ? 'selected' : '' }}>Kemeja Batik Koko</option>
                                <option value="Kemeja Padang" {{ request('category') == 'Kemeja Padang' ? 'selected' : '' }}>Kemeja Padang</option>
                                <option value="Rok Sekolah" {{ request('category') == 'Rok Sekolah' ? 'selected' : '' }}>Rok Sekolah</option>
                                <option value="Celana Sekolah" {{ request('category') == 'Celana Sekolah' ? 'selected' : '' }}>Celana Sekolah</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier" class="form-select">
                                <option value="">Semua Supplier</option>
                                <option value="PT Tekstil Nusantara" {{ request('supplier') == 'PT Tekstil Nusantara' ? 'selected' : '' }}>PT Tekstil Nusantara</option>
                                <option value="CV Garmen Jaya" {{ request('supplier') == 'CV Garmen Jaya' ? 'selected' : '' }}>CV Garmen Jaya</option>
                                <option value="UD Konveksi Mandiri" {{ request('supplier') == 'UD Konveksi Mandiri' ? 'selected' : '' }}>UD Konveksi Mandiri</option>
                                <option value="PT Fashion Indonesia" {{ request('supplier') == 'PT Fashion Indonesia' ? 'selected' : '' }}>PT Fashion Indonesia</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status Stok</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Tersedia (>5)</option>
                                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah (1-5)</option>
                                <option value="critical" {{ request('status') == 'critical' ? 'selected' : '' }}>Kritis (≤3)</option>
                                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Habis (0)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Periode</label>
                            <select name="period" class="form-select">
                                <option value="all" {{ request('period') == 'all' ? 'selected' : '' }}>Semua Waktu</option>
                                <option value="today" {{ request('period') == 'today' ? 'selected' : '' }}>Hari Ini</option>
                                <option value="week" {{ request('period') == 'week' ? 'selected' : '' }}>Minggu Ini</option>
                                <option value="month" {{ request('period') == 'month' ? 'selected' : '' }}>Bulan Ini</option>
                                <option value="year" {{ request('period') == 'year' ? 'selected' : '' }}>Tahun Ini</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="">Terbaru</option>
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="stock-asc" {{ request('sort') == 'stock-asc' ? 'selected' : '' }}>Stok ↑</option>
                                <option value="stock-desc" {{ request('sort') == 'stock-desc' ? 'selected' : '' }}>Stok ↓</option>
                                <option value="category-asc" {{ request('sort') == 'category-asc' ? 'selected' : '' }}>Kategori A-Z</option>
                                <option value="supplier-asc" {{ request('sort') == 'supplier-asc' ? 'selected' : '' }}>Supplier A-Z</option>
                            </select>
                        </div>
                        <div class="col-md-2 d-flex align-items-end">
                            <div class="d-flex gap-2 w-100">
                                <button type="submit" class="btn btn-primary flex-fill">
                                    <i class="bi bi-funnel me-1"></i>Filter
                                </button>
                                <a href="{{ route('inventory.report') }}" class="btn btn-outline-secondary flex-fill">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                </a>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <!-- Ringkasan Laporan -->
        <div class="row g-4 mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Item</h5>
                        <h2 class="display-6">{{ count($inventory_items) }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Total Stok</h5>
                        <h2 class="display-6">{{ $inventory_items->sum('stock') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-info text-white h-100">
                    <div class="card-body">
                        <h5 class="card-title">Nilai Inventaris</h5>
                        <h2 class="display-6">Rp {{ number_format($inventory_items->sum(function($item) { return $item->stock * $item->purchase_price; }), 0, ',', '.') }}</h2>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-dark h-100">
                    <div class="card-body">
                        <h5 class="card-title">Stok Rendah</h5>
                        <h2 class="display-6">{{ $inventory_items->where('stock', '<=', 'min_stock')->count() }}</h2>
                    </div>
                </div>
            </div>
        </div>

        <!-- Tabel Laporan -->
        <div class="card">
            <div class="card-header bg-white d-flex justify-content-between align-items-center">
                <h5 class="mb-0">Daftar Inventaris</h5>
                <div>
                    <button onclick="window.print()" class="btn btn-sm btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('inventory.export') }}" class="btn btn-sm btn-outline-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
            <div class="card-body p-0">
                <div class="table-responsive">
                    <table class="table table-hover mb-0">
                        <thead class="table-light">
                            <tr>
                                <th>Kode</th>
                                <th>Nama Item</th>
                                <th>Kategori</th>
                                <th>Ukuran</th>
                                <th>Stok</th>
                                <th>Harga Beli</th>
                                <th>Harga Jual</th>
                                <th>Supplier</th>
                                <th>Terakhir Diperbarui</th>
                            </tr>
                        </thead>
                        <tbody>
                            @forelse ($inventory_items as $item)
                                <tr>
                                    <td>{{ $item->code }}</td>
                                    <td>{{ $item->name }}</td>
                                    <td>{{ $item->category }}</td>
                                    <td>
                                        @if(is_string($item->sizes_available))
                                            {{ implode(', ', json_decode($item->sizes_available, true) ?? []) }}
                                        @elseif(is_array($item->sizes_available))
                                            {{ implode(', ', $item->sizes_available) }}
                                        @else
                                            {{ $item->sizes_available ?? '-' }}
                                        @endif
                                    </td>
                                    <td>
                                        @if ($item->stock <= $item->min_stock)
                                            <span class="badge bg-danger">{{ $item->stock }}</span>
                                        @elseif ($item->stock <= $item->min_stock * 1.5)
                                            <span class="badge bg-warning text-dark">{{ $item->stock }}</span>
                                        @else
                                            <span class="badge bg-success">{{ $item->stock }}</span>
                                        @endif
                                    </td>
                                    <td>Rp {{ number_format($item->purchase_price, 0, ',', '.') }}</td>
                                    <td>Rp {{ number_format($item->selling_price, 0, ',', '.') }}</td>
                                    <td>{{ $item->supplier }}</td>
                                    <td>{{ $item->last_restock }}</td>
                                </tr>
                            @empty
                                <tr>
                                    <td colspan="9" class="text-center py-4">Tidak ada data inventaris</td>
                                </tr>
                            @endforelse
                        </tbody>
                    </table>
                </div>
            </div>
            @if (method_exists($inventory_items, 'hasPages') && $inventory_items->hasPages())
                <div class="card-footer bg-white">
                    {{ $inventory_items->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
<style>
    @media print {
        .navbar, .btn, form, .no-print {
            display: none !important;
        }
        .card {
            border: none !important;
        }
        .card-header {
            background-color: #f8f9fa !important;
            color: #000 !important;
        }
    }
</style>
@endpush