@extends('layouts.customer')

@section('title', 'Edit Produk')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-primary mb-1">Edit Produk</h1>
                    <p class="text-muted mb-0">Ubah informasi produk seragam sekolah</p>
                </div>
                <div>
                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-info me-2">
                        <i class="bi bi-eye"></i> Lihat Detail
                    </a>
                    <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Daftar
                    </a>
                </div>
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

        <div class="row">
            <div class="col-md-8">
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-pencil-square text-warning"></i> Form Edit Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.products.update', $product->id) }}" id="productForm" enctype="multipart/form-data">
                            @csrf
                            @method('PUT')
                            
                            <div class="row">
                                {{-- Inventory Selection --}}
                                <div class="col-md-12 mb-3">
                                    <label for="inventory_id" class="form-label fw-semibold">
                                        <i class="bi bi-box-seam text-primary"></i> Item Inventaris
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="inventory_id" id="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Item Inventaris --</option>
                                        @foreach($inventories as $inventory)
                                            <option value="{{ $inventory->id }}" 
                                                data-category="{{ $inventory->category }}"
                                                data-name="{{ $inventory->name }}"
                                                data-selling-price="{{ $inventory->selling_price }}"
                                                data-sizes="{{ json_encode($inventory->sizes_available) }}"
                                                {{ (old('inventory_id', $product->inventory_id) == $inventory->id) ? 'selected' : '' }}>
                                                {{ $inventory->name }} ({{ $inventory->category }}) - Rp {{ number_format($inventory->selling_price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inventory_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Product Name --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-tag text-primary"></i> Nama Produk
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name', $product->name) }}" required placeholder="Contoh: Kemeja SD Pendek">
                                    @error('name')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Category --}}
                                <div class="col-md-6 mb-3">
                                    <label for="category" class="form-label fw-semibold">
                                        <i class="bi bi-grid text-primary"></i> Kategori
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="category" id="category" class="form-control @error('category') is-invalid @enderror" 
                                           value="{{ old('category', $product->category) }}" required>
                                    @error('category')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Size --}}
                                <div class="col-md-6 mb-3">
                                    <label for="size" class="form-label fw-semibold">
                                        <i class="bi bi-rulers text-primary"></i> Ukuran
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="size" id="size" class="form-select @error('size') is-invalid @enderror" required>
                                        <option value="">-- Pilih Ukuran --</option>
                                        <!-- Ukuran akan diisi secara dinamis berdasarkan inventory -->
                                    </select>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Price --}}
                                <div class="col-md-6 mb-3">
                                    <label for="price" class="form-label fw-semibold">
                                        <i class="bi bi-currency-dollar text-primary"></i> Harga Jual
                                        <span class="text-danger">*</span>
                                    </label>
                                    <div class="input-group">
                                        <span class="input-group-text">Rp</span>
                                        <input type="number" name="price" id="price" class="form-control @error('price') is-invalid @enderror" 
                                               value="{{ old('price', $product->price) }}" required min="0" step="1000" placeholder="0">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Stock --}}
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label fw-semibold">
                                        <i class="bi bi-box text-primary"></i> Stok
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" 
                                           value="{{ old('stock', $product->stock) }}" required min="0" placeholder="0">
                                    @error('stock')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Weight --}}
                                <div class="col-md-6 mb-3">
                                    <label for="weight" class="form-label fw-semibold">
                                        <i class="bi bi-speedometer text-primary"></i> Berat (gram)
                                    </label>
                                    <input type="number" name="weight" id="weight" class="form-control @error('weight') is-invalid @enderror" 
                                           value="{{ old('weight', $product->weight) }}" min="0" step="0.1" placeholder="0">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>

                                {{-- Image Upload --}}
                                <div class="col-md-12 mb-3">
                                    <label for="image_file" class="form-label fw-semibold">
                                        <i class="bi bi-image text-primary"></i> Upload Gambar Produk
                                    </label>
                                    <input type="file" name="image_file" id="image_file" class="form-control @error('image_file') is-invalid @enderror" 
                                           accept="image/*" onchange="previewImage(this)">
                                    @error('image_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB.</small>
                                    
                                    {{-- Current Image Display --}}
                                    <div class="mt-3">
                                        <div class="row">
                                            @if($product->image)
                                                <div class="col-md-6">
                                                    <label class="form-label fw-semibold">Gambar Saat Ini:</label>
                                                    <div class="border rounded p-2 text-center">
                                                        @if(file_exists(public_path('images/products/' . $product->image)))
                                                            <img src="{{ asset('images/products/' . $product->image) }}" alt="{{ $product->name }}" 
                                                                 class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                                        @elseif(file_exists(public_path('images/' . $product->image)))
                                                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" 
                                                                 class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                                        @else
                                                            <div class="bg-light d-flex align-items-center justify-content-center" 
                                                                 style="height: 150px; border-radius: 8px;">
                                                                <i class="bi bi-image text-muted fs-2"></i>
                                                            </div>
                                                        @endif
                                                        <small class="text-muted d-block mt-1">{{ $product->image }}</small>
                                                    </div>
                                                </div>
                                            @endif
                                            <div class="col-md-6">
                                                <label class="form-label fw-semibold">Preview Gambar Baru:</label>
                                                <div class="border rounded p-2 text-center" id="imagePreview" style="display: none;">
                                                    <img id="previewImg" src="#" alt="Preview" 
                                                         class="img-fluid rounded" style="max-height: 150px; object-fit: cover;">
                                                </div>
                                                <div class="border rounded p-2 text-center bg-light" id="noPreview" 
                                                     style="height: 150px; display: flex; align-items: center; justify-content: center;">
                                                    <span class="text-muted">Pilih gambar untuk preview</span>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                    
                                    {{-- Hidden field for manual image name (fallback) --}}
                                    <div class="mt-3">
                                        <label for="image" class="form-label fw-semibold">
                                            <i class="bi bi-pencil text-secondary"></i> Atau Masukkan Nama File Manual
                                        </label>
                                        <input type="text" name="image" id="image" class="form-control @error('image') is-invalid @enderror" 
                                               value="{{ old('image', $product->image) }}" placeholder="Contoh: kemeja-sd-pdk.png">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Kosongkan jika menggunakan upload file di atas</small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-card-text text-primary"></i> Deskripsi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" required placeholder="Masukkan deskripsi produk...">{{ old('description', $product->description) }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <div>
                                    <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">
                                        <i class="bi bi-arrow-left"></i> Batal
                                    </a>
                                    <a href="{{ route('admin.products.show', $product->id) }}" class="btn btn-outline-info">
                                        <i class="bi bi-eye"></i> Lihat Detail
                                    </a>
                                </div>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle"></i> Update Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
            </div>

            {{-- Info Panel --}}
            <div class="col-md-4">
                {{-- Current Product Info --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle text-info"></i> Informasi Produk Saat Ini
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="text-center mb-3">
                            @if($product->image && file_exists(public_path('images/' . $product->image)))
                                <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" 
                                     class="img-fluid rounded" style="max-height: 120px; object-fit: cover;">
                            @else
                                <div class="bg-light d-flex align-items-center justify-content-center mx-auto" 
                                     style="width: 120px; height: 120px; border-radius: 8px;">
                                    <i class="bi bi-image text-muted fs-2"></i>
                                </div>
                            @endif
                        </div>
                        
                        <h6 class="fw-bold">{{ $product->name }}</h6>
                        <p class="text-muted small mb-2">{{ $product->category }}</p>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Ukuran</small>
                                    <span class="badge bg-secondary">{{ $product->size }}</span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Harga</small>
                                    <strong class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</strong>
                                </div>
                            </div>
                        </div>
                        
                        <div class="row g-2 mb-3">
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Stok</small>
                                    <span class="fw-semibold {{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock }}
                                    </span>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-2 rounded text-center">
                                    <small class="text-muted d-block">Berat</small>
                                    <span class="fw-semibold">{{ $product->weight ?? 0 }}g</span>
                                </div>
                            </div>
                        </div>
                        
                        <div class="mb-2">
                            <small class="text-muted">Dibuat:</small>
                            <small class="d-block">{{ $product->created_at->format('d M Y H:i') }}</small>
                        </div>
                        
                        @if($product->updated_at != $product->created_at)
                            <div class="mb-2">
                                <small class="text-muted">Terakhir diubah:</small>
                                <small class="d-block">{{ $product->updated_at->format('d M Y H:i') }}</small>
                            </div>
                        @endif
                    </div>
                </div>

                {{-- Help Panel --}}
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-question-circle text-warning"></i> Tips Edit Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">Perhatikan:</h6>
                            <ul class="small">
                                <li>Mengubah ukuran akan membuat produk berbeda</li>
                                <li>Harga dapat disesuaikan per ukuran</li>
                                <li>Stok dihitung terpisah untuk setiap ukuran</li>
                                <li>Pastikan gambar sudah ada di folder images/</li>
                            </ul>
                        </div>
                        
                        <div class="alert alert-warning small">
                            <i class="bi bi-exclamation-triangle"></i>
                            <strong>Peringatan:</strong> Perubahan akan mempengaruhi pesanan yang sedang berjalan.
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const inventorySelect = document.getElementById('inventory_id');
            const nameInput = document.getElementById('name');
            const categoryInput = document.getElementById('category');
            const priceInput = document.getElementById('price');

            // Update form fields when inventory is selected
            inventorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const sizeSelect = document.getElementById('size');
                
                if (selectedOption.value) {
                    const inventoryName = selectedOption.dataset.name;
                    const inventoryCategory = selectedOption.dataset.category;
                    const sellingPrice = selectedOption.dataset.sellingPrice;
                    const availableSizes = selectedOption.dataset.sizes;

                    // Only update if current values are empty or user confirms
                    if (!nameInput.value || confirm('Apakah Anda ingin mengubah nama produk sesuai dengan inventaris yang dipilih?')) {
                        nameInput.value = inventoryName;
                    }
                    
                    if (!categoryInput.value || confirm('Apakah Anda ingin mengubah kategori produk sesuai dengan inventaris yang dipilih?')) {
                        categoryInput.value = inventoryCategory;
                    }
                    
                    // Update size options based on inventory
                    updateSizeOptions(availableSizes);
                    
                    // Add size suffix if size is selected
                    if (sizeSelect.value) {
                        nameInput.value = inventoryName + ' - ' + sizeSelect.value;
                    }
                    
                    // Price suggestion (don't auto-change)
                    if (priceInput.value != sellingPrice) {
                        const suggestion = document.createElement('small');
                        suggestion.className = 'text-info';
                        suggestion.innerHTML = `<i class="bi bi-lightbulb"></i> Harga inventaris: Rp ${parseInt(sellingPrice).toLocaleString('id-ID')}`;
                        
                        // Remove existing suggestion
                        const existingSuggestion = priceInput.parentNode.parentNode.querySelector('.text-info');
                        if (existingSuggestion) {
                            existingSuggestion.remove();
                        }
                        
                        priceInput.parentNode.parentNode.appendChild(suggestion);
                    }
                } else {
                    // Clear size options if no inventory selected
                    sizeSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';
                }
            });
            
            // Function to update size options
            function updateSizeOptions(sizesData) {
                const sizeSelect = document.getElementById('size');
                const currentSize = '{{ old("size", $product->size) }}';
                sizeSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';
                
                if (sizesData) {
                    let sizes = [];
                    
                    // Handle both JSON string and array formats
                    if (typeof sizesData === 'string') {
                        try {
                            sizes = JSON.parse(sizesData);
                        } catch (e) {
                            // If not JSON, try splitting by comma
                            sizes = sizesData.split(',').map(s => s.trim());
                        }
                    } else if (Array.isArray(sizesData)) {
                        sizes = sizesData;
                    }
                    
                    sizes.forEach(function(size) {
                        if (size) { // Skip empty sizes
                            const option = document.createElement('option');
                            option.value = size;
                            option.textContent = size;
                            if (size === currentSize) {
                                option.selected = true;
                            }
                            sizeSelect.appendChild(option);
                        }
                    });
                }
            }
            
            // Initialize size options on page load
            const selectedInventoryOption = inventorySelect.options[inventorySelect.selectedIndex];
            if (selectedInventoryOption && selectedInventoryOption.value) {
                const availableSizes = selectedInventoryOption.dataset.sizes;
                updateSizeOptions(availableSizes);
            }
        });
        
        // Function to preview selected image
        function previewImage(input) {
            const preview = document.getElementById('previewImg');
            const previewContainer = document.getElementById('imagePreview');
            const noPreviewContainer = document.getElementById('noPreview');
            
            if (input.files && input.files[0]) {
                const reader = new FileReader();
                
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    previewContainer.style.display = 'block';
                    noPreviewContainer.style.display = 'none';
                };
                
                reader.readAsDataURL(input.files[0]);
            } else {
                previewContainer.style.display = 'none';
                noPreviewContainer.style.display = 'flex';
            }
        }
    </script>
@endsection