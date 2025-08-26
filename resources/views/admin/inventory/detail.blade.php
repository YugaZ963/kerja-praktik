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
                                    $sizes = $item->sizes_available;
                                    if (is_string($sizes)) {
                                        $sizes = json_decode($sizes, true) ?? [];
                                    }
                                    if (!is_array($sizes)) {
                                        $sizes = [];
                                    }
                                @endphp
                                @if(count($sizes) > 0)
                                    @foreach ($sizes as $size)
                                        <span class="badge bg-info me-1">{{ $size }}</span>
                                    @endforeach
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
                        <h5 class="mb-0">
                            <i class="bi bi-box-seam text-primary me-2"></i>Kelola Produk Per Ukuran - {{ $item['name'] }}
                        </h5>
                        <button type="button" class="btn btn-success btn-sm" data-bs-toggle="modal" data-bs-target="#addCustomProductModal">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Produk Baru
                        </button>
                    </div>
                    <div class="card-body">
                        @php
                            $sizes = $item->sizes_available;
                            if (is_string($sizes)) {
                                $sizes = json_decode($sizes, true) ?? [];
                            }
                            if (!is_array($sizes)) {
                                $sizes = [];
                            }
                        @endphp
                        
                        @if(count($sizes) > 0)
                            <div class="row">
                                @foreach ($sizes as $size)
                                    @php
                                        // Ambil produk untuk ukuran ini
                                        $sizeProducts = $item->products ? $item->products->where('size', $size) : collect();
                                        $totalStock = $sizeProducts->sum('stock');
                                    @endphp
                                    <div class="col-md-6 col-lg-4 mb-4">
                                        <div class="card border-primary">
                                            <div class="card-header bg-primary text-white d-flex justify-content-between align-items-center">
                                                <h6 class="mb-0">
                                                    <i class="bi bi-rulers me-1"></i>Ukuran {{ $size }}
                                                </h6>
                                                <span class="badge bg-light text-dark">{{ $sizeProducts->count() }} Produk</span>
                                            </div>
                                            <div class="card-body">
                                                <div class="mb-3">
                                                    <div class="d-flex justify-content-between align-items-center mb-2">
                                                        <span class="text-muted">Total Stok:</span>
                                                        <span class="badge bg-info fs-6">{{ $totalStock }} unit</span>
                                                    </div>
                                                </div>
                                                
                                                @if($sizeProducts->count() > 0)
                                                    <div class="mb-3">
                                                        <h6 class="text-muted mb-2">Daftar Produk:</h6>
                                                        @foreach($sizeProducts as $product)
                                                            <div class="border rounded p-2 mb-2 bg-light">
                                                                <div class="d-flex justify-content-between align-items-start">
                                                                    <div class="flex-grow-1">
                                                                        <div class="fw-bold text-primary">{{ $product->name }}</div>
                                                                        <small class="text-muted">Stok: {{ $product->stock }} | Harga: Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                                                                    </div>
                                                                    <div class="btn-group btn-group-sm" role="group">
                                                                        <a href="{{ route('admin.products.edit', $product->id) }}" 
                                                                           class="btn btn-outline-primary" title="Edit Produk">
                                                                            <i class="bi bi-pencil"></i>
                                                                        </a>
                                                                        <button type="button" class="btn btn-outline-danger" 
                                                                                onclick="deleteProduct({{ $product->id }}, '{{ $product->name }}')" 
                                                                                title="Hapus Produk">
                                                                            <i class="bi bi-trash"></i>
                                                                        </button>
                                                                    </div>
                                                                </div>
                                                            </div>
                                                        @endforeach
                                                    </div>
                                                @else
                                                    <div class="text-center text-muted py-3">
                                                        <i class="bi bi-box display-6 mb-2"></i>
                                                        <p class="mb-0">Belum ada produk untuk ukuran ini</p>
                                                    </div>
                                                @endif
                                                
                                                {{-- Tombol tambah produk per ukuran dihapus sesuai permintaan --}}
                                            </div>
                                        </div>
                                    </div>
                                @endforeach
                            </div>
                        @else
                            <div class="text-center text-muted py-4">
                                <i class="bi bi-rulers display-4 mb-3"></i>
                                <h5>Belum Ada Ukuran Tersedia</h5>
                                <p class="mb-3">Silakan edit item inventaris untuk menambahkan ukuran yang tersedia.</p>
                                <a href="{{ route('inventory.edit', $item['id']) }}" class="btn btn-primary">
                                    <i class="bi bi-pencil me-1"></i>Edit Item Inventaris
                                </a>
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





    {{-- Modal Tambah Produk Baru Kustom --}}
    <div class="modal fade" id="addCustomProductModal" tabindex="-1" aria-labelledby="addCustomProductModalLabel" aria-hidden="true">
        <div class="modal-dialog modal-lg">
            <div class="modal-content">
                <form action="{{ route('admin.products.store') }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    <input type="hidden" name="inventory_id" value="{{ $item['id'] }}">
                    <input type="hidden" name="category" value="{{ $item['category'] }}">
                    
                    <div class="modal-header bg-success text-white">
                        <h5 class="modal-title" id="addCustomProductModalLabel">
                            <i class="bi bi-plus-circle me-2"></i>Tambah Produk Baru - {{ $item['name'] }}
                        </h5>
                        <button type="button" class="btn-close btn-close-white" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>Item Inventaris:</strong> {{ $item['name'] }}<br>
                            <strong>Kategori:</strong> {{ $item['category'] }} (Otomatis terisi)
                        </div>
                        
                        <div class="row">
                            {{-- Nama Produk --}}
                            <div class="col-md-12 mb-3">
                                <label for="custom_name" class="form-label fw-semibold">
                                    <i class="bi bi-tag text-primary"></i> Nama Produk
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="name" id="custom_name" class="form-control" 
                                       required placeholder="Contoh: Topi Sekolah Merah Ukuran XL">
                            </div>

                            {{-- Ukuran --}}
                            <div class="col-md-6 mb-3">
                                <label for="custom_size" class="form-label fw-semibold">
                                    <i class="bi bi-rulers text-primary"></i> Ukuran
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="text" name="size" id="custom_size" class="form-control" 
                                       required placeholder="Contoh: XL, XXL, Custom">
                                <small class="form-text text-muted">Masukkan ukuran kustom jika tidak ada di daftar standar</small>
                            </div>

                            {{-- Harga Jual --}}
                            <div class="col-md-6 mb-3">
                                <label for="custom_price" class="form-label fw-semibold">
                                    <i class="bi bi-currency-dollar text-primary"></i> Harga Jual
                                    <span class="text-danger">*</span>
                                </label>
                                <div class="input-group">
                                    <span class="input-group-text">Rp</span>
                                    <input type="number" name="price" id="custom_price" class="form-control" 
                                           required min="0" step="1000" placeholder="0">
                                </div>
                            </div>

                            {{-- Stok --}}
                            <div class="col-md-6 mb-3">
                                <label for="custom_stock" class="form-label fw-semibold">
                                    <i class="bi bi-box text-primary"></i> Stok
                                    <span class="text-danger">*</span>
                                </label>
                                <input type="number" name="stock" id="custom_stock" class="form-control" 
                                       required min="0" placeholder="0">
                            </div>

                            {{-- Berat --}}
                            <div class="col-md-6 mb-3">
                                <label for="custom_weight" class="form-label fw-semibold">
                                    <i class="bi bi-speedometer text-primary"></i> Berat (gram)
                                </label>
                                <input type="number" name="weight" id="custom_weight" class="form-control" 
                                       min="0" step="0.1" placeholder="0">
                                <small class="form-text text-muted">Opsional - untuk keperluan pengiriman</small>
                            </div>

                            {{-- Upload Gambar --}}
                            <div class="col-md-12 mb-3">
                                <label for="custom_image_file" class="form-label fw-semibold">
                                    <i class="bi bi-image text-primary"></i> Upload Gambar Produk
                                </label>
                                <input type="file" name="image_file" id="custom_image_file" class="form-control" 
                                       accept="image/jpeg,image/png,image/jpg,image/gif" onchange="previewCustomImage(event)">
                                <small class="form-text text-muted">Format yang didukung: JPEG, PNG, JPG, GIF. Maksimal 2MB</small>
                                
                                {{-- Image Preview --}}
                                <div class="mt-3">
                                    <img id="customImagePreview" src="" alt="Preview Gambar" 
                                         style="max-width: 200px; max-height: 200px; display: none; border: 1px solid #ddd; border-radius: 5px;">
                                </div>
                                
                                {{-- Fallback for manual image name input --}}
                                <div class="mt-3">
                                    <label for="custom_image" class="form-label fw-semibold">
                                        <i class="bi bi-pencil text-secondary"></i> Atau Masukkan Nama File Gambar Manual
                                    </label>
                                    <input type="text" name="image" id="custom_image" class="form-control" 
                                           placeholder="Contoh: topi-sekolah-xl.png">
                                    <small class="form-text text-muted">Opsional - jika tidak mengupload file, masukkan nama file gambar yang ada di folder public/images/</small>
                                </div>
                            </div>

                            {{-- Deskripsi --}}
                            <div class="col-md-12 mb-3">
                                <label for="custom_description" class="form-label fw-semibold">
                                    <i class="bi bi-card-text text-primary"></i> Deskripsi
                                    <span class="text-danger">*</span>
                                </label>
                                <textarea name="description" id="custom_description" class="form-control" 
                                          rows="4" required placeholder="Masukkan deskripsi produk..."></textarea>
                            </div>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-check-circle me-1"></i>Simpan Produk
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        // Fungsi untuk preview gambar kustom
        function previewCustomImage(event) {
            const file = event.target.files[0];
            const preview = document.getElementById('customImagePreview');
            
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

        // Fungsi untuk menghapus produk
        function deleteProduct(productId, productName) {
            if (confirm(`Apakah Anda yakin ingin menghapus produk "${productName}"?\n\nTindakan ini tidak dapat dibatalkan.`)) {
                // Buat form untuk menghapus produk
                const form = document.createElement('form');
                form.method = 'POST';
                form.action = `/admin/products/${productId}`;
                form.style.display = 'none';
                
                // Tambahkan CSRF token
                const csrfToken = document.createElement('input');
                csrfToken.type = 'hidden';
                csrfToken.name = '_token';
                csrfToken.value = '{{ csrf_token() }}';
                form.appendChild(csrfToken);
                
                // Tambahkan method DELETE
                const methodInput = document.createElement('input');
                methodInput.type = 'hidden';
                methodInput.name = '_method';
                methodInput.value = 'DELETE';
                form.appendChild(methodInput);
                
                // Submit form
                document.body.appendChild(form);
                form.submit();
            }
        }
    </script>
@endsection
