@extends('layouts.customer')

@section('title', ' - Detail Inventaris')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Breadcrumb -->
        <nav aria-label="breadcrumb" class="mb-4">
            <ol class="breadcrumb">
                <li class="breadcrumb-item"><a href="/inventory">Inventaris</a></li>
                <li class="breadcrumb-item active" aria-current="page">{{ $item['code'] }}</li>
            </ol>
        </nav>

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

        <!-- Action Buttons -->
        <div class="d-flex justify-content-between align-items-center mb-4">
            <h2 class="mb-0">Detail Item Inventaris</h2>
            <div>
                <a href="{{ route('inventory.edit', $item['id']) }}" class="btn btn-primary me-2" title="Edit Item">
                    <i class="bi bi-pencil"></i> Edit
                </a>
                <form action="{{ route('inventory.destroy', $item['id']) }}" method="POST" class="d-inline" 
                      onsubmit="return confirm('Apakah Anda yakin ingin menghapus item {{ $item['name'] }}? Semua data terkait akan ikut terhapus.')">
                    @csrf
                    @method('DELETE')
                    <button type="submit" class="btn btn-danger" title="Hapus Item">
                        <i class="bi bi-trash"></i> Hapus
                    </button>
                </form>
            </div>
        </div>

        <div class="row">
            <!-- Item Details -->
            <div class="col-md-8">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Item</h5>
                    </div>
                    <div class="card-body">
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kode Item</div>
                            <div class="col-md-8">{{ $item['code'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Nama Item</div>
                            <div class="col-md-8">{{ $item['name'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Kategori</div>
                            <div class="col-md-8">{{ $item['category'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Deskripsi</div>
                            <div class="col-md-8">{{ $item['description'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Lokasi Penyimpanan</div>
                            <div class="col-md-8">{{ $item['location'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Ukuran Tersedia</div>
                            <div class="col-md-8">
                                @php
                                    // Ambil ukuran dari produk yang sebenarnya ada di database
                                    $actualSizes = $item->available_sizes; // Menggunakan accessor yang sudah ada
                                    
                                    // Jika tidak ada produk, fallback ke sizes_available dari inventory
                                    if (empty($actualSizes)) {
                                        $sizes = $item->sizes_available;
                                        if (is_string($sizes)) {
                                            $sizes = json_decode($sizes, true) ?? [];
                                        }
                                        if (!is_array($sizes)) {
                                            $sizes = [];
                                        }
                                        $actualSizes = $sizes;
                                    }
                                @endphp
                                @if(count($actualSizes) > 0)
                                    @foreach ($actualSizes as $size)
                                        @php
                                            // Hitung stok untuk ukuran ini
                                            $sizeStock = $item->products()->where('size', $size)->sum('stock');
                                        @endphp
                                        <span class="badge {{ $sizeStock > 0 ? 'bg-success' : 'bg-secondary' }} me-1" 
                                              title="Stok: {{ $sizeStock }}">
                                            {{ $size }} ({{ $sizeStock }})
                                        </span>
                                    @endforeach
                                    <div class="mt-2">
                                        <small class="text-muted">
                                            <i class="bi bi-info-circle"></i> 
                                            Hijau: Ada stok, Abu-abu: Stok habis
                                        </small>
                                    </div>
                                @else
                                    <span class="text-muted">Tidak ada ukuran tersedia</span>
                                @endif
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Supplier</div>
                            <div class="col-md-8">{{ $item['supplier'] }}</div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4 fw-bold">Terakhir Diperbarui</div>
                            <div class="col-md-8">{{ $item['last_restock'] }}</div>
                        </div>
                    </div>
                </div>





                <!-- Kelola Produk Per Ukuran -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Kelola Produk Per Ukuran</h5>
                        <button type="button" class="btn btn-primary btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomProductModal">
                            <i class="bi bi-plus-circle"></i> Tambah Produk Baru
                        </button>
                    </div>
                    <div class="card-body">
                        @if($item->products->count() > 0)
                            @php
                                $groupedProducts = $item->products->groupBy('size');
                            @endphp
                            
                            @foreach($groupedProducts as $size => $products)
                                <div class="mb-4">
                                    <h6 class="text-primary mb-3">
                                        <i class="bi bi-tag"></i> Ukuran {{ $size }}
                                        <span class="badge bg-secondary ms-2">{{ $products->count() }} produk</span>
                                    </h6>
                                    
                                    <div class="row">
                                        @foreach($products as $product)
                                            <div class="col-md-6 col-lg-4 mb-3">
                                                <div class="card border">
                                                    <div class="card-body p-3">
                                                        <div class="d-flex justify-content-between align-items-start mb-2">
                                                            <h6 class="card-title mb-1">{{ $product->name }}</h6>
                                                            <div class="dropdown">
                                                                <button class="btn btn-sm btn-outline-secondary dropdown-toggle" type="button" data-bs-toggle="dropdown">
                                                                    <i class="bi bi-three-dots"></i>
                                                                </button>
                                                                <ul class="dropdown-menu">
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('admin.products.edit', $product->id) }}">
                                                                            <i class="bi bi-pencil"></i> Edit
                                                                        </a>
                                                                    </li>
                                                                    <li>
                                                                        <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="d-inline" onsubmit="return confirm('Apakah Anda yakin ingin menghapus produk ini?')">
                                                                            @csrf
                                                                            @method('DELETE')
                                                                            <button type="submit" class="dropdown-item text-danger">
                                                                                <i class="bi bi-trash"></i> Hapus
                                                                            </button>
                                                                        </form>
                                                                    </li>
                                                                    <li><hr class="dropdown-divider"></li>
                                                                    <li>
                                                                        <a class="dropdown-item" href="{{ route('customer.product.detail', $product->slug) }}" target="_blank">
                                                                            <i class="bi bi-eye"></i> Lihat di Katalog
                                                                        </a>
                                                                    </li>
                                                                </ul>
                                                            </div>
                                                        </div>
                                                        
                                                        <div class="row text-sm">
                                                            <div class="col-6">
                                                                <small class="text-muted">Stok:</small><br>
                                                                <span class="fw-bold {{ $product->stock <= 10 ? 'text-danger' : 'text-success' }}">
                                                                    {{ $product->stock }}
                                                                </span>
                                                            </div>
                                                            <div class="col-6">
                                                                <small class="text-muted">Harga:</small><br>
                                                                <span class="fw-bold text-primary">Rp {{ number_format($product->price, 0, ',', '.') }}</span>
                                                            </div>
                                                        </div>
                                                        
                                                        @if($product->image)
                                                            <div class="mt-2">
                                                                <img src="{{ asset('storage/' . $product->image) }}" alt="{{ $product->name }}" class="img-thumbnail" style="max-height: 60px; max-width: 60px;">
                                                            </div>
                                                        @endif
                                                    </div>
                                                </div>
                                            </div>
                                        @endforeach
                                    </div>
                                </div>
                                
                                @if(!$loop->last)
                                    <hr class="my-4">
                                @endif
                            @endforeach
                        @else
                            <div class="text-center py-4">
                                <i class="bi bi-box-seam text-muted" style="font-size: 3rem;"></i>
                                <p class="text-muted mt-2">Belum ada produk untuk inventaris ini</p>
                                <button type="button" class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#addCustomProductModal">
                                    <i class="bi bi-plus-circle"></i> Tambah Produk Pertama
                                </button>
                            </div>
                        @endif
                    </div>
                </div>

                <!-- Stock History -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Stok</h5>

                    </div>
                    <div class="card-body p-0">
                        <div class="table-responsive">
                            <table class="table table-hover mb-0">
                                <thead class="table-light">
                                    <tr>
                                        <th>Tanggal</th>
                                        <th>Tipe</th>
                                        <th>Ukuran</th>
                                        <th>Jumlah</th>
                                        <th>Keterangan</th>
                                    </tr>
                                </thead>
                                <tbody>
                                    @if(isset($item['stock_history']) && is_array($item['stock_history']))
                                        @foreach ($item['stock_history'] as $history)
                                            <tr>
                                                <td>{{ $history['date'] ?? '-' }}</td>
                                                <td>
                                                    @if (($history['type'] ?? '') == 'in')
                                                        <span class="badge bg-success">Masuk</span>
                                                    @else
                                                        <span class="badge bg-danger">Keluar</span>
                                                    @endif
                                                </td>
                                                <td>
                                                    @if(isset($history['size']))
                                                        <span class="badge bg-secondary">{{ $history['size'] }}</span>
                                                    @else
                                                        <span class="text-muted">-</span>
                                                    @endif
                                                </td>
                                                <td>{{ $history['quantity'] ?? '-' }}</td>
                                                <td>{{ $history['notes'] ?? '-' }}</td>
                                            </tr>
                                        @endforeach
                                    @else
                                        <tr>
                                            <td colspan="5" class="text-center text-muted">Belum ada riwayat stok</td>
                                        </tr>
                                    @endif
                                </tbody>
                            </table>
                        </div>
                    </div>
                </div>
            </div>

            <!-- Stock Info -->
            <div class="col-md-4">
                <div class="card mb-4">
                    <div class="card-header bg-white">
                        <h5 class="mb-0">Informasi Stok</h5>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-4">
                            <div class="display-4 fw-bold mb-2">{{ $item['stock'] }}</div>
                            <p class="text-muted">Jumlah Stok Saat Ini</p>

                            @php
                                $stockPercentage = ($item['stock'] / ($item['min_stock'] * 3)) * 100;
                                $progressClass = 'bg-success';

                                if ($stockPercentage <= 33) {
                                    $progressClass = 'bg-danger';
                                } elseif ($stockPercentage <= 66) {
                                    $progressClass = 'bg-warning';
                                }
                            @endphp

                            <div class="progress" style="height: 10px;">
                                <div class="progress-bar {{ $progressClass }}" role="progressbar"
                                    style="width: {{ min($stockPercentage, 100) }}%" aria-valuenow="{{ $item['stock'] }}"
                                    aria-valuemin="0" aria-valuemax="{{ $item['min_stock'] * 3 }}">
                                </div>
                            </div>

                            <div class="d-flex justify-content-between mt-1">
                                <small class="text-danger">Minimum ({{ $item['min_stock'] }})</small>
                                <small class="text-success">Optimal ({{ $item['min_stock'] * 3 }})</small>
                            </div>
                        </div>

                        <hr>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Harga Beli</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['purchase_price']) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Harga Jual</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['selling_price']) }}</div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Margin</div>
                            <div class="col-6 text-end">
                                @php
                                    $margin = $item['selling_price'] - $item['purchase_price'];
                                    $marginPercentage = ($margin / $item['purchase_price']) * 100;
                                @endphp
                                Rp {{ number_format($margin) }} ({{ number_format($marginPercentage, 1) }}%)
                            </div>
                        </div>

                        <div class="row mb-3">
                            <div class="col-6 fw-bold">Nilai Total</div>
                            <div class="col-6 text-end">Rp {{ number_format($item['purchase_price'] * $item['stock']) }}
                            </div>
                        </div>

                        <hr>

                        <div class="d-grid gap-2">
                            <button class="btn btn-primary" data-bs-toggle="modal" data-bs-target="#increaseStockModal">
                                <i class="bi bi-plus-circle"></i> Tambah Stok
                            </button>
                            <button class="btn btn-outline-primary" data-bs-toggle="modal" data-bs-target="#decreaseStockModal">
                                <i class="bi bi-dash-circle"></i> Kurangi Stok
                            </button>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>






<!-- Modal Tambah Produk Custom -->
<div class="modal fade" id="addCustomProductModal" tabindex="-1" aria-labelledby="addCustomProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="addCustomProductModalLabel">
                    <i class="bi bi-plus-circle"></i> Tambah Produk Baru untuk {{ $item->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form method="POST" action="{{ route('admin.products.store') }}" enctype="multipart/form-data">
                @csrf
                <input type="hidden" name="inventory_id" value="{{ $item->id }}">
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="product_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_size" class="form-label">Ukuran <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="product_size" name="size" placeholder="Masukkan ukuran (contoh: S, M, L, XL, 14, 16, dll)" required>
                                <div class="form-text">Masukkan ukuran produk. Anda bisa menambahkan ukuran baru.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="product_price" name="price" min="0" step="100" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_stock" class="form-label">Stok Awal <span class="text-danger">*</span></label>
                                <input type="number" class="form-control" id="product_stock" name="stock" min="0" value="0" required>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_weight" class="form-label">Berat (gram)</label>
                                <input type="number" class="form-control" id="product_weight" name="weight" min="0" step="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="product_image" class="form-label">Upload Gambar</label>
                                <input type="file" class="form-control" id="product_image" name="image" accept="image/*" onchange="previewImage(this)">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="product_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="product_description" name="description" rows="3" placeholder="Deskripsi produk (opsional)"></textarea>
                    </div>
                    
                    <!-- Preview Gambar -->
                    <div id="imagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">Preview Gambar:</label><br>
                        <img id="preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Simpan Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('preview').src = e.target.result;
            document.getElementById('imagePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('imagePreview').style.display = 'none';
    }
}
</script>

<!-- Modal Edit Produk -->
<div class="modal fade" id="editProductModal" tabindex="-1" aria-labelledby="editProductModalLabel" aria-hidden="true">
    <div class="modal-dialog modal-lg">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="editProductModalLabel">
                    <i class="bi bi-pencil-square"></i> Edit Produk
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form id="editProductForm" method="POST" enctype="multipart/form-data">
                @csrf
                @method('PUT')
                
                <div class="modal-body">
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_name" class="form-label">Nama Produk <span class="text-danger">*</span></label>
                                <input type="text" class="form-control" id="edit_product_name" name="name" required>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_size" class="form-label">Ukuran <span class="text-danger">*</span></label>
                                <select class="form-select" id="edit_product_size" name="size" required>
                                    <option value="">Pilih Ukuran</option>
                                    @if($item->available_sizes && count($item->available_sizes) > 0)
                                        @foreach($item->available_sizes as $size)
                                            <option value="{{ $size }}">{{ $size }}</option>
                                        @endforeach
                                    @endif
                                </select>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_price" class="form-label">Harga Jual <span class="text-danger">*</span></label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" class="form-control" id="edit_product_price" name="price" min="0" step="100" required>
                                </div>
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_stock" class="form-label">Stok Saat Ini</label>
                                <input type="number" class="form-control" id="edit_product_stock" name="stock" min="0" readonly>
                                <div class="form-text">Stok dikelola melalui fitur Tambah/Kurangi Stok</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="row">
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_weight" class="form-label">Berat (gram)</label>
                                <input type="number" class="form-control" id="edit_product_weight" name="weight" min="0" step="1">
                            </div>
                        </div>
                        <div class="col-md-6">
                            <div class="mb-3">
                                <label for="edit_product_image" class="form-label">Upload Gambar Baru</label>
                                <input type="file" class="form-control" id="edit_product_image" name="image" accept="image/*" onchange="previewEditImage(this)">
                                <div class="form-text">Format: JPG, PNG, GIF. Maksimal 2MB. Kosongkan jika tidak ingin mengubah gambar.</div>
                            </div>
                        </div>
                    </div>
                    
                    <div class="mb-3">
                        <label for="edit_product_description" class="form-label">Deskripsi</label>
                        <textarea class="form-control" id="edit_product_description" name="description" rows="3" placeholder="Deskripsi produk (opsional)"></textarea>
                    </div>
                    
                    <!-- Current Image -->
                    <div id="currentImage" class="mb-3">
                        <label class="form-label">Gambar Saat Ini:</label><br>
                        <img id="current_image_preview" src="" alt="Current Image" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                    
                    <!-- Preview Gambar Baru -->
                    <div id="editImagePreview" class="mb-3" style="display: none;">
                        <label class="form-label">Preview Gambar Baru:</label><br>
                        <img id="edit_preview" src="" alt="Preview" class="img-thumbnail" style="max-height: 200px;">
                    </div>
                </div>
                
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-check-circle"></i> Update Produk
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<script>
function previewEditImage(input) {
    if (input.files && input.files[0]) {
        const reader = new FileReader();
        reader.onload = function(e) {
            document.getElementById('edit_preview').src = e.target.result;
            document.getElementById('editImagePreview').style.display = 'block';
        };
        reader.readAsDataURL(input.files[0]);
    } else {
        document.getElementById('editImagePreview').style.display = 'none';
    }
}

function editProduct(productId, name, size, price, stock, weight, description, imageUrl) {
    // Set form action
    document.getElementById('editProductForm').action = `/admin/products/${productId}`;
    
    // Fill form fields
    document.getElementById('edit_product_name').value = name;
    document.getElementById('edit_product_size').value = size;
    document.getElementById('edit_product_price').value = price;
    document.getElementById('edit_product_stock').value = stock;
    document.getElementById('edit_product_weight').value = weight || '';
    document.getElementById('edit_product_description').value = description || '';
    
    // Show current image
    if (imageUrl) {
        document.getElementById('current_image_preview').src = imageUrl;
        document.getElementById('currentImage').style.display = 'block';
    } else {
        document.getElementById('currentImage').style.display = 'none';
    }
    
    // Reset new image preview
    document.getElementById('editImagePreview').style.display = 'none';
    document.getElementById('edit_product_image').value = '';
    
    // Show modal
    new bootstrap.Modal(document.getElementById('editProductModal')).show();
}
</script>

<!-- Modal Tambah Stok -->
<div class="modal fade" id="increaseStockModal" tabindex="-1" aria-labelledby="increaseStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="increaseStockModalLabel">
                    <i class="bi bi-plus-circle"></i> Tambah Stok - {{ $item->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.add-stock', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="increase_size" class="form-label">Ukuran <span class="text-danger">*</span></label>
                        <select class="form-select" id="increase_size" name="size" required>
                            <option value="">Pilih Ukuran</option>
                            @if($item->available_sizes && count($item->available_sizes) > 0)
                                @foreach($item->available_sizes as $size)
                                    <option value="{{ $size }}">{{ $size }}</option>
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">Pilih ukuran yang ingin ditambah stoknya</div>
                    </div>
                    <div class="mb-3">
                        <label for="increase_stock" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="increase_stock" name="stock" min="1" required>
                        <div class="form-text">Masukkan jumlah stok yang ingin ditambahkan</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-primary">
                        <i class="bi bi-plus-circle"></i> Tambah Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

<!-- Modal Kurangi Stok -->
<div class="modal fade" id="decreaseStockModal" tabindex="-1" aria-labelledby="decreaseStockModalLabel" aria-hidden="true">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="decreaseStockModalLabel">
                    <i class="bi bi-dash-circle"></i> Kurangi Stok - {{ $item->name }}
                </h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <form action="{{ route('inventory.reduce-stock', $item->id) }}" method="POST">
                @csrf
                <div class="modal-body">
                    <div class="mb-3">
                        <label for="decrease_size" class="form-label">Ukuran <span class="text-danger">*</span></label>
                        <select class="form-select" id="decrease_size" name="size" required>
                            <option value="">Pilih Ukuran</option>
                            @if($item->available_sizes && count($item->available_sizes) > 0)
                                @foreach($item->available_sizes as $size)
                                    @php
                                        $sizeStock = $item->products()->where('size', $size)->sum('stock');
                                    @endphp
                                    @if($sizeStock > 0)
                                        <option value="{{ $size }}">{{ $size }} (Stok: {{ $sizeStock }})</option>
                                    @endif
                                @endforeach
                            @endif
                        </select>
                        <div class="form-text">Pilih ukuran yang ingin dikurangi stoknya (hanya ukuran dengan stok > 0)</div>
                    </div>
                    <div class="mb-3">
                        <label for="decrease_stock" class="form-label">Jumlah Stok <span class="text-danger">*</span></label>
                        <input type="number" class="form-control" id="decrease_stock" name="stock" min="1" required>
                        <div class="form-text">Masukkan jumlah stok yang ingin dikurangi</div>
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                    <button type="submit" class="btn btn-outline-primary">
                        <i class="bi bi-dash-circle"></i> Kurangi Stok
                    </button>
                </div>
            </form>
        </div>
    </div>
</div>

@endsection
