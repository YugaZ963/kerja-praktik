@extends('layouts.customer')

@section('title', 'Produk Kami')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-5 rounded mb-4 text-center">
            <h1 class="display-5 fw-bold text-primary">Koleksi Seragam Sekolah</h1>
            <p class="lead">Pilih koleksi seragam sekolah terlengkap dengan kualitas terbaik dan harga kompetitif</p>
        </div>

        <form method="GET" action="{{ route('customer.products') }}">
            <div class="row mb-4">
                <div class="col-md-12">
                    <div class="card">
                        <div class="card-body">
                            <!-- Search Bar -->
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <div class="input-group input-group-lg">
                                        <span class="input-group-text bg-primary text-white">
                                            <i class="bi bi-search"></i>
                                        </span>
                                        <input type="text" class="form-control" name="search" 
                                               placeholder="Cari produk berdasarkan nama, kategori, ukuran, atau deskripsi..." 
                                               value="{{ request('search') }}">
                                        <button type="submit" class="btn btn-primary">
                                            <i class="bi bi-search me-1"></i>Cari
                                        </button>
                                    </div>
                                </div>
                            </div>
                            
                            <!-- Advanced Filters -->
                            <div class="row align-items-end g-3">
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Kategori</label>
                                    <select class="form-select" name="category">
                                        <option value="">Semua Kategori</option>
                                        <option value="Kemeja Sekolah" {{ request('category') == 'Kemeja Sekolah' ? 'selected' : '' }}>Kemeja Sekolah</option>
                                        <option value="Kemeja Batik" {{ request('category') == 'Kemeja Batik' ? 'selected' : '' }}>Kemeja Batik</option>
                                        <option value="Kemeja Batik Koko" {{ request('category') == 'Kemeja Batik Koko' ? 'selected' : '' }}>Kemeja Batik Koko</option>
                                        <option value="Kemeja Padang" {{ request('category') == 'Kemeja Padang' ? 'selected' : '' }}>Kemeja Padang</option>
                                        <option value="Rok Sekolah" {{ request('category') == 'Rok Sekolah' ? 'selected' : '' }}>Rok Sekolah</option>
                                        <option value="Celana Sekolah" {{ request('category') == 'Celana Sekolah' ? 'selected' : '' }}>Celana Sekolah</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Ukuran</label>
                                    <select class="form-select" name="size">
                                        <option value="">Semua Ukuran</option>
                                        <!-- Ukuran Angka -->
                                        @foreach(['3', '4', '5', '6', '7', '8', '9', '10', '11', '12', '13', '14', '15', '16'] as $sizeNum)
                                            <option value="{{ $sizeNum }}" {{ request('size') == $sizeNum ? 'selected' : '' }}>{{ $sizeNum }}</option>
                                        @endforeach
                                        <!-- Ukuran Huruf -->
                                        @foreach(['S', 'M', 'L', 'XL', 'L3', 'L4', 'L5', 'L6'] as $sizeLetter)
                                            <option value="{{ $sizeLetter }}" {{ request('size') == $sizeLetter ? 'selected' : '' }}>{{ $sizeLetter }}</option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Rentang Harga</label>
                                    <div class="input-group">
                                        <input type="number" class="form-control" name="price_min" 
                                               placeholder="Min" value="{{ request('price_min') }}">
                                        <span class="input-group-text">-</span>
                                        <input type="number" class="form-control" name="price_max" 
                                               placeholder="Max" value="{{ request('price_max') }}">
                                    </div>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Status Stok</label>
                                    <select class="form-select" name="stock_status">
                                        <option value="">Semua Status</option>
                                        <option value="available" {{ request('stock_status') == 'available' ? 'selected' : '' }}>Tersedia</option>
                                        <option value="low" {{ request('stock_status') == 'low' ? 'selected' : '' }}>Stok Rendah</option>
                                        <option value="out" {{ request('stock_status') == 'out' ? 'selected' : '' }}>Habis</option>
                                    </select>
                                </div>
                                <div class="col-md-2">
                                    <label class="form-label fw-semibold">Urutkan</label>
                                    <select class="form-select" name="sort">
                                        <option value="">Terbaru</option>
                                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Harga ↑</option>
                                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Harga ↓</option>
                                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                                        <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>Nama Z-A</option>
                                        <option value="stock-asc" {{ request('sort') == 'stock-asc' ? 'selected' : '' }}>Stok ↑</option>
                                        <option value="stock-desc" {{ request('sort') == 'stock-desc' ? 'selected' : '' }}>Stok ↓</option>
                                    </select>
                                </div>
                                <div class="col-md-2 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">
                                        <i class="bi bi-funnel me-1"></i>Filter
                                    </button>
                                    <a href="{{ route('customer.products') }}" class="btn btn-outline-secondary flex-fill">
                                        <i class="bi bi-arrow-clockwise me-1"></i>Reset
                                    </a>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </form>

        <div class="row g-4">
            @forelse ($products as $product)
                <div class="col-md-3">
                    <div class="card h-100 shadow-sm">
                        <div class="position-relative">
                            <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/kemeja-sd-pdk.png') }}"
                                class="card-img-top" alt="{{ $product->name }}" style="height:200px;object-fit:cover;">
                            @if ($product->stock <= 5 && $product->stock > 0)
                                <span class="badge bg-warning text-dark position-absolute top-0 end-0 m-2">Stok
                                    Rendah</span>
                            @elseif($product->stock == 0)
                                <span class="badge bg-danger position-absolute top-0 end-0 m-2">Habis</span>
                            @endif
                        </div>
                        <div class="card-body d-flex flex-column">
                            <h6 class="card-title mb-1">{{ $product->name }}</h6>
                            <small class="text-muted mb-2">{{ $product->category }}</small>
                            <p class="fw-bold text-primary mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <p class="mb-2 small">Ukuran: {{ $product->size }}</p>
                            <a href="{{ route('customer.product.detail', $product->slug) }}"
                                class="btn btn-sm btn-outline-primary mt-auto w-100">Lihat Detail</a>
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-search" style="font-size:3rem;"></i>
                    <h4 class="mt-3">Produk Tidak Ditemukan</h4>
                    <p class="text-muted">Coba ubah filter atau kata kunci Anda</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        /* Products Page Responsive Styles */
        @media (max-width: 576px) {
            .container {
                padding-left: 10px;
                padding-right: 10px;
            }
            
            .bg-light.p-5 {
                padding: 2rem !important;
            }
            
            .display-5 {
                font-size: 1.8rem;
            }
            
            .lead {
                font-size: 1rem;
            }
            
            .input-group-lg .form-control {
                font-size: 0.9rem;
            }
            
            .input-group-lg .btn {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
            
            .row.align-items-end.g-3 {
                margin-bottom: 1rem;
            }
            
            .col-md-2, .col-md-3 {
                margin-bottom: 0.75rem;
            }
            
            .form-label {
                font-size: 0.9rem;
                margin-bottom: 0.25rem;
            }
            
            .form-select, .form-control {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
            
            .btn {
                font-size: 0.9rem;
                padding: 0.5rem 0.75rem;
            }
            
            .col-lg-3, .col-md-4, .col-sm-6 {
                margin-bottom: 1rem;
            }
            
            .card {
                height: auto;
            }
            
            .card-img-top {
                height: 180px;
                object-fit: cover;
            }
            
            .card-title {
                font-size: 0.95rem;
            }
            
            .text-muted {
                font-size: 0.8rem;
            }
            
            .fw-bold.text-primary {
                font-size: 1rem;
            }
            
            .btn-sm {
                font-size: 0.8rem;
                padding: 0.375rem 0.5rem;
            }
            
            .bi {
                font-size: 2rem;
            }
        }
        
        @media (min-width: 577px) and (max-width: 768px) {
            .container {
                padding-left: 15px;
                padding-right: 15px;
            }
            
            .bg-light.p-5 {
                padding: 3rem !important;
            }
            
            .display-5 {
                font-size: 2.2rem;
            }
            
            .col-md-2, .col-md-3 {
                flex: 0 0 auto;
                width: 50%;
                margin-bottom: 0.75rem;
            }
            
            .col-lg-3, .col-md-4 {
                flex: 0 0 auto;
                width: 50%;
            }
            
            .card-img-top {
                height: 200px;
                object-fit: cover;
            }
        }
        
        @media (min-width: 769px) and (max-width: 992px) {
            .container {
                max-width: 720px;
            }
            
            .col-md-2 {
                flex: 0 0 auto;
                width: 20%;
            }
            
            .col-md-3 {
                flex: 0 0 auto;
                width: 25%;
            }
            
            .col-lg-3 {
                flex: 0 0 auto;
                width: 33.333333%;
            }
            
            .card-img-top {
                height: 220px;
                object-fit: cover;
            }
        }
        
        @media (min-width: 993px) and (max-width: 1200px) {
            .container {
                max-width: 960px;
            }
            
            .col-lg-3 {
                flex: 0 0 auto;
                width: 25%;
            }
            
            .card-img-top {
                height: 240px;
                object-fit: cover;
            }
        }
    </style>
@endsection