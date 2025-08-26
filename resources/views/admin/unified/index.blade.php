@extends('layouts.customer')

@section('title', 'Manajemen Produk & Inventaris Terpadu')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">📊 Manajemen Produk & Inventaris Terpadu</h1>
            <p class="lead">Kelola produk dan inventaris seragam sekolah dalam satu tampilan terpadu</p>
            <div class="alert alert-info mt-3 mb-0">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Tampilan Terpadu:</strong> Lihat data inventaris beserta semua produk terkait dalam satu halaman untuk kemudahan pengelolaan.
            </div>
        </div>

        {{-- Alert Messages --}}
        @if (session('success'))
            <div class="alert alert-success alert-dismissible fade show" role="alert">
                <i class="bi bi-check-circle me-2"></i>
                {{ session('success') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        @if (session('error'))
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('error') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        {{-- Statistics Cards --}}
        <div class="row mb-4">
            <div class="col-md-3">
                <div class="card bg-primary text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Inventaris</h6>
                                <h3 class="mb-0">{{ $stats['total_inventories'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-box-seam fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-success text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Total Produk</h6>
                                <h3 class="mb-0">{{ $stats['total_products'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-grid fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-warning text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Stok Rendah</h6>
                                <h3 class="mb-0">{{ $stats['low_stock_inventories'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-exclamation-triangle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
            <div class="col-md-3">
                <div class="card bg-danger text-white">
                    <div class="card-body">
                        <div class="d-flex justify-content-between">
                            <div>
                                <h6 class="card-title">Stok Habis</h6>
                                <h3 class="mb-0">{{ $stats['out_of_stock_inventories'] }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-x-circle fs-1"></i>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Action Buttons --}}
        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between flex-wrap gap-2">
                <div>
                    <div class="btn-group me-2">
                        <button type="button" class="btn btn-primary dropdown-toggle" data-bs-toggle="dropdown" aria-expanded="false">
                            <i class="bi bi-plus-circle"></i> Tambah Baru
                        </button>
                        <ul class="dropdown-menu">
                            <li><a class="dropdown-item" href="{{ route('inventory.create') }}">
                                <i class="bi bi-box me-2"></i>Tambah Item Inventaris
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.create') }}">
                                <i class="bi bi-box-seam me-2"></i>Tambah Produk Baru
                            </a></li>
                            <li><hr class="dropdown-divider"></li>
                            <li><a class="dropdown-item" href="{{ route('admin.products.index') }}">
                                <i class="bi bi-list me-2"></i>Kelola Produk Terpisah
                            </a></li>
                            <li><a class="dropdown-item" href="{{ route('inventory.index') }}">
                                <i class="bi bi-archive me-2"></i>Kelola Inventaris Terpisah
                            </a></li>
                        </ul>
                    </div>
                    <a href="{{ route('inventory.report') }}" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-text"></i> Laporan
                    </a>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('admin.unified.export') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        {{-- Filter Section --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.unified.index') }}">
                    <!-- Search Bar -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Cari berdasarkan nama inventaris, produk, kategori, kode, atau supplier..." 
                                       value="{{ request('search') }}">
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-search me-1"></i>Cari
                                </button>
                            </div>
                        </div>
                    </div>
                    
                    <!-- Advanced Filters -->
                    <div class="row">
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier" class="form-select">
                                <option value="">Semua Supplier</option>
                                @foreach($suppliers as $supplier)
                                    <option value="{{ $supplier }}" {{ request('supplier') == $supplier ? 'selected' : '' }}>
                                        {{ $supplier }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Ukuran</label>
                            <select name="size" class="form-select">
                                <option value="">Semua Ukuran</option>
                                @foreach($sizes as $size)
                                    <option value="{{ $size }}" {{ request('size') == $size ? 'selected' : '' }}>
                                        {{ $size }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status Stok</label>
                            <select name="stock_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="adequate" {{ request('stock_status') == 'adequate' ? 'selected' : '' }}>Stok Cukup</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                <option value="critical" {{ request('stock_status') == 'critical' ? 'selected' : '' }}>Stok Kritis</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Stok Habis</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="stock-asc" {{ request('sort') == 'stock-asc' ? 'selected' : '' }}>Stok Terendah</option>
                                <option value="stock-desc" {{ request('sort') == 'stock-desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                                <option value="category-asc" {{ request('sort') == 'category-asc' ? 'selected' : '' }}>Kategori A-Z</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="{{ route('admin.unified.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Unified Table --}}
        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Daftar Inventaris & Produk Terpadu</h5>
                    </div>
                    <div class="col-auto">
                        <small class="text-muted">Total: {{ $inventories->total() }} item inventaris</small>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-unified-table :inventories="$inventories" />
            </div>
            @if ($inventories->hasPages())
                <div class="card-footer bg-white">
                    {{ $inventories->withQueryString()->links('pagination::bootstrap-5') }}
                </div>
            @endif
        </div>
    </div>
@endsection

@push('styles')
    <style>
        .table th {
            white-space: nowrap;
        }
        
        .badge {
            font-size: .9em;
        }
        
        .product-item {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 0.75rem;
            margin-bottom: 0.5rem;
            background-color: #f8f9fa;
        }
        
        .inventory-row {
            background-color: #fff;
        }
        
        .products-section {
            background-color: #f8f9fa;
        }
    </style>
@endpush

@push('scripts')
<script>
// Toggle product details
function toggleProducts(inventoryId) {
    const productsRow = document.getElementById('products-' + inventoryId);
    const toggleBtn = document.querySelector('[data-inventory-id="' + inventoryId + '"]');
    
    if (productsRow.style.display === 'none' || productsRow.style.display === '') {
        productsRow.style.display = 'table-row';
        toggleBtn.innerHTML = '<i class="bi bi-chevron-up"></i> Sembunyikan Produk';
    } else {
        productsRow.style.display = 'none';
        toggleBtn.innerHTML = '<i class="bi bi-chevron-down"></i> Lihat Produk';
    }
}
</script>
@endpush