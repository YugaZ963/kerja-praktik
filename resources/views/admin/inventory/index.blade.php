{{-- resources/views/inventory/index.blade.php --}}
@extends('layouts.customer')

@section('title', 'Manajemen Inventaris')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Manajemen Inventaris</h1>
            <p class="lead">Kelola inventaris seragam sekolah dengan mudah dan efisien</p>
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

        @if (session('warning'))
            <div class="alert alert-warning alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                {{ session('warning') }}
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <x-inventory-stats :inventory_items="$inventory_items" />

        <div class="row mb-4">
            <div class="col-md-12 d-flex justify-content-between flex-wrap gap-2">
                <div>
                    <a href="{{ route('inventory.report') }}" class="btn btn-success me-2">
                        <i class="bi bi-file-earmark-text"></i> Laporan Stok
                    </a>

                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('inventory.export') }}" class="btn btn-outline-primary">
                        <i class="bi bi-file-earmark-excel"></i> Export Excel
                    </a>
                </div>
            </div>
        </div>

        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('inventory.index') }}">
                    <!-- Search Bar -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Cari berdasarkan nama, kategori, kode, atau deskripsi..." 
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
                            <label class="form-label fw-semibold">Status Stok</label>
                            <select name="status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="ready" {{ request('status') == 'ready' ? 'selected' : '' }}>Tersedia (>100)</option>
                                <option value="low" {{ request('status') == 'low' ? 'selected' : '' }}>Stok Rendah (1-100)</option>
                                <option value="critical" {{ request('status') == 'critical' ? 'selected' : '' }}>Kritis (≤50)</option>
                                <option value="out" {{ request('status') == 'out' ? 'selected' : '' }}>Habis (0)</option>
                            </select>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Rentang Harga</label>
                            <div class="input-group">
                                <input type="number" class="form-control" name="price_min" 
                                       placeholder="Min" value="{{ request('price_min') }}">
                                <span class="input-group-text">-</span>
                                <input type="number" class="form-control" name="price_max" 
                                       placeholder="Max" value="{{ request('price_max') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Tanggal</label>
                            <div class="input-group">
                                <input type="date" class="form-control" name="date_from" 
                                       value="{{ request('date_from') }}">
                                <span class="input-group-text">-</span>
                                <input type="date" class="form-control" name="date_to" 
                                       value="{{ request('date_to') }}">
                            </div>
                        </div>
                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="">Terbaru</option>
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="stock-asc" {{ request('sort') == 'stock-asc' ? 'selected' : '' }}>Stok ↑</option>
                                <option value="stock-desc" {{ request('sort') == 'stock-desc' ? 'selected' : '' }}>Stok ↓</option>
                                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Harga ↑</option>
                                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Harga ↓</option>
                                <option value="category-asc" {{ request('sort') == 'category-asc' ? 'selected' : '' }}>Kategori A-Z</option>
                            </select>
                        </div>
                    </div>
                    
                    <div class="row mt-3">
                        <div class="col-md-12 d-flex gap-2">
                            <button type="submit" class="btn btn-primary">
                                <i class="bi bi-funnel me-1"></i>Terapkan Filter
                            </button>
                            <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise me-1"></i>Reset Filter
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        <div class="card">
            <div class="card-header bg-white">
                <div class="row align-items-center">
                    <div class="col">
                        <h5 class="mb-0">Daftar Item Inventaris</h5>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <button type="button" class="btn btn-outline-info" onclick="toggleSizeBreakdown()">
                            <i class="bi bi-rulers"></i> <span id="toggleText">Tampilkan Detail Ukuran</span>
                        </button>
                        <form method="GET" action="{{ route('inventory.index') }}" class="input-group input-group-sm"
                            style="width:260px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari item..."
                                value="{{ request('search') }}">
                            <button class="btn btn-outline-secondary"><i class="bi bi-search"></i></button>
                        </form>
                    </div>
                </div>
            </div>
            <div class="card-body p-0">
                <x-inventory-table :inventory_items="$inventory_items" />
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
        .table th {
            white-space: nowrap
        }

        .badge {
            font-size: .9em
        }
        
        .product-edit-item {
            border: 1px solid #dee2e6;
            border-radius: 0.375rem;
            padding: 1rem;
            margin-bottom: 1rem;
        }
    </style>
@endpush

@push('scripts')
<script>
// Event listeners untuk modal tambah stok
document.addEventListener('DOMContentLoaded', function() {
    // Handle modal tambah stok
    const addStockModal = document.getElementById('addStockModal');
    if (addStockModal) {
        addStockModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const inventoryId = button.getAttribute('data-inventory-id');
            const size = button.getAttribute('data-size');
            
            // Set form action
            const form = document.getElementById('addStockForm');
            form.action = `/inventory/${inventoryId}/add-stock`;
            
            // Fill form data
            document.getElementById('add_inventory_id').value = inventoryId;
            document.getElementById('add_size').value = size;
            document.getElementById('add_size_info').textContent = size;
        });
    }
    
    // Handle modal kurangi stok
    const reduceStockModal = document.getElementById('reduceStockModal');
    if (reduceStockModal) {
        reduceStockModal.addEventListener('show.bs.modal', function(event) {
            const button = event.relatedTarget;
            const inventoryId = button.getAttribute('data-inventory-id');
            const size = button.getAttribute('data-size');
            const currentStock = button.getAttribute('data-current-stock');
            
            // Set form action
            const form = document.getElementById('reduceStockForm');
            form.action = `/inventory/${inventoryId}/reduce-stock`;
            
            // Fill form data
            document.getElementById('reduce_inventory_id').value = inventoryId;
            document.getElementById('reduce_size').value = size;
            document.getElementById('reduce_size_info').textContent = size;
            document.getElementById('current_stock_display').textContent = currentStock;
            
            // Set max value for reduce stock input
            const reduceStockInput = document.getElementById('reduce_stock');
            reduceStockInput.max = currentStock;
        });
    }
});
</script>
@endpush
