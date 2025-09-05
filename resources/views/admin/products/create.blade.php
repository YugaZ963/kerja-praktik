@extends('layouts.customer')

@section('title', 'Tambah Produk')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-primary mb-1">Tambah Produk Baru</h1>
                    <p class="text-muted mb-0">Tambahkan produk seragam sekolah dengan ukuran dan harga spesifik</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali ke Daftar Produk
                </a>
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
                @if($isDuplicate)
                    <div class="alert alert-warning alert-dismissible fade show" role="alert">
                        <i class="bi bi-exclamation-triangle-fill me-2"></i>
                        <strong>Produk Sudah Ada!</strong> 
                        Produk dengan ukuran ini sudah terdaftar untuk item inventaris yang dipilih. 
                        Silakan pilih ukuran lain atau edit produk yang sudah ada.
                        <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
                    </div>
                    <div class="text-center py-4">
                        <i class="bi bi-box-seam display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Form Tambah Produk Disembunyikan</h5>
                        <p class="text-muted">Produk dengan ukuran yang dipilih sudah ada dalam sistem.</p>
                        <a href="{{ route('admin.products.create') }}" class="btn btn-primary me-2">
                            <i class="bi bi-arrow-left me-1"></i> Pilih Ulang
                        </a>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-list me-1"></i> Lihat Semua Produk
                        </a>
                    </div>
                @else
                    <div class="card">
                        <div class="card-header">
                            <h5 class="card-title mb-0">
                                <i class="bi bi-plus-circle text-primary"></i> Form Tambah Produk
                            </h5>
                        </div>
                        <div class="card-body">
                        <form method="POST" action="{{ route('admin.products.store') }}" id="productForm" enctype="multipart/form-data">
                            @csrf
                            
                            <div class="row">
                                {{-- Inventory Selection --}}
                                <div class="col-md-12 mb-3">
                                    <label for="inventory_id" class="form-label fw-semibold">
                                        <i class="bi bi-box-seam text-primary"></i> Pilih Item Inventaris
                                        <span class="text-danger">*</span>
                                    </label>
                                    <select name="inventory_id" id="inventory_id" class="form-select @error('inventory_id') is-invalid @enderror" required>
                                        <option value="">-- Pilih Item Inventaris --</option>
                                        @foreach($inventories as $inventory)
                                            <option value="{{ $inventory->id }}" 
                                                {{ (old('inventory_id') ?? request('inventory_id')) == $inventory->id ? 'selected' : '' }}
                                                data-category="{{ $inventory->category }}"
                                                data-name="{{ $inventory->name }}"
                                                data-selling-price="{{ $inventory->selling_price }}"
                                                data-sizes="{{ json_encode($inventory->sizes_available) }}">
                                                {{ $inventory->name }} ({{ $inventory->category }}) - Rp {{ number_format($inventory->selling_price, 0, ',', '.') }}
                                            </option>
                                        @endforeach
                                    </select>
                                    @error('inventory_id')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Pilih item inventaris yang akan dijadikan produk</small>
                                </div>

                                {{-- Product Name --}}
                                <div class="col-md-6 mb-3">
                                    <label for="name" class="form-label fw-semibold">
                                        <i class="bi bi-tag text-primary"></i> Nama Produk
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="text" name="name" id="name" class="form-control @error('name') is-invalid @enderror" 
                                           value="{{ old('name') }}" required placeholder="Contoh: Kemeja SD Pendek">
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
                                           value="{{ old('category') }}" required placeholder="Akan terisi otomatis">
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
                                        @foreach($availableSizes as $size)
                                            <option value="{{ $size }}" {{ (old('size') ?? request('size')) == $size ? 'selected' : '' }}>{{ $size }}</option>
                                        @endforeach
                                    </select>
                                    @error('size')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Ukuran berdasarkan daftar harga yang tersedia</small>
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
                                               value="{{ old('price') }}" required min="0" step="1000" placeholder="0">
                                    </div>
                                    @error('price')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Harga dapat berbeda untuk setiap ukuran</small>
                                </div>

                                {{-- Stock --}}
                                <div class="col-md-6 mb-3">
                                    <label for="stock" class="form-label fw-semibold">
                                        <i class="bi bi-box text-primary"></i> Stok
                                        <span class="text-danger">*</span>
                                    </label>
                                    <input type="number" name="stock" id="stock" class="form-control @error('stock') is-invalid @enderror" 
                                           value="{{ old('stock') }}" required min="0" placeholder="0">
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
                                           value="{{ old('weight') }}" min="0" step="0.1" placeholder="0">
                                    @error('weight')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Opsional - untuk keperluan pengiriman</small>
                                </div>

                                {{-- Image Upload --}}
                                <div class="col-md-12 mb-3">
                                    <label for="image_file" class="form-label fw-semibold">
                                        <i class="bi bi-image text-primary"></i> Upload Gambar Produk
                                    </label>
                                    <input type="file" name="image_file" id="image_file" class="form-control @error('image_file') is-invalid @enderror" 
                                           accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewImage(event)">
                                    @error('image_file')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                                    
                                    {{-- Image Preview --}}
                                    <div class="mt-3">
                                        <img id="imagePreview" src="" alt="Preview Gambar" 
                                             style="max-width: 200px; max-height: 200px; display: none; border: 1px solid #ddd; border-radius: 5px;">
                                    </div>
                                    
                                    {{-- Fallback for manual image name input --}}
                                    <div class="mt-3">
                                        <label for="image" class="form-label fw-semibold">
                                            <i class="bi bi-pencil text-secondary"></i> Atau Masukkan Nama File Gambar Manual
                                        </label>
                                        <input type="text" name="image" id="image" class="form-control @error('image') is-invalid @enderror" 
                                               value="{{ old('image') }}" placeholder="Contoh: kemeja-sd-pdk.png">
                                        @error('image')
                                            <div class="invalid-feedback">{{ $message }}</div>
                                        @enderror
                                        <small class="form-text text-muted">Opsional - jika tidak mengupload file, masukkan nama file gambar yang ada di folder public/images/</small>
                                    </div>
                                </div>

                                {{-- Description --}}
                                <div class="col-md-12 mb-3">
                                    <label for="description" class="form-label fw-semibold">
                                        <i class="bi bi-card-text text-primary"></i> Deskripsi
                                        <span class="text-danger">*</span>
                                    </label>
                                    <textarea name="description" id="description" class="form-control @error('description') is-invalid @enderror" 
                                              rows="4" required placeholder="Masukkan deskripsi produk...">{{ old('description') }}</textarea>
                                    @error('description')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                </div>
                            </div>

                            <div class="d-flex justify-content-between">
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Produk
                                </button>
                            </div>
                        </form>
                        </div>
                    </div>
                @endif
            </div>

            {{-- Help Panel --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-info-circle text-info"></i> Panduan Penggunaan
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="mb-3">
                            <h6 class="fw-semibold">Langkah-langkah:</h6>
                            <ol class="small">
                                <li>Pilih item inventaris yang akan dijadikan produk</li>
                                <li>Nama produk dan kategori akan terisi otomatis</li>
                                <li>Pilih ukuran yang spesifik</li>
                                <li>Tentukan harga untuk ukuran tersebut</li>
                                <li>Masukkan stok yang tersedia</li>
                                <li>Lengkapi informasi lainnya</li>
                            </ol>
                        </div>
                        
                        <div class="mb-3">
                            <h6 class="fw-semibold">Tips:</h6>
                            <ul class="small">
                                <li>Setiap ukuran dapat memiliki harga berbeda</li>
                                <li>Stok dihitung per ukuran</li>
                                <li>Gambar harus sudah ada di folder public/images/</li>
                                <li>Deskripsi yang detail membantu customer</li>
                            </ul>
                        </div>

                        <div class="alert alert-info small">
                            <i class="bi bi-lightbulb"></i>
                            <strong>Contoh:</strong> Untuk satu item "Kemeja SD", Anda bisa membuat beberapa produk dengan ukuran S, M, L dengan harga yang berbeda-beda.
                        </div>
                    </div>
                </div>

                {{-- Preview Card --}}
                <div class="card mt-3" id="previewCard" style="display: none;">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-eye text-success"></i> Preview Produk
                        </h6>
                    </div>
                    <div class="card-body">
                        <div id="previewContent">
                            <!-- Preview will be populated by JavaScript -->
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
            const previewCard = document.getElementById('previewCard');
            const previewContent = document.getElementById('previewContent');

            // Auto-fill from URL parameters on page load
            const urlParams = new URLSearchParams(window.location.search);
            const inventoryIdFromUrl = urlParams.get('inventory_id');
            const sizeFromUrl = urlParams.get('size');
            
            if (inventoryIdFromUrl && inventorySelect.value === inventoryIdFromUrl) {
                // Trigger auto-fill for pre-selected inventory
                const selectedOption = inventorySelect.options[inventorySelect.selectedIndex];
                if (selectedOption.value) {
                    const inventoryName = selectedOption.dataset.name;
                    const inventoryCategory = selectedOption.dataset.category;
                    const sellingPrice = selectedOption.dataset.sellingPrice;

                    nameInput.value = inventoryName;
                    categoryInput.value = inventoryCategory;
                    priceInput.value = sellingPrice;
                    
                    // Add size suffix if size is specified
                    if (sizeFromUrl) {
                        nameInput.value = inventoryName + ' - ' + sizeFromUrl;
                    }
                }
                updatePreview();
            }

            // Update form fields when inventory is selected
            inventorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                const sizeSelect = document.getElementById('size');
                
                if (selectedOption.value) {
                    const inventoryName = selectedOption.dataset.name;
                    const inventoryCategory = selectedOption.dataset.category;
                    const sellingPrice = selectedOption.dataset.sellingPrice;
                    const availableSizes = selectedOption.dataset.sizes;

                    nameInput.value = inventoryName;
                    categoryInput.value = inventoryCategory;
                    priceInput.value = sellingPrice;
                    
                    // Update size options based on inventory
                    updateSizeOptions(availableSizes);
                    
                    // Add size suffix if size is selected
                    if (sizeSelect.value) {
                        nameInput.value = inventoryName + ' - ' + sizeSelect.value;
                    }
                } else {
                    // Clear size options if no inventory selected
                    sizeSelect.innerHTML = '<option value="">-- Pilih Ukuran --</option>';
                }
                updatePreview();
            });
            
            // Function to update size options
            function updateSizeOptions(sizesData) {
                const sizeSelect = document.getElementById('size');
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
                            sizeSelect.appendChild(option);
                        }
                    });
                }
            }
            
            // Update product name when size changes and check for duplicates
            document.getElementById('size').addEventListener('change', function() {
                const selectedInventoryOption = inventorySelect.options[inventorySelect.selectedIndex];
                if (selectedInventoryOption.value && this.value) {
                    const inventoryName = selectedInventoryOption.dataset.name;
                    nameInput.value = inventoryName + ' - ' + this.value;
                    updatePreview();
                    
                    // Check for duplicate product
                    checkDuplicateProduct(selectedInventoryOption.value, this.value);
                }
            });
            
            // Also check when inventory changes
            inventorySelect.addEventListener('change', function() {
                const sizeSelect = document.getElementById('size');
                if (this.value && sizeSelect.value) {
                    checkDuplicateProduct(this.value, sizeSelect.value);
                }
            });
            
            // Function to check duplicate product
            function checkDuplicateProduct(inventoryId, size) {
                if (!inventoryId || !size) return;
                
                // Redirect to same page with parameters to trigger server-side check
                const currentUrl = new URL(window.location);
                currentUrl.searchParams.set('inventory_id', inventoryId);
                currentUrl.searchParams.set('size', size);
                window.location.href = currentUrl.toString();
            }

            // Update preview when form changes
            const formInputs = document.querySelectorAll('#productForm input, #productForm select, #productForm textarea');
            formInputs.forEach(input => {
                input.addEventListener('input', updatePreview);
                input.addEventListener('change', updatePreview);
            });

            function updatePreview() {
                const name = nameInput.value;
                const category = categoryInput.value;
                const size = document.getElementById('size').value;
                const price = priceInput.value;
                const stock = document.getElementById('stock').value;
                const description = document.getElementById('description').value;

                if (name && category && size && price) {
                    previewCard.style.display = 'block';
                    previewContent.innerHTML = `
                        <div class="text-center mb-3">
                            <div class="bg-light d-flex align-items-center justify-content-center mx-auto" style="width: 80px; height: 80px; border-radius: 8px;">
                                <i class="bi bi-image text-muted fs-3"></i>
                            </div>
                        </div>
                        <h6 class="fw-bold">${name}</h6>
                        <p class="text-muted small mb-2">${category}</p>
                        <div class="d-flex justify-content-between align-items-center mb-2">
                            <span class="badge bg-secondary">${size}</span>
                            <strong class="text-success">Rp ${parseInt(price).toLocaleString('id-ID')}</strong>
                        </div>
                        <div class="mb-2">
                            <small class="text-muted">Stok: ${stock || '0'}</small>
                        </div>
                        ${description ? `<p class="small text-muted">${description.substring(0, 100)}${description.length > 100 ? '...' : ''}</p>` : ''}
                    `;
                } else {
                    previewCard.style.display = 'none';
                }
            }
        });
        
        // Function to preview uploaded image
        function previewImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('imagePreview');
            
            if (file) {
                const reader = new FileReader();
                reader.onload = function(e) {
                    preview.src = e.target.result;
                    preview.style.display = 'block';
                };
                reader.readAsDataURL(file);
            } else {
                preview.style.display = 'none';
            }
        }
    </script>
@endsection