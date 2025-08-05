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
                                @foreach ($item['sizes_available'] as $size)
                                    <span class="badge bg-info me-1">{{ $size }}</span>
                                @endforeach
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

                <!-- Size Breakdown -->
                <x-inventory-size-breakdown :item="$item" />

                <!-- Stock History -->
                <div class="card mt-4">
                    <div class="card-header bg-white d-flex justify-content-between align-items-center">
                        <h5 class="mb-0">Riwayat Stok</h5>
                        <div class="btn-group" role="group">
                            <button class="btn btn-sm btn-outline-success" data-bs-toggle="modal" data-bs-target="#increaseStockModal">
                                <i class="bi bi-plus-circle"></i> Tambah Stok
                            </button>
                            <button class="btn btn-sm btn-outline-warning" data-bs-toggle="modal" data-bs-target="#decreaseStockModal">
                                <i class="bi bi-dash-circle"></i> Kurangi Stok
                            </button>
                        </div>
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

    <!-- Modal Tambah Stok -->
    <div class="modal fade" id="increaseStockModal" tabindex="-1" aria-labelledby="increaseStockModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <form action="{{ route('inventory.adjust-stock', $item['id']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="adjustment_type" value="increase">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="increaseStockModalLabel">
                            <i class="bi bi-plus-circle text-success me-2"></i>Tambah Stok
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="alert alert-info">
                            <i class="bi bi-info-circle me-2"></i>
                            <strong>{{ $item['name'] }}</strong><br>
                            Total stok saat ini: <span class="badge bg-primary">{{ $item['stock'] }}</span>
                        </div>
                        
                        <div class="mb-3">
                            <label for="increase_size" class="form-label">Ukuran</label>
                            <select class="form-select" id="increase_size" name="size" required>
                                <option value="">Pilih Ukuran</option>
                                @if($item->products && $item->products->count() > 0)
                                    @foreach($item->products->unique('size')->sortBy('size') as $product)
                                        <option value="{{ $product->size }}">
                                            {{ $product->size }} (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                @endif
                                <!-- Opsi untuk ukuran baru -->
                                <option value="S">S (Ukuran Baru)</option>
                                <option value="M">M (Ukuran Baru)</option>
                                <option value="L">L (Ukuran Baru)</option>
                                <option value="XL">XL (Ukuran Baru)</option>
                                <option value="XXL">XXL (Ukuran Baru)</option>
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="increase_quantity" class="form-label">Jumlah yang akan ditambahkan</label>
                            <input type="number" class="form-control" id="increase_quantity" name="quantity" 
                                   min="1" required placeholder="Masukkan jumlah">
                        </div>
                        
                        <div class="mb-3">
                            <label for="increase_notes" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="increase_notes" name="notes" rows="3" 
                                      placeholder="Contoh: Restock dari supplier, Penyesuaian stok, dll."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-success">
                            <i class="bi bi-plus-circle me-1"></i>Tambah Stok
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
                <form action="{{ route('inventory.adjust-stock', $item['id']) }}" method="POST">
                    @csrf
                    <input type="hidden" name="adjustment_type" value="decrease">
                    
                    <div class="modal-header">
                        <h5 class="modal-title" id="decreaseStockModalLabel">
                            <i class="bi bi-dash-circle text-warning me-2"></i>Kurangi Stok
                        </h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    
                    <div class="modal-body">
                        <div class="alert alert-warning">
                            <i class="bi bi-exclamation-triangle me-2"></i>
                            <strong>{{ $item['name'] }}</strong><br>
                            Total stok saat ini: <span class="badge bg-primary">{{ $item['stock'] }}</span><br>
                            <small>Pastikan jumlah yang dikurangi tidak melebihi stok ukuran yang tersedia.</small>
                        </div>
                        
                        <div class="mb-3">
                            <label for="decrease_size" class="form-label">Ukuran</label>
                            <select class="form-select" id="decrease_size" name="size" required onchange="updateMaxQuantity()">
                                <option value="">Pilih Ukuran</option>
                                @if($item->products && $item->products->count() > 0)
                                    @foreach($item->products->unique('size')->sortBy('size') as $product)
                                        <option value="{{ $product->size }}" data-stock="{{ $product->stock }}">
                                            {{ $product->size }} (Stok: {{ $product->stock }})
                                        </option>
                                    @endforeach
                                @endif
                            </select>
                        </div>
                        
                        <div class="mb-3">
                            <label for="decrease_quantity" class="form-label">Jumlah yang akan dikurangi</label>
                            <input type="number" class="form-control" id="decrease_quantity" name="quantity" 
                                   min="1" required placeholder="Masukkan jumlah">
                            <div class="form-text" id="max-quantity-text">Pilih ukuran terlebih dahulu</div>
                        </div>
                        
                        <div class="mb-3">
                            <label for="decrease_notes" class="form-label">Keterangan (Opsional)</label>
                            <textarea class="form-control" id="decrease_notes" name="notes" rows="3" 
                                      placeholder="Contoh: Barang rusak, Penjualan, Penyesuaian stok, dll."></textarea>
                        </div>
                    </div>
                    
                    <div class="modal-footer">
                        <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                        <button type="submit" class="btn btn-warning">
                            <i class="bi bi-dash-circle me-1"></i>Kurangi Stok
                        </button>
                    </div>
                </form>
            </div>
        </div>
    </div>

    <script>
        function updateMaxQuantity() {
            const sizeSelect = document.getElementById('decrease_size');
            const quantityInput = document.getElementById('decrease_quantity');
            const maxQuantityText = document.getElementById('max-quantity-text');
            
            const selectedOption = sizeSelect.options[sizeSelect.selectedIndex];
            
            if (selectedOption.value && selectedOption.dataset.stock) {
                const maxStock = parseInt(selectedOption.dataset.stock);
                quantityInput.max = maxStock;
                maxQuantityText.textContent = `Maksimal: ${maxStock} unit`;
                
                if (maxStock === 0) {
                    quantityInput.disabled = true;
                    maxQuantityText.textContent = 'Stok ukuran ini habis';
                    maxQuantityText.className = 'form-text text-danger';
                } else {
                    quantityInput.disabled = false;
                    maxQuantityText.className = 'form-text';
                }
            } else {
                quantityInput.max = '';
                quantityInput.disabled = true;
                maxQuantityText.textContent = 'Pilih ukuran terlebih dahulu';
                maxQuantityText.className = 'form-text';
            }
        }
    </script>
@endsection
