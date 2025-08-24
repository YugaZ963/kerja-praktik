@extends('layouts.customer')

@section('title', 'Detail Produk')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4">
            <div class="d-flex justify-content-between align-items-center">
                <div>
                    <h1 class="h3 fw-bold text-primary mb-1">Detail Produk</h1>
                    <p class="text-muted mb-0">Informasi lengkap produk seragam sekolah</p>
                </div>
                <div>
                    <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning me-2">
                        <i class="bi bi-pencil-square"></i> Edit Produk
                    </a>
                    <a href="{{ route('admin.products.index') }}" class="btn btn-outline-secondary">
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
            {{-- Product Image and Basic Info --}}
            <div class="col-md-4">
                <div class="card">
                    <div class="card-body text-center">
                        @if($product->image && file_exists(public_path('images/' . $product->image)))
                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="img-fluid rounded mb-3" style="max-height: 300px; object-fit: cover;">
                        @else
                            <div class="bg-light d-flex align-items-center justify-content-center mx-auto mb-3" 
                                 style="width: 200px; height: 200px; border-radius: 8px;">
                                <i class="bi bi-image text-muted" style="font-size: 4rem;"></i>
                            </div>
                        @endif
                        
                        <h4 class="fw-bold text-primary">{{ $product->name }}</h4>
                        <p class="text-muted mb-3">{{ $product->category }}</p>
                        
                        <div class="d-flex justify-content-center align-items-center mb-3">
                            <span class="badge bg-secondary fs-6 me-3">{{ $product->size }}</span>
                            <h3 class="text-success mb-0">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                        </div>
                        
                        <div class="row g-2">
                            <div class="col-6">
                                <div class="bg-light p-3 rounded">
                                    <i class="bi bi-box text-primary fs-4 d-block mb-2"></i>
                                    <small class="text-muted d-block">Stok</small>
                                    <strong class="{{ $product->stock > 0 ? 'text-success' : 'text-danger' }}">
                                        {{ $product->stock }}
                                    </strong>
                                </div>
                            </div>
                            <div class="col-6">
                                <div class="bg-light p-3 rounded">
                                    <i class="bi bi-speedometer text-primary fs-4 d-block mb-2"></i>
                                    <small class="text-muted d-block">Berat</small>
                                    <strong>{{ $product->weight ?? 0 }}g</strong>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Quick Actions --}}
                <div class="card mt-3">
                    <div class="card-header">
                        <h6 class="card-title mb-0">
                            <i class="bi bi-lightning text-warning"></i> Aksi Cepat
                        </h6>
                    </div>
                    <div class="card-body">
                        <div class="d-grid gap-2">
                            <a href="{{ route('admin.products.edit', $product->id) }}" class="btn btn-warning">
                                <i class="bi bi-pencil-square"></i> Edit Produk
                            </a>
                            
                            <button type="button" class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#deleteModal">
                                <i class="bi bi-trash"></i> Hapus Produk
                            </button>
                            
                            <a href="{{ route('admin.inventory.edit', $product->inventory_id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-box-seam"></i> Edit Inventaris
                            </a>
                        </div>
                    </div>
                </div>
            </div>

            {{-- Detailed Information --}}
            <div class="col-md-8">
                {{-- Product Details --}}
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-info-circle text-info"></i> Informasi Produk
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">ID Produk</label>
                                <p class="mb-0">#{{ str_pad($product->id, 4, '0', STR_PAD_LEFT) }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Slug</label>
                                <p class="mb-0"><code>{{ $product->slug }}</code></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Nama Produk</label>
                                <p class="mb-0">{{ $product->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Kategori</label>
                                <p class="mb-0">
                                    <span class="badge bg-primary">{{ $product->category }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Ukuran</label>
                                <p class="mb-0">
                                    <span class="badge bg-secondary fs-6">{{ $product->size }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Harga</label>
                                <p class="mb-0 text-success fw-bold fs-5">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Stok</label>
                                <p class="mb-0">
                                    <span class="badge {{ $product->stock > 0 ? 'bg-success' : 'bg-danger' }} fs-6">
                                        {{ $product->stock }} unit
                                    </span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Berat</label>
                                <p class="mb-0">{{ $product->weight ?? 0 }} gram</p>
                            </div>
                            <div class="col-md-12 mb-3">
                                <label class="form-label fw-semibold text-muted">File Gambar</label>
                                <p class="mb-0">
                                    @if($product->image)
                                        <code>{{ $product->image }}</code>
                                        @if(file_exists(public_path('images/' . $product->image)))
                                            <span class="badge bg-success ms-2">✓ File ada</span>
                                        @else
                                            <span class="badge bg-danger ms-2">✗ File tidak ditemukan</span>
                                        @endif
                                    @else
                                        <span class="text-muted">Tidak ada gambar</span>
                                    @endif
                                </p>
                            </div>
                            <div class="col-md-12">
                                <label class="form-label fw-semibold text-muted">Deskripsi</label>
                                <div class="bg-light p-3 rounded">
                                    <p class="mb-0">{{ $product->description }}</p>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                {{-- Inventory Information --}}
                @if($product->inventory)
                <div class="card mb-3">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-box-seam text-primary"></i> Informasi Inventaris
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Kode Inventaris</label>
                                <p class="mb-0"><code>{{ $product->inventory->code }}</code></p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Nama Item</label>
                                <p class="mb-0">{{ $product->inventory->name }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Kategori Inventaris</label>
                                <p class="mb-0">
                                    <span class="badge bg-info">{{ $product->inventory->category }}</span>
                                </p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Harga Jual Inventaris</label>
                                <p class="mb-0">Rp {{ number_format($product->inventory->selling_price, 0, ',', '.') }}</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Total Stok Inventaris</label>
                                <p class="mb-0">{{ $product->inventory->stock }} unit</p>
                            </div>
                            <div class="col-md-6 mb-3">
                                <label class="form-label fw-semibold text-muted">Ukuran Tersedia</label>
                                <p class="mb-0">
                                    @if($product->inventory->sizes_available)
                                        @foreach($product->inventory->sizes_available as $size)
                                            <span class="badge bg-outline-secondary me-1">{{ $size }}</span>
                                        @endforeach
                                    @else
                                        <span class="text-muted">Tidak ada data ukuran</span>
                                    @endif
                                </p>
                            </div>
                        </div>
                        
                        <div class="mt-3">
                            <a href="{{ route('admin.inventory.edit', $product->inventory->id) }}" class="btn btn-outline-primary">
                                <i class="bi bi-pencil-square"></i> Edit Inventaris
                            </a>
                        </div>
                    </div>
                </div>
                @endif

                {{-- Timestamps --}}
                <div class="card">
                    <div class="card-header">
                        <h5 class="card-title mb-0">
                            <i class="bi bi-clock text-secondary"></i> Riwayat
                        </h5>
                    </div>
                    <div class="card-body">
                        <div class="row">
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Dibuat</label>
                                <p class="mb-0">
                                    <i class="bi bi-calendar-plus text-success"></i>
                                    {{ $product->created_at->format('d M Y, H:i') }}
                                    <small class="text-muted d-block">{{ $product->created_at->diffForHumans() }}</small>
                                </p>
                            </div>
                            <div class="col-md-6">
                                <label class="form-label fw-semibold text-muted">Terakhir Diubah</label>
                                <p class="mb-0">
                                    <i class="bi bi-calendar-check text-warning"></i>
                                    {{ $product->updated_at->format('d M Y, H:i') }}
                                    <small class="text-muted d-block">{{ $product->updated_at->diffForHumans() }}</small>
                                </p>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>

        {{-- Related Products --}}
        @if($relatedProducts->count() > 0)
        <div class="card mt-4">
            <div class="card-header">
                <h5 class="card-title mb-0">
                    <i class="bi bi-collection text-info"></i> Produk Terkait ({{ $relatedProducts->count() }})
                    <small class="text-muted">Produk lain dari inventaris yang sama</small>
                </h5>
            </div>
            <div class="card-body">
                <div class="row">
                    @foreach($relatedProducts as $related)
                    <div class="col-md-3 mb-3">
                        <div class="card h-100 {{ $related->id == $product->id ? 'border-primary' : '' }}">
                            <div class="card-body text-center">
                                @if($related->image && file_exists(public_path('images/' . $related->image)))
                                    <img src="{{ asset('images/' . $related->image) }}" alt="{{ $related->name }}" 
                                         class="img-fluid rounded mb-2" style="max-height: 80px; object-fit: cover;">
                                @else
                                    <div class="bg-light d-flex align-items-center justify-content-center mx-auto mb-2" 
                                         style="width: 60px; height: 60px; border-radius: 4px;">
                                        <i class="bi bi-image text-muted"></i>
                                    </div>
                                @endif
                                
                                <h6 class="card-title small">{{ $related->name }}</h6>
                                <div class="d-flex justify-content-between align-items-center mb-2">
                                    <span class="badge bg-secondary">{{ $related->size }}</span>
                                    <small class="text-success fw-bold">Rp {{ number_format($related->price, 0, ',', '.') }}</small>
                                </div>
                                <small class="text-muted">Stok: {{ $related->stock }}</small>
                                
                                @if($related->id != $product->id)
                                    <div class="mt-2">
                                        <a href="{{ route('admin.products.show', $related->id) }}" class="btn btn-sm btn-outline-primary">
                                            <i class="bi bi-eye"></i> Lihat
                                        </a>
                                    </div>
                                @else
                                    <div class="mt-2">
                                        <span class="badge bg-primary">Produk Saat Ini</span>
                                    </div>
                                @endif
                            </div>
                        </div>
                    </div>
                    @endforeach
                </div>
            </div>
        </div>
        @endif
    </div>

    {{-- Delete Confirmation Modal --}}
    <div class="modal fade" id="deleteModal" tabindex="-1" aria-labelledby="deleteModalLabel" aria-hidden="true">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title" id="deleteModalLabel">
                        <i class="bi bi-exclamation-triangle text-danger"></i> Konfirmasi Hapus Produk
                    </h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <div class="text-center mb-3">
                        @if($product->image && file_exists(public_path('images/' . $product->image)))
                            <img src="{{ asset('images/' . $product->image) }}" alt="{{ $product->name }}" 
                                 class="img-fluid rounded" style="max-height: 100px; object-fit: cover;">
                        @endif
                    </div>
                    
                    <p>Apakah Anda yakin ingin menghapus produk berikut?</p>
                    
                    <div class="bg-light p-3 rounded">
                        <strong>{{ $product->name }}</strong><br>
                        <small class="text-muted">{{ $product->category }} - Ukuran {{ $product->size }}</small><br>
                        <small class="text-success">Rp {{ number_format($product->price, 0, ',', '.') }}</small>
                    </div>
                    
                    <div class="alert alert-warning mt-3">
                        <i class="bi bi-exclamation-triangle"></i>
                        <strong>Peringatan:</strong> Tindakan ini tidak dapat dibatalkan. Produk akan dihapus secara permanen.
                    </div>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">
                        <i class="bi bi-x-circle"></i> Batal
                    </button>
                    <form method="POST" action="{{ route('admin.products.destroy', $product->id) }}" class="d-inline">
                        @csrf
                        @method('DELETE')
                        <button type="submit" class="btn btn-danger">
                            <i class="bi bi-trash"></i> Ya, Hapus Produk
                        </button>
                    </form>
                </div>
            </div>
        </div>
    </div>
@endsection