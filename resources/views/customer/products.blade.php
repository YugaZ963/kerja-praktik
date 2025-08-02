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
                            <div class="row align-items-end g-3">
                                <div class="col-md-3">
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
                                <div class="col-md-3">
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
                                <div class="col-md-3">
                                    <label class="form-label fw-semibold">Urutkan</label>
                                    <select class="form-select" name="sort">
                                        <option value="">Terbaru</option>
                                        <option value="price-asc" {{ request('sort') == 'price-asc' ? 'selected' : '' }}>
                                            Harga ↑</option>
                                        <option value="price-desc" {{ request('sort') == 'price-desc' ? 'selected' : '' }}>
                                            Harga ↓</option>
                                        <option value="name-asc" {{ request('sort') == 'name-asc' ? 'selected' : '' }}>Nama
                                            A-Z</option>
                                        <option value="name-desc" {{ request('sort') == 'name-desc' ? 'selected' : '' }}>
                                            Nama Z-A</option>
                                    </select>
                                </div>
                                <div class="col-md-3 d-flex gap-2">
                                    <button type="submit" class="btn btn-primary flex-fill">Filter</button>
                                    <a href="{{ route('customer.products') }}"
                                        class="btn btn-outline-secondary flex-fill">Reset</a>
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
                            <img src="{{ $product->image ? asset('storage/' . $product->image) : asset('images/kemeja-sd-pdk.png') }}"
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
@endsection