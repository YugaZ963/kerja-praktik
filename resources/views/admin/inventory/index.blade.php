{{-- resources/views/inventory/index.blade.php --}}
@extends('layouts.app')

@section('title', 'Manajemen Inventaris')

@section('content')
<div class="container-fluid px-4">
    <div class="row">
        <div class="col-12">
            <!-- Page Header -->
            <div class="d-flex justify-content-between align-items-center mb-4">
                <div>
                    <h2 class="fw-bold text-dark mb-1">
                        <i class="fas fa-boxes text-primary me-3"></i>Manajemen Inventaris
                    </h2>
                    <p class="text-muted mb-0">Kelola inventaris seragam sekolah dengan mudah dan efisien</p>
                </div>
                <div class="d-flex gap-3">
                    <span class="badge bg-primary px-3 py-2">
                        <i class="fas fa-user-shield me-1"></i>
                        Administrator
                    </span>
                </div>
            </div>

            <div class="alert alert-info border-0 shadow-sm mb-4">
                <i class="bi bi-info-circle me-2"></i>
                <strong>Info:</strong> Stok inventaris dikelola otomatis melalui data produk. Tambah/kurangi stok dengan mengelola produk di setiap ukuran.
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



            {{-- Action Buttons --}}
            <div class="row mb-4">
                <div class="col-md-12 d-flex justify-content-between flex-wrap gap-2">
                    <div>
                        <a href="{{ route('inventory.create') }}" class="btn btn-success me-2">
                        <i class="bi bi-box"></i> Tambah Item Inventaris
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
                    
                    <!-- Basic Filters -->
                    <div class="row g-3">
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Kategori</label>
                            <select name="category" class="form-select">
                                <option value="">Semua Kategori</option>
                                <option value="Kemeja Sekolah" {{ request('category') == 'Kemeja Sekolah' ? 'selected' : '' }}>Kemeja Sekolah</option>
                                <option value="Kemeja Batik" {{ request('category') == 'Kemeja Batik' ? 'selected' : '' }}>Kemeja Batik</option>
                                <option value="Kemeja Padang" {{ request('category') == 'Kemeja Padang' ? 'selected' : '' }}>Kemeja Padang</option>
                                <option value="Rok Sekolah" {{ request('category') == 'Rok Sekolah' ? 'selected' : '' }}>Rok Sekolah</option>
                                <option value="Celana Sekolah" {{ request('category') == 'Celana Sekolah' ? 'selected' : '' }}>Celana Sekolah</option>
                                <option value="Aksesoris" {{ request('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                                <option value="Pramuka" {{ request('category') == 'Pramuka' ? 'selected' : '' }}>Pramuka</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Status Stok</label>
                            <select name="stock_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Habis</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Supplier</label>
                            <select name="supplier" class="form-select">
                                <option value="">Semua Supplier</option>
                                <option value="PT Seragam Jaya" {{ request('supplier') == 'PT Seragam Jaya' ? 'selected' : '' }}>PT Seragam Jaya</option>
                                <option value="CV Batik Nusantara" {{ request('supplier') == 'CV Batik Nusantara' ? 'selected' : '' }}>CV Batik Nusantara</option>
                                <option value="PT Sabana" {{ request('supplier') == 'PT Sabana' ? 'selected' : '' }}>PT Sabana</option>
                                <option value="PD Padang Garment" {{ request('supplier') == 'PD Padang Garment' ? 'selected' : '' }}>PD Padang Garment</option>
                                <option value="CV Aksesoris Sekolah" {{ request('supplier') == 'CV Aksesoris Sekolah' ? 'selected' : '' }}>CV Aksesoris Sekolah</option>
                                <option value="CV Pramuka Indonesia" {{ request('supplier') == 'CV Pramuka Indonesia' ? 'selected' : '' }}>CV Pramuka Indonesia</option>
                            </select>
                        </div>
                        <div class="col-md-3">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                                <option value="name" {{ request('sort') == 'name' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="category" {{ request('sort') == 'category' ? 'selected' : '' }}>Kategori</option>
                                <option value="stock" {{ request('sort') == 'stock' ? 'selected' : '' }}>Stok</option>
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
                        <h5 class="mb-0">Daftar Inventaris</h5>
                    </div>
                    <div class="col-auto d-flex gap-2">
                        <form method="GET" action="{{ route('inventory.index') }}" class="input-group input-group-sm"
                            style="width:260px;">
                            <input type="text" name="search" class="form-control" placeholder="Cari inventaris..."
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

function toggleSizeBreakdown() {
    const sizeBreakdowns = document.querySelectorAll('.size-breakdown');
    const toggleText = document.getElementById('toggleText');
    const isVisible = sizeBreakdowns[0] && sizeBreakdowns[0].style.display !== 'none';
    
    sizeBreakdowns.forEach(breakdown => {
        breakdown.style.display = isVisible ? 'none' : 'table-row';
    });
    
    toggleText.textContent = isVisible ? 'Tampilkan Detail Ukuran' : 'Sembunyikan Detail Ukuran';
}
</script>
@endpush
