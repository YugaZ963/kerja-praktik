@extends('layouts.customer')

@section('title', 'Edit Produk - ' . $inventory->name . ' (' . $size . ')')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 mb-1 text-primary">Edit Produk</h1>
                    <p class="text-muted mb-0">{{ $inventory->name }} - Ukuran {{ $size }}</p>
                </div>
                <a href="{{ route('inventory.index') }}" class="btn btn-outline-secondary">
                    <i class="bi bi-arrow-left"></i> Kembali
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

        @if ($errors->any())
            <div class="alert alert-danger alert-dismissible fade show" role="alert">
                <i class="bi bi-exclamation-triangle me-2"></i>
                <strong>Terjadi kesalahan:</strong>
                <ul class="mb-0 mt-2">
                    @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                    @endforeach
                </ul>
                <button type="button" class="btn-close" data-bs-dismiss="alert" aria-label="Close"></button>
            </div>
        @endif

        <div class="card">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-box-seam me-2"></i>
                    Produk {{ $inventory->name }} - Ukuran {{ $size }}
                </h5>
            </div>
            <div class="card-body">
                @if($products->count() > 0)
                    @foreach($products as $product)
                        <div class="border rounded p-3 mb-3">
                            <form action="{{ route('admin.products.update', $product->id) }}" method="POST" enctype="multipart/form-data">
                                @csrf
                                @method('PUT')
                                
                                <div class="row g-3">
                                    <div class="col-md-6">
                                        <label for="name_{{ $product->id }}" class="form-label">Nama Produk</label>
                                        <input type="text" class="form-control" id="name_{{ $product->id }}" name="name" 
                                               value="{{ old('name', $product->name) }}" required>
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="price_{{ $product->id }}" class="form-label">Harga</label>
                                        <input type="number" class="form-control" id="price_{{ $product->id }}" name="price" 
                                               value="{{ old('price', $product->price) }}" required min="0">
                                    </div>
                                    
                                    <div class="col-md-3">
                                        <label for="stock_{{ $product->id }}" class="form-label">Stok</label>
                                        <input type="number" class="form-control" id="stock_{{ $product->id }}" name="stock" 
                                               value="{{ old('stock', $product->stock) }}" required min="0">
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="image_{{ $product->id }}" class="form-label">Foto Produk</label>
                                        <input type="file" class="form-control" id="image_{{ $product->id }}" name="image" accept="image/*">
                                        @if($product->image)
                                            <small class="text-muted">Foto saat ini: {{ $product->image }}</small>
                                        @endif
                                    </div>
                                    
                                    <div class="col-md-6">
                                        <label for="category_{{ $product->id }}" class="form-label">Kategori</label>
                                        <input type="text" class="form-control" id="category_{{ $product->id }}" name="category" 
                                               value="{{ old('category', $product->category) }}" required>
                                    </div>
                                    
                                    <div class="col-12">
                                        <label for="description_{{ $product->id }}" class="form-label">Deskripsi</label>
                                        <textarea class="form-control" id="description_{{ $product->id }}" name="description" rows="3">{{ old('description', $product->description) }}</textarea>
                                    </div>
                                    
                                    <div class="col-12">
                                        <div class="d-flex gap-2">
                                            <button type="submit" class="btn btn-primary">
                                                <i class="bi bi-check-circle me-1"></i> Update Produk
                                            </button>
                                            
                                            <button type="button" class="btn btn-danger" 
                                                    onclick="if(confirm('Yakin ingin menghapus produk ini?')) { 
                                                        document.getElementById('delete-form-{{ $product->id }}').submit(); 
                                                    }">
                                                <i class="bi bi-trash me-1"></i> Hapus
                                            </button>
                                        </div>
                                    </div>
                                </div>
                                
                                <input type="hidden" name="inventory_id" value="{{ $inventory->id }}">
                                <input type="hidden" name="size" value="{{ $size }}">
                            </form>
                            
                            {{-- Form untuk hapus produk --}}
                            <form id="delete-form-{{ $product->id }}" 
                                  action="{{ route('admin.products.destroy', $product->id) }}" 
                                  method="POST" style="display: none;">
                                @csrf
                                @method('DELETE')
                            </form>
                        </div>
                    @endforeach
                @else
                    <div class="text-center py-4">
                        <i class="bi bi-box display-1 text-muted"></i>
                        <h5 class="text-muted mt-3">Tidak ada produk untuk ukuran {{ $size }}</h5>
                        <p class="text-muted">Silakan tambah produk terlebih dahulu melalui halaman inventaris.</p>
                        <a href="{{ route('inventory.index') }}" class="btn btn-primary">
                            <i class="bi bi-arrow-left me-1"></i> Kembali ke Inventaris
                        </a>
                    </div>
                @endif
            </div>
        </div>
    </div>
@endsection

@push('styles')
<style>
    .border {
        border-color: #dee2e6 !important;
    }
    
    .form-control:focus {
        border-color: #0d6efd;
        box-shadow: 0 0 0 0.2rem rgba(13, 110, 253, 0.25);
    }
    
    .btn-primary {
        background-color: #0d6efd;
        border-color: #0d6efd;
    }
    
    .btn-primary:hover {
        background-color: #0b5ed7;
        border-color: #0a58ca;
    }
</style>
@endpush