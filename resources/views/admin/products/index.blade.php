@extends('layouts.customer')

@section('title', 'Kelola Produk')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Kelola Produk</h1>
            <p class="lead">Kelola produk seragam sekolah per ukuran dengan harga berbeda</p>
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
                                <h6 class="card-title">Total Produk</h6>
                                <h3 class="mb-0">{{ $products->total() }}</h3>
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
                                <h6 class="card-title">Stok Tersedia</h6>
                                <h3 class="mb-0">{{ $products->where('stock', '>', 10)->count() }}</h3>
                            </div>
                            <div class="align-self-center">
                                <i class="bi bi-check-circle fs-1"></i>
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
                                <h3 class="mb-0">{{ $products->where('stock', '<=', 10)->where('stock', '>', 0)->count() }}</h3>
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
                                <h3 class="mb-0">{{ $products->where('stock', 0)->count() }}</h3>
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
                    <a href="{{ route('admin.products.create') }}" class="btn btn-primary me-2">
                        <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                    </a>
                    <button type="button" class="btn btn-danger me-2" id="bulkDeleteBtn" style="display: none;">
                        <i class="bi bi-trash"></i> Hapus Terpilih
                    </button>
                </div>
                <div>
                    <button onclick="window.print()" class="btn btn-outline-secondary me-2">
                        <i class="bi bi-printer"></i> Cetak
                    </button>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-primary">
                        <i class="bi bi-box-seam"></i> Kelola Inventaris
                    </a>
                </div>
            </div>
        </div>

        {{-- Filters --}}
        <div class="card mb-4">
            <div class="card-body">
                <form method="GET" action="{{ route('admin.products.index') }}">
                    <!-- Search Bar -->
                    <div class="row mb-3">
                        <div class="col-md-12">
                            <div class="input-group input-group-lg">
                                <span class="input-group-text bg-primary text-white">
                                    <i class="bi bi-search"></i>
                                </span>
                                <input type="text" class="form-control" name="search" 
                                       placeholder="Cari berdasarkan nama, kategori, ukuran, atau deskripsi..." 
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
                                @foreach($categories as $category)
                                    <option value="{{ $category }}" {{ request('category') == $category ? 'selected' : '' }}>
                                        {{ $category }}
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
                            <label class="form-label fw-semibold">Inventaris</label>
                            <select name="inventory_id" class="form-select">
                                <option value="">Semua Inventaris</option>
                                @foreach($inventories as $inventory)
                                    <option value="{{ $inventory->id }}" {{ request('inventory_id') == $inventory->id ? 'selected' : '' }}>
                                        {{ $inventory->name }}
                                    </option>
                                @endforeach
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Status Stok</label>
                            <select name="stock_status" class="form-select">
                                <option value="">Semua Status</option>
                                <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia (>10)</option>
                                <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Rendah (≤10)</option>
                                <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Habis (0)</option>
                            </select>
                        </div>

                        <div class="col-md-2">
                            <label class="form-label fw-semibold">Urutkan</label>
                            <select name="sort" class="form-select">
                                <option value="" {{ request('sort') == '' ? 'selected' : '' }}>Terbaru</option>
                                <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                                <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                                <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Harga Terendah</option>
                                <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                                <option value="stock-asc" {{ request('sort') == 'stock-asc' ? 'selected' : '' }}>Stok Terendah</option>
                                <option value="stock-desc" {{ request('sort') == 'stock-desc' ? 'selected' : '' }}>Stok Tertinggi</option>
                                <option value="size-asc" {{ request('sort') == 'size-asc' ? 'selected' : '' }}>Ukuran A-Z</option>
                                <option value="size-desc" {{ request('sort') == 'size-desc' ? 'selected' : '' }}>Ukuran Z-A</option>
                            </select>
                        </div>

                        <div class="col-md-2 d-flex align-items-end">
                            <button type="submit" class="btn btn-primary me-2">
                                <i class="bi bi-funnel"></i> Filter
                            </button>
                            <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
                                <i class="bi bi-arrow-clockwise"></i> Reset
                            </a>
                        </div>
                    </div>
                </form>
            </div>
        </div>

        {{-- Products Table --}}
        <div class="card">
            <div class="card-body">
                @if($products->count() > 0)
                    <form id="bulkDeleteForm" method="POST" action="{{ route('admin.products.bulk-destroy') }}">
                        @csrf
                        @method('DELETE')
                        
                        <div class="table-responsive">
                            <table class="table table-striped table-hover">
                                <thead class="table-primary">
                                    <tr>
                                        <th width="50">
                                            <input type="checkbox" id="selectAll" class="form-check-input">
                                        </th>
                                        <th>Gambar</th>
                                        <th>Nama Produk</th>
                                        <th>Kategori</th>
                                        <th>Ukuran</th>
                                        <th>Harga</th>
                                        <th>Stok</th>
                                        <th>Inventaris</th>
                                        <th>Aksi</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @foreach($products as $product)
                                        <tr>
                                            <td>
                                                <input type="checkbox" name="product_ids[]" value="{{ $product->id }}" class="form-check-input product-checkbox">
                                            </td>
                                            <td>
                                                @if($product->image)
                                                    <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="width: 50px; height: 50px; object-fit: cover;">
                                                @else
                                                    <div class="bg-light d-flex align-items-center justify-content-center" style="width: 50px; height: 50px; border-radius: 4px;">
                                                        <i class="bi bi-image text-muted"></i>
                                                    </div>
                                                @endif
                                            </td>
                                            <td>
                                                <strong>{{ $product->name }}</strong>
                                                <br>
                                                <small class="text-muted">{{ Str::limit($product->description, 50) }}</small>
                                            </td>
                                            <td>{{ $product->category }}</td>
                                            <td>
                                                <span class="badge bg-secondary">{{ $product->size }}</span>
                                            </td>
                                            <td>
                                                <strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                            </td>
                                            <td>
                                                @if($product->stock == 0)
                                                    <span class="badge bg-danger">Habis</span>
                                                @elseif($product->stock <= 10)
                                                    <span class="badge bg-warning">Rendah ({{ $product->stock }})</span>
                                                @else
                                                    <span class="badge bg-success">{{ $product->stock }}</span>
                                                @endif
                                            </td>
                                            <td>
                                                @if($product->inventory)
                                                    <small class="text-muted">{{ $product->inventory->name }}</small>
                                                @else
                                                    <small class="text-danger">Tidak terkait</small>
                                                @endif
                                            </td>
                                            <td>
                                                <div class="btn-group" role="group">
                                                    <a href="{{ route('admin.products.show', $product) }}" class="btn btn-sm btn-outline-info" title="Detail">
                                                        <i class="bi bi-eye"></i>
                                                    </a>
                                                    <a href="{{ route('admin.products.edit', $product) }}" class="btn btn-sm btn-outline-warning" title="Edit">
                                                        <i class="bi bi-pencil"></i>
                                                    </a>
                                                    <button type="button" class="btn btn-sm btn-outline-danger" title="Hapus" 
                                                            onclick="confirmDelete('{{ $product->id }}', '{{ $product->name }}', '{{ $product->size }}')">
                                                        <i class="bi bi-trash"></i>
                                                    </button>
                                                </div>
                                            </td>
                                        </tr>
                                    @endforeach
                                </tbody>
                            </table>
                        </div>
                    </form>
                    
                    {{-- Pagination --}}
                    <div class="d-flex justify-content-between align-items-center mt-4">
                        <div>
                            <p class="text-muted mb-0">
                                Menampilkan {{ $products->firstItem() }} - {{ $products->lastItem() }} dari {{ $products->total() }} produk
                            </p>
                        </div>
                        <div>
                            {{ $products->links() }}
                        </div>
                    </div>
                @else
                    <div class="text-center py-5">
                        <i class="bi bi-box-seam display-1 text-muted"></i>
                        <h4 class="mt-3 text-muted">Tidak ada produk ditemukan</h4>
                        <p class="text-muted">Silakan tambah produk baru atau ubah filter pencarian</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary">
                            <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Konfirmasi Hapus</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
                </div>
                <div class="modal-body">
                    <p>Apakah Anda yakin ingin menghapus produk <strong id="productName"></strong> ukuran <strong id="productSize"></strong>?</p>
                    <p class="text-danger"><small>Tindakan ini tidak dapat dibatalkan.</small></p>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <form id="deleteForm" method="POST" style="display: inline;">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">Hapus</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <script>
        // Select all checkbox functionality
        document.getElementById('selectAll').addEventListener('change', function() {
            const checkboxes = document.querySelectorAll('.product-checkbox');
            checkboxes.forEach(checkbox => {
                checkbox.checked = this.checked;
            });
            toggleBulkDeleteButton();
        });

        // Individual checkbox change
        document.querySelectorAll('.product-checkbox').forEach(checkbox => {
            checkbox.addEventListener('change', toggleBulkDeleteButton);
        });

        function toggleBulkDeleteButton() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            const bulkDeleteBtn = document.getElementById('bulkDeleteBtn');
            
            if (checkedBoxes.length > 0) {
                bulkDeleteBtn.style.display = 'inline-block';
            } else {
                bulkDeleteBtn.style.display = 'none';
            }
        }

        // Bulk delete confirmation
        document.getElementById('bulkDeleteBtn').addEventListener('click', function() {
            const checkedBoxes = document.querySelectorAll('.product-checkbox:checked');
            if (checkedBoxes.length > 0) {
                if (confirm(`Apakah Anda yakin ingin menghapus ${checkedBoxes.length} produk yang dipilih?`)) {
                    document.getElementById('bulkDeleteForm').submit();
                }
            }
        });

        // Single delete confirmation
        function confirmDelete(productId, productName, productSize) {
            document.getElementById('productName').textContent = productName;
            document.getElementById('productSize').textContent = productSize;
            document.getElementById('deleteForm').action = `/admin/products/${productId}`;
            
            const modal = new bootstrap.Modal(document.getElementById('deleteModal'));
            modal.show();
        }
    </script>
@endsection