@extends('layouts.customer')

@section('title', 'Produk Kami')

@section('content')
    <div class="container mt-4">
        <x-navbar />

        <div class="bg-light p-4 rounded mb-4 text-center">
            <h1 class="h3 fw-bold text-primary">Koleksi Seragam Sekolah</h1>
            <p class="mb-0">Pilih seragam sekolah berkualitas dengan harga terjangkau</p>
        </div>

        <!-- Filter Sederhana -->
        <form method="GET" action="{{ route('customer.products') }}" class="mb-4">
            <div class="row g-3 align-items-end">
                <div class="col-md-6">
                    <div class="input-group">
                        <input type="text" class="form-control" name="search" 
                               placeholder="Cari produk..." value="{{ request('search') }}">
                        <button type="submit" class="btn btn-primary">
                            <i class="bi bi-search"></i>
                        </button>
                    </div>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="category">
                        <option value="">Semua Kategori</option>
                        <option value="Kemeja Sekolah" {{ request('category') == 'Kemeja Sekolah' ? 'selected' : '' }}>Kemeja Sekolah</option>
                        <option value="Kemeja Batik" {{ request('category') == 'Kemeja Batik' ? 'selected' : '' }}>Kemeja Batik</option>
                        <option value="Kemeja Batik Koko" {{ request('category') == 'Kemeja Batik Koko' ? 'selected' : '' }}>Kemeja Batik Koko</option>
                        <option value="Kemeja Padang" {{ request('category') == 'Kemeja Padang' ? 'selected' : '' }}>Kemeja Padang</option>
                        <option value="Rok Sekolah" {{ request('category') == 'Rok Sekolah' ? 'selected' : '' }}>Rok Sekolah</option>
                        <option value="Celana Sekolah" {{ request('category') == 'Celana Sekolah' ? 'selected' : '' }}>Celana Sekolah</option>
                        <option value="Aksesoris" {{ request('category') == 'Aksesoris' ? 'selected' : '' }}>Aksesoris</option>
                    </select>
                </div>
                <div class="col-md-3">
                    <select class="form-select" name="sort">
                        <option value="">Urutkan</option>
                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>Harga Terendah</option>
                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>Harga Tertinggi</option>
                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama A-Z</option>
                    </select>
                </div>
            </div>
        </form>

        <div class="row g-3">
            @forelse ($products as $product)
                <div class="col-md-4 col-lg-3">
                    <div class="card h-100">
                        <img src="{{ $product->image ? asset('images/' . $product->image) : asset('images/kemeja-sd-pdk.png') }}"
                            class="card-img-top" alt="{{ $product->name }}" style="height:180px;object-fit:cover;">
                        <div class="card-body text-center">
                            <h6 class="card-title mb-2">{{ $product->name }}</h6>
                            <p class="text-primary fw-bold mb-2">Rp {{ number_format($product->price, 0, ',', '.') }}</p>
                            <small class="text-muted d-block mb-3">{{ $product->size }}</small>
                            @if($product->stock == 0)
                                <button class="btn btn-secondary btn-sm w-100" disabled>Stok Habis</button>
                            @else
                                <a href="{{ route('customer.product.detail', $product->slug) }}"
                                    class="btn btn-primary btn-sm w-100">Lihat Detail</a>
                            @endif
                        </div>
                    </div>
                </div>
            @empty
                <div class="col-12 text-center py-5">
                    <i class="bi bi-box-seam" style="font-size:3rem;color:#ccc;"></i>
                    <h5 class="mt-3 text-muted">Produk Tidak Ditemukan</h5>
                    <p class="text-muted">Coba kata kunci lain</p>
                </div>
            @endforelse
        </div>

        <div class="d-flex justify-content-center mt-5">
            {{ $products->withQueryString()->links('pagination::bootstrap-5') }}
        </div>
    </div>

    <style>
        .card {
            transition: transform 0.2s;
        }
        .card:hover {
            transform: translateY(-2px);
        }
        @media (max-width: 768px) {
            .col-md-4 {
                flex: 0 0 50%;
                max-width: 50%;
            }
        }
    </style>
@endsection