{{-- resources/views/product.blade.php --}}
@extends('layouts.customer')

@section('content')
    <div class="container mt-4">
        <!-- Navbar -->
        <x-navbar />

        <!-- Hero Section -->
        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Detail Produk</h1>
            <p class="lead">Informasi lengkap tentang produk kami</p>
        </div>

        <!-- Product Detail -->
        <div class="row mb-5">
            <div class="col-md-5">
                <div class="card border-0 shadow-sm">
                    <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/kemeja-sd-pdk.png') }}" 
                         class="card-img-top" alt="{{ $product->name }}" style="height:400px;object-fit:cover;">
                </div>
            </div>
            <div class="col-md-7">
                <h2 class="mb-3">{{ $product->name }}</h2>
                <p class="badge bg-primary mb-3">{{ $product->category }}</p>
                
                <h3 class="text-primary mb-3">Rp {{ number_format($product->price, 0, ',', '.') }}</h3>
                
                <div class="mb-3">
                    <h5>Ukuran</h5>
                    <p>{{ $product->size }}</p>
                </div>
                
                <div class="mb-4">
                    <h5>Stok</h5>
                    @if ($product->stock > 5)
                        <p class="text-success">Tersedia ({{ $product->stock }})</p>
                    @elseif ($product->stock <= 5 && $product->stock > 0)
                        <p class="text-warning">Stok Terbatas ({{ $product->stock }})</p>
                    @else
                        <p class="text-danger">Habis</p>
                    @endif
                </div>
                
                <div class="mb-4">
                    <h5>Deskripsi</h5>
                    <p>{{ $product->description }}</p>
                </div>
                
                <div class="d-grid gap-2 d-md-flex">
                    <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary">
                        <i class="bi bi-arrow-left"></i> Kembali ke Produk
                    </a>
                    @if ($product->stock > 0)
                        <button class="btn btn-primary">
                            <i class="bi bi-cart-plus"></i> Tambah ke Keranjang
                        </button>
                    @endif
                </div>
             </div>
        </div>
    </div>
    </div>
@endsection
