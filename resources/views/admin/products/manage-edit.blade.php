@extends('layouts.app')

@section('content')
<div class="container-fluid">
    <div class="row">
        <div class="col-12">
            <div class="card">
                <div class="card-header d-flex justify-content-between align-items-center">
                    <h4 class="mb-0">
                        <i class="bi bi-pencil me-2"></i>
                        Edit Info Produk - {{ $inventory->name }} (Ukuran {{ $size }})
                    </h4>
                    <div>
                        <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                            <i class="bi bi-arrow-left me-1"></i>Kembali
                        </a>
                    </div>
                </div>
                <div class="card-body">
                    @if(session('success'))
                        <div class="alert alert-success alert-dismissible fade show" role="alert">
                            <i class="bi bi-check-circle me-2"></i>{{ session('success') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    @if(session('error'))
                        <div class="alert alert-danger alert-dismissible fade show" role="alert">
                            <i class="bi bi-exclamation-triangle me-2"></i>{{ session('error') }}
                            <button type="button" class="btn-close" data-bs-dismiss="alert"></button>
                        </div>
                    @endif

                    <form method="POST" action="{{ route('admin.products.manage.edit', ['inventory' => $inventory->id, 'size' => $size]) }}">
                        @csrf
                        
                        <div class="row mb-4">
                            <div class="col-md-6">
                                <div class="card bg-light">
                                    <div class="card-body">
                                        <h6 class="card-title">Informasi Inventaris</h6>
                                        <p class="mb-1"><strong>Nama:</strong> {{ $inventory->name }}</p>
                                        <p class="mb-1"><strong>Kategori:</strong> {{ $inventory->category }}</p>
                                        <p class="mb-0"><strong>Ukuran:</strong> <span class="badge bg-primary">{{ $size }}</span></p>
                                    </div>
                                </div>
                            </div>
                            <div class="col-md-6">
                                <div class="card bg-warning text-dark">
                                    <div class="card-body">
                                        <h6 class="card-title">Total Produk</h6>
                                        <h3 class="mb-0">{{ $products->count() }} Produk</h3>
                                        <small>Akan diedit informasinya</small>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div class="row">
                            @foreach($products as $index => $product)
                                <div class="col-md-6 mb-4">
                                    <div class="card border-warning">
                                        <div class="card-header bg-warning text-dark">
                                            <h6 class="mb-0">
                                                <i class="bi bi-box me-2"></i>
                                                Produk #{{ $product->id }}
                                                <span class="badge bg-dark ms-2">Stok: {{ $product->stock }}</span>
                                            </h6>
                                        </div>
                                        <div class="card-body">
                                            <div class="mb-3">
                                                <label for="name_{{ $product->id }}" class="form-label">
                                                    <i class="bi bi-tag me-1"></i>Nama Produk
                                                </label>
                                                <input type="text" 
                                                       class="form-control @error('product_updates.'.$product->id.'.name') is-invalid @enderror" 
                                                       id="name_{{ $product->id }}"
                                                       name="product_updates[{{ $product->id }}][name]" 
                                                       value="{{ old('product_updates.'.$product->id.'.name', $product->name) }}" 
                                                       required>
                                                @error('product_updates.'.$product->id.'.name')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="mb-3">
                                                <label for="price_{{ $product->id }}" class="form-label">
                                                    <i class="bi bi-currency-dollar me-1"></i>Harga
                                                </label>
                                                <div class="input-group">
                                                    <span class="input-group-text">Rp</span>
                                                    <input type="number" 
                                                           class="form-control @error('product_updates.'.$product->id.'.price') is-invalid @enderror" 
                                                           id="price_{{ $product->id }}"
                                                           name="product_updates[{{ $product->id }}][price]" 
                                                           value="{{ old('product_updates.'.$product->id.'.price', $product->price) }}" 
                                                           min="0" 
                                                           step="0.01" 
                                                           required>
                                                    @error('product_updates.'.$product->id.'.price')
                                                        <div class="invalid-feedback">{{ $message }}</div>
                                                    @enderror
                                                </div>
                                            </div>

                                            <div class="mb-3">
                                                <label for="description_{{ $product->id }}" class="form-label">
                                                    <i class="bi bi-text-paragraph me-1"></i>Deskripsi
                                                </label>
                                                <textarea class="form-control @error('product_updates.'.$product->id.'.description') is-invalid @enderror" 
                                                          id="description_{{ $product->id }}"
                                                          name="product_updates[{{ $product->id }}][description]" 
                                                          rows="3"
                                                          placeholder="Deskripsi produk (opsional)">{{ old('product_updates.'.$product->id.'.description', $product->description) }}</textarea>
                                                @error('product_updates.'.$product->id.'.description')
                                                    <div class="invalid-feedback">{{ $message }}</div>
                                                @enderror
                                            </div>

                                            <div class="row">
                                                <div class="col-6">
                                                    <small class="text-muted">
                                                        <i class="bi bi-info-circle me-1"></i>
                                                        Ukuran: {{ $product->size }}
                                                    </small>
                                                </div>
                                                <div class="col-6 text-end">
                                                    <small class="text-muted">
                                                        <i class="bi bi-calendar me-1"></i>
                                                        {{ $product->created_at->format('d/m/Y') }}
                                                    </small>
                                                </div>
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            @endforeach
                        </div>

                        <div class="d-flex justify-content-between align-items-center mt-4">
                            <div>
                                <button type="button" class="btn btn-outline-info" onclick="resetAllForms()">
                                    <i class="bi bi-arrow-clockwise me-1"></i>Reset Semua
                                </button>
                                <button type="button" class="btn btn-outline-success" onclick="applyBulkPrice()">
                                    <i class="bi bi-currency-dollar me-1"></i>Set Harga Sama
                                </button>
                            </div>
                            <div>
                                <a href="{{ route('inventory.index') }}" class="btn btn-secondary me-2">
                                    <i class="bi bi-x-circle me-1"></i>Batal
                                </a>
                                <button type="submit" class="btn btn-warning">
                                    <i class="bi bi-check-circle me-1"></i>Simpan Perubahan
                                </button>
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
    </div>
</div>

<!-- Modal untuk Bulk Price -->
<div class="modal fade" id="bulkPriceModal" tabindex="-1">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Set Harga Sama untuk Semua Produk</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal"></button>
            </div>
            <div class="modal-body">
                <div class="mb-3">
                    <label for="bulkPrice" class="form-label">Harga Baru</label>
                    <div class="input-group">
                        <span class="input-group-text">Rp</span>
                        <input type="number" class="form-control" id="bulkPrice" min="0" step="0.01" placeholder="Masukkan harga">
                    </div>
                </div>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Batal</button>
                <button type="button" class="btn btn-success" onclick="setBulkPrice()">Terapkan</button>
            </div>
        </div>
    </div>
</div>

<script>
function resetAllForms() {
    @foreach($products as $product)
        document.getElementById('name_{{ $product->id }}').value = '{{ $product->name }}';
        document.getElementById('price_{{ $product->id }}').value = '{{ $product->price }}';
        document.getElementById('description_{{ $product->id }}').value = '{{ $product->description }}';
    @endforeach
}

function applyBulkPrice() {
    const modal = new bootstrap.Modal(document.getElementById('bulkPriceModal'));
    modal.show();
}

function setBulkPrice() {
    const bulkPrice = document.getElementById('bulkPrice').value;
    if (bulkPrice && bulkPrice >= 0) {
        @foreach($products as $product)
            document.getElementById('price_{{ $product->id }}').value = bulkPrice;
        @endforeach
        
        const modal = bootstrap.Modal.getInstance(document.getElementById('bulkPriceModal'));
        modal.hide();
        
        // Reset input
        document.getElementById('bulkPrice').value = '';
    } else {
        alert('Masukkan harga yang valid!');
    }
}
</script>
@endsection