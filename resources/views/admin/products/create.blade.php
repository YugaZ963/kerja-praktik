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
                <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-plus-circle text-primary"></i> Form Tambah Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <form method="POST" action="{{ route('admin.products.store') }}" id="productForm">
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
                                            <option value="{{ $inventory->id }}" {{ old('inventory_id') == $inventory->id ? 'selected' : '' }}
                                                    data-category="{{ $inventory->category }}"
                                                    data-name="{{ $inventory->name }}"
                                                    data-selling-price="{{ $inventory->selling_price }}">
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
                                        <option value="XS" {{ old('size') == 'XS' ? 'selected' : '' }}>XS</option>
                                        <option value="S" {{ old('size') == 'S' ? 'selected' : '' }}>S</option>
                                        <option value="M" {{ old('size') == 'M' ? 'selected' : '' }}>M</option>
                                        <option value="L" {{ old('size') == 'L' ? 'selected' : '' }}>L</option>
                                        <option value="XL" {{ old('size') == 'XL' ? 'selected' : '' }}>XL</option>
                                        <option value="XXL" {{ old('size') == 'XXL' ? 'selected' : '' }}>XXL</option>
                                        <option value="XXXL" {{ old('size') == 'XXXL' ? 'selected' : '' }}>XXXL</option>
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

                                {{-- Image --}}
                                <div class="col-md-12 mb-3">
                                    <label for="image" class="form-label fw-semibold">
                                        <i class="bi bi-image text-primary"></i> Nama File Gambar
                                    </label>
                                    <input type="text" name="image" id="image" class="form-control @error('image') is-invalid @enderror" 
                                           value="{{ old('image') }}" placeholder="Contoh: kemeja-sd-pdk.png">
                                    @error('image')
                                        <div class="invalid-feedback">{{ $message }}</div>
                                    @enderror
                                    <small class="form-text text-muted">Masukkan nama file gambar yang ada di folder public/images/</small>
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
                                <a href="{{ route('admin.products.index') }}" class="btn btn-secondary">
                                    <i class="bi bi-arrow-left"></i> Batal
                                </a>
                                <button type="submit" class="btn btn-primary">
                                    <i class="bi bi-check-circle"></i> Simpan Produk
                                </button>
                            </div>
                        </form>
                    </div>
                </div>
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

            // Auto-fill when inventory is selected
            inventorySelect.addEventListener('change', function() {
                const selectedOption = this.options[this.selectedIndex];
                if (selectedOption.value) {
                    const inventoryName = selectedOption.dataset.name;
                    const inventoryCategory = selectedOption.dataset.category;
                    const sellingPrice = selectedOption.dataset.sellingPrice;

                    nameInput.value = inventoryName;
                    categoryInput.value = inventoryCategory;
                    priceInput.value = sellingPrice;
                }
                updatePreview();
            });

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
    </script>
@endsection